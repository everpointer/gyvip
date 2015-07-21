<?php
require_once '../common.php';
require_once '../function.inc.php';
require_once '../checkMember.php';
require_once '../sdk/leancloud/AV.php';

if (!isset($_SESSION['uid']) || !isset($_REQUEST['prize_id']))
{
  header($_SERVER["SERVER_PROTOCOL"]." 500 Bad Request"); 
  exit;
}
$prizeId = $_REQUEST['prize_id'];

if (!isset($memberInfo)) {
  header($_SERVER["SERVER_PROTOCOL"]." 501 Not Member"); 
  exit;
}

// 实现积分兑换和订单，奖品纪录(方便展示)
// 查询奖品信息
// $query = new leancloud\AVQuery("CreditPrize");
$prizeQuery = new leancloud\AVQuery("CreditPrize/$prizeId");
$prize = $prizeQuery->find();
if (!$prize) {
  header($_SERVER["SERVER_PROTOCOL"]." 502 Unknown prize id."); 
  exit;
}
// 判断检查：限制条件，会员是否兑换过该奖品
try {
  $memberPrizeQuery = new leancloud\AVQuery("MemberCreditPrize");
  $memberPrizeQuery->where('member', toAVPointer('Member', $memberInfo['id']));
  $memberPrizeQuery->where('creditPrize', toAVPointer('CreditPrize', $prize->objectId));
  $memberPrizeResult = $memberPrizeQuery->find();
  // 存在兑换纪录
  if (!empty($memberPrizeResult->results)) {
    echo apiJsonResult(false, array(), "每个用户只能兑换一次奖品");
    exit;
  }
} catch (Exception $e) {
  header($_SERVER["SERVER_PROTOCOL"]." 505 Fail to query member credit prize"); 
  exit;
}

// 创建积分兑换订单（leancloud), 后续操作失败，需要清楚原始的订单
$creditOrderId = "";
try {
  $creditOrder = new leancloud\AVObject("CreditOrder");
  $creditOrder->score = $prize->score;
  $creditOrder->member = toAVPointer('Member', $memberInfo['id']); 
  $creditOrder->creditPrize = toAVPointer('CreditPrize', $prize->objectId);
  $creditOrder->status = 'created';
  $savedOrder = $creditOrder->save();
  $creditOrderId = $savedOrder->objectId;
  unset($creditOrder);
  unset($savedOrder);
} catch (Exception $e) {
  header($_SERVER["SERVER_PROTOCOL"]." 503 Fail to save credit order."); 
  exit;
}

// 消费积分
$payResult = payScore($memberInfo['cardNumber'], $prize->score, $creditOrderId);
if (!$payResult['success']) {
  echo apiJsonResult(false, array(), $payResult['errMsg']);
  exit;
}

// 更新积分兑换订单（leancloud)
$updateCreditOrder = new leancloud\AVObject("CreditOrder");
$updateCreditOrder->outTradeNo = $payResult["outTradeNo"];
$updateCreditOrder->status = "done";
$updateResult = $updateCreditOrder->update($creditOrderId);
if (!$updateResult) {
  echo apiJsonResult(false, array(), "积分订单更新失败");
  exit;
}

// 创建用户奖品纪录 (leancloud)
try {
  $memberCreditPrize = new leancloud\AVObject("MemberCreditPrize");
  $memberCreditPrize->content = "123456789"; //todo: figure content setting and configure
  $memberCreditPrize->creditPrize = toAVPointer('CreditPrize', $prize->objectId);
  $memberCreditPrize->creditOrder = toAVPointer('CreditOrder', $creditOrderId);
  $memberCreditPrize->member = toAVPointer('Member', $memberInfo["id"]);
  $memberCreditPrize->save();
} catch (Exception $e) {
  header($_SERVER["SERVER_PROTOCOL"]." 504 Fail to save user credit prize."); 
  exit;
}

// successfully api
echo apiJsonResult(true, array());

//------------------------------------------------------------------------------
// functions
function toAVPointer($className, $objectId) {
  return array(
    "__type" => "Pointer",
    "className" => $className,
    "objectId" => $objectId);
}
function apiJsonResult($success, $data, $errMsg = "") {
  return json_encode(array(
    "success" => $success,
    "data" => $data,
    "errMsg" => $errMsg
  )); 
}
/**
 * @return array("success" => boolean, "outTradeNo", "errMsg")
 */
function payScore($cardNumber, $score, $orderId) {
  if (!is_numeric($score)) {
    throw new Exception('Param Error, bad type param.');
  }
  $api = new LyfMember\Api();
  $apiParams = array(
   'name'  => $cardNumber,
   'userType' => 2,
   'amount' => 0,
   'amount1' => $score,
   'businessId' => 1,
   'orderId' => $orderId,
   'merchantId' => 'ZB001',
   'opId' => 'gyvip',
   'opName' => '果忆会员系统',
   'description' => '果忆会员系统积分兑换'
  );
  $apiParams['sign'] = genKmtkApiSign($apiParams);
  $resultStr = $api->call('kmtkPayScore', $apiParams);
  $result = json_decode($resultStr);
  
  if(!$result) {
    throw new Exception('Api failed with unknown error');
  }
  if ($result->code == 1 && empty($result->message) ) {
    return payScoreResult(true, $result->data);
  } else if ($result->message) {
    return payScoreResult(false, "", $result->message);
  } else if ($result->code > 1) {
    return payScoreResult(false, "", "发生未知错误");
  } else {
    throw new Exception('Api fail to identify response code and cannot be parsed');
  }
}

function payScoreResult($success, $outTradeNo, $errMsg = "") {
  return array(
    "success" => $success,
    "outTradeNo" => $outTradeNo,
    "errMsg" => $errMsg
  );
}
?>
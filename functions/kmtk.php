<?php
require_once (__DIR__ . '/../function.inc.php');
require_once (__DIR__ . '/../Api.php');
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
    return payScoreResult(false, "", "发生未知错误，错误代码：" . $result->code);
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
<?php
require_once '../common.php';
require_once '../function.inc.php';
require_once '../checkMember.php';
require_once '../sdk/leancloud/AV.php';

// 所有异常处理
function exception_handler($exception) {
  error_log('Uncaught exception: ' . $exception->getMessage());
  echo apiJsonResult(false, array(), '未知错误');
}
set_exception_handler("exception_handler");

if (!isset($_SESSION['uid']) || !isset($_POST['member_prize_id']))
{
  echo apiJsonResult(false, array(), '内部错误，错误的请求参数');
  exit;
}

if (!isset($memberInfo)) {
  echo apiJsonResult(false, array(), '内部错误，非会员请求');
  exit;
}

$memberPrizeId = $_POST['member_prize_id'];
$memberPrize = array();
try {
  $memberPrizeQuery = new leancloud\AVQuery('MemberCreditPrize');
  $memberPrizeQuery->where('objectId', $memberPrizeId);
  $memberPrizeQuery->wherePointer('member', 'Member', $memberInfo['id']);
  $memberPrizeQuery->setLimit(1);
  $memberPrizeQuery->whereInclude('creditPrize');
  $memberPrizeResult = $memberPrizeQuery->find();
  if (empty($memberPrizeResult->results)) {
    echo apiJsonResult(false, array(), '内部错误，未找到会员兑换的商品');
    exit;
  }
  $memberPrize = $memberPrizeResult->results[0];
} catch (Exception $e) {
  echo apiJsonResult(false, array(), '内部错误，查询会员兑换的奖品时发生异常');
  exit;
}
// 奖品可能已使用
if ($memberPrize->status != 'normal') {
  echo apiJsonResult(false, array(), '奖品状态异常，无法使用');
  exit;
}
// 使用奖品，更新状态
try {
  $memberPrizeObject = new leancloud\AVObject('MemberCreditPrize');
  $memberPrizeObject->status = 'used';
  $memberPrizeObject->usedAt = strftime('%Y-%m-%d %H:%M:%S');
  $updateResult = $memberPrizeObject->update($memberPrize->objectId);
  if (!$updateResult) {
    echo apiJsonResult(false, array(), '奖品使用失败，请联系客服处理');
    exit;
  }
} catch (Exception $e) {
  // testing write errors into php_error.log file (best practice when for debug production)
  error_log("更新会员兑换的奖品时发生异常, 详情：" . $e->getMessage());
  echo apiJsonResult(false, array(), '内部错误，更新会员兑换的奖品时发生异常');
  exit;
}

echo apiJsonResult(true, array());
exit;

// ----------------------------------------------------------------------------
// functoins
function apiJsonResult($success, $data, $errMsg = "") {
  return json_encode(array(
    "success" => $success,
    "data" => $data,
    "errMsg" => $errMsg
  )); 
}
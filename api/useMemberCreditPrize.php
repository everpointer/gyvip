<?php
require_once '../common.php';
require_once '../function.inc.php';
require_once '../checkMember.php';
require_once '../sdk/leancloud/AV.php';

if (!isset($_SESSION['uid']) || !isset($_POST['member_prize_id']))
{
  header($_SERVER["SERVER_PROTOCOL"]." 500 Bad Request"); 
  exit;
}

if (!isset($memberInfo)) {
  header($_SERVER["SERVER_PROTOCOL"]." 501 Not Member"); 
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
    header($_SERVER["SERVER_PROTOCOL"]." 503 Member credit prize not found"); 
    exit;
  }
  $memberPrize = $memberPrizeResult->results[0];
} catch (Exception $e) {
  header($_SERVER["SERVER_PROTOCOL"]." 502 Fail to query member credit prize"); 
  exit;
}
// 奖品可能已使用
if ($memberPrize->status != 'created') {
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
  header($_SERVER["SERVER_PROTOCOL"]." 503 Fail to update member credit prize"); 
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
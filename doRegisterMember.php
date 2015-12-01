<?php
require_once 'common.php';
require_once 'UserInfo.php';
require_once 'function.inc.php';
require_once 'model/Member.php';

function exception_handler($e) {
  error_log("已购买会员创建失败，uid: " . $_SESSION['uid'] .
            ", platform: " . $_SESSION['platform'] . ", mobile: $mobile \n".
            '[Exception] in ' . $e->getFile() . ' on line ' . $e->getLine() . "\n" .
            "错误详情：" . $e->getMessage() ); 
  echo apiJsonResult(false, array(), '系统异常，发生未知错误');
  exit;
}

set_exception_handler("exception_handler");

if (!isset($_POST['mobile']) ||
    !isset($_POST['smsCode']) ||
    !isset($_SESSION['uid']))
{
  echo apiJsonResult(false, array(), '内部错误，错误的请求参数');
  exit;
} 

$config = (require 'config.php');

$mobile = $_POST['mobile'];
$smsCode = $_POST['smsCode'];

try {
  $cardOrderQuery = new leancloud\AVQuery('CardOrder');
  $cardOrderQuery->where('uid', $_SESSION['uid']);
  $cardOrderQuery->setLimit(1);
  $cardOrderResult = $cardOrderQuery->find();
  if (empty($cardOrderResult->results)) {
    echo apiJsonResult(false, array(), '内部错误，未找到会员购买订单');
    exit;
  }
  $cardOrder = $cardOrderResult->results[0];
} catch (Exception $e) {
  echo apiJsonResult(false, array(), '内部错误，查询会员购买订单时发生异常');
  exit;
}

if (!$cardOrder) {
  echo apiJsonResult(false, array(), '内部错误，获取订单数据失败');
  exit;
}
if (!$cardOrder->paid) {
  error_log("订单错误：" . var_export($cardOrder, true), 3, "php_errors.log");
  echo apiJsonResult(false, array(), '内部错误，订单未成功支付');
  exit;
}
if ($cardOrder->binded) {
  echo apiJsonResult(false, array(), '内部错误，订单已绑定会员卡');
  exit;
}

// verify mobile smsCode
$api = new LyfMember\Api();
$verifyResultStr = $api->callExtUrl('verifySmsCode', array("mobilePhoneNumber" => $mobile), $smsCode);
$verifyResult = json_decode($verifyResultStr);
// // for testting
// $verifyResult = true;

if(!$verifyResult || isset($verifyResult->error)) {
  echo apiJsonResult(false, array(), '内部错误，手机号码验证失败');
  exit;
}

// 再次检测手机号码是否已经绑定会员
$mobile = $_REQUEST['mobile'];
$member = new KMTK\Member();
$result = $member->queryUserByMobile($mobile);
if ($result && !empty($result)) {
  echo apiJsonResult(false, array(), '内部错误，该手机号码已绑定了一个会员');
  exit;
}

// create member on KMTK
$memberParams = genKmtkRegisterMemberApiParams($config, array('mobile' => $mobile));
$result = $api->call('kmtkRegisterMember', $memberParams);
// // for testing
// $result = true;

if ($result) {
  // 开始创建会员卡, TODO: return member card ID
  // create member on leancloud
  $memberInfo = fromKmtkRegisterMemberParams($memberParams);
  $memberInfo['uid'] = $_SESSION['uid'];
  $memberInfo['platform'] = $_SESSION['platform'];
  $memberInfo['from'] = $_SESSION['platform'];
  $result = $api->call('registerMember', $memberInfo);
  
  if (!$result) {
    echo apiJsonResult(false, array(), '保存注册会员失败');
    exit;
  }
  // 更新订单状态
  $result = $api->callExtUrl('updateCardOrder', array(
      "binded" => true,
      "orderId" => $cardOrder->orderId
    ),
    $cardOrder->orderId
  );
  if (!$result) {
    echo apiJsonResult(false, array(), '内部错误，更新会员卡订单失败');
    exit;
  }
  // 用户积分充值，与用户绑定逻辑一致
  $result = reward_credit_gift($memberInfo['cardNumber'], $config['credit_gift']);
  if ($result != true) { // 积分充值失败只记录，不影响后续操作
    error_log("Fail to reward_credit_gift for cardNumber:" . $memberInfo['cardNumber'] . " due to:" . $result['error']);
  }
  //TODO:
  // 1. add member card to alipaypass
  // 2. show member card page
  echo apiJsonResult(true, array());
  exit;
} else {
  // echo "会员创建失败";
  error_log("已购买会员创建失败，uid: " . $_SESSION['uid'] .
            ", platform: " . $_SESSION['platform'] . ", mobile: $mobile");
  echo apiJsonResult(false, array(), '内部错误，注册会员失败');
  exit;
}

// ----------------------------------------------------------------------------
// functoins
function apiJsonResult($success, $data, $errMsg = "") {
  return json_encode(array(
    "success" => $success,
    "data" => $data,
    "errMsg" => $errMsg
  )); 
}
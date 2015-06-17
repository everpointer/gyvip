<?php
require_once 'common.php';
require_once 'UserInfo.php';
require_once 'function.inc.php';

if (!isset($_POST['mobile']) ||
    !isset($_POST['smsCode']) ||
    !isset($_SESSION['uid']))
{
  exit(500);  // malformed request
} 

$config = (require 'config.php');

$mobile = $_POST['mobile'];
$smsCode = $_POST['smsCode'];

$responseStr = $api->call('getCardOrder', array("uid" => $_SESSION['uid']));
$response = json_decode($responseStr);
$userOrder = $response->results[0];

if (!$userOrder) { header($_SERVER["SERVER_PROTOCOL"]." 502 获取订单数据失败！"); exit; }
if (!$userOrder->paid) { header($_SERVER["SERVER_PROTOCOL"]." 503 订单未成功支付！"); exit; }
if ($userOrder->binded) { header($_SERVER["SERVER_PROTOCOL"]." 504 订单已绑定会员卡! "); exit; }

// verify mobile smsCode
$api = new LyfMember\Api();
$verifyResultStr = $api->callExtUrl('verifySmsCode', array("mobilePhoneNumber" => $mobile), $smsCode);
$verifyResult = json_decode($verifyResultStr);
// $verifyResult = true;

if(!$verifyResult || isset($verifyResult->error)) {
  header($_SERVER["SERVER_PROTOCOL"]." 501 Bad Request"); 
  exit;
}

// create member on KMTK
$memberParams = genKmtkRegisterMemberApiParams($config, array('mobile' => $mobile));
$result = $api->call('kmtkRegisterMember', $memberParams);
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
    header($_SERVER["SERVER_PROTOCOL"]." 501 Fail to register member"); 
    exit;
  }
  // 更新订单状态
  $result = $api->callExtUrl('updateCardOrder', array(
      "binded" => true,
      "orderId" => $userOrder->orderId
    ),
    $userOrder->orderId
  );
  if (!result) {
    header($_SERVER["SERVER_PROTOCOL"]." 501 Fail to update card order status"); 
    exit;
  }
  $_SESSION['memberInfo'] = $memberInfo;
  
  // header("Location: showMember.php");
  //TODO:
  // 1. add member card to alipaypass
  // 2. show member card page
  echo "会员创建成功";
} else {
  // echo "会员创建失败";
  header($_SERVER["SERVER_PROTOCOL"]." 501 Bad Request"); 
  exit;
}
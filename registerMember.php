<?php
require_once 'common.php';
require_once 'UserInfo.php';
require_once 'function.inc.php';

if (!isset($_POST['mobile']) ||
    !isset($uid))
{
  exit(500);  // malformed request
} 

$config = (require 'config.php');

$mobile = $_POST['mobile'];

$responseStr = $api->call('getCardOrder', array("uid" => $uid));
$response = json_decode($responseStr);
$userOrder = $response->results[0];

if (!$userOrder) exit("获取订单数据失败！");
if (!$userOrder->paid) exit("订单未成功支付！");
if ($userOrder->binded) exit("订单已绑定会员卡");

// create member on KMTK
$memberParams = genKmtkRegisterMemberApiParams($config, array('mobile' => $mobile));
$result = $api->call('kmtkRegisterMember', $memberParams);

if ($result) {
  // 开始创建会员卡, TODO: return member card ID
  // create member on leancloud
  $memberInfo = fromKmtkRegisterMemberParams($memberParams);
  $memberInfo['uid'] = $uid;
  $memberInfo['from'] = 'alipay';
  // $newMember['alipay_uid'] = $uid;
  $result = $api->call('registerMember', $memberInfo);
  
  // 更新订单状态
  $api->callExtUrl('updateCardOrder', array(
      "binded" => true,
      "orderId" => $userOrder->orderId
    ),
    $userOrder->orderId
  );
  $_SESSION['memberInfo'] = $memberInfo;
  
  header("Location: showMember.php");
  //TODO:
  // 1. add member card to alipaypass
  // 2. show member card page
  echo "会员创建成功";
} else {
  echo "会员创建失败";
}
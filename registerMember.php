<?php
require_once 'autoload.php';
require_once 'common.php';
require_once 'AlipayPass.php';

$api = new \LyfMember\Api();
$config = (require 'conifg.php');

if (!isset($_POST['mobile']) ||
    !isset($_POST['password']) ||
    !isset($_POST['uid']))
{
  exit(500);  // malformed request
} else if ($uid != $_POST['uid']) {
  exit(501);  // malformed request
}

$mobile = $_POST['mobile'];
$password = $_POST['password'];


$responseStr = $api->call('getCardOrder', array("uid" => $uid));
$response = json_decode($responseStr);
$userOrder = $response->results[0];

if (!$userOrder) exit("获取订单数据失败！");
if (!$userOrder->paid) exit("订单未成功支付！");
if ($userOrder->binded) exit("订单已绑定会员卡");

// 开始创建会员卡, TODO: return member card ID
$result = $api->call('register', array(
  "mobile" => $mobile,
  "password" => $password,
  "uid"      => $uid
));

// 更新订单状态
if ($result) {
  $api->callExtUrl('updateCardOrder', array(
      "binded" => true,
      "orderId" => $userOrder->orderId
    ),
    $userOrder->orderId
  );
  // get member card numer and setting session
  $responseStr = $api->call('getMemberInfo', array(
    'where' => json_encode(array('uid' => $uid))
  ));
  $response = json_decode($responseStr);
  if (!$response || empty($response->results)) exit(503);
  
  $member = $response->results[0];
  $memberInfo = array(
    'cardNumber' => $member->cardNumber,
    'mobile' => $member->mobile,
    'createdAt' => $member->createdAt
  );
  $_SESSION['memberInfo'] = $memberInfo;
  
  $alipayPass = new AlipayPass($uid, $userOrder->orderId, $config['app_id']);
  $fileContent = file_get_contents('AlipassSDKDemo/alipass/714126920.alipass');
  $alipayPass->syncAdd($fileContent);
  //TODO:
  // 1. add member card to alipaypass
  // 2. show member card page
  echo "会员创建成功";
} else {
  echo "会员创建失败";
}
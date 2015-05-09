<?php
require_once 'autoload.php';
require_once 'UserInfo.php';
require_once 'function.inc.php';

$api = new \LyfMember\Api();
$config = (require 'config.php');

$userinfo = new UserInfo ();
$uid = $userinfo->getUserId ();

if (!isset($_POST['mobile']) ||
    !isset($_POST['password']) ||
    !isset($uid))
{
  exit(500);  // malformed request
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
$result = $api->call('registerMember', array(
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
  $memberInfo = memberToMemberInfo($member);
  $_SESSION['memberInfo'] = $memberInfo;
  
  header("Location: member/show.php");
  //TODO:
  // 1. add member card to alipaypass
  // 2. show member card page
  echo "会员创建成功";
} else {
  echo "会员创建失败";
}
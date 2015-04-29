<?php
require_once 'autoload.php';

$uid = '12345678';
$api = new \LyfMember\Api();

if (!isset($_POST['username']) ||
    !isset($_POST['password']) ||
    !isset($_POST['uid']))
{
  exit(500);  // malformed request
} else if ($uid != $_POST['uid']) {
  exit(501);  // malformed request
}

$username = $_POST['username'];
$password = $_POST['password'];


$responseStr = $api->call('getCardOrder', array("uid" => $uid));
$response = json_decode($responseStr);
$userOrder = $response->results[0];

if (!$userOrder) exit("获取订单数据失败！");
if (!$userOrder->paid) exit("订单未成功支付！");
if ($userOrder->binded) exit("订单已绑定会员卡");

// 开始创建会员卡, TODO: return member card ID
$result = $api->call('register', array(
  "username" => $username,
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
  //TODO:
  // 1. add member card to alipaypass
  // 2. show member card page
  echo "会员创建成功";
} else {
  echo "会员创建失败";
}
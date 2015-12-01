<?php
require_once '../common.php';
require_once '../checkMember.php';
require_once '../sdk/leancloud/AV.php';
require_once '../functions/duiba.php';

// use duiba credit store instead of mine

if (!isset($memberInfo)) {
  header("Location: /index.php");
  exit;
}
// // query member balance
// $api = new LyfMember\Api();
// // 查询用户积分余额
// $apiParams = array(
//   'name' => $memberInfo['cardNumber'],
//   'userType' => 2, // member card
//   'accountType' => 3, // 积分余额
//   'businessId' => 1,
// );
// $apiParams['sign'] = genKmtkApiSign($apiParams);
// $resultStr = $api->call('kmtkBalance', $apiParams);
// $result = json_decode($resultStr);

// if(!$result || isset($result->data->error)) {
//   header($_SERVER["SERVER_PROTOCOL"]." 501 Bad Request"); 
//   exit();
// } else if (!empty($result->message)) {
//   die("发生错误：$result->message");
// }

// generate duiba page url, url的有效期为5分钟，保证安全
$duibaUid = $memberInfo['cardNumber'];  // 会员卡号为用户标识，统一支付宝和微信
$credits = floor($result->data->BALANCE);
$appKey = $config['duiba']['app_key'];
$appSecret =  $config['duiba']['app_secret'];
$url=buildCreditAutoLoginRequest($appKey,$appSecret,$duibaUid,$credits);
header("Location: $url");
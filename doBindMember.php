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

// verify member by mobile and password
$responseStr = $api->call('getMemberInfo', array(
  'where' => json_encode(array('mobile' => $mobile, 'password' => $password))
));

$response = json_decode($responseStr);
if (!$response || empty($response->results)) exit("信息不正确，绑定失败!");

$member = $response->results[0];

// 更新会员支付宝uid
$result = $api->callExtUrl('bind', array(
      "uid" => $uid,
    ),
    $member->objectId
);

// 更新订单状态
if ($result) {
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
  echo "会员绑定成功";
} else {
  echo "会员绑定失败";
}

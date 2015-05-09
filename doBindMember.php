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

// 更新会员支付宝uid
$result = $api->call('bindMember', array(
    "uid" => $uid,
    "mobile" => $mobile,
    "password" => $password
  )
);
// 更新订单状态
if ($result && !strstr($result, 'error')) { // result checking only for leancloude
  header("Location: member/show.php");
  //TODO:
  // 1. add member card to alipaypass
  // 2. show member card page
  echo "会员绑定成功";
} else {
  echo "会员绑定失败";
}

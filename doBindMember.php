<?php
require_once 'common.php';
require_once 'UserInfo.php';
require_once 'function.inc.php';
require_once 'View.php';

if (!isset($_REQUEST['mobile']) ||
    !isset($uid))
{
  exit(500);  // malformed request
}

$mobile = $_REQUEST['mobile'];


// Todo: query user by mobile
if (!isset($_GET['action'])) {
  $result = true;
  if ($result) {
    header("Location: ?action=verifyMobile&mobile=$mobile");
    exit();
  }
}

$action = $_GET['action'];
if ($action == 'verifyMobile') {
  echo $twig->render('verifyMobile.html', array('mobile' => $mobile));
}

// // 更新会员支付宝uid
// $result = $api->call('bindMember', array(
//     "uid" => $uid,
//     "mobile" => $mobile,
//     "password" => $password
//   )
// );
// // 更新订单状态
// if ($result && !strstr($result, 'error')) { // result checking only for leancloude
//   header("Location: showMember.php");
//   //TODO:
//   // 1. add member card to alipaypass
//   // 2. show member card page
//   echo "会员绑定成功";
// } else {
//   echo "会员绑定失败";
// }

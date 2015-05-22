<?php
require_once 'common.php';
require_once 'UserInfo.php';
require_once 'function.inc.php';
require_once 'View.php';
require_once 'model/Member.php';

// session is really hard to control.(duplicated?)
@session_start();

if ((!isset($_GET['action']) && !isset($_REQUEST['mobile']))
    || !isset($uid))
{
  exit(500);  // malformed request
}


// Todo: query user by mobile
if (!isset($_GET['action']) && isset($_REQUEST['mobile'])) {
  $mobile = $_REQUEST['mobile'];
  $member = new KMTK\Member();
  $result = $member->queryUserByMobile($mobile);
  if ($result) {
    $_SESSION['bindingMember']  = fromKmtkMember($result);
    $_GET['action'] = 'verifyMobile';
  } else if ($result == 0) {
   exit("没有找到对应的会员");
  } else {
    $member->triggerError();
    exit();
  }
}
// verify condition
if (!isset($_SESSION['bindingMember'])) {
  header("Location: bindMember.php");
  exit();
}
$member = $_SESSION['bindingMember'];
// bindingMember actions
$action = $_GET['action'];
if ($action == 'verifyMobile') {
  echo $twig->render('verifyMobile.html', array('mobile' => $member['mobile']));
} else if ($action == 'bind') {
  // todo: smscode verification
  $verifyResult = true;
  
  if($verifyResult) {
    // create old member on leancloud
    $member['uid'] = $uid;
    $member['from'] = 'store';  // 来自门店的老会员
    $newMember = $member;
    // $newMember['alipay_uid'] = $uid;
    $result = $api->call('registerMember', $newMember);
    if ($result) {
      $_SESSION['memberInfo'] = $member;
      unset($_SESSION['bindingMember']);
      header("Location: showMember.php");
      echo "绑定成功";
    }
  }
} else {
  exit("No resources founded");
}

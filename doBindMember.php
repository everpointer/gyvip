<?php
require_once 'common.php';
require_once 'UserInfo.php';
require_once 'function.inc.php';
require_once 'View.php';
require_once 'model/Member.php';

// session is really hard to control.(duplicated?)
// @session_start();

if ((!isset($_GET['action']) && !isset($_REQUEST['mobile']))
    || !isset($_SESSION['uid']))
{
  header($_SERVER["SERVER_PROTOCOL"]." 500 Bad Request"); 
  exit;
}

// verify mobile user existed
if (!isset($_GET['action']) && isset($_REQUEST['mobile']) &&
    !empty($_REQUEST['mobile']))
{
  $mobile = $_REQUEST['mobile'];
  $member = new KMTK\Member();
  $result = $member->queryUserByMobile($mobile);
  if ($result && !empty($result)) {
    $_SESSION['bindingMember'] = fromKmtkMember($result);
    $_GET['action'] = 'verifyMobile';
  } else if (empty($result)) {
    echo $twig->render('message.html', array(
      'msg' => "没有找到对应的会员"
    ));
    exit;
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
  echo $twig->render('verifyMobile.html', array(
    'mobile' => $member['mobile'],
    'leancloud_app_id' => $config['leancloud']['app_id'],
    'leancloud_app_key' => $config['leancloud']['app_key']
  ));
} else if ($action == 'bind') {
  // todo: smscode verification
  if (!isset($_POST['smsCode'])) exit("Bad request.");
  $smsCode = $_POST['smsCode'];
  $api = new LyfMember\Api();
  $verifyResultStr = $api->callExtUrl('verifySmsCode', array("mobilePhoneNumber" => $member['mobile']), $smsCode);
  $verifyResult = json_decode($verifyResultStr);
  // $verifyResult = true;
  
  if($verifyResult && !isset($verifyResult->error)) {
    // create old member on leancloud
    $member['uid'] = $_SESSION['uid'];
    $member['platform'] = $_SESSION['platform'];
    $member['from'] = 'store';  // 来自门店的老会员
    $newMember = $member;
    $result = $api->call('registerMember', $newMember);
    if ($result) {
      $_SESSION['memberInfo'] = $member;
      unset($_SESSION['bindingMember']);
      echo "绑定成功";
    }
  } else {
    // header code 501
    header($_SERVER["SERVER_PROTOCOL"]." 501 Bad Request"); 
  }
} else {
  exit("No resources founded");
}

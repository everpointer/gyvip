<?php
require_once 'common.php';
require_once 'UserInfo.php';
require_once 'function.inc.php';
require_once 'View.php';
require_once 'model/Member.php';

function exception_handler($e) {
  error_log('[Exception] in ' . $e->getFile() . ' on line ' . $e->getLine() . "\n" .
            "错误详情：" . $e->getMessage() ); 
  echo apiJsonResult(false, array(), '系统异常，发生未知错误');
  exit;
}
set_exception_handler("exception_handler");

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
    $result = $api->call('registerMember', $member);
    if ($result) {
      unset($_SESSION['bindingMember']);
      echo apiJsonResult(true, array());
      exit;
    }
  } else {
    // header code 501
    echo apiJsonResult(false, array(), '内部错误，手机号码验证失败');
    exit;
  }
} else {
  exit("No resources founded");
}

// ----------------------------------------------------------------------------
// functoins
function apiJsonResult($success, $data, $errMsg = "") {
  return json_encode(array(
    "success" => $success,
    "data" => $data,
    "errMsg" => $errMsg
  )); 
}
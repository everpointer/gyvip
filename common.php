<?php
require_once 'autoload.php';
require_once 'function.inc.php';
require_once 'UserInfo.php';

$userinfo = new UserInfo ();
$uid = $userinfo->getUserId ();
if (!$uid && isset( $_REQUEST['auth_code'] )) {
  $uid = $userinfo->getUserId ( $_REQUEST['auth_code'] );
} else if (!$uid) {
  exit('User unauthorized!');
}
if (isset($_SESSION['memberInfo'])) {
  $memberInfo = $_SESSION['memberInfo'];
} else {
  $api = new \LyfMember\Api();
  $membersStr = $api->call('getMemberInfo', array(
    'where' => json_encode(array("uid" => $uid))
  ));
  
  $members = json_decode($membersStr);
  if ($members && !empty($members->results)) {
    $member = $members->results[0];
    $memberInfo = memberToMemberInfo($member);
    $_SESSION['memberInfo'] = $memberInfo;
  } 
}
//Todo: comment when production
// unset($_SESSION['memberInfo']);
// unset($memberInfo);
<?php
require_once 'Api.php';
require_once 'function.inc.php';

@session_start();
$api = new \LyfMember\Api();
$config = (require 'config.php');
if (isset($_SESSION['memberInfo'])) {
  $memberInfo = $_SESSION['memberInfo'];
} else {
  $api = new \LyfMember\Api();
  $membersStr = $api->call('getMemberInfo', array(
    'where' => json_encode(array("uid" => $_SESSION['uid'])),
    'include' => 'member'
  ));

  $members = json_decode($membersStr);
  if ($members && !empty($members->results)) {
    $member = $members->results[0];
    $memberInfo = memberToMemberInfo($member->member);
    $_SESSION['memberInfo'] = $memberInfo;
  }
}
// Todo: comment when production
// unset($_SESSION['memberInfo']);
// unset($memberInfo);
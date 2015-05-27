<?php
require_once 'Api.php';
require_once 'function.inc.php';

$api = new \LyfMember\Api();
$config = (require 'config.php');
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
// Todo: comment when production
// unset($_SESSION['memberInfo']);
// unset($memberInfo);
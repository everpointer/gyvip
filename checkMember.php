<?php
require_once 'Api.php';
require_once 'function.inc.php';

if (isset($_SESSION['memberInfo'])) {
  $memberInfo = $_SESSION['memberInfo'];
} else {
  $member = null;
  try {
    $member = queryMemberThird( $_SESSION['uid'], $_SESSION['platform'] );
  } catch (Exception $e) {
   die( genError($e->getMessage()) );
  }
  $memberInfo = memberToMemberInfo($member->member);
  $_SESSION['memberInfo'] = $memberInfo;
}
// Todo: comment when production
// unset($_SESSION['memberInfo']);
// unset($memberInfo);
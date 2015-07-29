<?php
require_once 'Api.php';
require_once 'function.inc.php';

if (isset($_SESSION['memberInfo'])) {
  $memberInfo = $_SESSION['memberInfo'];
} else {
  $member = queryMemberThird( $_SESSION['uid'], $_SESSION['platform'] );
  if ($member) {
    $memberInfo = memberToMemberInfo($member->member);
    $_SESSION['memberInfo'] = $memberInfo;
  }
}
// Todo: comment when production
// unset($_SESSION['memberInfo']);
// unset($memberInfo);
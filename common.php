<?php
require_once 'Api.php';
require_once 'function.inc.php';
require_once 'UserInfo.php';

$api = new \LyfMember\Api();
$config = (require 'config.php');
$userinfo = new UserInfo ();
$uid = $userinfo->getUserId ();
// $uid = '20881016718708634955964451718217';
if (!$uid && isset( $_REQUEST['auth_code'] )) {
  $uid = $userinfo->getUserId ( $_REQUEST['auth_code'] );
} else if (!$uid) {
  exit('User unauthorized!');
}


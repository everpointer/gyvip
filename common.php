<?php
require_once 'UserInfo.php';

$userinfo = new UserInfo ();
$uid = $userinfo->getUserId ();
if (!$uid && isset( $_REQUEST['auth_code'] )) {
  $uid = $userinfo->getUserId ( $_REQUEST['auth_code'] );
}
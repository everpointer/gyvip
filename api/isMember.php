<?php
require_once '../common.php';
require_once '../function.inc.php';
require_once '../model/Member.php';

if (!isset($_REQUEST['mobile']) || !isset($_SESSION['uid']))
{
  header($_SERVER["SERVER_PROTOCOL"]." 501 Bad Request"); 
  exit;
}

$mobile = $_REQUEST['mobile'];
$member = new KMTK\Member();
$result = $member->queryUserByMobile($mobile);
if ($result && !empty($result)) {
  echo "true";
} else {
  echo "false";
}
exit;

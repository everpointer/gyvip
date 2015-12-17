<?php
require_once '../common.php';
require_once '../function.inc.php';
require_once '../model/Member.php';

function exception_handler($e) {
  error_log('[Exception] in ' . $e->getFile() . ' on line ' . $e->getLine() . "\n" .
            "错误详情：" . $e->getMessage() ); 
  header($_SERVER["SERVER_PROTOCOL"]." 503 Unknown exception"); 
  echo "Unknown Exception: " . $e->getMessage();
}
set_exception_handler("exception_handler");

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
} else if (empty($result)) {
  echo "false";
} else {
  header($_SERVER["SERVER_PROTOCOL"]." 502 Fail to query member"); 
  $member->triggerError();
}
exit;

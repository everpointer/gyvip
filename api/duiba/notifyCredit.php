<?php
require_once '../../functions/duiba.php';
require_once '../../functions/kmtk.php';

// 所有异常处理
function exception_handler($exception) {
  error_log('API duiba/notifyCredit failed due to: ' . $exception->getMessage());
  throw new Exception($exception->getMessage());
}
set_exception_handler("exception_handler");

$config = (require '../../config.php');

// 验证兑吧请求
$request = parseCreditNotify($config['duiba']['app_key'], $config['duiba']['app_secret'], $_REQUEST);
$duibaOrderId = $_REQUEST['orderNum'];

if ($request['success']) {
  // mark successful
} else {
  /*
   * Todo: 实现kmtk回滚积分操作，同时避免重复提醒时的重复操作
   */
  error_log("Duiba order: $duibaOrderId failed because " . $request['errorMessage']);
}
echo "ok";
exit;

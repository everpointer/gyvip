<?php
require_once '../../functions/duiba.php';
require_once '../../functions/kmtk.php';

// 所有异常处理
function exception_handler($exception) {
  error_log('API duiba/comsumeCredit failed due to: ' . $exception->getMessage());
  throw new Exception($exception->getMessage());
}
set_exception_handler("exception_handler");

$config = (require '../../config.php');

// 验证兑吧请求
// var_dump($config['duiba']);
// var_dump($_REQUEST);
// exit;
$request = parseCreditConsume($config['duiba']['app_key'], $config['duiba']['app_secret'], $_REQUEST);
$credits = $request['credits'];
$duibaOrderId = $request['orderNum'];
$cardNumber = $_REQUEST['uid']; // not in parsed request

// 消费积分
$payResult = payScore($cardNumber, $credits, $duibaOrderId);

// {"status":"ok","message":"查询成功","data":{"bizId":"9381"}}
// {"status":"fail","message":"","errorMessage":"余额不足"}
if (!$payResult['success']) {
  echo json_encode(array(
    "status" => "fail",
    "message" => "",
    "errorMessage" => "兑换失败：" . $payResult['errMsg'],
  ));
  error_log("外部订单: " . $duibaOrderId . " 积分兑换失败，原因：" . $payResult['errMsg']);
} else {
  echo json_encode(array(
    "status" => "ok",
    "message" => "积分消费成功",
    "data" => array("bizId" => $payResult["outTradeNo"])
  ));
}
exit;

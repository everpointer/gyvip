<?php
// temply no auth check
require_once '../function.inc.php';
require_once '../Api.php';

// 所有异常处理
function exception_handler($exception) {
  error_log('Uncaught exception: ' . $exception->getMessage());
  echo apiJsonResult(false, array(), '未知错误');
}
set_exception_handler("exception_handler");

if (!isset($_REQUEST['cardNumber']))
{
  echo apiJsonResult(false, array(), '内部错误，错误的请求参数');
  exit;
}
$cardNumber = $_REQUEST['cardNumber'];
// query member balance
$api = new LyfMember\Api();
// 查询用户积分余额
$apiParams = array(
  'name' => $cardNumber,
  'userType' => 2, // member card
  'accountType' => 3, // 积分余额
  'businessId' => 1,
);
$apiParams['sign'] = genKmtkApiSign($apiParams);
$resultStr = $api->call('kmtkBalance', $apiParams);
$result = json_decode($resultStr);

if(!$result || isset($result->data->error)) {
  echo apiJsonResult(false, array(), '内部错误，查询积分出错');
  exit;
} else if (!empty($result->message)) {
  echo apiJsonResult(false, array(), "内部错误，查询积分异常：".$result->message);
}
//积分余额
$balance = $result->data->BALANCE;
echo apiJsonResult(true, array(
  "score" => $balance
));
exit;

// ----------------------------------------------------------------------------
// functoins
function apiJsonResult($success, $data, $errMsg = "") {
  return json_encode(array(
    "success" => $success,
    "data" => $data,
    "errMsg" => $errMsg
  )); 
}
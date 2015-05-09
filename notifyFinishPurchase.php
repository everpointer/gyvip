<?php
require_once 'Api.php';

// 读取异步通知数据
$notify = json_decode(file_get_contents("php://input"));

// error_log("错误" . json_encode($notify->order_no));
// 对异步通知做处理
if(!isset($notify->object)){
  exit("fail");
}
switch($notify->object)
{
  case "charge":
    // 开发者在此处加入对支付异步通知的处理代码
    $api = new \LyfMember\Api();
    $api->callExtUrl('updateCardOrder', array(
      "orderId" => $notify->order_no,
      "paid" => true
    ), $notify->order_no);
    exit("success");
  case "refund":
    // 开发者在此处加入对退款异步通知的处理代码
    exit("success");
  default:
    exit("fail");
}

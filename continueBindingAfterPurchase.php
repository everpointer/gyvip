<?php
require_once('vendor/autoload.php');
require_once('common.php');
require_once 'View.php';

$appId = $config['pingxx']['app_id']; //GYMember-GY
$envKey = $config['pingxx']['env_key'];
$appKey  = $config['pingxx'][$envKey]; // Live Key

// 查询用户历史会员卡订单
$responseStr = $api->call('getCardOrder', array(
  'where' => json_encode(array('uid' => $_SESSION['uid']))
));
$response = json_decode($responseStr);
if ($response && !empty($response->results)) {
  $cardOrder = $response->results[0];
  
  if ($cardOrder->paid && !$cardOrder->binded) {
    header("Location: finishPurchase.php?out_trade_no=$cardOrder->orderId");
    exit();
  } else { // 无已支付，但未绑定的订单
    echo $twig->render('message.html', array(
      'msg' => "没有找到已支付为绑定的纪录"
    ));
  }
} else {
  echo $twig->render('message.html', array(
      'msg' => "没有找到支付纪录"
    ));
}


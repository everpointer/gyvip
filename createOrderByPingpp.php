<?php
require_once('vendor/autoload.php');
require_once('common.php');

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
    header("Location: finishPurchase.php");
    exit();
  } else if (!$cardOrder->paid) { // 删除未支付完成的订单
    $api->callExtUrl('deleteCardOrder', array(
      "orderId" => $cardOrder->orderId
    ), $cardOrder->objectId);
  }
}
$responseStr = $api->call('createCardOrder', array(
  'uid' => $_SESSION['uid'],
  'amount' => $config['card_price'],
  'paid' => false,
  'binded' => false
));

$response = json_decode($responseStr);

if (isset($response->objectId)) {
  $orderId = $response->objectId;
} else {
  header("Location: purchase.php");
  exit();
}

\Pingpp\Pingpp::setApiKey($appKey);

if ($_SESSION['platform'] == 'alipay') {
  $chargeParams['channel'] = 'alipay_wap';
  $chargeParams['extra'] = array(
    "success_url" => $config['pingxx']['success_url'],
    "cancel_url"  => $config['pingxx']['cancel_url']
  );
} else if ($_SESSION['platform'] == 'wechat') {
  $chargeParams['channel'] = 'wx_pub';
  $chargeParams['extra'] = array(
    'open_id' => $_SESSION['uid']
  );
} else {
  exit('Fail to generate unknown platform paymemnt');
}

$ch = \Pingpp\Charge::create(
    array(
        'order_no'  => $orderId,  // should be orderId
        'app'       => array('id' => $appId),
        'channel'   => $chargeParams['channel'],
        'amount'    => $config['card_price'] * 100,
        'client_ip' => '127.0.0.1',
        'currency'  => 'cny',
        'subject'   => '花果山会员卡一张',
        'body'      => '花果山电子会员卡，与普通会员卡功能一致',
        'extra'     => $chargeParams['extra']
    )
);
// 更新外部订单支付接口
$chJson = json_decode($ch);
// todo: 上述操作timeout，会导致订单请求失败
$api->callExtUrl('updateCardOrder', array(
  "orderId" => $orderId,
  "outTradeNo" => $chJson->id
), $orderId);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>会员卡购买</title>
    <script type="text/javascript" src="assets/js/pingpp_pay.js"></script>
  </head>
  <body>
    <script type="text/javascript">
      pingpp.createPayment(<?php echo $ch; ?>, function(result, err){
          if(result == "success"){
            // 微信公众账号支付的结果会在这里返回
            // payment succeed, redirect to finishPurchase, only for wechat
            alert("支付成功，继续下一步");
            window.location = "<?php echo $config['pingxx']['success_url'] . '?out_trade_no=' . $orderId; ?>";
          } else if (result == "fail") {
            // charge 不正确或者微信公众账号支付失败时会在此处返回
            alert("支付失败: " + err.msg + " " + err.extra);
            window.location = "<?php echo $config['pingxx']['cancel_url']; ?>";
          }else if (result == "cancel") {
            // 微信公众账号支付取消支付
            // alert("开始取消订单");
            window.location = "<?php echo $config['pingxx']['cancel_url']; ?>";
          }
      });
    </script>
  </body>
</html>

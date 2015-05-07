<?php
require_once('vendor/autoload.php');
require_once('autoload.php');
require_once('common.php');

$appId = 'app_u1mrPCG4GeXDLa1O';
$appKey  = 'sk_test_1i1abHv5mbjHHCuTKCDyXP08'; // Test Key
// $appKey  = 'sk_live_wULlucfQQ6k4bEYjp6k8r2h9'; // Live Key

$api = new \LyfMember\Api();

// 查询用户历史会员卡订单
$responseStr = $api->call('getCardOrder', array(
  'where' => json_encode(array('uid' => $uid))
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
$amount = 10;
$responseStr = $api->call('createCardOrder', array(
  'uid' => $uid,
  'amount' => $amount,
  'paid' => false,
  'binded' => false
));

$response = json_decode($responseStr);
$orderId = $response->objectId;

\Pingpp\Pingpp::setApiKey($appKey);

$ch = \Pingpp\Charge::create(
    array(
        'order_no'  => $orderId,  // should be orderId
        'app'       => array('id' => $appId),
        'channel'   => 'alipay_wap',
        'amount'    => $amount,
        'client_ip' => '127.0.0.1',
        'currency'  => 'cny',
        'subject'   => '花果山会员卡一张',
        'body'      => '花果山电子会员卡，与普通会员卡功能一致',
        'extra'     => array(
          "success_url" => "https://member-laoyufu.c9.io/finishPurchase.php",
          "cancel_url"  => "https://member-laoyufu.c9.io/cancelPurchase.php"
        )
    )
);
// 更新外部订单支付接口
$chJson = json_decode($ch);
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
          if(result=="success"){
              // payment succeed
            alert(result);
          } else {
            alert(err.msg);
            console.log(result+" "+err.msg+" "+err.extra);
          }
      });
    </script>
  </body>
</html>

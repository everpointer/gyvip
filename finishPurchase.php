<?php
require_once 'autoload.php';
require_once 'common.php';

$orderId = $_GET['out_trade_no'];

$api = new \LyfMember\Api();
$responseStr = $api->call('getCardOrder', array(
  'orderId' => $orderId,
  'where' => json_encode(array("orderId" => $orderId))
));

$response = json_decode($responseStr);
if (!$response) exit("获取订单数据发生错误！");

$order = $response->results[0]; // only for leancloud
if (!$order) exit("获取订单数据失败！");
if (!$order->paid) exit("订单未成功支付！");
if ($order->binded) exit("订单已绑定会员卡");
if ($order->uid != $uid) exit("订单不属于您");

?>
<!DOCTYPE html>
<html>
  <head>
    <meta title="注册会员">
  </head>
  <body>
    <form action="registerMember.php" method="POST">
      <input type="text" name="mobile" placeholder="手机号"/>
      <input type="password" name="password" placeholder="会员密码"/>
      <input type="submit" value="注册"/>
      <input type="hidden" name="uid" value="<?php echo $uid; ?>">
    </form>
  </body>
</html>

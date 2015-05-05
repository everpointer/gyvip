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
    <title>注册会员</title>
    <link rel="stylesheet" href="assets/css/furtive.min.css" type="text/css" /> 
    <link rel="stylesheet" href="assets/css/base.css" type="text/css" /> 
  </head>
  <body>
    <section class="measure p2">
      <h3>完善会员信息</h3>
      <p class="h4">您已成功购买会员卡，请继续填写一下信息。</p>
      <form action="registerMember.php" method="POST" class="my2">
        <label for="mobile">手机</label>
        <input type="text" name="mobile" placeholder="请输入手机号"/>
        <label for="password">密码</label>
        <input type="password" name="password" placeholder="请输入密码"/>
        <input type="submit" value="注册" class="btn--blue" />
        <input type="hidden" name="uid" value="<?php echo $uid; ?>">
      </form>
    </section>
  </body>
</html>

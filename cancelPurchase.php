<?php
require_once 'common.php';

// $api = new \LyfMember\Api();

// $where = array('uid' => $_SESSION['uid']);
// $responseStr = $api->call('getCardOrder', array(
//   'where' => json_encode($where)
// ));
// $response = json_decode($responseStr);
// if ($response && !empty($response->results)) {
//   $cardOrder = $response->results[0];
//   // 再次检查，删除没有支付成功的订单
//   if (!$cardOrder->paid) {
//     $api->callExtUrl('deleteCardOrder', array(
//         "orderId" => $cardOrder->orderId
//       ), $cardOrder->objectId);
//   }
// }
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <title>取消订单支付</title>
</head>
<body>
  <script type="text/javascript">
    //Todo 增加等待异步通知结果，再显示结果的代码
    alert("已为您取消订单");
    window.location = "<?php echo $config['host'] ?>";
  </script>
</body>
</html>

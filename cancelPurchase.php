<?php
require_once 'autoload.php';
require_once 'common.php';

$api = new \LyfMember\Api();

$where = array('uid' => $uid);
$responseStr = $api->call('getCardOrder', array(
  'where' => json_encode($where)
));
$response = json_decode($responseStr);
if ($response && !empty($response->results)) {
  $cardOrder = $response->results[0];
  if (!$cardOrder->paid) {
    $api->callExtUrl('deleteCardOrder', array(
        "orderId" => $cardOrder->orderId
      ), $cardOrder->objectId);
  }
}
echo "订单已成功取消";
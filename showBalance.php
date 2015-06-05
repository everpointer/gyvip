<?php
require_once 'common.php';
require_once 'checkMember.php';

if (!isset($memberInfo)) {
  header("Location: index.php");
}
// query member balance
$api = new LyfMember\Api();
// 查询用户积分余额
$apiParams = array(
  'name' => $memberInfo['cardNumber'],
  'userType' => 2, // member card
  'accountType' => 3, // 积分余额
  'businessId' => 1,
);
$apiParams['sign'] = genKmtkApiSign($apiParams);
$resultStr = $api->call('kmtkBalance', $apiParams);
$result = json_decode($resultStr);

if(!$result || isset($result->data->error)) {
  header($_SERVER["SERVER_PROTOCOL"]." 501 Bad Request"); 
  exit();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>会员积分</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css" />
  </head>
  <body>
    <div class="c-main-container">
      <div class="l-container">
        <h3 class="u-text-align-center">会员积分余额</h3>
        <table class="o-table o-table--border">
          <tr>
            <td>会员卡号</td><td><?php echo $memberInfo['cardNumber']; ?></td>
          </tr>
          <tr>
            <td>积分余额</td><td><?php echo $result->data->BALANCE; ?></td>
          </tr>
            <?php if ($result->data->LASTAMOUNT && $result->data->LASTTYPE == '1') { ?>
              <tr>
                <td>最后赠送时间</td><td><?php echo strftime('%Y-%m-%d %H:%M:%S', strtotime($result->data->MODIFYON)); ?></td>
              </tr>
              <tr>
                <td>最后赠送积分</td><td><?php echo $result->data->LASTAMOUNT; ?></td>
              </tr>
            <?php } else if ($result->data->LASTAMOUNT && $result->data->LASTTYPE == '2'){ ?>
              <tr>
                <td>最后消费时间</td><td><?php echo strftime('%Y-%m-%d %H:%M:%S', strtotime($result->data->MODIFYON)); ?></td>
              </tr>
              <tr>
                <td>最后消费积分</td><td><?php echo $result->data->LASTAMOUNT; ?></td>
              </tr>
            <?php } ?>
          </tr>
        </table>
      </div>
    </div>    
  </body>
</html>
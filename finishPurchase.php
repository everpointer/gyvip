<?php
require_once 'common.php';

$where = array('uid' => $_SESSION['uid']);
if (isset($_GET['out_trade_no'])) {
  $orderId = $_GET['out_trade_no'];
  $where['orderId'] = $orderId;
} else {
  exit("Bad Request!");
}
// var_dump($where);
$responseStr = $api->call('getCardOrder', array(
  'where' => json_encode($where)
));
// var_dump($responseStr);exit;

$response = json_decode($responseStr);
if (!$response) { 
  exit("获取订单数据发生错误！"); 
} else if(count($response->results) == 0) {
  exit("订单不存在！");
}


$order = $response->results[0]; // only for leancloud
if (!$order) exit("获取订单数据失败！");
if (!$order->paid) exit("订单未成功支付！");
if ($order->binded) exit("订单已绑定会员卡");
if ($order->uid != $uid) exit("订单不属于您");

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>绑定会员卡</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css" />
  </head>
  <body class="u-color-bg-primary">
    <div class="c-main-container">
       <section class="l-container">
          <form id="isMemberForm" method="POST" class="form-horizontal c-form">
            <div class="c-form__header">1. 手机号码验证</div>
            <p class="c-form__desc">一个手机号码只能绑定一个会员</p>
            <input type="text" name="mobile" placeholder="请输入您的手机号"/>
            <input type="submit" value="下一步" class="c-button c-button--full-bleed" />
          </form>
      </section>
    </div>
    <script type="text/javascript" src="assets/js/zepto.min.js"></script>
    <script type="text/javascript" src="assets/js/spin.min.js"></script>
    <script type="text/javascript" src="assets/js/av-mini.js"></script>
    <script>
      $(function() {
        $("#isMemberForm").submit(function(e) {
          e.preventDefault();
          
          var mobile = $("input[name='mobile']").val();
          if (! mobile.match(/(1(([35][0-9])|(47)|[8][01236789]))\d{8}$/)) {
            alert("请输入正确的手机号码");
            return false;
          }
          
          showSpinnerBox();
          $.post('api/isMember.php', $("#isMemberForm").serialize(), function(response) {
            hideSpinnerBox();
            if (response == "true") {
              $("#isMemberForm")[0].reset();
              alert("发生错误：该手机号码已绑定会员卡。");
            } else if (response == "false") {
              window.location.href = "registerMember.php?out_trade_no=<?php echo $_GET['out_trade_no'] ?>&mobile=" + mobile;
            } else {
              alert("发生系统错误，请稍后重试");
              console.log("Error: " + response);
            }
          });
        });
        
      // functions
      function showSpinnerBox(parent) {
        // add overlay
        var overlay = document.createElement('div');
        overlay.className = 'overlay';
        document.body.appendChild(overlay);
        
        var div = document.createElement('div');
        var spinnerHtml = "<div id='spinner_box' class='spinner-box'><div class='spinner-wrapper'></div><div class='spinner-text'>加载中...</div></div>";
        if (typeof parent == "undefined") {
          parent = document.body;
        }
        parent.appendChild(div).innerHTML = spinnerHtml;
        var spinnerWrapper = document.querySelector('#spinner_box .spinner-wrapper');
        // setting spin.js
        new Spinner({color:'white', lines: 17, width: 2, length: 4, radius: 8, scale: 0.5}).spin(spinnerWrapper);
      }
      function hideSpinnerBox() {
        var spinnerBox = document.getElementById('spinner_box');
        if (spinnerBox) {
          spinnerBox.remove();
          document.querySelector('.overlay').remove();
        }
      }
      });
    </script>
  </body>
</html>

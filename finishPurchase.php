<?php
require_once 'common.php';

$config = (require 'config.php');

$where = array('uid' => $uid);
if (isset($_GET['out_trade_no'])) {
  $orderId = $_GET['out_trade_no'];
  $where['orderId'] = $orderId;
}
$responseStr = $api->call('getCardOrder', array(
  'where' => json_encode($where)
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
    <meta charset="utf-8">
    <title>注册会员</title>
    <link rel="stylesheet" href="assets/css/furtive.min.css" type="text/css" /> 
    <link rel="stylesheet" href="assets/css/base.css" type="text/css" /> 
  </head>
  <body>
    <section class="measure p2">
      <h2>完善会员信息</h2>
      <p class="h3">您已成功购买会员卡，请继续填写一下信息。</p>
      <form id="registerForm" action="registerMember.php" method="POST" class="my2">
        <input type="text" name="mobile" placeholder="请输入手机号" class="my2"/>
        <a href="#" class="btn--blue" id="requestSmsCode" class="my2">获取验证码</a>
        <input type="text" name="smsCode" placeholder="输入验证码" class="my2"/>
        <input type="submit" value="注册" class="btn--blue" />
      </form>
    </section>
    <script type="text/javascript" src="assets/js/zepto.min.js"></script>
    <script type="text/javascript" src="assets/js/spin.min.js"></script>
    <script type="text/javascript" src="assets/js/av-mini.js"></script>
    <script type="text/javascript">
      // Todo: limit api requester
      AV.initialize("0s4hffciblz94hah0m63rsn0x970m2obrjthz0cwmqwsipdy", "0b7jsd5h44y4wcv6w4w0alomwmpwufx8odk3irmvk36q2g10");
      
      document.getElementById('requestSmsCode').onclick = function(e) {
        e.preventDefault();
        if (this.disabled === true) {
          return false;
        }
        var mobile = $("input[name='mobile']").val();
        if (! mobile.match(/(1(([35][0-9])|(47)|[8][01236789]))\d{8}$/)) {
          alert('请输入正确的手机号码');
          return false;
        }
        countDown(this, this.text);
        AV.Cloud.requestSmsCode(mobile).then(function(){
          //发送成功
        }, function(err){
          //发送失败
          alert("发送失败");
        });
      };
      
     $("#registerForm").submit(function(e) {
        e.preventDefault();
        
        var mobile = $("input[name='mobile']").val();
        var smsCode = $("input[name='smsCode']").val();
        if (! mobile.match(/(1(([35][0-9])|(47)|[8][01236789]))\d{8}$/) ||
            smsCode == "") {
          return false;
        }
        
        var spinner = new Spinner({color:'#545454', lines: 12}).spin(document.body);
        $("body").addClass("bg--off-white").css("opaticy", 0.6);
        $.ajax({
          type: 'POST',
          url: 'registerMember.php',
          data: $("#registerForm").serialize(),
          success: function() {
            spinner.stop();
            alert("注册成功");
            window.location = 'showMember.php';
          },
          error: function() {
            spinner.stop();
            alert("验证失败");
          }
        });
      }); 
      
      function countDown(element, content) {
        // disable element
        element.disabled = true;
        // add disabled styling
        element.className = "btn btn--light-gray";
        // seconds count down
        var seconds = 60;
        element.textContent = content + '('  + seconds + ')';
        var intervalId = setInterval(function() {
          if (seconds-- > 0) {
            element.textContent = content + '(' + seconds + ')';
          } else {
            clearInterval(intervalId);
            element.textContent = content;
            element.className = "btn btn--blue";
            element.disabled = false;
          }
        }, 1000);
      }
    </script>
  </body>
</html>

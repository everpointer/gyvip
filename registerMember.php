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
    <title>注册会员</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css" /> 
  </head>
  <body class="u-color-bg-primary">
    <div class="c-main-container">
      <section class="l-container">
        <form id="registerForm" action="doRegisterMember.php" method="POST" class="form-horizontal c-form">
          <div class="c-form__header">完善会员信息</div>
          <p class="c-form__desc">您已成功购买会员卡，还需绑定手机号码。</p>
          <div class="form-group">
            <input type="text" name="mobile" placeholder="请输入手机号" value="<?php echo $_GET['mobile'] ?>" class="u-one-half u-four-fifths-from-lap" readonly/>
            <a href="#" id="requestSmsCode" class="c-button">获取验证码</a>
          </div>
          <div class="form-group">
            <input type="text" name="smsCode" placeholder="输入验证码" class="u-one-half u-four-fifths-from-lap" />
          </div>
          <input type="submit" value="注册" class="c-button c-button--full-bleed"/>
        </form>
      </section>
    </div>
    <script type="text/javascript" src="assets/js/zepto.min.js"></script>
    <script type="text/javascript" src="assets/js/spin.min.js"></script>
    <script type="text/javascript" src="assets/js/av-mini.js"></script>
    <script type="text/javascript">
      // Todo: limit api requester
      AV.initialize("<?php echo $config['leancloud']['app_id']; ?>", "<?php echo $config['leancloud']['app_key']; ?>");
      
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
          url: 'doRegisterMember.php',
          data: $("#registerForm").serialize(),
          success: function(result) {
            spinner.stop();
            if (result.success == true) {
              alert("注册成功");
              window.location = 'showMember.php';
            } else {
              alert("兑换失败，失败原因：" + result.errMsg + "，请联系客服咨询");
            }
          },
          error: function(xhr, status, error) {
            spinner.stop();
            alert("兑换失败，发生未知错误，请联系客服咨询");
          }
        });
      }); 
      
      function countDown(element, content) {
        // disable element
        element.disabled = true;
        // add disabled styling
        element.className = "c-button c-button--light-gray";
        // seconds count down
        var seconds = 60;
        element.textContent = content + '('  + seconds + ')';
        var intervalId = setInterval(function() {
          if (seconds-- > 0) {
            element.textContent = content + '(' + seconds + ')';
          } else {
            clearInterval(intervalId);
            element.textContent = content;
            element.className = "c-button";
            element.disabled = false;
          }
        }, 1000);
      }
    </script>
  </body>
</html>

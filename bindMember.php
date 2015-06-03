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
          <form id="bindForm" action="doBindMember.php" method="POST" class="form-horizontal c-form">
            <div class="c-form__header">1. 手机号码绑定</div>
            <p class="c-form__desc">请输入预留手机号码</p>
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
        $("#bindForm").submit(function(e) {
          e.preventDefault();
          
          var mobile = $("input[name='mobile']").val();
          if (! mobile.match(/(1(([35][0-9])|(47)|[8][01236789]))\d{8}$/)) {
            return false;
          }
          
          var spinner = new Spinner({color:'#545454', lines: 12}).spin(document.body);
          $("body").addClass("bg--off-white").css("opaticy", 0.6);
          $.post('doBindMember.php', $("#bindForm").serialize(), function(response) {
            spinner.stop();
            $("body").html(response);
          });
        });
      });
    </script>
  </body>
</html>

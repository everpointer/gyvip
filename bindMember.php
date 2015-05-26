<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>绑定会员卡</title>
    <link rel="stylesheet" href="assets/css/furtive.min.css" type="text/css" />
    <link rel="stylesheet" href="assets/css/base.css" type="text/css" />
  </head>
  <body>
    <section class="measure p2">
      <h2>老会员绑定</h2>
      <p class="h3">老会员电子会员卡绑定</p>
      <form id="bindForm" action="doBindMember.php" method="POST" class="my2">
        <input type="text" name="mobile" placeholder="请输入您的手机号"/>
        <input type="submit" value="下一步" class="btn--blue" />
      </form>
    </section>
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

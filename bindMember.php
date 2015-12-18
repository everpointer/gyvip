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
    <script type="text/javascript" src="assets/js/av-mini.js"></script>
    <script type="text/javascript" src="assets/js/spin.min.js"></script>
    <script>
      $(function() {
        $("#bindForm").submit(function(e) {
          e.preventDefault();
          
          var mobile = $("input[name='mobile']").val();
          if (! mobile.match(/(1(([35][0-9])|(47)|[8][01236789]))\d{8}$/)) {
            alert("请输入正确的手机号码");
            return false;
          }
          
          showSpinnerBox();
          $.ajax({
            type: 'POST',
            url: 'doBindMember.php',
            data: $("#bindForm").serialize(),
            success: function(result) {
              $("body").html(result);
            },
            error: function(xhr, status, error) {
              alert("发生错误：" + error);
            },
            complete: function() {
              hideSpinnerBox();
            }
          });
          // $.post('doBindMember.php', $("#bindForm").serialize(), function(data, status, xhr) {
          //   hideSpinnerBox();
          //   $("body").html(data);
          // });
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
            $(spinnerBox).remove();
            // document.querySelector('.overlay').remove();
            $(".overlay").remove();
          }
        }
      });
    </script>
  </body>
</html>

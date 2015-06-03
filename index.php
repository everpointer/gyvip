<?php
require_once 'common.php';
require_once 'checkMember.php';

if (!$uid) exit(500);

if (isset($memberInfo)) {
  header("Location: showMember.php");
} else {
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>会员中心</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css" />
  </head>
  <body class="u-color-bg-primary">
    <div role="main">
      <div class="bg-mask"></div>
       <div class="member-card">
         <img src="assets/images/gy-member-card.png" />
         <div class="img-line-mask"></div>
       </div>
       <!--<div class="c-button u-three-quarters u-s-ms-one-eighth">-->
       <!--  购买/绑定我的会员卡-->
       <!--</div>-->
       <div class="c-modal">
          <label for="modal-membercard">
            <div class="modal-trigger u-three-quarters u-s-ms-one-eighth">购买/绑定我的会员卡</div>
          </label>
          <input class="modal-state" id="modal-membercard" type="checkbox" />
          <div class="modal-fade-screen">
            <div class="modal-inner">
              <div class="modal-close" for="modal-membercard">X</div>
              <!--<h1>Modal Title</h1>-->
              <p class="modal-intro">
                "老会员，请点击绑定会员卡；新会员，请购买会员卡。"
              </p>
              <p class="modal-content">
                <a href="bindMember.php" class="c-button c-button-small c-button--full-bleed u-s-mb-small">绑定会员卡</a>
                <a href="purchase.php" class="c-button c-button-small c-button--full-bleed c-button--secondary">购买会员卡</a>
              </p>
            </div>
          </div>
        </div>

       <div class="c-list c-list-inset u-s-ms-base">
            <a href="#" class="c-item">
              会员卡办理说明
              <div class="u-float-right"> > </div>
            </a>
            <a href="#" class="c-item">
              余额查询
              <div class="u-float-right"> > </div>
            </a>
         </div>
       </div>
    </div>
    <script type="text/javascript" src="assets/js/zepto.min.js"></script>
    <script>
      $(function() {
        $("#modal-1").on("change", function() {
          if ($(this).is(":checked")) {
            $("body").addClass("modal-open");
          } else {
            $("body").removeClass("modal-open");
          }
        });
      
        $(".modal-fade-screen, .modal-close").on("click", function() {
          $(".modal-state:checked").prop("checked", false).change();
        });
      
        $(".modal-inner").on("click", function(e) {
          e.stopPropagation();
        });
      });

    </script>
  </body>
</html>
<?php
}
?>
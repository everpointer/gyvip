<?php
require_once 'common.php';
require_once 'checkMember.php';

if (!isset($memberInfo)) {
  header("Location: index.php");
}
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
           <img src="assets/images/gy-member-card.png"></img>
           <p>使用时请向收银员出示此卡</p>
           <span class="card-number">No. <?php echo $memberInfo['cardNumber'] ?></span>
         </div>
         <div class="barcode">
          <img id="barcode" />
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
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/JsBarcode.all.min.js"></script>
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
        
        // barcode
        $("#barcode").JsBarcode("<?php echo $memberInfo['cardNumber']; ?>",{height: 60, displayValue:true, fontSize:20});
      });

    </script>
  </body>
</html>


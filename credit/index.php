<?php
require_once '../common.php';
require_once '../checkMember.php';
require_once '../sdk/leancloud/AV.php';

if (!isset($memberInfo)) {
  header("Location: index.php");
  exit;
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
} else if (!empty($result->message)) {
  die("发生错误：$result->message");
}
$currentBalance = $result->data->BALANCE;
// get credit prizes
$creditPrizes = array();
$memberCreditPrizes = array();
try {
  $prizeQuery = new leancloud\AVQuery("CreditPrize");
  $creditPrizeResult = $prizeQuery->find();
  $creditPrizes = $creditPrizeResult->results;
  $memberPrizeQuery = new leancloud\AVQuery("MemberCreditPrize");
  $memberPrizeQuery->wherePointer('member', 'Member', $memberInfo['id']);
  $memberCreditPrizeResult = $memberPrizeQuery->find();
  $memberCreditPrizes = $memberCreditPrizeResult->results;
} catch (Exception $e) {
  header($_SERVER["SERVER_PROTOCOL"]." 502 Fail to call leancloud api"); 
  exit();
}
// prizes 排序， 同时加上兑换状态
$normalPrizes = array();
$redeemedPrizes = array();
foreach ($creditPrizes as $creditPrize) {
  $creditPrize->status = "normal";
  foreach($memberCreditPrizes as $memberCreditPrize) {
    if ($memberCreditPrize->creditPrize->objectId == $creditPrize->objectId) {
      $creditPrize->status = "redeemed";
    }
  }
  if ($creditPrize->status == "normal") {
    array_push($normalPrizes, $creditPrize);
  } else if ($creditPrize->status == "redeemed") {
    array_push($redeemedPrizes, $creditPrize);
  }
}
// normal 在前，redeemed在后
$orderedPrizes = array_merge($normalPrizes, $redeemedPrizes);
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>积分商城</title>
  <link rel="stylesheet" href="../assets/css/style.css" type="text/css" />
</head>
<body class="u-color-bg-primary">
  <div style="position:fixed; height: 50px;width: 100%;text-align: center;background: white;line-height: 50px;">
    当前积分：<span style="font-size: 1.5em; color: rgb(255, 171, 90);"><?php echo $currentBalance; ?></span> 分
  </div>
  </header>
  <div class="c-tabbar">
    <a href="/" class="c-tabbar-item">会员卡</a>
    <a href="/prizes" class="c-tabbar-item">使用商品</a>
  </div>
  <div class="c-main-container c-main-container--tabbar c-main-container--header">
    <div class="l-container">
      <!--<div class="u-text-align-center" style="font-weight: bold;margin-bottom: 1em;">-->
      <!--  当前积分：<span style="font-size: 1.5em; color: rgb(255, 171, 90);"><?php echo $currentBalance; ?></span> 分-->
      <!--</div> -->
      <div class="prize-list">
        <?php foreach ($orderedPrizes as $prize) { ?>
          <div class="c-media">
            <!--100*68px 为最佳比例-->
            <div class="c-media-image">
              <!-- todo: add image url -->
              <img src="<?php echo $prize->imageUrl ?>" alt="Logo image">
            </div>
            <div class="c-media-content">
              <h5><?php echo $prize->name ?></h5>
              <p>
                <?php echo $prize->score; ?> 积分
                <?php if ($prize->status == "normal") { ?>
                  <a class="c-button c-button--small u-float-right modal-trigger" data-prize-id="<?php echo $prize->objectId ?>" data-prize-title="<?php echo $prize->name ?>">
                    兑换
                  </a>
                <?php } else if ($prize->status == "redeemed") { ?>
                  <a class="c-button c-button--small c-button--light-gray u-float-right" data-prize-id="<?php echo $prize->objectId ?>" data-prize-title="<?php echo $prize->name ?>">
                    已兑换
                  </a>
                <?php } ?>
              </p>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>  
  </div>
   <div class="c-modal">
    <!--<label for="modal-membercard">-->
    <!--  <div id="modal-trigger" class="modal-trigger u-three-quarters u-s-ms-one-eighth">test</div>-->
    <!--</label>-->
    <input class="modal-state" id="modal-checkbox" type="checkbox" />
    <div class="modal-fade-screen">
      <div class="modal-inner">
        <div class="modal-close" for="modal-membercard">X</div>
        <h1 class="u-text-align-center">提示</h1>
        <p class="modal-intro"></p>
        <p class="modal-content">
          <a href="#" class="c-button modal-cancel u-width-two-fifths">取消</a>
          <a href="#" id="btn_redeem_prize" data-prize-id="" class="c-button c-button--secondary u-width-two-fifths u-float-right">确定</a>
        </p>
      </div>
    </div>
  </div>
    <script type="text/javascript" src="../assets/js/zepto.min.js"></script>
    <script type="text/javascript" src="../assets/js/spin.min.js"></script>
    <script>
      $(function() {
        $(".modal-trigger").on("click", function() {
           var prize_id = $(this).attr('data-prize-id');
           var prize_title = $(this).attr('data-prize-title');
           $(".c-modal .modal-intro").text("确定兑换" + prize_title + "吗？" )
           $("#btn_redeem_prize").attr('data-prize-id', prize_id);
           $("#modal-checkbox").prop("checked", true);
        });
        $("#modal-checkbox").on("change", function() {
          if ($(this).is(":checked")) {
            $("body").addClass("modal-open");
          } else {
            $("body").removeClass("modal-open");
          }
        });
      
        $(".modal-fade-screen, .modal-close, .modal-cancel").on("click", function() {
          $(".modal-state:checked").prop("checked", false).change();
          // reset state
          $("#btn_redeem_prize").text("确定").data('disabled', 'false');
        });
      
        $(".modal-inner").on("click", function(e) {
          e.stopPropagation();
        });
        
        // 兑换礼品
        $("#btn_redeem_prize").click(function(event) {
          event.preventDefault();
          if ($(this).data('disabled') == "true") return false;
          var _this = this;
          
          var prize_id = $(this).attr('data-prize-id');
          if (prize_id == "") { alert("发生未知错误"); return false; }
          
          // todo: add redeem spinner (like wechat)
          closeModal();
          showSpinnerBox();
          $.ajax({
            type: 'POST',
            url: '../api/redeemCreditPrize.php',
            dataType: 'json',
            data: {
              'prize_id':  prize_id,
            },
            success: function(result) {
              // spinner.stop();
              $(".modal-state:checked").prop("checked", false).change();
              $(_this).text("确定").data('disabled', 'false');
              if (result.success == true) {
                alert("兑换成功");
                window.location.href="/prizes";
              } else {
                alert("兑换失败，原因" + result.errMsg);
              }
            },
            error: function(xhr, status, error) {
              // spinner.stop();
              $(_this).text("确定").data('disabled', 'false');
              alert("兑换失败");
              console.log("兑换失败: " + error);
            },
            complete: function() {
              hideSpinnerBox();
            }
          });
          
        });
        
        // spinner box
        // parent is dom object
      });
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
      function closeModal() {
        $(".modal-state:checked").prop("checked", false).change();
      }
    </script>
</body>
</html>
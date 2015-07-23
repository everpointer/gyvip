<?php
require_once '../common.php';
require_once '../checkMember.php';
require_once '../sdk/leancloud/AV.php';

if (!isset($memberInfo)) {
  header("Location: index.php");
}

if (!isset($_GET['member_prize_id'])) {
  echo genError('Bad Request');
  exit;
}
$memberPrizeId = $_GET['member_prize_id'];
$memberPrize = array();
try {
  $memberPrizeQuery = new leancloud\AVQuery('MemberCreditPrize');
  $memberPrizeQuery->where('objectId', $memberPrizeId);
  $memberPrizeQuery->wherePointer('member', 'Member', $memberInfo['id']);
  $memberPrizeQuery->setLimit(1);
  $memberPrizeQuery->whereInclude('creditPrize');
  $memberPrizeResult = $memberPrizeQuery->find();
  if (empty($memberPrizeResult->results)) {
    echo genError('Server Error: Credit Prize not found');
    exit;
  }
  $memberPrize = $memberPrizeResult->results[0];
} catch (Exception $e) {
  echo genError('Server Error: Fail to query member prize');
  exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $memberPrize->creditPrize->name ?></title>
  <link rel="stylesheet" href="../assets/css/style.css" type="text/css" />
</head>
<body class="u-color-bg-primary">
  <div class="c-main-container u-s-pt-small">
    <div class="l-container">
       <?php if ($memberPrize->status == 'used') { ?>
        <div class='u-color-success u-text-align-center' style='padding:0.5em 1em;'>
          <div class='u-text-size-xxx-large'>已成功使用</div>
          <div>使用时间：<?php echo strftime('%Y-%m-%d %H:%M:%S', strtotime($memberPrize->usedAt)) ?></div>
        </div>
      <?php } ?>
      <div class="prize-usage">
        <h4 class="u-text-align-center u-s-m-small"><?php echo $memberPrize->creditPrize->name ?></h3> 
        <h5>奖品说明</h5>
        <p class="u-s-mb-small">
          <?php echo $memberPrize->creditPrize->description ?>
        </p>
        <h5>使用规则</h5>
        <p class="u-s-mb-small">
          <?php echo formatUsageRule($memberPrize->creditPrize->usageRule); ?>
        </p>
        <h5>适用门店</h5>
        <p class="u-s-mb-small">
          <?php if (!$memberPrize->creditPrize->availableStores || empty($memberPrize->creditPrize->availableStores)) { ?>
            所有
          <?php } else { echo implode("，", $memberPrize->creditPrize->availableStores); } ?>
        </p>
      </div>
    </div>  
    <div class="c-tabbar">
      <!-- 收银员使用 -->
      <?php if ($memberPrize->creditPrize->type == 'used_by_cashier' && $memberPrize->status == 'created') { ?>
        <a href="#" id="btn_use_prize" data-member-prize-id="<?php echo $memberPrize->objectId ?>" class="c-button c-tabbar-item--button">
          使用（收银员点击）
        </a>
      <?php } else if ($memberPrize->status == 'used') { ?>
        <a href="/prizes" class="c-button c-tabbar-item--button">
          使用完毕，返回
        </a>
      <?php } ?>
    </div>
  </div>
  <script type="text/javascript" src="../assets/js/zepto.min.js"></script>
  <script type="text/javascript" src="../assets/js/spin.min.js"></script>
  <script>
    $('.btn-toggle-prize-rule').click(function() {
      var prizeRuleDesc = $(this).parent().next('.prize-rule-desc');
      if (prizeRuleDesc && prizeRuleDesc.length > 0) {
        prizeRuleDesc.toggleClass('u-hide');
      }
    });
    $(function() {
      // 使用礼品
      $("#btn_use_prize").click(function(event) {
        event.preventDefault();
        if (!window.confirm('确定要使用吗？')) {
          return false; 
        } else {
          $(this)
        }
        
        var prize_id = $(this).attr('data-member-prize-id');
        if (prize_id == "") { alert("发生未知错误"); return false; }
        
        // todo: add redeem spinner (like wechat)
        showSpinnerBox();
        $.ajax({
          type: 'POST',
          url: '../api/useMemberCreditPrize.php',
          dataType: 'json',
          data: {
            'member_prize_id':  prize_id,
          },
          success: function(result) {
            // spinner.stop();
            if (result.success == true) {
              window.location.reload();
            } else {
              alert("兑换失败，原因" + result.errMsg);
            }
          },
          error: function(xhr, status, error) {
            // spinner.stop();
            alert("兑换失败");
            console.log("兑换失败: " + error);
          },
          complete: function() {
            hideSpinnerBox();
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
</div>
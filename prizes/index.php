<?php
require_once '../common.php';
require_once '../checkMember.php';
require_once '../sdk/leancloud/AV.php';

if (!isset($memberInfo)) {
  header("Location: index.php");
  exit;
}

$memberCreditPrizes = array();
try {
  $memberPrizeQuery = new leancloud\AVQuery("MemberCreditPrize");
  $memberPrizeQuery->wherePointer('member', 'Member', $memberInfo['id']);
  $memberPrizeQuery->whereInclude('creditPrize');
  $memberPrizeQuery->orderByAscending('status');
  $memberCreditPrizeResult = $memberPrizeQuery->find();
  $memberCreditPrizes = $memberCreditPrizeResult->results;
} catch (Exception $e) {
  //todo: add 404 page (or Server Error Page)
  die("Ops, something went wrong!");
}

?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>兑换记录</title>
  <link rel="stylesheet" href="../assets/css/style.css" type="text/css" />
</head>
<body class="u-color-bg-primary">
  <header class='c-navigation' role="banner">
    <a href="javascript:history.back();" class="c-navigation-nav-link c-navigation-nav-link--left">< 返回</a>
    <a href="/" class="c-navigation-nav-link c-navigation-nav-link--right">首页 ></a>
    <div class="c-navigation-title">兑换纪录</div>
  </header>
  <div class="c-main-container c-main-container--header">
    <div class="l-container">
      <p class="u-text-align-center"><em>请点击查看，了解详情和使用方式</em></p>
      <div class="prize-list">
        <?php foreach ($memberCreditPrizes as $prize) { ?>
          <div class="c-media c-media--no-margin">
            <!--100*68px 为最佳比例-->
            <div class="c-media-image">
              <!-- todo: add image url -->
              <img src="<?php echo $prize->creditPrize->imageUrl ?>" alt="Logo image">
            </div>
            <div class="c-media-content">
              <h5><?php echo $prize->creditPrize->name ?></h5>
              <p>
                兑换时间：<?php echo strftime('%Y-%m-%d', strtotime($prize->creditPrize->createdAt)); ?>
                <?php if ($prize->status == "created") { ?>
                  <a href="show.php?member_prize_id=<?php echo $prize->objectId ?>"class="c-button c-button--small u-float-right modal-trigger" data-member-prize-id="<?php echo $prize->objectId ?> ">
                    查看
                  </a>
                <?php } else if ($prize->status == "used") { ?>
                  <a class="c-button c-button--small c-button--light-gray u-float-right" data-member-prize-id="<?php echo $prize->objectId ?>" >
                    已使用
                  </a>
                <?php } ?>
              </p>
            </div>
          </div>
          <div class="prize-rule">
            <div class="prize-rule-toggle">
              <a href="#" class="btn-toggle-prize-rule">使用规则 v</a>
            </div>
            <p class="prize-rule-desc u-hide"><?php echo str_replace('\n', "<br />", $prize->creditPrize->usageRule); ?></p>
          </div>
        <?php } ?>
      </div>
    </div>  
  </div>
  <script type="text/javascript" src="../assets/js/zepto.min.js"></script>
  <script>
    $('.btn-toggle-prize-rule').click(function() {
      var prizeRuleDesc = $(this).parent().next('.prize-rule-desc');
      if (prizeRuleDesc && prizeRuleDesc.length > 0) {
        prizeRuleDesc.toggleClass('u-hide');
      }
    });
  </script>
</body>
</div>
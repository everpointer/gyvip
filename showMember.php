<?php
require_once 'common.php';
require_once 'checkMember.php';

if (!isset($memberInfo)) {
  header("Location: index.php");
}
$member_ad_badge = getenv('member_ad_badge');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>会员中心</title>
    <!-- <script src="assets/js/mui.min.js"></script> -->
    <link href="assets/css/iconfont.css" rel="stylesheet"> 
    <link href="assets/css/mui.min.css" rel="stylesheet"/>
    <link href="assets/css/ny-style.css" rel="stylesheet"/>
    <style type="text/css">
      .title {
        margin: 20px 15px 7px;
        color: #6d6d72;
        font-size: 15px;
      }
      .CardNum, .CardNum .title {
          margin-bottom: 10px;
          margin-top: 10px;
          font-size: 15px;
          text-align: center;
      }
    </style>
</head>
<body>
  <div class="mui-content mui-content-padded">
    <div class="memberCard">
      <div class="overimg">
        <img style="width:80%;margin-top: 30px;" src="assets/images/果忆会员卡.gif"/>
        <i class="light"></i>
      </div>
    </div>
    <div class="CardNum">
      <div class="title">
        使用时请向收银员出示此卡
      </div>
      <div class="barcode">
        <img id="barcode" />
        <div class="cardnumber">
          <?php echo $memberInfo['cardNumber']; ?>
        </div>
      </div>
      <!--<div class="barcode">-->
      <!--  <img alt="barcode" src="barcode.php?size=45&text=<?php echo $memberInfo['cardNumber']; ?>" />-->
        
      <!--  <div class="cardnumber">-->
      <!--    <?php echo $memberInfo['cardNumber']; ?>-->
      <!--  </div>-->
      <!--</div>-->
    </div>
    <div class="title">
      会员特权
    </div>
      <ul class="mui-table-view">
        <li class="mui-table-view-cell">
          <a class="mui-navigate-right" href="/credit">
            <span class="mui-icon iconfont icon-duihuan"></span>
            <span class="li-con">积分兑换</span>
            <?php if (!empty($member_ad_badge)) { ?>
              <span class="mui-badge mui-badge-danger"><?php echo $member_ad_badge; ?></span>
            <?php } ?>
          </a>
        <iframe id="tmp_downloadhelper_iframe" style="display: none;"></iframe></li>
        <!--
        <li class="mui-table-view-cell">
          <a class="mui-navigate-right">
            <span class="mui-icon  iconfont  icon-jifen"></span>
            <span class="li-con">我的积分</span>
          </a>
        </li>
        <li class="mui-table-view-cell">
          <a class="mui-navigate-right">
            <span class="mui-icon  iconfont  icon-shangcheng"></span>
            <span class="li-con">最新活动</span>
          </a>
        </li>-->
      </ul>
    
    
    <div class="title">
      关于会员卡
    </div>
      <ul class="mui-table-view">
        <li class="mui-table-view-cell">
          <a class="mui-navigate-right" href="articles/membercard.html">
            <span class="mui-icon  iconfont  icon-huiyuanqia"></span>
            <span class="li-con">会员卡详情</span>
          </a>
        <iframe id="tmp_downloadhelper_iframe" style="display: none;"></iframe></li>
        <li class="mui-table-view-cell">
          <a class="mui-navigate-right" href="https://yuntu.amap.com/share/mquMfq">
            <span class="mui-icon  mui-icon-location"></span>
            <span class="li-con">适用门店</span>
          </a>
        </li>
        <li class="mui-table-view-cell">
          <a class="mui-navigate-right" href="https://h5.youzan.com/v2/im?c=wsc&v=2&kdt_id=273165#talk!id=273165&sf=wx_menu">
            <span class="mui-icon  iconfont  icon-kefu"></span>
            <span class="li-con">在线客服</span>
          </a>
        </li>
      </ul>
      
      <div class="bottomTitle title">
        果忆 · 我的美好水果记忆<br/>
        宁波花果山果品有限公司©2015
      </div>
  </div>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/JsBarcode.all.min.js"></script>
  <script type="text/javascript" charset="utf-8">
    $(function() {
      $("#barcode").JsBarcode("<?php echo $memberInfo['cardNumber']; ?>",
        {height: 50, width: 1.5});
    });
  </script>
</body>
</html>
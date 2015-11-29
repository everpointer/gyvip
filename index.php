<?php
require_once 'common.php';
require_once 'checkMember.php';

if (!$_SESSION['uid']) {
  header($_SERVER["SERVER_PROTOCOL"]." 500 Bad Request"); 
  exit;
}

if (isset($memberInfo)) {
  header("Location: showMember.php");
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>会员中心</title>
    <!--<script src="assets/js/mui.min.js"></script>-->
    <link href="assets/css/iconfont.css" rel="stylesheet"> 
    <link href="assets/css/mui.min.css" rel="stylesheet"/>
    <link href="assets/css/ny-style.css" rel="stylesheet"/>
 </head>
<body>
	<div class="mui-content mui-content-padded">
		<div class="memberCard">
			<img class="gray" style="width:80%;margin-top: 30px;" src="assets/images/果忆会员卡.gif"/>
			<div class="CardNum">
				<div class="title">
					您的电子会员卡未激活😂
				</div>
			</div>
		</div>
		
		<div>
			<a href="bindMember.php" type="button" class="mui-btn mui-btn-success btnBindCard">绑定实体会员卡</a>
		</div>
		
			<ul class="mui-table-view">
				<!--
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right">
						<span class="mui-icon  iconfont  icon-icon-bind"></span>
						<span class="li-con">绑定实体会员卡</span>
					</a>
				<iframe id="tmp_downloadhelper_iframe" style="display: none;"></iframe></li>-->
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="purchase.php">
						<span class="mui-icon  iconfont  icon-icon-buy"></span>
						<span class="li-con">购买电子会员卡</span>
					</a>
				</li>
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
					<a class="mui-navigate-right" href="https://wap.koudaitong.com/v2/showcase/physicalstore?kdt_id=273165&sf=wx_menu">
						<span class="mui-icon  mui-icon-location"></span>
						<span class="li-con">适用门店</span>
					</a>
				</li>
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right">
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
	<script type="text/javascript" charset="utf-8">
    // mui.init();
  </script>	
</body>
</html>
<?php
}
?>
<?php
require_once 'common.php';

if (!isset($memberInfo)) exit("您还不是会员");
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="assets/css/member.css" media="all">
  <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css" media="all">
  <title>会员中心</title>
  <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
  <!-- Mobile Devices Support @begin -->
  <meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
  <meta content="no-cache" http-equiv="pragma">
  <meta content="0" http-equiv="expires">
  <meta content="telephone=no, address=no" name="format-detection">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <!-- apple devices fullscreen -->
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <!-- Mobile Devices Support @end -->
</head>
<body onselectstart="return true;" ondragstart="return false;">
    <div class="container card">
      <header>
        <div class="header card">
          <div id="card" data-role="card">
            <div class="front" style="background-image:url(assets/image/member-card.jpg);">
              <span class="no" style="max-width: 280px; font-size:14px; color:#FFFFFF; top:145px; left:170px; bottom:inherit; right:inherit;">
                <?php echo $config['cardNumber']; ?>
              </span>
            </div>
          </div>
        </div>
      </header>
      <div class="body">
        <ul class="list_ul">
          <div>
            <li class="li_i">
              <a class="label" href="tel:<?php echo $memberInfo['mobile'] ?>">
              <i>&nbsp;</i><?php echo $memberInfo['mobile'] ?><span> &nbsp;</span>
              </a>
            </li>
          </div>
        </ul>
      </div>
    </div>
  </body>
</html>

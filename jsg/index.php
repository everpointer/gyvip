<?php
// 设置时区
date_default_timezone_set("PRC");
@session_start();
$config = (require '../config.php');
// $_SESSION['uid'] = '20881016718708634955964451718217';
if (!isset($_SESSION['uid'])) {
  // wechat oauth using wechat-oauth package
  $oauth = new \Henter\WeChat\OAuth($config['wechat']['app_id'], $config['wechat']['app_secret']);
  if (!isset($_GET['code'])) {
    $callback_url = $config['host'] . "/jsg?platform=wechat";
    $url = $oauth->getWeChatAuthorizeURL($callback_url, 'snsapi_userinfo');
    header("Location: $url");
    exit;
  } else {
    $code = $_GET['code'];
    if($access_token = $oauth->getAccessToken('code', $code)){
      $refresh_token = $oauth->getRefreshToken();
      $expires_in = $oauth->getExpiresIn();
      $openid = $oauth->getOpenid();
      $_SESSION['uid'] = $openid;
      $_SESSION['platform'] = $platform;
    }else{
      // echo $oauth->error();
      exit("User fail to authorized!");
    }
  }
}
$uid = $_SESSION['uid'];
// get token and jsapi_ticket
require_once "jssdk.php";
$jssdk = new JSSDK($config['wechat']['app_id'], $config['wechat']['app_secret']);
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" /> 
  <meta http-equiv="Pragma" content="no-cache" /> 
  <meta http-equiv="Expires" content="0" />
  <title>果忆集仕港店盛大开业</title>
  <link rel="stylesheet" href="https://su.yzcdn.cn/v2/build_css/stylesheets/wap/base_2dafc42a32.css" onerror="_cdnFallback(this)">
  <link rel="stylesheet" href="https://su.yzcdn.cn/v2/build_css/stylesheets/wap/pages/showcase/mp_news_30b1ca7f2c.css" onerror="_cdnFallback(this)">
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
  <div>
   <div id="shareTimeline" class="c-button c-button--center">点击分享到朋友圈，领取奖品</div>
   <div class="container rich_media" style="min-height: 710px;">
    <div class="rich_media_inner content ">
        <h2 class="rich_media_title" id="activity-name">
        果忆集仕港店9.26日盛大开业        </h2>
        <div class="rich_media_meta_list">
            <em id="post-date" class="rich_media_meta text">2015-09-22</em>
            <em class="rich_media_meta text"></em>
            <a class="rich_media_meta link nickname js-no-follow js-open-follow" href="javascript:;" id="post-user">果忆鲜果</a>
        </div>
        <div id="page-content" class="content">
            <div id="img-content">
                
                <div class="rich_media_content" id="js_content">
                    <section class="pEditor" data-id="1820" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><section style="margin: 5px; border: 1px solid rgb(23, 171, 87); text-align: center;"><section style="padding: 0px; margin: 0px auto; min-height: 10em; color: rgb(255, 255, 238); border-color: rgb(87, 209, 140); background-color: rgb(23, 171, 87);"><section style="margin: 10px; display: inline-block; border-color: rgb(23, 171, 87); padding: 5px; color: inherit;"><p style="margin-bottom: 5px; color: inherit; border-color: rgb(23, 171, 87); font-size: 24px;"><strong>盛大开业</strong></p></section></section><section style="display: inline-block; margin-top: -6em; color: inherit;"><img src="https://mmbiz.qlogo.cn/mmbiz/4Jia5vVic4cNz7tmapMjxY52lKvZ2Yuuy7WRRgjLDcibtLbjW7nQvpcQtmpsGx1KZjHw45ysmUx7K31icUMoIbyg6Q/0?wx_fmt=jpeg" title="1442903050609081209.jpg" alt="0908logo.jpg" style="box-sizing: border-box; border-radius: 50%; color: inherit; margin: 0px; padding: 0px; height: 12em !important; width: 12em !important; background-color: rgb(255, 255, 255);"></section><section style="max-width: 100%; margin: 0.5em; color: inherit;"><section style="height: 36px; display: inline-block; color: rgb(255, 255, 238); font-weight: bold; padding: 0px 10px; line-height: 36px; vertical-align: top; border-color: rgb(87, 209, 140); box-sizing: border-box !important; background-color: rgb(23, 171, 87);"><span style="color: rgb(255, 255, 255);">No.23 集仕港店</span></section></section><section style="padding: 5px 10px 15px; text-align: justify; color: inherit;"><p style="margin-bottom: 5px; color: inherit;">花果山第23家新店－集仕港店，将于9.26日盛大开业。</p></section></section></section><p><br></p><section class="pEditor" data-id="1799" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><section style="border: 0px; vertical-align: top; margin: 0.8em 0px 0.5em; box-sizing: border-box; padding: 0px;"><section style="border-left-width: 1px; border-left-style: solid; font-size: 1.2em; font-family: inherit; font-weight: inherit; text-decoration: inherit; color: rgb(23, 171, 87); border-color: rgb(23, 171, 87); box-sizing: border-box;"><section style="height: 1.5em; border-left-width: 5px; border-left-style: solid; line-height: 1.5em; padding-left: 5px; border-color: rgb(23, 171, 87); box-sizing: border-box; color: inherit;"><section style="box-sizing: border-box; border-color: rgb(23, 171, 87); color: inherit;">集仕港店</section></section><section style="margin: 10px 0px 0px 10px; font-size: 0.8em; font-family: inherit; font-weight: inherit; text-decoration: inherit; color: rgb(0, 0, 0); box-sizing: border-box;"><p style="margin-bottom: 5px; color: inherit;"><span style="color: rgb(62, 62, 62); font-size: 14px; line-height: 28px; text-indent: 28px;">门店地址：集仕港老菜场南门对面利时购物广场1-4</span></p><p style="margin-bottom: 5px; color: inherit;"><span style="color: rgb(62, 62, 62); font-size: 14px; line-height: 28px; text-indent: 28px;">门店电话：0574-87257758</span></p></section></section></section></section><section class="pEditor" data-id="1664" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><section><section style="margin: 0.5em 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); line-height: 25px; border: 0px rgb(23, 171, 87); word-wrap: break-word !important;"><section style="margin: 0px; padding: 0px; width: 845px; box-sizing: border-box; display: inline-block; text-align: center; color: inherit; word-wrap: break-word !important;"><img src="https://mmbiz.qlogo.cn/mmbiz/DCE5nxPSl5QnCJprAzcyRfQw0UQngRAvwya3eiaIEKxMg2r28oYU19My4bN8K2GPic2ic5z1EH4v87QDrEiaTxq9cQ/0?wx_fmt=png" title="1434359050208032993.png" alt="新媒体排版" style="box-sizing: border-box; color: inherit; height: 65px; margin: 0px auto; padding: 0px; width: 60px; visibility: visible !important; word-wrap: break-word !important; background-color: rgb(255, 255, 255);"></section><section style="margin: -2.3em 0px 0px; padding: 2em 0px 0px; max-width: 100%; box-sizing: border-box; min-height: 15em; font-size: 1em; font-weight: inherit; text-decoration: inherit; color: rgb(255, 255, 238); border-color: rgb(87, 209, 140); word-wrap: break-word !important; background-image: url(http://mmbiz.qlogo.cn/mmbiz/ugnYPLVSd8sGQrPwFE4piaiacUlJzd4hzQAFQAnTZcCvVibdU0HJP8yRxNE4EKn3BzPNFfiaPfYjPrKSmw3R37UhTg/0); background-color: rgb(23, 171, 87); background-repeat: repeat;"><section style="margin: 0px auto; padding: 0.5em; max-width: 100%; box-sizing: border-box; width: 7em; height: 3.5em; line-height: 2em; overflow: hidden; -webkit-transform: rotate(-5deg); font-size: 32px; font-weight: inherit; text-align: center; text-decoration: inherit; color: inherit; border-color: rgb(23, 171, 87); word-wrap: break-word !important; background-image: url(https://mmbiz.qlogo.cn/mmbiz/DCE5nxPSl5QnCJprAzcyRfQw0UQngRAvREs4lnWVwJxWcrOribGV3Jv129NQY6HlVTtGBdh5WFpRTIWjpuC0Zpg/0?wx_fmt=png); background-size: contain; background-repeat: no-repeat;"><section style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; border-color: rgb(23, 171, 87); color: inherit; word-wrap: break-word !important;"><span style="box-sizing: border-box; color: inherit; margin: 0px; max-width: 100%; padding: 0px; border-color: rgb(23, 171, 87); word-wrap: break-word !important;">开业活动</span></section></section><section style="margin: 0px; padding: 1em; max-width: 100%; box-sizing: border-box; border-color: rgb(23, 171, 87); color: inherit; word-wrap: break-word !important;"><section class="pBrush" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: inherit; border-color: rgb(23, 171, 87); word-wrap: break-word !important;"><p style="margin-bottom: 5px; border-color: rgb(23, 171, 87); color: inherit;">活动时间：9.26 ～ 9.28日<br></p><p style="margin-bottom: 5px; border-color: rgb(23, 171, 87); color: inherit;">活动说明：</p><p style="margin-bottom: 5px; border-color: rgb(23, 171, 87); color: inherit;">&nbsp; 1. 消费抽奖，100%中奖</p><p style="margin-bottom: 5px; border-color: rgb(23, 171, 87); color: inherit;">&nbsp; 2. 促销产品，开业价</p><p style="margin-bottom: 5px; border-color: rgb(23, 171, 87); color: inherit;">&nbsp; 3. 满28元赠价值10元的会员卡一张</p></section></section></section></section></section></section><section class="pEditor" data-id="1842" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><section style="border: 0px rgb(23, 171, 87); margin: 0.5em 0px; box-sizing: border-box; padding: 0px;"><section style="display: inline-block; width: 1.6em; height: 1.6em; border-radius: 50%; font-size: 3em; line-height: 1.5; text-align: center; text-decoration: inherit; color: rgb(255, 255, 238); border-color: rgb(87, 209, 140); box-sizing: border-box; background-color: rgb(23, 171, 87);"><span style="font-family: 微软雅黑, 'microsoft yahei' border-color: rgb(23, 171, 87); color: inherit;">抽</span></section><section style="display: inline-block; margin-left: 0.7em; margin-top: 2px; padding: 0.6em 0px; box-sizing: border-box; color: inherit;"><section class="pBrush" style="line-height: 1.4; font-size: 1.5em; text-align: inherit; text-decoration: inherit; color: rgb(23, 171, 87); box-sizing: border-box; border-color: rgb(23, 171, 87);"><span style="font-family: inherit; border-color: rgb(23, 171, 87); color: inherit;">六重大奖</span></section><section style="line-height: 1.4; margin-left: 0.2em; font-size: 1em; text-align: inherit; text-decoration: inherit; color: rgb(23, 171, 87); box-sizing: border-box; border-color: rgb(23, 171, 87);"><span style="color: inherit; border-color: rgb(23, 171, 87);">100%中奖，抽完为止</span></section></section></section></section><section class="pEditor" data-id="1762" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><table align="left" cellpadding="0" cellspacing="0" width="844" style="width: 426px;"><tbody style="color: inherit;"><tr height="18" class="firstRow" style="color: inherit;"><td align="right" colspan="1" rowspan="3" valign="top" width="161" style="border-color: rgb(23, 171, 87); word-break: break-all; text-align: right; vertical-align: top; color: inherit;"><p style="color: inherit;"><span style="font-size: 14px;">特等奖</span></p><p style="color: inherit;"><span style="font-size: 14px;">一等奖</span></p><p style="color: inherit;"><span style="font-size: 14px;">二等奖</span></p><p style="color: inherit;"><span style="font-size: 14px;">三等奖</span></p><p style="color: inherit;"><span style="font-size: 14px;">幸运奖</span></p><p style="color: inherit;"><span style="font-size: 14px;">纪念奖</span></p></td><td align="left" colspan="1" rowspan="3" valign="top" width="166" style="border-color: rgb(23, 171, 87); vertical-align: top; color: inherit; word-break: break-all;"><p style="color: inherit;"><span style="font-size: 14px;">500元储值卡一张</span></p><p style="color: inherit;"><span style="font-size: 14px; color: inherit;">288元储值卡一张</span></p><p style="color: inherit;">68元冬枣一箱<br></p><p style="color: inherit;"><span style="font-size: 14px;">18元果忆定制雨伞1把</span></p><p style="color: inherit;"><span style="font-size: 14px; color: inherit;">8元爆米花一桶</span></p><p style="color: inherit;"><span style="font-size: 14px;">精美礼品一份</span></p></td></tr><tr></tr><tr></tr></tbody></table></section><section class="pEditor" data-id="1842" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><section style="border: 0px rgb(23, 171, 87); margin: 0.5em 0px; box-sizing: border-box; padding: 0px;"><section style="display: inline-block; width: 1.6em; height: 1.6em; border-radius: 50%; font-size: 3em; line-height: 1.5; text-align: center; text-decoration: inherit; color: rgb(255, 255, 238); border-color: rgb(87, 209, 140); box-sizing: border-box; background-color: rgb(23, 171, 87);"><span style="font-family: 微软雅黑, 'microsoft yahei' border-color: rgb(23, 171, 87); color: inherit;">抢</span></section><section style="display: inline-block; margin-left: 0.7em; margin-top: 2px; padding: 0.6em 0px; box-sizing: border-box; color: inherit;"><section class="pBrush" style="line-height: 1.4; font-size: 1.5em; text-align: inherit; text-decoration: inherit; color: rgb(23, 171, 87); box-sizing: border-box; border-color: rgb(23, 171, 87);">促销产品</section><section style="line-height: 1.4; margin-left: 0.2em; font-size: 1em; text-align: inherit; text-decoration: inherit; color: rgb(23, 171, 87); box-sizing: border-box; border-color: rgb(23, 171, 87);"><span style="color: inherit; border-color: rgb(23, 171, 87);">总有一款让你心动</span></section></section></section></section><section class="pEditor" data-id="1832" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><blockquote style="padding-top: 20px; padding-right: 15px; padding-bottom: 20px; border: none rgb(23, 171, 87); word-wrap: break-word !important; box-sizing: border-box !important; background-image: url(https://mmbiz.qlogo.cn/mmbiz/cZV2hRpuAPia3RFX6Mvw06kePJ7HbmI7bklicgzC7wP3YqUUCN0ibiaRCSDQicpGPwBvHCgcMwsSkbr4DrxlK12vI9A/0?wx_fmt=png); background-size: contain; background-repeat: repeat;"><section style="padding: 0px; text-align: center; margin: 0px auto; border-top-width: 2px; border-top-style: solid; border-color: rgb(23, 171, 87); background-color: rgb(254, 254, 254);"><section style="color: inherit; display: inline-block; margin: 5px 0px; border-color: rgb(23, 171, 87);"><section style="margin: 10px; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 0.8em 0px 0.5em; line-height: 32px; font-weight: bold; display: inline-block; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 8px; display: inline-block; float: left; width: 0px; height: 0px; border-left-width: 8px; border-left-style: solid; border-color: rgb(23, 171, 87) transparent; border-right-width: 8px; border-right-style: solid; border-bottom-width: 16px; border-bottom-style: solid; color: inherit;"></section><section class="pBrush" style="margin-left: 36px; color: inherit; text-align: left; border-color: rgb(23, 171, 87);">徐香猕猴桃</section></section></section><p style="margin: 10px 20px; border-color: rgb(23, 171, 87); color: inherit;"><img src="http://media.freshfresh.com/media/catalog/product/cache/1/small_image/413x413/af097278c5db4767b0fe9bb92fe21690/_/2/_2_37_3.jpg" width="" height="" border="" vspace="" title="" alt="" style="box-sizing: border-box; border-color: rgb(198, 198, 198); color: inherit; background-color: rgb(255, 255, 255);"></p></section><section style="margin: 0px; text-align: left; border-color: rgb(23, 171, 87);"><p style="margin: 0px 20px 10px; font-size: 14px; padding: 5px;"><span style="color: rgb(255, 0, 0); background-color: rgb(255, 255, 0);"><em>开业价</em></span></p><p style="margin-right: 20px; margin-bottom: 5px; margin-left: 20px; color: inherit; font-size: 20px; padding: 0px 5px 10px; border-color: rgb(198, 198, 198);"><span style="border-color: rgb(198, 198, 198); color: rgb(0, 176, 80);">?.98元/斤</span></p></section></section></blockquote></section><section class="pEditor" data-id="1832" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><blockquote style="padding-top: 20px; padding-right: 15px; padding-bottom: 20px; border: none rgb(23, 171, 87); word-wrap: break-word !important; box-sizing: border-box !important; background-image: url(https://mmbiz.qlogo.cn/mmbiz/cZV2hRpuAPia3RFX6Mvw06kePJ7HbmI7bklicgzC7wP3YqUUCN0ibiaRCSDQicpGPwBvHCgcMwsSkbr4DrxlK12vI9A/0?wx_fmt=png); background-size: contain; background-repeat: repeat;"><section style="padding: 0px; text-align: center; margin: 0px auto; border-top-width: 2px; border-top-style: solid; border-color: rgb(23, 171, 87); background-color: rgb(254, 254, 254);"><section style="color: inherit; display: inline-block; margin: 5px 0px; border-color: rgb(23, 171, 87);"><section style="margin: 10px; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 0.8em 0px 0.5em; line-height: 32px; font-weight: bold; display: inline-block; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 8px; display: inline-block; float: left; width: 0px; height: 0px; border-left-width: 8px; border-left-style: solid; border-color: rgb(23, 171, 87) transparent; border-right-width: 8px; border-right-style: solid; border-bottom-width: 16px; border-bottom-style: solid; color: inherit;"></section><section class="pBrush" style="margin-left: 36px; color: inherit; text-align: left; border-color: rgb(23, 171, 87);">四川甜石榴</section></section></section><p style="margin: 10px 20px; border-color: rgb(23, 171, 87); color: inherit;"><img src="http://media.freshfresh.com/media/catalog/product/cache/1/small_image/413x413/af097278c5db4767b0fe9bb92fe21690/_/1/_1_39_1.jpg" width="" height="" border="" vspace="" title="" alt="" style="box-sizing: border-box; border-color: rgb(198, 198, 198); color: inherit; background-color: rgb(255, 255, 255);"></p></section><section style="margin: 0px; text-align: left; border-color: rgb(23, 171, 87);"><p style="margin: 0px 20px 10px; font-size: 14px; padding: 5px;"><span style="color: rgb(255, 0, 0); background-color: rgb(255, 255, 0);"><em>开业价</em></span></p><p style="margin-right: 20px; margin-bottom: 5px; margin-left: 20px; color: inherit; font-size: 20px; padding: 0px 5px 10px; border-color: rgb(198, 198, 198);"><span style="border-color: rgb(198, 198, 198); color: rgb(0, 176, 80);">?元/4只</span></p></section></section></blockquote></section><section class="pEditor" data-id="1832" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><blockquote style="padding-top: 20px; padding-right: 15px; padding-bottom: 20px; border: none rgb(23, 171, 87); word-wrap: break-word !important; box-sizing: border-box !important; background-image: url(https://mmbiz.qlogo.cn/mmbiz/cZV2hRpuAPia3RFX6Mvw06kePJ7HbmI7bklicgzC7wP3YqUUCN0ibiaRCSDQicpGPwBvHCgcMwsSkbr4DrxlK12vI9A/0?wx_fmt=png); background-size: contain; background-repeat: repeat;"><section style="padding: 0px; text-align: center; margin: 0px auto; border-top-width: 2px; border-top-style: solid; border-color: rgb(23, 171, 87); background-color: rgb(254, 254, 254);"><section style="color: inherit; display: inline-block; margin: 5px 0px; border-color: rgb(23, 171, 87);"><section style="margin: 10px; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 0.8em 0px 0.5em; line-height: 32px; font-weight: bold; display: inline-block; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 8px; display: inline-block; float: left; width: 0px; height: 0px; border-left-width: 8px; border-left-style: solid; border-color: rgb(23, 171, 87) transparent; border-right-width: 8px; border-right-style: solid; border-bottom-width: 16px; border-bottom-style: solid; color: inherit;"></section><section class="pBrush" style="margin-left: 36px; color: inherit; text-align: left; border-color: rgb(23, 171, 87);">江西蜜桔</section></section></section><p style="margin: 10px 20px; border-color: rgb(23, 171, 87); color: inherit;"><img src="https://dn-kdt-img.qbox.me/upload_files/2015/09/22/Fszx2NkpkmRxXjzWcrdrcF4cAczp.jpeg!730x0.jpg"></p></section><section style="margin: 0px; text-align: left; border-color: rgb(23, 171, 87);"><p style="margin: 0px 20px 10px; font-size: 14px; padding: 5px;"><span style="color: rgb(255, 0, 0); background-color: rgb(255, 255, 0);"><em>开业价</em></span></p><p style="margin-right: 20px; margin-bottom: 5px; margin-left: 20px; color: inherit; font-size: 20px; padding: 0px 5px 10px; border-color: rgb(198, 198, 198);"><span style="border-color: rgb(198, 198, 198); color: rgb(0, 176, 80); font-size: 20px;">?.58元/斤</span></p></section></section></blockquote></section><section class="pEditor" data-id="1832" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><blockquote style="padding-top: 20px; padding-right: 15px; padding-bottom: 20px; border: none rgb(23, 171, 87); word-wrap: break-word !important; box-sizing: border-box !important; background-image: url(https://mmbiz.qlogo.cn/mmbiz/cZV2hRpuAPia3RFX6Mvw06kePJ7HbmI7bklicgzC7wP3YqUUCN0ibiaRCSDQicpGPwBvHCgcMwsSkbr4DrxlK12vI9A/0?wx_fmt=png); background-size: contain; background-repeat: repeat;"><section style="padding: 0px; text-align: center; margin: 0px auto; border-top-width: 2px; border-top-style: solid; border-color: rgb(23, 171, 87); background-color: rgb(254, 254, 254);"><section style="color: inherit; display: inline-block; margin: 5px 0px; border-color: rgb(23, 171, 87);"><section style="margin: 10px; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 0.8em 0px 0.5em; line-height: 32px; font-weight: bold; display: inline-block; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 8px; display: inline-block; float: left; width: 0px; height: 0px; border-left-width: 8px; border-left-style: solid; border-color: rgb(23, 171, 87) transparent; border-right-width: 8px; border-right-style: solid; border-bottom-width: 16px; border-bottom-style: solid; color: inherit;"></section><section class="pBrush" style="margin-left: 36px; color: inherit; text-align: left; border-color: rgb(23, 171, 87);">红富士苹果</section></section></section><p style="margin: 10px 20px; border-color: rgb(23, 171, 87); color: inherit;"><img src="http://media.freshfresh.com/media/catalog/product/cache/1/small_image/413x413/af097278c5db4767b0fe9bb92fe21690/4/-/4-10_1.jpg" width="" height="" border="" vspace="" title="" alt="" style="box-sizing: border-box; border-color: rgb(198, 198, 198); color: inherit; background-color: rgb(255, 255, 255);"></p></section><section style="margin: 0px; text-align: left; border-color: rgb(23, 171, 87);"><p style="margin: 0px 20px 10px; font-size: 14px; padding: 5px;"><span style="color: rgb(255, 0, 0); background-color: rgb(255, 255, 0);"><em>开业价</em></span></p><p style="margin-right: 20px; margin-bottom: 5px; margin-left: 20px; color: inherit; font-size: 20px; padding: 0px 5px 10px; border-color: rgb(198, 198, 198);"><span style="border-color: rgb(198, 198, 198); color: rgb(0, 176, 80);">?.28元/斤</span></p></section></section></blockquote></section><section class="pEditor" data-id="1832" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><blockquote style="padding-top: 20px; padding-right: 15px; padding-bottom: 20px; border: none rgb(23, 171, 87); word-wrap: break-word !important; box-sizing: border-box !important; background-image: url(https://mmbiz.qlogo.cn/mmbiz/cZV2hRpuAPia3RFX6Mvw06kePJ7HbmI7bklicgzC7wP3YqUUCN0ibiaRCSDQicpGPwBvHCgcMwsSkbr4DrxlK12vI9A/0?wx_fmt=png); background-size: contain; background-repeat: repeat;"><section style="padding: 0px; text-align: center; margin: 0px auto; border-top-width: 2px; border-top-style: solid; border-color: rgb(23, 171, 87); background-color: rgb(254, 254, 254);"><section style="color: inherit; display: inline-block; margin: 5px 0px; border-color: rgb(23, 171, 87);"><section style="margin: 10px; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 0.8em 0px 0.5em; line-height: 32px; font-weight: bold; display: inline-block; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 8px; display: inline-block; float: left; width: 0px; height: 0px; border-left-width: 8px; border-left-style: solid; border-color: rgb(23, 171, 87) transparent; border-right-width: 8px; border-right-style: solid; border-bottom-width: 16px; border-bottom-style: solid; color: inherit;"></section><section class="pBrush" style="margin-left: 36px; color: inherit; text-align: left; border-color: rgb(23, 171, 87);">琯溪蜜柚</section></section></section><p style="margin: 10px 20px; border-color: rgb(23, 171, 87); color: inherit;"><img src="http://media.freshfresh.com/media/catalog/product/cache/1/small_image/413x413/af097278c5db4767b0fe9bb92fe21690/9/-/9-18_1.jpg" width="" height="" border="" vspace="" title="" alt="" style="box-sizing: border-box; border-color: rgb(198, 198, 198); color: inherit; background-color: rgb(255, 255, 255);"></p></section><section style="margin: 0px; text-align: left; border-color: rgb(23, 171, 87);"><p style="margin: 0px 20px 10px; font-size: 14px; padding: 5px;"><span style="color: rgb(255, 0, 0); background-color: rgb(255, 255, 0);"><em>开业价</em></span></p><p style="margin-right: 20px; margin-bottom: 5px; margin-left: 20px; color: inherit; font-size: 20px; padding: 0px 5px 10px; border-color: rgb(198, 198, 198);"><span style="border-color: rgb(198, 198, 198); color: rgb(0, 176, 80);">?元/1只</span></p></section></section></blockquote></section><section class="pEditor" data-id="1832" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><blockquote style="padding-top: 20px; padding-right: 15px; padding-bottom: 20px; border: none rgb(23, 171, 87); word-wrap: break-word !important; box-sizing: border-box !important; background-image: url(https://mmbiz.qlogo.cn/mmbiz/cZV2hRpuAPia3RFX6Mvw06kePJ7HbmI7bklicgzC7wP3YqUUCN0ibiaRCSDQicpGPwBvHCgcMwsSkbr4DrxlK12vI9A/0?wx_fmt=png); background-size: contain; background-repeat: repeat;"><section style="padding: 0px; text-align: center; margin: 0px auto; border-top-width: 2px; border-top-style: solid; border-color: rgb(23, 171, 87); background-color: rgb(254, 254, 254);"><section style="color: inherit; display: inline-block; margin: 5px 0px; border-color: rgb(23, 171, 87);"><section style="margin: 10px; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 0.8em 0px 0.5em; line-height: 32px; font-weight: bold; display: inline-block; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 8px; display: inline-block; float: left; width: 0px; height: 0px; border-left-width: 8px; border-left-style: solid; border-color: rgb(23, 171, 87) transparent; border-right-width: 8px; border-right-style: solid; border-bottom-width: 16px; border-bottom-style: solid; color: inherit;"></section><section class="pBrush" style="margin-left: 36px; color: inherit; text-align: left; border-color: rgb(23, 171, 87);">进口甜橙</section></section></section><p style="margin: 10px 20px; border-color: rgb(23, 171, 87); color: inherit;"><img src="http://media.freshfresh.com/media/catalog/product/cache/1/small_image/413x413/af097278c5db4767b0fe9bb92fe21690/9/-/9-16_1_1.jpg" width="" height="" border="" vspace="" title="" alt="" style="box-sizing: border-box; border-color: rgb(198, 198, 198); color: inherit; background-color: rgb(255, 255, 255);"></p></section><section style="margin: 0px; text-align: left; border-color: rgb(23, 171, 87);"><p style="margin: 0px 20px 10px; font-size: 14px; padding: 5px;"><span style="color: rgb(255, 0, 0); background-color: rgb(255, 255, 0);"><em>开业价</em></span></p><p style="margin-right: 20px; margin-bottom: 5px; margin-left: 20px; color: inherit; font-size: 20px; padding: 0px 5px 10px; border-color: rgb(198, 198, 198);"><span style="border-color: rgb(198, 198, 198); color: rgb(0, 176, 80);">?.98元/斤</span></p></section></section></blockquote></section><section class="pEditor" data-id="1832" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><blockquote style="padding-top: 20px; padding-right: 15px; padding-bottom: 20px; border: none rgb(23, 171, 87); word-wrap: break-word !important; box-sizing: border-box !important; background-image: url(https://mmbiz.qlogo.cn/mmbiz/cZV2hRpuAPia3RFX6Mvw06kePJ7HbmI7bklicgzC7wP3YqUUCN0ibiaRCSDQicpGPwBvHCgcMwsSkbr4DrxlK12vI9A/0?wx_fmt=png); background-size: contain; background-repeat: repeat;"><section style="padding: 0px; text-align: center; margin: 0px auto; border-top-width: 2px; border-top-style: solid; border-color: rgb(23, 171, 87); background-color: rgb(254, 254, 254);"><section style="color: inherit; display: inline-block; margin: 5px 0px; border-color: rgb(23, 171, 87);"><section style="margin: 10px; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 0.8em 0px 0.5em; line-height: 32px; font-weight: bold; display: inline-block; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 8px; display: inline-block; float: left; width: 0px; height: 0px; border-left-width: 8px; border-left-style: solid; border-color: rgb(23, 171, 87) transparent; border-right-width: 8px; border-right-style: solid; border-bottom-width: 16px; border-bottom-style: solid; color: inherit;"></section><section class="pBrush" style="margin-left: 36px; color: inherit; text-align: left; border-color: rgb(23, 171, 87);">佳沛奇异果<br></section></section></section><p style="margin: 10px 20px; border-color: rgb(23, 171, 87); color: inherit;"><img src="http://media.freshfresh.com/media/catalog/product/cache/1/small_image/413x413/af097278c5db4767b0fe9bb92fe21690/7/-/7-14_25_2-3.jpg" width="" height="" border="" vspace="" title="" alt="" style="box-sizing: border-box; border-color: rgb(198, 198, 198); color: inherit; background-color: rgb(255, 255, 255);"></p></section><section style="margin: 0px; text-align: left; border-color: rgb(23, 171, 87);"><p style="margin: 0px 20px 10px; font-size: 14px; padding: 5px;"><span style="color: rgb(255, 0, 0); background-color: rgb(255, 255, 0);"><em>开业价</em></span></p><p style="margin-right: 20px; margin-bottom: 5px; margin-left: 20px; color: inherit; font-size: 20px; padding: 0px 5px 10px; border-color: rgb(198, 198, 198);"><span style="border-color: rgb(198, 198, 198); color: rgb(0, 176, 80);">?9元/1箱</span></p></section></section></blockquote></section><section class="pEditor" data-id="1832" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><blockquote style="padding-top: 20px; padding-right: 15px; padding-bottom: 20px; border: none rgb(23, 171, 87); word-wrap: break-word !important; box-sizing: border-box !important; background-image: url(https://mmbiz.qlogo.cn/mmbiz/cZV2hRpuAPia3RFX6Mvw06kePJ7HbmI7bklicgzC7wP3YqUUCN0ibiaRCSDQicpGPwBvHCgcMwsSkbr4DrxlK12vI9A/0?wx_fmt=png); background-size: contain; background-repeat: repeat;"><section style="padding: 0px; text-align: center; margin: 0px auto; border-top-width: 2px; border-top-style: solid; border-color: rgb(23, 171, 87); background-color: rgb(254, 254, 254);"><section style="color: inherit; display: inline-block; margin: 5px 0px; border-color: rgb(23, 171, 87);"><section style="margin: 10px; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 0.8em 0px 0.5em; line-height: 32px; font-weight: bold; display: inline-block; border-color: rgb(23, 171, 87); color: inherit;"><section style="margin: 8px; display: inline-block; float: left; width: 0px; height: 0px; border-left-width: 8px; border-left-style: solid; border-color: rgb(23, 171, 87) transparent; border-right-width: 8px; border-right-style: solid; border-bottom-width: 16px; border-bottom-style: solid; color: inherit;"></section><section class="pBrush" style="margin-left: 36px; color: inherit; text-align: left; border-color: rgb(23, 171, 87);">甜脆大冬枣</section></section></section><p style="margin: 10px 20px; border-color: rgb(23, 171, 87); color: inherit;"><img src="http://media.freshfresh.com/media/catalog/product/cache/1/small_image/413x413/af097278c5db4767b0fe9bb92fe21690/_/1/_1_47_5.jpg" width="" height="" border="" vspace="" title="" alt="" style="box-sizing: border-box; border-color: rgb(198, 198, 198); color: inherit; background-color: rgb(255, 255, 255);"></p></section><section style="margin: 0px; text-align: left; border-color: rgb(23, 171, 87);"><p style="margin: 0px 20px 10px; font-size: 14px; padding: 5px;"><span style="color: rgb(255, 0, 0); background-color: rgb(255, 255, 0);"><em>开业价</em></span></p><p style="margin-right: 20px; margin-bottom: 5px; margin-left: 20px; color: inherit; font-size: 20px; padding: 0px 5px 10px; border-color: rgb(198, 198, 198);"><span style="border-color: rgb(198, 198, 198); color: rgb(0, 176, 80);">?2元/1箱</span></p></section></section></blockquote></section><section class="pEditor" data-id="1709" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><section><img src="https://mmbiz.qlogo.cn/mmbiz/h1VV7TJtQuckEz2ibKdGmQfkdCFPIEfJ98rTAlIrmV6Un0Ec8fV4AJQJrwIIuQyIWNQymJUia5aWkibjzJkLBoBZA/0?wx_fmt=png" style="box-sizing: border-box; width: 40px; vertical-align: top; color: inherit; background-color: rgb(255, 255, 255);"><img src="https://mmbiz.qlogo.cn/mmbiz/h1VV7TJtQuckEz2ibKdGmQfkdCFPIEfJ9v4EbIOezEiaMqAibwIDgCJEU5L1QISkF8nlCSxSnQbicZBBuvHriaBFF3A/0?wx_fmt=png" title="1433765645760065679.png" alt="新媒体排版" style="box-sizing: border-box; margin-top: 1.8em; vertical-align: top; padding: 0px; color: inherit; background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;"><section style="padding: 10px; border-radius: 1em; width: 338px; margin-top: 0.7em; margin-left: -0.4em; display: inline-block; color: rgb(255, 255, 238); border-color: rgb(87, 209, 140); background-color: rgb(23, 171, 87);"><p class="pBrush" style="margin-top: 0px; margin-bottom: 0px; border-color: rgb(23, 171, 87); color: inherit;">价格还打?号，搞神秘啊？</p></section></section><br><section style="text-align: right;"><section style="padding: 10px; border-radius: 1em; width: 338px; text-align: left; margin-top: 0.7em; margin-right: -0.4em; display: inline-block; color: rgb(255, 255, 238); border-color: rgb(87, 209, 140); background-color: rgb(23, 171, 87);"><p class="pBrush" style="margin-top: 0px; margin-bottom: 0px; border-color: rgb(23, 171, 87); color: inherit;">放心亲，开业低价，您来看了就知道！</p></section><img src="https://mmbiz.qlogo.cn/mmbiz/h1VV7TJtQuckEz2ibKdGmQfkdCFPIEfJ9feJb1XDbBFHbSFTiaXwJicwTazYk4bFEtYr7DZ7MOwb8hjfV6b6hdBBg/0?wx_fmt=png" title="1433765665732096786.png" alt="新媒体排版" style="box-sizing: border-box; margin-top: 1.8em; vertical-align: top; padding: 0px; color: inherit; background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;"><img src="https://mmbiz.qlogo.cn/mmbiz/h1VV7TJtQuckEz2ibKdGmQfkdCFPIEfJ9vEicaL1CQ4hxW10a0MBwkPHaFacFOw1ghaTKxk5k4wOGy1NmSwnBeIA/0?wx_fmt=png" style="box-sizing: border-box; width: 40px; vertical-align: top; color: inherit; background-color: rgb(255, 255, 255);"></section></section><p><br></p><p><br></p><section class="pEditor" data-id="1759" style="padding: 0px; font-family: Avenir, sans-serif; white-space: normal;"><section style="margin: 0px; padding: 0px; border: 0px; max-width: 100%; font-size: 16.3636360168457px; text-align: center; word-wrap: break-word !important; box-sizing: border-box !important;"><section style="max-width: 100%; display: inline-block; word-wrap: break-word !important; box-sizing: border-box !important;"><section style="max-width: 100%; display: inline-block; word-wrap: break-word !important; box-sizing: border-box !important;"><p style="margin-top: 0px; margin-bottom: 0px; max-width: 100%; word-wrap: normal; min-height: 1em; white-space: pre-wrap; box-sizing: border-box !important;"><strong style="max-width: 100%; text-transform: uppercase; box-sizing: border-box !important; word-wrap: break-word !important;">·END·</strong><br style="max-width: 100%; word-wrap: break-word !important; box-sizing: border-box !important;"></p></section><section class="新媒体管家title" style="max-width: 100%; margin: 0em 0.5em 0.1em; color: rgb(16, 146, 113); font-size: 1.8em; line-height: 1; font-weight: inherit; word-wrap: break-word !important; box-sizing: border-box !important;"><span style="font-size: 20px;"><strong style="border-bottom-style: solid; border-bottom-width: 1px; border-color: rgb(2, 2, 2); display: block; line-height: 28px; max-width: 100%; padding: 0px 50px 3px; box-sizing: border-box !important; word-wrap: break-word !important;">果忆鲜果</strong></span></section><section class="pbrush" style="max-width: 100%; margin: 0.5em 1em; font-size: 1em; line-height: 1; font-weight: inherit; text-align: inherit; text-decoration: inherit; color: rgb(120, 124, 129); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="margin-bottom: 5px;">果忆鲜果，一路领鲜<br></p><p style="margin-bottom: 5px;"><img src="https://dn-kdt-img.qbox.me/upload_files/2015/09/22/Fvydot_Ra0c39R6NCgfe05HV4qJ8.jpg!730x0.jpg"></p></section><span class="pBrush" style="border-radius: 0.3em; box-shadow: rgb(16, 146, 113) 0.1em 0.1em 0.1em; color: white; display: inline-block; font-size: 1em; font-weight: inherit; max-width: 100%; padding: 0.3em; text-align: inherit; text-decoration: inherit; box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(16, 146, 113);">微信号：果忆鲜果</span></section></section></section>                </div>
                </div>
            </div>
        </div>    </div>
  </div>
  <!-- c-modal -->
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
</body>
<script type="text/javascript" src="vendor/jsSHA/src/sha.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdn1.lncld.net/static/js/av-mini-0.6.1.js"></script>
<script src="http://cdnjs.gtimg.com/cdnjs/libs/zepto/1.1.4/zepto.min.js"></script>
<script src="js/spin.min.js"></script>
<script type="text/javascript" charset="utf-8">
// AV setting
AV.initialize("<?php echo $config['leancloud']['app_id']; ?>", "<?php echo $config['leancloud']['app_key']; ?>");

// temp
var uid = "<?php echo $uid; ?>";
var url = location.href.split('#')[0];

wx.config({
debug: false,
appId: '<?php echo $signPackage["appId"];?>',
timestamp: <?php echo $signPackage["timestamp"];?>,
nonceStr: '<?php echo $signPackage["nonceStr"];?>',
signature: '<?php echo $signPackage["signature"];?>',
jsApiList: [
  'onMenuShareTimeline'
]
});

wx.ready(function(){
// 朋友圈
wx.onMenuShareTimeline({
  title: "花果山果忆No.23集仕港店盛大开业",
  link: url,
  imgUrl: "https://dn-kdt-img.qbox.me/upload_files/2015/09/22/Frg7qECk0_XrbF_kHYoXZNdGvbGS.png",
  success: function() {
    // loading
    showSpinnerBox();
    // 查询用户礼品卡获取状态
    getUserJsgGiftCard(uid, {
      success: function(object) {
        if (typeof object === "undefined") {
          // 记录不存在，创建一份礼品卡
          createJsgGiftCard(uid, {
            success: function(object) {
              hideSpinnerBox();
              hideShareOverlay();
              initScreen(object);
            },
            error: function() {
              hideSpinnerBox();
              alert('礼品卡获取失败');
            },
          });
        } else {
          //记录已存在，显示已有的状态
          hideSpinnerBox();
          hideShareOverlay();
          initScreen(object);
        }
      },
      error: function() {
        hideSpinnerBox();
        hideShareOverlay();
        alert("发生未知错误");
      }
    });
  },
  cancel: function() {
    // console.log("canceled");
    hideShareOverlay();
  }
});
});

wx.error(function(res){

});

$(document).ready(function($) {
// todo: add spinner 提示
// 初始化页面状态
getUserJsgGiftCard(uid, {
  success: initScreen,
  error: function() {
    console.log("初始化页面失败");
  }
});
// 绑定事件
$('#shareTimeline').on('click', function() {
  showShareOverlay();  
});
$('body').on('click', '.overlay-black', function() {
  hideShareOverlay();  
});
// setting modal
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
  // $("#btn_redeem_prize").text("确定").data('disabled', 'false');
});

$(".modal-inner").on("click", function(e) {
  e.stopPropagation();
});
// 测试验证礼品卡
$(".modal-content").on('click', '#verifyJsgGiftCard', function() {
  verifyJsgGiftCard(uid, {
      success: function() {
        $(".c-modal .modal-content").html("<span style='color: rgb(32, 210, 32);'>兑换成功，您现在可以领取奖品了</span>");
      },
      error: function(msg) {
        alert("验证出错：" + msg);
      },
      fail: function(msg) {
        alert("验证失败，原因：" + msg);
      }
  });
});
})

// funcitons
// --------------------------------------------
function sign(params) {
var paramsString = "jsapi_ticket=" + params.jsapi_ticket +
  "&noncestr=" + params.nonceStr + "&timestamp=" + params.timestamp +
  "&url=" + url;
// alert(paramsString);
var shaObj = new jsSHA("SHA-1", "TEXT");
shaObj.update(paramsString);
var hash = shaObj.getHash("HEX");
return hash
}

function createJsgGiftCard(uid, callbacks) {
var card = AV.Object.new('JsgGiftCard');
card.set('uid', uid);
card.set('status', 'normal');
// save时无法获得默认生成后的数据
card.save(null, {
  success: function(card) {
    console.log("Create JsgGiftCard record successfully");
    callbacks.success(card);
  },
  error: function(card, error) {
    // 失败之后执行其他逻辑
    // error 是 AV.Error 的实例，包含有错误码和描述信息.
    alert('Failed to create new object, with error message: ' + error.message);
    callbacks.error();
  }
});
}

function verifyJsgGiftCard(uid, callbacks) {
getUserJsgGiftCard(uid, {
  success: function(object) {
    if (typeof object === "undefined") {
      callbacks.fail("礼品不存在");
    } else if (object.get('status') == "used") {
      callbacks.fail("已使用");
    } else {
      object.set('status', 'used');
      object.set('usedAt', (new Date()).Format("MM-dd hh:mm:ss"));
      object.save();
      callbacks.success();
    }
  },
  error: function(error) {
    callbacks.error("verifyJsgGiftCard Error: " + error.code + " " + error.message);
  }
});
}

// 查询用户GiftCard (ajax)
function getUserJsgGiftCard(uid, callbacks) {
var Card = AV.Object.extend('JsgGiftCard');
var query = new AV.Query(Card);
query.equalTo("uid", uid);
query.first({
  success: callbacks.success,
  error: callbacks.error
});
}



// 初始化屏幕gift card状态
function initScreen(object) {
if(typeof object === 'undefined') {
  return;
}
if (object.get('status') === "used") {
  $(".c-modal .modal-content").html("<div style='color:red;'>您已兑换过奖品<br/>领取时间：" + object.get('usedAt') + "</div>");
  $("#modal-checkbox").prop("checked", true);
} else if (object.get('status') === "normal") {
  $(".c-modal .modal-content").html( 
    "您已获得奖品<br/>请让收银员点击验证按钮，进行兑换<br/>" + "<a href='#' class='c-button c-button--secondary c-button--full-bleed' id='verifyJsgGiftCard'>收银员验证</a>");
  $("#modal-checkbox").prop("checked", true);
}
}



// spinner helpers
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
  $(".overlay").remove();
}
}

//overlay
function showShareOverlay() {
var overlay = document.createElement('div');
overlay.className = 'overlay-black';
// append tip image to overlay
var img = document.createElement('img');
$(overlay).html("<div class='overlay-tips'><img src='images/share-tip.png' /></div>");
document.body.appendChild(overlay); 
}
function hideShareOverlay() {
$('.overlay-black').remove();
}

/* helpers
*/
Date.prototype.Format = function (fmt) { //author: meizz 
  var o = {
      "M+": this.getMonth() + 1, //月份 
      "d+": this.getDate(), //日 
      "h+": this.getHours(), //小时 
      "m+": this.getMinutes(), //分 
      "s+": this.getSeconds(), //秒 
      "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
      "S": this.getMilliseconds() //毫秒 
  };
  if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
  for (var k in o)
  if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
  return fmt;
}    
</script>

</html>
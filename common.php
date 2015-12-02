<?php
// 设置时区
date_default_timezone_set("PRC");

require_once 'Api.php';
require_once 'function.inc.php';
require_once 'UserInfo.php';
require 'vendor/autoload.php';


@session_start();
$config = (require 'config.php');
$api = new \LyfMember\Api();
// $_SESSION['uid'] = '20881016718708634955964451718217'; // 20881016718708634955964451718217, oWFVzuPlWpI_z2LNot16KQP1wZ4I
// $_SESSION['platform'] = 'alipay'; // alipay, wechat
if (isset($_SESSION['uid']) &&
    !isset($_GET['proxy_redirect'])) {
  $uid = $_SESSION['uid'];
} else if (isset($_REQUEST['platform'])) { // index entry point
  $platform = $_GET['platform'];
  // 平台OAuth，获取uid
  if ($platform == "alipay") {
    if (isset( $_REQUEST['auth_code'] )) {
      $userinfo = new UserInfo ();
      $uid = $userinfo->getUserId ( $_REQUEST['auth_code'] ); // will set $_SESSION['uid']
      $_SESSION['uid'] = $uid;
      $_SESSION['platform'] = $platform;
    } else {
      exit('User unauthorized!');
    }
  } else if ($platform == "wechat") {
    // wechat oauth using wechat-oauth package
    $oauth = new \Henter\WeChat\OAuth($config['wechat']['app_id'], $config['wechat']['app_secret']);
    if (!isset($_GET['code'])) {
      $callback_url = $config['host'] . "?platform=$platform";
      // url入口格式：host?platform=wechat&proxy_redirect
      // 通过proxy_redirect实现转交code和重定向
      if (isset($_GET['proxy_redirect'])) {
        $callback_url = $callback_url . "&proxy_redirect=" . $_GET['proxy_redirect'];
      }
      $url = $oauth->getWeChatAuthorizeURL($callback_url, 'snsapi_userinfo');
      header("Location: $url");
      exit;
    } else {
      $code = $_GET['code'];
      // 转交微信auth code实现统一授权 for weiqing
      if (isset($_GET['proxy_redirect'])) {
        $proxy_redirect_url = $_GET['proxy_redirect'] . "&code=" . $code;
        header("Location: $proxy_redirect_url");
        exit;
      } else {
        if($access_token = $oauth->getAccessToken('code', $code)){
          $refresh_token = $oauth->getRefreshToken();
          $expires_in = $oauth->getExpiresIn();
          $openid = $oauth->getOpenid();
          $_SESSION['uid'] = $openid;
          $_SESSION['platform'] = $platform;
          $uid = $openid;
        }else{
          // echo $oauth->error();
          exit("User fail to authorized!");
        }
      }
    }
  }
} else { // other pages without uid, fail
  // 只能通过支付宝等平台登陆，还得通过Oauth
  exit('Wrong request');
}


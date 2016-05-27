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
// source_store for store competition
if (isset($_REQUEST['source_store'])) {
  $_SESSION['source_store'] = $_REQUEST['source_store'];
}
if (isset($_SESSION['uid']) &&
    !isset($_GET['proxy_redirect_base64'])) {
  $uid = $_SESSION['uid'];
} else if (isset($_REQUEST['platform'])) { // index entry point
  $platform = $_GET['platform'];
  // session store dest url (for credit.php)
  if (!isset($_SESSION['dest_url'])) {
    $_SESSION['dest_url'] = request_URI();
  }
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
      if (isset($_GET['proxy_redirect_base64'])) {
        $proxy_redirect_url_base64 =  $_GET['proxy_redirect_base64'];
        $state = base64_encode($_GET['state']); // urlencode based on hhpt
        $callback_url = $callback_url . "&proxy_redirect_base64=" . $proxy_redirect_url_base64;
      }
      $url = $oauth->getWeChatAuthorizeURL($callback_url, 'snsapi_userinfo', $state);
      header("Location: $url");
      exit;
    } else {
      $code = $_GET['code'];
      $state = $_GET['state'];
      // 转交微信auth code实现统一授权 for weiqing
      if (isset($_GET['proxy_redirect_base64'])) {
        $state = base64_decode($state);
        // 针对wq连接的特殊处理（去除参数）
        $decoded_proxy_redirect_base64 = base64_decode($_GET['proxy_redirect_base64']);
        if ('state_as_redirect_uri' == $decoded_proxy_redirect_base64) {
          $proxy_redirect_url = urldecode($state)."&code=$code";
        } else {
          $proxy_redirect_url = urldecode(base64_decode($_GET['proxy_redirect_base64'])). "&code=$code&state=$state";
        }
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
          if (isset($_SESSION['dest_url'])) {
            $destUrl = $_SESSION['dest_url'];
            unset($_SESSION['dest_url']);
            header("Location: $destUrl");
          }
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


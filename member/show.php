<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/common.php';
$config = (require $_SERVER['DOCUMENT_ROOT'].'/config.php');

if (!isset($memberInfo)) exit("您还不是会员");
?>
<!DOCTYPE html>
<html>
  <head>
    <title>会员卡</title>
    <style type="text/css">
      html {
        background: -webkit-linear-gradient(#0bcca5, #019875);
        background: linear-gradient(#0bcca5, #019875);
      }
      
      html, body {
        width: 100%;
        height: 100%;
        font-size: 62.5%;
        padding-top: 20px;
      }
      
      .member-card {
        cursor: -webkit-grab;
        cursor: grab;
        position: relative;
        float: none;
        margin: 0 auto;
        width: 700px;
        height: 400px;
        background: -webkit-linear-gradient(270deg, #333, #111 55%);
        background: linear-gradient(-180deg, #333, #111 55%);
        border-radius: 10px;
        border: 2px solid #4d4d4d;
        box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.4), 0 5px 5px 3px rgba(0, 0, 0, 0.2);
      }
      .member-card::before {
        position: absolute;
        top: 0;
        left: 0;
        content: "";
        width: 300px;
        height: 200px;
        border-radius: 10px;
        box-sizing: border-box;
        background: -webkit-linear-gradient(285deg, rgba(255, 255, 255, 0.035) 45%, transparent 45%);
        background: linear-gradient(-195deg, rgba(255, 255, 255, 0.035) 45%, transparent 45%);
      }
      .member-card::after {
        position: absolute;
        right: 0;
        bottom: 0;
        width: 300px;
        height: 200px;
        background: url("http://www.androidpolice.com/wp-content/uploads/2012/10/nexusae0_unnamed-18.png");
      }
      .member-card .number {
        position: relative;
        top: 40%;
        left: 10%;
        color: #ABABAB;
        text-shadow: 0 2px #000;
        font-family: "PT Sans";
        font-size: 40px;
        font-size: 6rem;
        font-style: bold;
      }
      .member-card .company {
        position: absolute;
        right: 10px;
        bottom: 10px;
        width: 80px;
        height: 50px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
      }
      .member-card .name {
        position: absolute;
        bottom: 15px;
        left: 15px;
        color: #ABABAB;
        text-shadow: 0 2px #000;
        font-family: "PT Sans";
        font-size: 30px;
        font-size: 4rem;
      }
      .member-card .visa {
        bottom: 0;
        background-image: url("http://impotxpert.com/attachments/Image/logo_visa.gif");
      }
      .member-card .mastercard {
        background-image: url("https://cdn0.iconfinder.com/data/icons/member-card-debit-card-payment-PNG/128/Mastercard-Curved.png");
      }

      .info-item {
        height: 20px;
        background: #F9F9F9;
        font-size: 18px;
        font-size: 2rem;
        color: #666666;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="card-wrapper">
        <div class="member-card">
          <div class="name"><?php echo $config['partner_name']; ?></div>
          <div class="number"><?php echo $memberInfo['cardNumber']; ?></div>
        </div>
        <!--<div class="info-list">-->
        <!--  <div class="info-item">-->
        <!--    <a href="#">-->
        <!--      <i class="tel">手机</i>-->
        <!--      <span class="mobile"><php echo $memberInfo['mobile']; ?></span>-->
        <!--    </a>-->
        <!--  </div>-->
        <!--</div>-->
      </div>
    </div>
  </body>
</html>
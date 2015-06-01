<?php
require_once 'common.php';
require_once 'checkMember.php';

if (!$uid) exit(500);

if (isset($memberInfo)) {
  header("Location: showMember.php");
} else {
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>会员中心</title>
        <!--<link rel="stylesheet" href="assets/css/furtive.min.css" type="text/css" />-->
        <link rel="stylesheet" href="https://preview.c9.io/everpointer_1/nodejs/scally/css/style.css" type="text/css" />
        <link rel="stylesheet" href="assets/css/base.css" type="text/css" />
    </head>
<body>
    <div class="c-main-container">
        <section class="l-container">
            
            <h2 class="u-text-align-center">会员中心</h2>
            <div>
                <a href="bindMember.php" class="c-button c-button--full-bleed u-s-mb-base">绑定会员卡</a>
                <a href="purchase.php" class="c-button c-button--secondary c-button--full-bleed">购买会员卡</a>
            </div>
        </section>
    </div>
</body>
</html>
<?php
}
?>
<?php
require_once 'autoload.php';
require_once 'common.php';

if (!$uid) exit(500);

if (isset($memberInfo)) {
  header("Location: member/show.php");
} else {
?>

<!DOCTYPE html>
<html>
    <head>
        <title>会员中心</title>
        <link rel="stylesheet" href="assets/css/furtive.min.css" type="text/css" />
        <link rel="stylesheet" href="assets/css/base.css" type="text/css" />
        <style type="text/css">
            .block {
                display: block;
                width: 100%;
            }
        </style>
    </head>
<body>
    <section class="measure p2">
        <h2 class="txt--center">会员中心</h2>
        <div>
            <a href="bindMember.php" class="btn--green block my2 h3">绑定会员卡</a>
            <a href="purchase.php" class="btn--blue block my2 h3">购买会员卡</a>
        </div>
    </section>
</body>
</html>
<?php
}
?>
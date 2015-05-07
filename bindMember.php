<!DOCTYPE html>
<html>
  <head>
    <title>绑定会员卡</title>
    <link rel="stylesheet" href="assets/css/furtive.min.css" type="text/css" /> 
    <link rel="stylesheet" href="assets/css/base.css" type="text/css" /> 
  </head>
  <body>
    <section class="measure p2">
      <h2>老会员绑定</h2>
      <p class="h3">老会员电子会员卡绑定</p>
      <form action="doBindMember.php" method="POST" class="my2">
        <label for="mobile">手机</label>
        <input type="text" name="mobile" placeholder="请输入手机号"/>
        <label for="password">密码</label>
        <input type="password" name="password" placeholder="请输入密码"/>
        <input type="submit" value="绑定" class="btn--blue" />
      </form>
    </section>
  </body>
</html>

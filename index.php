<?php
require_once 'autoload.php';
require_once 'common.php';

if (!$uid) exit(500);

$api = new \LyfMember\Api();
$membersStr = $api->call('getMemberInfo', array(
  'where' => json_encode(array("uid" => $uid))
));
$members = json_decode($membersStr);
if ($members && !empty($members->results)) {
  $memberInfo = $members->results[0];
  var_dump($memberInfo);
} else {
?>

<p><a href="#">绑定会员卡</a></p>
<p><a href="pingpp.php">购买会员卡</a></p>

<?php
}
?>
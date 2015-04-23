<?php
namespace LyfMember;

require 'autoload.php';

$user = new User('111111', 'alipay');
$member = new Member($user);

$result = $member->bind('18888888888', '123456');
var_dump($result);

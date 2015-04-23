<?php
namespace LyfMember;

require 'autoload.php';

// 正常访问, $user已存在，session调用；$uid 代表当前系统的用户
$member = new Member();
$uid = '123456';

// 检测获绑定 会员信息 和 当前用户信息（会员信息是新的信息)
// $result = $member->bind(array(
//     'username' => '18888888888',
//     'password' => '123456',
//     'uid'      => $uid
// ));
$result = $member->register(array(
    'username' => '18888888889',
    'password' => '123456',
    'uid'      => $uid
));
var_dump($result);

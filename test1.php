<?php
require_once 'autoload.php';
require_once 'common.php';
require_once 'AlipayMember.php';

$config = (require 'config.php');

$alipayMember = new AlipayMember();
$result = $alipayMember->openCard("12345678", "1234567890", array(
    "uid" => $uid
));

var_dump($result);

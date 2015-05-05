<?php
require_once 'autoload.php';
require_once 'common.php';
require_once 'AlipayPass.php';

$config = (require 'config.php');

$alipayPass = new AlipayPass($uid, '419243700', $config['partner_id']);
$fileContent = base64_encode(file_get_contents('AlipassSDKDemo/alipass/419243700.alipass'));
$result = $alipayPass->syncAdd($fileContent);

var_dump($result);

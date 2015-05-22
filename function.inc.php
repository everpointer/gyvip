<?php
function writeLog($text) {
	// $text=iconv("GBK", "UTF-8//IGNORE", $text);
	$text = characet ( $text );
	file_put_contents ( dirname ( __FILE__ ) . "/memberlog.txt", date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
}

//转换编码
function characet($data) {
	if (! empty ( $data )) {
		$fileType = mb_detect_encoding ( $data, array (
				'UTF-8',
				'GBK',
				'GB2312',
				'LATIN1',
				'BIG5' 
		) );
		if ($fileType != 'UTF-8') {
			$data = mb_convert_encoding ( $data, 'UTF-8', $fileType );
		}
	}
	return $data;
}

/**
 * 使用SDK执行接口请求
 * @param unknown $request
 * @param string $token
 * @return Ambigous <boolean, mixed>
 */
function aopclient_request_execute($request, $token = NULL) {
	$config = (require 'config.php');
	$aop = new AopClient ();
	$aop->gatewayUrl = $config ['gatewayUrl'];
	$aop->appId = $config ['app_id'];
	$aop->rsaPrivateKeyFilePath = $config ['merchant_private_key_file'];
	$aop->apiVersion = "1.0";
	$result = $aop->execute ( $request, $token );
	writeLog("response: ".var_export($result,true));
	return $result;
}

// leancloud member object to client member info
function memberToMemberInfo($member) {
	return array(
    'cardNumber' => $member->cardNumber,
    'mobile' => $member->mobile,
    'name'   => $member->name,
    'sex'		 => $member->name,
    'merchantId' => $member->merchantId,
    'registedAt' => $member->registedAt,
    'createdAt' => $member->createdAt
  );	
}
// kmtk member
function fromKmtkMember($member) {
	return array(
    "cardNumber" => $member['CARDNO'],
    "mobile" => $member['MOBILE'],
    "name"   => $member['NAME'],
    "sex"    => $member['SEX'],
    "merchantId" => $member['MERCHANTID'],
    "registedAt" => $member['CREATEON']
  );
}
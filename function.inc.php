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
    'sex'		 => $member->sex,
    'merchantId' => $member->merchantId,
    'registeredAt' => $member->registeredAt,
    'createdAt' => $member->createdAt,
    'from' => $member->from
  );	
}
/**
 * KMTK help functions
 */
// kmtk member
function fromKmtkMember($member) {
	$mobile = $member['MOBILE'];
	if (empty($mobile) && !empty($member['TELEPHONE'])) {
		$mobile = $member['TELEPHONE'];
	}
	return array(
    "cardNumber" => $member['CARDNO'],
    "mobile" => $mobile,
    "name"   => $member['NAME'],
    "sex"    => $member['SEX'],
    "merchantId" => $member['MERCHANTID'],
    "registeredAt" => $member['CREATEON']
  );
}
// generate api sign
function genKmtkApiSign($params) {
	// kmtk api key
	$key = '123456';
	return md5($key . join("", $params));
}
// generate card number
function genKmtkCardNumber() {
	return time() . rand(10, 99);
}
// params['mobile']
function genKmtkRegisterMemberApiParams($config, $params) {
	$apiName = 'kmtkRegisterMember';
	$mobile = $params['mobile'];
	if (!isset($config) || !isset($config['api'][$apiName])) {
		throw new Exception("Bad API configuration");
	}
	// initialize params
	$memberParams = array_fill_keys($config['api'][$apiName]['params'], '');
	$memberParams['name'] = $mobile;
	$memberParams['cardno'] = genKmtkCardNumber();
	$memberParams['CardType'] = 1;
	$memberParams['birthday'] = '2015-01-01';
	$memberParams['telephone'] = $mobile;
	$memberParams['mobile'] = $mobile;
	$memberParams['idCardType'] = '身份证';
	$memberParams['idCard'] = $mobile;
	$memberParams['amount'] = 0;
	$memberParams['businessId'] = 1;  // 花果山
	$memberParams['merchantId'] = 'ZB001'; // 花果山
	$memberParams['opId'] = 'laoyufu';
	$memberParams['opName'] = '老渔夫';
	$memberParams['description'] = '支付宝注册';
	// sign 需要在最后计算
	$memberParams['sign'] = genKmtkApiSign($memberParams);
	return $memberParams;
}
// kmtk member
function fromKmtkRegisterMemberParams($params) {
	return array(
    "cardNumber" => $params['cardno'],
    "mobile" => $params['mobile'],
    "name"   => $params['name'],
    "sex"    => $params['sex'],
    "merchantId" => $params['merchantId'],
    "registeredAt" => date('Y-m-d H:i:s')
  );
}
// get millisecond from microtime
function getMillisecond() {
    list($s1, $s2) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}
// generate LeanCloud App Sign
function genLeanCloudAppSign($appKey) {
	$timestamp = getMillisecond();
	$sign = md5($timestamp . $appKey);
	return "$sign,$timestamp";
}
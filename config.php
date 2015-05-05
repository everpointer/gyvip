<?php
return array(
  'alipay_public_key_file' => dirname ( __FILE__ ) . "/key/alipay_rsa_public_key.pem",
	'merchant_private_key_file' => dirname ( __FILE__ ) . "/key/rsa_private_key.pem",
	'merchant_public_key_file' => dirname ( __FILE__ ) . "/key/rsa_public_key.pem",		
	'charset' => "GBK",
	'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
	'app_id' => "2015031700036703",
	'partner_id' => "2088311515754978",
	'partner_name' => "果忆",
  'header' => array(
    'X-AVOSCloud-Application-Id: 0s4hffciblz94hah0m63rsn0x970m2obrjthz0cwmqwsipdy',
    'X-AVOSCloud-Application-Key: 0b7jsd5h44y4wcv6w4w0alomwmpwufx8odk3irmvk36q2g10'  
  ),
  'api' => array(
    'bind' => array(
      'method' => 'update',
      'url' => 'https://leancloud.cn/1.1/login',
      'params' => array('mobile', 'password', 'uid')
    ),
      /**
       * @return Member return a member object with member card ID status code: 200 means a
       * successful request.
       */ 
    'register' => array(
      'method' => 'post',
      'url' => 'https://api.leancloud.cn/1.1/classes/Member',
      'params' => array('mobile', 'password', 'uid')
    ),
    'getMemberInfo' => array(
      'method' => 'get',
      'url' => 'https://api.leancloud.cn/1.1/classes/Member',
      'params' => array(),
      'optionParams' => array('uid', 'where', 'mobile', 'password')
    ),
    'updateMemberInfo' => array(
      'method' => 'put',
      'url' => 'https://api.leancloud.cn/1.1/classes/Member/%s',
      'params' => array('uid')
    ),
      /**
       * @return string return a string orderId and status code: 200 means a
       * successful request.
       */ 
    'createCardOrder' => array(
      'method' => 'post',
      'url' => 'https://api.leancloud.cn/1.1/classes/CardOrder',
      'params' => array('uid', 'amount', 'paid', 'binded')
    ),
    'updateCardOrder' => array(
      'method' => 'put',
      'url' => 'https://api.leancloud.cn/1.1/classes/CardOrder/%s',
      'params' => array('orderId'),
      'optionParams' => array('outTradeNo', 'paid', 'binded', 'status')
    ),
      /**
       * @return CardOrder return one user card order code: 200 means a
       * successful request.
       */ 
    'getCardOrder' => array(
      'method' => 'get',
      'url'    => 'https://api.leancloud.cn/1.1/classes/CardOrder',
      'params' => array(),
      'optionParams' => array('uid', 'orderId', 'where')
    )
  )
);

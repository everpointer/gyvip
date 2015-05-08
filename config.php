<?php
return array(
  'alipay_public_key_file' => dirname ( __FILE__ ) . "/key/alipay_rsa_public_key.pem", // 支付宝公钥文件路径
	'merchant_private_key_file' => dirname ( __FILE__ ) . "/key/rsa_private_key.pem", // 商户私钥文件路径
	'merchant_public_key_file' => dirname ( __FILE__ ) . "/key/rsa_public_key.pem",	// 商户公钥文件路径
	'charset' => "GBK", // 字符集，支付宝接口需要GBK，无需改变
	'gatewayUrl' => "https://openapi.alipay.com/gateway.do", // 支付宝接口网关地址, 无需改变
	'app_id' => "2015031700036703", // 服务窗app id, 服务窗后台查看
	'partner_id' => "2088311515754978", // 商户partner id，支付宝后台查看
	'partner_name' => "果忆", // 商户名称
	'card_price' => 10,  // 会员卡单价，单位元
	'pingxx' => array( // ping++ 配置, ping++后台可查看
  	'app_id' => 'app_unXbrDnvDaj90O4e',  // ping++ app_id
  	'test_key' => 'sk_test_1i1abHv5mbjHHCuTKCDyXP08', // ping++ 测试环境key
  	'live_key' => 'sk_live_wULlucfQQ6k4bEYjp6k8r2h9', // ping++ 正式环境key
  	'env_key' => 'live_key' // 当前使用环境, 正式环境才可调用支付宝
	 ),
  'header' => array( // 调用Rest API时，需要额外传入的请求头部信息, 没有就保持空
    'X-AVOSCloud-Application-Id: 0s4hffciblz94hah0m63rsn0x970m2obrjthz0cwmqwsipdy',
    'X-AVOSCloud-Application-Key: 0b7jsd5h44y4wcv6w4w0alomwmpwufx8odk3irmvk36q2g10'
  ),
  'api' => array( // 接口列表，配置对应的url, params 和 optionParams
      /**
       * 新会员注册接口
       *
       */
    'register' => array(
      'method' => 'post',
      'url' => 'https://api.leancloud.cn/1.1/classes/Member',
      'params' => array('mobile', 'password', 'uid')
    ),
    /**
     * 会员信息查询接口，支持通过支付宝用户ID 或 手机号和密码 来查询用户
     *
     */
    'getMemberInfo' => array(
      'method' => 'get',
      'url' => 'https://api.leancloud.cn/1.1/classes/Member',
      'params' => array(),
      'optionParams' => array('uid', 'where', 'mobile', 'password')
    ),
    /**
     * 老会员绑定支付宝用户接口
     *
     */
    'updateMemberInfo' => array(
      'method' => 'put',
      'url' => 'https://api.leancloud.cn/1.1/classes/Member/%s',
      'params' => array('uid')
    ),
      /**
       * 会员卡购买订单创建接口
       *
       */
    'createCardOrder' => array(
      'method' => 'post',
      'url' => 'https://api.leancloud.cn/1.1/classes/CardOrder',
      'params' => array('uid', 'amount', 'paid', 'binded')
    ),
    /**
     * 会员卡购买订单更新接口
     *
     */
    'updateCardOrder' => array(
      'method' => 'put',
      'url' => 'https://api.leancloud.cn/1.1/classes/CardOrder/%s',
      'params' => array('orderId'),
      'optionParams' => array('outTradeNo', 'paid', 'binded', 'status')
    ),
      /**
       * 会员卡订单查询接口
       * @return CardOrder return one user card order code: 200 means a
       * successful request.
       */ 
    'getCardOrder' => array(
      'method' => 'get',
      'url'    => 'https://api.leancloud.cn/1.1/classes/CardOrder',
      'params' => array(),
      'optionParams' => array('uid', 'orderId', 'where')
    ),
    /**
     * 会员卡订单删除接口（或取消接口）
     *
     */
    'deleteCardOrder' => array(
      'method' => 'delete',
      'url'    => 'https://api.leancloud.cn/1.1/classes/CardOrder/%s',
      'params' => array('orderId')
    )
  )
);

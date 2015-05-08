<?php
return array(
  'alipay_public_key_file' => dirname ( __FILE__ ) . "/key/alipay_rsa_public_key.pem", // 支付宝公钥文件路径
	'merchant_private_key_file' => dirname ( __FILE__ ) . "/key/rsa_private_key.pem", // 商户私钥文件路径
	'merchant_public_key_file' => dirname ( __FILE__ ) . "/key/rsa_public_key.pem",	// 商户公钥文件路径
	'charset' => "GBK", // 字符集，支付宝接口需要GBK，无需改变
	'gatewayUrl' => "https://openapi.alipay.com/gateway.do", // 支付宝接口网关地址, 无需改变
	'app_id' => "——请填写——", // 服务窗app id, 服务窗后台查看
	'partner_id' => "——请填写——", // 商户partner id，支付宝后台查看
	'partner_name' => "——请填写——", // 商户名称
	'card_price' => 10,  // 会员卡单价，单位元
	'pingxx' => array( // ping++ 配置, ping++后台可查看
  	'app_id' => '——请填写——',  // ping++ app_id
  	'test_key' => '——请填写——', // ping++ 测试环境key
  	'live_key' => '——请填写——', // ping++ 正式环境key
  	'env_key' => 'live_key' // 当前使用环境, 正式环境才可调用支付宝
	 ),
  'header' => array( // 调用Rest API时，需要额外传入的请求头部信息, 没有就保持空
    // 'header-key: header-value',
  ),
  'api' => array( // 接口列表，配置对应的url, params 和 optionParams
      /**
       * 新会员注册接口
       * @param string mobile 手机号
       * @param string password 会员密码
       * @param string uid 支付宝用户ID
       * @return
       *        status code: 200, successful
       *        otherwise: fail
       */
    'register' => array(
      'method' => 'post',
      'url' => 'https://api.leancloud.cn/1.1/classes/Member',
      'params' => array('mobile', 'password', 'uid')
    ),
    /**
     * 老会员绑定支付宝用户接口
     * @param uid string 支付宝会员ID
     * @return todo: update mobile 接口
     */
    'bind' => array(
      'method' => 'put',
      'url' => 'https://api.leancloud.cn/1.1/classes/Member/%s',
      'params' => array('uid')
    ),
    /**
     * 会员信息查询接口，支持通过支付宝用户ID 或 手机号和密码 来查询用户
     * no reuqired params
     * @optionParams: 任选一种
     *    - uid string 支付宝用户ID
     *    - mobile & password string  用户手机号和密码
     *    - where string 自定义查询条件
     * @return todo: update mobile 接口
     */
    'getMemberInfo' => array(
      'method' => 'get',
      'url' => 'https://api.leancloud.cn/1.1/classes/Member',
      'params' => array(),
      'optionParams' => array('uid', 'where', 'mobile', 'password')
    ),
    /**
     * 会员卡购买订单创建接口
     * @param uid string 支付宝会员ID
     * @param amount integer 会员卡单价
     * @param paid bool 订单支付状态，默认为false
     * @param binded bool 订单绑定状态，默认为false
     * @return
     *      status code: 200, successful
     *      otherwise: fail
     */
    'createCardOrder' => array(
      'method' => 'post',
      'url' => 'https://api.leancloud.cn/1.1/classes/CardOrder',
      'params' => array('uid', 'amount', 'paid', 'binded')
    ),
    /**
     * 会员卡购买订单更新接口
     * @param orderId string 订单编号
     * @optionParams
     *    - outTradeNo string 支付宝等外部订单编号
     *    - paid bool 订单支付状态
     *    －binded bool 订单绑定状态
     */
    'updateCardOrder' => array(
      'method' => 'put',
      'url' => 'https://api.leancloud.cn/1.1/classes/CardOrder/%s',
      'params' => array('orderId'),
      'optionParams' => array('outTradeNo', 'paid', 'binded')
    ),
    /**
     * 会员卡订单查询接口
     * @optionParams
     *    - uid string 支付宝会员ID
     *    - orderId string 订单编号
     *    - where string 自定义查询条件
     * @return
     *    - status code: 200
     *      json data: format 
     *        {'orderId': '', 'binded': , 'paid': }
     *    - otherwise: false
     */ 
    'getCardOrder' => array(
      'method' => 'get',
      'url'    => 'https://api.leancloud.cn/1.1/classes/CardOrder',
      'params' => array(),
      'optionParams' => array('uid', 'orderId', 'where')
    ),
    /**
     * 会员卡订单删除接口（或取消接口）
     * @param orderId string 订单编号
     * @return
     *    - status code: 200, successful
     *    - otherwise: fail
     */
    'deleteCardOrder' => array(
      'method' => 'delete',
      'url'    => 'https://api.leancloud.cn/1.1/classes/CardOrder/%s',
      'params' => array('orderId')
    )
  )
);
       *

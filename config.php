<?php
require_once 'function.inc.php';
// check environment variables
if (!getenv('env')) {
  exit("Website wrongly setuped!");
}
return array(
  'alipay_public_key_file' => dirname ( __FILE__ ) . "/key/alipay_rsa_public_key.pem", // 支付宝公钥文件路径
  'merchant_private_key_file' => dirname ( __FILE__ ) . "/key/rsa_private_key.pem", // 商户私钥文件路径
  'merchant_public_key_file' => dirname ( __FILE__ ) . "/key/rsa_public_key.pem", // 商户公钥文件路径
  'charset' => "GBK", // 字符集，支付宝接口需要GBK，无需改变
  'gatewayUrl' => "https://openapi.alipay.com/gateway.do", // 支付宝接口�关地址, 无需改变
  'app_id' => getenv('alipay_app_id'), // 服务窗app id, 服务窗后台查看
  'partner_id' => getenv('alipay_partner_id'), // 商户partner id，支付宝后台查看
  'partner_name' => "果忆", // 商户名称
  'card_price' => 10,  // 会�卡单价，单位元
  'pingxx' => array( // ping++ 配置, ping++后台可查看
    'app_id' => getenv('pingxx_app_id'),  // ping++ app_id
    'test_key' => getenv('pingxx_test_key'), // ping++ 测试环境key
    'live_key' => getenv('pingxx_live_key'), // ping++ 正式环境key
    'env_key' => getenv('pingxx_env_key'), // 当前使用环境, 正式环境才可调用支付宝
    'success_url' => getenv('host') . '/finishPurchase.php',
    'cancel_url' => getenv('host') . '/cancelPurchase.php',
   ),
   'leancloud' => array( // leancloud 配置
    'app_id' => getenv('leancloud_app_id'),
    'app_key' => getenv('leancloud_app_key')
   ),
  'header' => array( // 调用Rest API时，需要额外传入的请求头部信息, 没有就保持空
    'X-AVOSCloud-Application-Id: ' . getenv('leancloud_app_id'),
    'X-AVOSCloud-Request-Sign: ' . genLeanCloudAppSign(getenv('leancloud_app_key'))
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
    'registerMember' => array(
      'method' => 'post',
      'url' => 'https://api.leancloud.cn/1.1/classes/Member',
      'params' => array('mobile', 'cardNumber', 'uid'),
      'optionParams' => array('name', 'sex', 'merchantId', 'registedAt', 'from')
    ),
    /**
     * 老会员绑定支付宝用户接口
     * @param uid string 支付宝会员ID
     * @param mobile string 会员手机号
     * @param password string 会员密码
     * @return
     *    - status code: 200, successful
     *    - otherwise: string error messages
     */
    'bindMember' => array(
      'method' => 'post',
      'url' => 'https://leancloud.cn/1.1/functions/bindMember',
      'params' => array('uid', 'mobile', 'password')
    ),
    /**
     * 会员信息查询接口，支持通过支付宝用户ID 或 手机号和密码 来查询用户
     * no reuqired params
     * @optionParams: 任选一种
     *    - uid string 支付宝用户ID
     *    - where string 自定义查询条件
     * @return
     *    - status code: 200
     *      json data : string, format as belows
     *        {'results': [{'cardNumber': '', 'mobile': , 'createdAt': }] }
     *    - otherwise: 500 like error http code
     */
    'getMemberInfo' => array(
      'method' => 'get',
      'url' => 'https://api.leancloud.cn/1.1/classes/Member',
      'params' => array(),
      'optionParams' => array('uid', 'where')
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
     *    －binded bool 订单绑定状�
     */
    'updateCardOrder' => array(
      'method' => 'put',
      'url' => 'https://api.leancloud.cn/1.1/classes/CardOrder/%s',
      'params' => array('orderId'),
      'optionParams' => array('outTradeNo', 'paid', 'binded')
    ),
    /**
     * ��员卡订单查询接口
     * @optionParams
     *    - uid string 支付宝会员ID
     *    - orderId string 订单编号
     *    - where string 自定义查询条件
     * @return
     *    - status code: 200
     *      json data: format
     *        {'results': [{'orderId': '', 'binded': , 'paid': }] }
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
    ),
    /**
     * 手机号码验证短信验证接口
     */
    'verifySmsCode' => array(
      'method' => 'post',
      'url' => 'https://api.leancloud.cn/1.1/verifySmsCode/%s',
      'params' => array('mobilePhoneNumber')
      // 'optionParams' => array('smsCode')
    ),
    /**
     * 注册KMTK用户
     */
    'kmtkRegisterMember' => array(
      'method' => 'get',
      'url' => 'http://' . getenv('kmtk_pay_host') . '/api/Service/CLZRegisterAndDeposit',
      'params' => array('name', 'cardno', 'CardType', 'checksum', 'fullName',
                        'sex', 'birthday', 'telephone', 'mobile', 'address',
                        'postcode', 'email', 'idCardType', 'idCard', 'amount',
                        'businessId', 'merchantId', 'opId', 'opName',
                        'description', 'sign')
    ),
    /**
     * 查询KMTK用户余额
     */
    'kmtkBalance' => array(
      'method' => 'get',
      'url' => 'http://' . getenv('kmtk_pay_host') . '/api/Service/Balance',
      'params' => array('name', 'userType', 'accountType', 'businessId', 'sign')
    )
  )
);

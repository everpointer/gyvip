# 支付宝服务窗会员模块
此模块可独立做为一个web应用，绑定在服务窗菜单上使用，也可以与现有服务窗代码集成，作为其一部分。

## 功能
- 老会员绑定
	使用手机号和密码等会员信息，验证后绑定到当前支付宝账户
- 新会员购买
	支付宝购买，支付成功后，注册当前支付宝会员账号
- 显示会员账号信息
	显示会员卡号，用于门店出示使用

## 安装说明（依赖）
### 运行环境
- 开发语言
	PHP, 版本 \>= 5.4 （低版本未测试过，开发版本为5.4）
- 模块依赖
	- php extensions: php\_curl.so
		支持REST API调用
	- composer packages 
		pingpp-php:  支付网关库，支付宝支付接口的中间件
		**composer.json:**
	{
	  "require": {
		"pingplusplus/pingpp-php": "dev-master"
	  }
	}
## 模块配置
### 配置文件
配置文件为config.example.php, 根据要求填写文件中标注**“\_\_请填写\_\_”**的部分，最后将配置文件重命名为config.php.

#### 配置说明
- 服务窗参数配置 : 配置密钥，用于调用支付宝接口
- ping++配置 : ping++支付网关平台参数配置，参考附录2
- api配置 : 商户自己的会员接口配置 

### 会员接口说明
Restful接口配置说明，可以在config.example.php里查看，只要根据配置要求的请求参数和返回值格式，实现下述接口，再把接口URL填写到config.example.php即可.
- bindMember : 会员绑定接口
- registerMember: 会员注册接口
- getMemberInfo: 获取会员信息接口
- createCardOder: 会员卡订单创建接口
- updateCardOrder: 会员卡订单更新接口
- getCardOrder: 会员卡订单查询接口
- deleteCardOrder: 会员卡订单删除接口
## 目录结构
- GYMember : 模块目录
	- config.example.php : 模块基本配置(完成后，重命名为config.php)

	- aop
	- lotusphp\_runtime
	- AlipaySign.php
	- AopSdk.php
	- Gateway.php
	- HttpRequest.php
		支付宝服务窗SDK，用于调用支付宝OAuth等接口。单独项目需要包含这些文件，若集成当前服务窗项目，则应该已自带。

	-  key
		- alipay\_rsa\_public\_key.pem
		- rsa\_private\_key.pem
		- rsa\_public\_key.pem
		rsa密钥文件，服务窗，支付宝手机网站支付和ping++等接口的配置，需要上述密钥信息。密钥生成方式，参考附录1。

	- assets
		- css
		- image
			- member-card-bg.png : 会员中心会员卡背景
			- member-card.jpg : 会员中心会员卡图片
			- member-icons.png : 会员中心图标图片集
		- js
			- pingpp\_pay.js : ping++ Html客户端库
会员展示和购买等页面，需要用到的资源

- Rest.php : Rest API基础类（基于curl）
	- Api.php : config 里的接口调用实用类（基于Rest.php)
	- function.inc.php : 常用方法库
	- common.php : 通用文件，包含常用方法库，设置支付宝用户ID等
	- composer.json : php包依赖管理文件

	- index.php : 会员中心首页
	- showMember.php : 会员展示页
	- bindMember.php : 会员绑定显示页
	- doBindMember.php : 会员绑定处理页
	- purchase.php : 会员购买页
	- createOrderByPingpp.php : 会员购买创建支付页（通过ping++, 生成支付宝支付页面）
	- notifyFinishPurchase.php : 支付完成异步通知页
	- finishPurchase.php : 支付完成返回显示页（会员注册页）
	- cancelPurchase.php : 支付取消或失败的返回显示页
	- registerMember.php : 会员注册处理页（购买成功后）

	- UserInfo.php : 支付宝会员信息接口类
	- .gitignore
	- README.md : 模块说明页
## 测试
   1. 开通商户支付宝账号手机网站支付权限（否则无法调用支付接口）
2. 在ping++平台创建产品和应用，填写支付宝网关信息
3. 配置服务窗菜单－会员卡中心连接，格式如下：
	[https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app\_id=\_\_填写APP\_ID\_\_&redirect\_uri=\_\_填写return\_url\_\_&scope=auth\_userinfo][1]
	填写参数：
	- app\_id : 服务窗app id
	- return\_url : 会员中心页部署后的实际地址
## 提示
- 支付宝会员卡权限 : 如有此权限，可实现将会员卡信息绑定到支付宝服务窗的会员卡页面

## 附录
1. [支付宝密钥使用指南][2]
2. [ping++支付网关平台][3]

[1]:	https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=2015031700036703&redirect_uri=https://member-laoyufu.c9.io&scope=auth_userinfo
[2]:	https://openhome.alipay.com/doc/docIndex.htm?url=https://openhome.alipay.com/doc/viewKbDoc.htm?key=236615_428849&type=info "支付宝密钥使用指南"
[3]:	https://pingxx.com "ping++支付网关平台"
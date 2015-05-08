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
- 配置文件 : config.php

### 会员接口说明
- bind : 会员绑定接口
- register: 会员注册接口
- getMemberInfo: 获取会员信息接口
- createCardOder: 会员卡订单创建接口
- updateCardOrder: 会员卡订单更新接口
- getCardOrder: 会员卡订单查询接口
- deleteCardOrder: 会员卡订单删除接口
## 目录结构
- GYMember : 项目目录
	-  config.php : 模块基本配置，API接口设置

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
		- js
			- pingpp\_pay.js : ping++ Html客户端库
		会员展示和购买等页面，需要用到的资源

	- src
		- Rest.php : Rest API基础类（基于curl）
		- Api.php : config 里的接口调用实用类（基于Rest.php)
		- Member.php : 会员API封装类（基于Api.php）
	- autoload.php : 自动加载src目录里的类
	- function.inc.php : 常用方法库
	- common.php : 通用文件，包含常用方法库，设置支付宝用户ID等
	- composer.json : php包依赖管理文件

	- index.php : 会员中心首页
	- member
		- show.php : 会员展示页
	- bindMember.php : 会员绑定显示页
	- doBindMember.php : 会员绑定处理页
	- purchase.php : 会员购买页
	- pingpp.php : 会员购买创建支付页（通过ping++, 生成支付宝支付页面）
	- notifyFinishPurchase.php : 支付完成异步通知页
	- finishPurchase.php : 支付完成返回显示页（会员注册页）
	- cancelPurchase.php : 支付取消或失败的返回显示页
	- registerMember.php : 会员注册处理页（购买成功后）

	- UserInfo.php : 支付宝会员信息接口类
	- .gitignore
	- README.md : 模块说明页
## 附录
1. [支付宝密钥使用指南][1]

[1]:	https://openhome.alipay.com/doc/docIndex.htm?url=https://openhome.alipay.com/doc/viewKbDoc.htm?key=236615_428849&type=info "支付宝密钥使用指南"
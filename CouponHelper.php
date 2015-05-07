<?php
/**
 * Coupon模板alipass文件数据生成示例

 * @author junhua.pan
 *
 */
class CouponHelper{


	static function warpAlipassData($serialNumber="",$channelID="",$product=""){

		//APP配置
		$app = new APPConfig();
		$app->android_appid = "com.taobao.ecoupon";
		$app->android_download = "http://download.taobaocdn.com/freedom/17988/andriod/Ecoupon_2.0.1_taobao_wap.apk";
		$app->android_launch = "com.taobao.ecoupon";
		$app->ios_appid = "583295537";
		$app->ios_download = "https://itunes.apple.com/cn/app/id583295537";
		$app->ios_launch = "taobaolife://alipay";

		//alipass 背景色
		$style = new Style("RGB(75,175,8)");

		//背面APP配置
		$appInfo = new AppInfo("淘宝券券","用最省的钱，享受更品质的生活！",$app);

		//【必需属性】alipass文件屬性配置
		$fileInfo = new FileInfo($serialNumber);

		$yourCallBack = "http://www.taobao.com/alipass/success.do";//可以为空
		//【必需属性】alipass回調服務配置
		$platform = new PlatForm($channelID,$yourCallBack,"");

		//操作区域 配置-例子
		$opt4Text = new Operations("",Operations::TEXT,Operations::CHARSET_UTF8,array(
		array("label"=>"验证码","value"=>"20130678904"),
		array("label"=>"兑换码","value"=>"A45612346579465")
		));
		$opt4Barcode = new Operations("兑换码：A45612346579465",Operations::BARCODE,Operations::CHARSET_UTF8,"A45612346579465");
		$opt4Qrcode = new Operations("兑换码：A45612346579465",Operations::QRCODE,Operations::CHARSET_UTF8,"A45612346579465");
		$opt4App = new Operations("继续购买优惠券",Operations::APP,Operations::CHARSET_UTF8,$app);
		$opt4Url = new Operations("如家快捷酒店网站",Operations::URL,Operations::CHARSET_UTF8,"http://www.homeinns.com");

		//声波核销区域：message最大支持15个字符
		$opt4Wave = new Operations("声波核销",Operations::WAVE,Operations::CHARSET_UTF8,"CP2013082714261");

		//图片二维码
		$opt4Img = new Operations("CP2013082714261",Operations::IMG,Operations::CHARSET_UTF8,array(
			"img"=>"http://lib.2weima.org/wp-content/uploads/2013/02/api-350x350.png",//二维码图片地址
			"target"=>""//运营URL，目前不可用
		));


		//【必需属性】alipass主体信息配置
		$einfo = new Einfo();
		$einfo->logoText = "PHP-如家快捷酒店-99元重磅特惠";
		$einfo->secondLogoText = "全国所有汉庭酒店均可使用";

		$field1 = new Field("lastDate", "截至日期", "2015-07-27", Field::TEXT);
		$einfo->headFields = array($field1);

		$field11 = new Field("validDate", "", "有效期至： 2015-07-27 23:59:59", Field::TEXT);
		$einfo->secondaryFields = array($field11);

		$field2 = new Field("description", "优惠内容", "1、入住如家、莫泰酒店均有机会享受“99风暴”优惠（即99元、199元、299元等优惠价）\n2、房间有限，先订先得，不与其它优惠同享\n3、各酒店具体价格以官网公示为准", Field::TEXT);
		$field3 = new Field("shops", "可用门店", "全国所有门店均可使用", Field::TEXT);
		$field4 = new Field("disclaimer", "免责声明", "99元重磅特惠\n除特殊注明外，本优惠不能与其他优惠同时享受； 本优惠最终解释权归商家所有，如有疑问请与商家联系。 提示：为了使您得到更好的服务，请在进店时出示本券。", Field::TEXT);
		$field5 = new Field("tel", "服务电话", "400-820-3333", Field::TEL);
		$einfo->backFields = array($field2,$field3,$field4,$field5);

		//【必需属性】alipass基础信息
		$evoucher = new EvoucherInfo();
		$evoucher->einfo = $einfo;
		$evoucher->title = "如家快捷酒店优惠券";
		$evoucher->type = T::T_COUPON;
		$evoucher->product = $product;
		$evoucher->startDate = "2014-07-27 00:00:00";//注意格式
		$evoucher->endDate = "2015-07-27 23:59:59";//注意格式

		//注意格式：操作区域目前支持一下六种，可以根据自己实际情况设置
		$evoucher->operation = array($opt4Img,$opt4Wave,$opt4Text,$opt4Qrcode,$opt4App,$opt4Barcode,$opt4Url);


		//提供Alipass商户信息
		$merchant = new Merchant();
		$merchant->mname = "如家快捷酒店";
		$merchant->mtel="400-820-3333";
		$merchant->minfo="http://www.homeinns.com";

		//AlipassData数据汇总
		$alipassData = new AlipassData();
		$alipassData->evoucherInfo = $evoucher;
		$alipassData->style=$style ;
		$alipassData->fileInfo=$fileInfo ;
		$alipassData->merchant = $merchant ;
		$alipassData->locations= NULL ;
		$alipassData->platform =$platform ;
		$alipassData->appInfo = $appInfo ;

		return $alipassData;
	}

}
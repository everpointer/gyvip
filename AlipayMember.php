<?php
require_once 'AopSdk.php';
require_once 'function.inc.php';
require_once 'aop/request/AlipayMemberCardOpenRequest.php';

class AlipayMember {
  public function openCard($outTradeNo, $cardNumber, $memberInfo) {
    $openRequest = new AlipayMemberCardOpenRequest(); 
    
    $openRequest->setBizSerialNo($outTradeNo);
    $openRequest->setExternalCardNo($cardNumber); // Todo: 必须要有一个外部交易号？，对应支付宝订单,否则无法操作
    $openRequest->setRequestFrom("PARTNER"); // Todo: 必须要有一个外部交易号？，对应支付宝订单,否则无法操作
    $openRequest->setCardUserInfo(json_encode(array(
      "userUniId" => $memberInfo["uid"],
      "userUniIdType" => "UID"
    )));
    
    $result = aopclient_request_execute ( $openRequest );
		return $result;
  }  
}
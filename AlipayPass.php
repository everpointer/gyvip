<?php
require_once 'AopSdk.php';
require_once 'function.inc.php';
require_once 'aop/request/AlipayPassSyncAddRequest.php';

class AlipayPass {
  public function __construct($uid, $outTradeNo, $partnerId) {
    $this->uid = $uid;
    $this->outTradeNo = $outTradeNo;
    $this->partnerId = $partnerId;
  }
  
  public function syncAdd($fileContent) {
    $syncRequest = new AlipayPassSyncAddRequest(); 
    
    $syncRequest->setTerminalType("mobile");
    // $syncRequest->setUserId($this->uid);
    $syncRequest->setOutTradeNo($this->outTradeNo); // Todo: 必须要有一个外部交易号？，对应支付宝订单,否则无法操作
    $syncRequest->setFileContent($fileContent);
    $syncRequest->setPartnerId($this->partnerId);
    
    $result = aopclient_request_execute ( $syncRequest );
		return $result;
  }  
}
<?php
/**
 * ALIPAY API: alipay.exsc.user.currentsign.get request
 *
 * @author auto create
 * @since 1.0, 2014-10-30 22:34:50
 */
class AlipayExscUserCurrentsignGetRequest
{
	/** 
	 * 支付宝 cif的accountNo 格式：支付宝userId+0156
	 **/
	private $alipayId;

	private $apiParas = array();
	private $terminalType;
	private $terminalInfo;
	private $prodCode;
	
	public function setAlipayId($alipayId)
	{
		$this->alipayId = $alipayId;
		$this->apiParas["alipay_id"] = $alipayId;
	}

	public function getAlipayId()
	{
		return $this->alipayId;
	}

	public function getApiMethodName()
	{
		return "alipay.exsc.user.currentsign.get";
	}

	public function getApiParas()
	{
		return $this->apiParas;
	}

	public function getTerminalType()
	{
		return $this->terminalType;
	}

	public function setTerminalType($terminalType)
	{
		$this->terminalType = $terminalType;
	}

	public function getTerminalInfo()
	{
		return $this->terminalInfo;
	}

	public function setTerminalInfo($terminalInfo)
	{
		$this->terminalInfo = $terminalInfo;
	}

	public function getProdCode()
	{
		return $this->prodCode;
	}

	public function setProdCode($prodCode)
	{
		$this->prodCode = $prodCode;
	}
}

<?php
class Aurigait_CountyCurrencySwitcher_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getCountryByIp($ip)
	{
		// API call to get Country code of requested ip
		$url="http://api.ipinfodb.com/v3/ip-country/?key=3c1d963333567e3dc050d75f5de6ef3817ea852152644a388ef817498d6b6eec&ip=".$ip;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 400);
		$data = curl_exec($ch);
		curl_close($ch);
		if(curl_errno($ch))
		{
			return 'US';
		}
		$county_conf=explode(';',$data);
		if(@$county_conf[3] && !empty($county_conf[3]))
		{
			return $county_conf[3];
		}
		else
			return 'US';		
	}	

	public function getCurrentCurrencyCode()
	{
		$c_code=$this->getCountryByIp($_SERVER['REMOTE_ADDR']);//'122.161.85.80'
		$write = Mage::getSingleton("core/resource")->getConnection("core_write");
		$sql="select * from ".Mage::getSingleton('core/resource')->getTableName('country_currency')." where country_id='".$c_code."'";
		$row=$write->fetchRow($sql);
		//Mage::app()->getStore()->setCurrentCurrencyCode($row['currency_id']);
		return $row['currency_id'];			
	}
}
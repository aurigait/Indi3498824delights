<?php
class Aurigait_CommonIndidelights_Model_Shippingvalidate extends Mage_Core_Model_Abstract
{

	public function isInternationShippingAllowed()
	{
		$quote=Mage::getModel('checkout/cart')->getQuote();
		$base_country=Mage::getStoreConfig('shipping/origin/country_id');
		$country=$quote->getShippingAddress()->getCountry();
		$products=array();
		if($country!=$base_country)
		{
			foreach($quote->getAllItems() as $item)
			{
			 	$_product=$item->getProduct()->load($item->getProduct()->getId());
			 	if($_product->getTypeId() != "simple")
			 	{
			 		continue;
			 	}
			 	$intShipping=$_product->getResource()->getAttribute('ga_int_ship_allowed');
				if($intShipping && strtolower($intShipping->getFrontend()->getValue($_product))!='yes')
				{
					$products[]=$item->getProduct()->getName();					
				}
			}
		}		
		return $products;
	}
		
}

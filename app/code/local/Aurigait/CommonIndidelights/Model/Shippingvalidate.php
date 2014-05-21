<?php
class Aurigait_CommonIndidelights_Model_Shippingvalidate extends Mage_Core_Model_Abstract
{

	public function isInternationShippingAllowed()
	{
		$quote=Mage::getModel('checkout/cart')->getQuote();
		$base_country=Mage::getStoreConfig('general/country/default');
		$country=$quote->getShippingAddress()->getCountry();
		$products=array();
		
		if($country!=$base_country)
		{
			foreach($quote->getAllItems() as $item)
			{
				if(!$item->getProduct()->getData('ga_int_ship_allowed'))
				{
					$products[]=$item->getProduct()->getName();					
				}
			}
		}		
		return $products;
	}
		
}

<?php   
class Aurigait_Voucher_Block_Allvoucher extends Mage_Core_Block_Template{   


	public function methodblock()
	{
		return "informations about my block !!" ;
	}
	
	public function getallcoupon()
	{
		$rulesCollection = array();
		//$rulesCollection = Mage::getModel('salesrule/rule')->getCollection()->getData();//$rulesCollection->load(true,true);
		//$rulesCollection->getSelect()->where('rule_coupons.expiration_date > now()');
	//	echo "<pre>";print_r($rulesCollection);
		
		
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		if($customer->getId())
		{
			$helperobj = Mage::Helper('voucher/customhelper');
			$cutomercoupon = $helperobj->getAllvoucher();
			$rulesCollection = $cutomercoupon;
			
		}
			
		
		return $rulesCollection;
	}

}

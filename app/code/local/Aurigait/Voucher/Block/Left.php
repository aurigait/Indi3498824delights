<?php   
class Aurigait_Voucher_Block_Left extends Mage_Core_Block_Template{   


	public function methodblock()
	{
		return "informations about my block !!" ;
	}
	
	public function getallcoupon()
	{
		$rulesCollection = Mage::getModel('salesrule/rule')->getCollection();//$rulesCollection->load(true,true);
		$rulesCollection->getSelect()->where('rule_coupons.expiration_date > now()');
		//	$rulesCollection->load(true,true);
		return $rulesCollection;
	}


}
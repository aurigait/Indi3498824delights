<?php   
class Aurigait_Voucher_Block_Referal extends Mage_Core_Block_Template{   



	public function getAllreferal()
	{
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		$referal =   Mage::getModel('invitefriend/invitefriend')->getAllreferal($customer->getId());
		
		return $referal;
	}

}
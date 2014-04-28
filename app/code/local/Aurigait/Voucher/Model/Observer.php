<?php 

require_once "Aurigait/Voucher/controllers/IndexController.php";

class Aurigait_Voucher_Model_Observer
{
	public function __construct()
	{
		
	}
	
	public function apply_discount_percent(Varien_Event_Observer $observer)
	{
		$event = $observer->getEvent();
		$product = $event->getProduct();
		
		$quote = $observer->getEvent()->getQuote();
		
	/*	echo $quote->getSubtotal();
		
		echo "<pre>";
		echo "########################";
		print_r($quote);
		echo "</pre>"; die; */	 
	}
	
	public function mailwelcomevouhcer()
	{
		$templateId = 1;
		
		$senderName = Mage::getStoreConfig('trans_email/ident_support/name');
		$senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
		$sender = array('name' => $senderName,
				'email' => $senderEmail);
		

		
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		$customerData = Mage::getModel('customer/customer')->load( $customer->getId());

		
		$recepientEmail = $customerData->getEmail(); //$customerData->getEmail();
		$recepientName =  $customerData->getFirstname(); // .'@@@'.$customerData->getEmail().'$$$' ;// $customerData->getFirstname();//.'@@'.$customerFName;
		
		// Get Store ID
		$store = Mage::app()->getStore()->getId();
		
		
		$couponCode = 'WELCOME';
		$oCoupon = Mage::getModel('salesrule/coupon')->load($couponCode, 'code');
		$oRule = Mage::getModel('salesrule/rule')->load($oCoupon->getRuleId());
		 
		
		
		// Set variables that can be used in email template. To get this variable write like {{var customerEmail}} in transactional Email.
		//.Aurigait_Voucher_IndexController::WELCOMECODE ,// Aurigait_Voucher_IndexController::WELCOMECODE,
		$vars = array(	
				'coupon_prize' => $oRule['discount_amount'],
				'coupon_code' =>$couponCode,
				'name' => $recepientName,
				'email' => $recepientEmail,
				);
		
		$translate  = Mage::getSingleton('core/translate');
		
		
		
		// Send Transactional Email
		Mage::getModel('core/email_template')
		->addBcc($senderEmail)      // You can remove it if you don't need to send bcc
		->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);
		
		$translate->setTranslateInline(true);
		
	
	}
}
?>
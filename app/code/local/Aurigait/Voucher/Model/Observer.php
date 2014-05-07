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
	
	public function setDiscount($observer)
    {
		$quote = Mage::getSingleton('checkout/cart')->getQuote();
    	$totals =  $quote->getTotals();
    	
    	$subtotal = $totals["subtotal"]->getValue();
    	
    	$Discount = $totals["discount"]->getValue();
    	
    	$Discount = $Discount * -1;
    	
    	$currentcouponcode =  $quote->getCouponCode();
    	$oCoupon = Mage::getModel('salesrule/coupon')->load($currentcouponcode, 'code');
    	$rule = Mage::getModel('salesrule/rule')->load($oCoupon->getRuleId());
    	
    	$max_discount_amountrule = $rule->getMaxDiscountAmount();
   
    	$maxDiscount = 0;
    	if((int)$max_discount_amountrule>0)
    	{ 
    		$maxDiscount = $Discount>$max_discount_amountrule?$max_discount_amountrule:$Discount;
    	//	echo $maxDiscount;
    		$maxDiscount = $maxDiscount - $Discount;
    	}
    	 
        $quote=$observer->getEvent()->getQuote();
        $quoteid=$quote->getId();
        $discountAmount= $maxDiscount;
   	    if($quoteid) 
   	    {
       		      
			if($discountAmount) 
			{
				$total=$quote->getBaseSubtotal();
   			    $quote->setSubtotal(0);
 			    $quote->setBaseSubtotal(0);

			    $quote->setSubtotalWithDiscount(0);
			    $quote->setBaseSubtotalWithDiscount(0);
			
			    $quote->setGrandTotal(0);
			    $quote->setBaseGrandTotal(0);
  
    
				$canAddItems = $quote->isVirtual()? ('billing') : ('shipping'); 
   				foreach ($quote->getAllAddresses() as $address) 
   				{
    
					$address->setSubtotal(0);
		            $address->setBaseSubtotal(0);
		
		            $address->setGrandTotal(0);
		            $address->setBaseGrandTotal(0);
		
		            $address->collectTotals();
		
		            $quote->setSubtotal((float) $quote->getSubtotal() + $address->getSubtotal());
		            $quote->setBaseSubtotal((float) $quote->getBaseSubtotal() + $address->getBaseSubtotal());
		
		            $quote->setSubtotalWithDiscount(
		                (float) $quote->getSubtotalWithDiscount() + $address->getSubtotalWithDiscount()
		            );
		            $quote->setBaseSubtotalWithDiscount(
		                (float) $quote->getBaseSubtotalWithDiscount() + $address->getBaseSubtotalWithDiscount()
		            );
		
		            $quote->setGrandTotal((float) $quote->getGrandTotal() + $address->getGrandTotal());
		            $quote->setBaseGrandTotal((float) $quote->getBaseGrandTotal() + $address->getBaseGrandTotal());
		 
   					$quote ->save(); 
 
				    $quote->setGrandTotal($quote->getBaseSubtotal()-$discountAmount)
				      ->setBaseGrandTotal($quote->getBaseSubtotal()-$discountAmount)
				      ->setSubtotalWithDiscount($quote->getBaseSubtotal()-$discountAmount)
				      ->setBaseSubtotalWithDiscount($quote->getBaseSubtotal()-$discountAmount)
				      ->save(); 
      
    
                    if($address->getAddressType()==$canAddItems) 
                    {
					    //echo $address->setDiscountAmount; exit;
					     $address->setSubtotalWithDiscount((float) $address->getSubtotalWithDiscount()-$discountAmount);
					     $address->setGrandTotal((float) $address->getGrandTotal()-$discountAmount);
					     $address->setBaseSubtotalWithDiscount((float) $address->getBaseSubtotalWithDiscount()-$discountAmount);
					     $address->setBaseGrandTotal((float) $address->getBaseGrandTotal()-$discountAmount);
					     if($address->getDiscountDescription())
					     {
						     $address->setDiscountAmount(-($address->getDiscountAmount()-$discountAmount));
						    // $address->setDiscountDescription($address->getDiscountDescription().', Custom Discount');
						     $address->setDiscountDescription($address->getDiscountDescription() );
						     $address->setBaseDiscountAmount(-($address->getBaseDiscountAmount()-$discountAmount));
					     }
					     else 
					     {
						     $address->setDiscountAmount(-($discountAmount));
						   //  $address->setDiscountDescription('Custom Discount');
						     $address->setBaseDiscountAmount(-($discountAmount));
					     }
					     $address->save();
					 }//end: if
				 } //end: foreach
					   //echo $quote->getGrandTotal();
  
	  			foreach($quote->getAllItems() as $item)
	  			{
	                 //We apply discount amount based on the ratio between the GrandTotal and the RowTotal
	                 $rat=$item->getPriceInclTax()/$total;
	                 $ratdisc=$discountAmount*$rat;
	                 $item->setDiscountAmount(($item->getDiscountAmount()+$ratdisc) * $item->getQty());
	                 $item->setBaseDiscountAmount(($item->getBaseDiscountAmount()+$ratdisc) * $item->getQty())->save();
	                
	            }
	                
            }
	            
	    }
 	}
}
?>
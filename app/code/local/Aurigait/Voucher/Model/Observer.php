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
	
	public function mailwelcomevouhcer(Varien_Event_Observer $observer)
	{
		
		$senderName = Mage::getStoreConfig('trans_email/ident_support/name');
		$senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
		$sender = array('name' => $senderName,
				'email' => $senderEmail);
		
		$customerdata = $observer->getCustomer()->getData();
		
	
		
		$recepientEmail = $customerdata['email'];  //$customerData->getEmail();
		$recepientName =  $customerdata['firstname'] ; // .'@@@'.$customerData->getEmail().'$$$' ;// $customerData->getFirstname();//.'@@'.$customerFName;
		
		// Get Store ID
		$store = Mage::app()->getStore()->getId();
		
		
	/*	$couponCode = 'WELCOME';
		$oCoupon = Mage::getModel('salesrule/coupon')->load($couponCode, 'code');
		
		$oRule = Mage::getModel('salesrule/rule')->load($oCoupon->getRuleId());
	*/	 
		$customrule_type= 2;
		//$rulesCollection = Mage::getModel('salesrule/rule')->getCollection();
		//$rulesCollection->getSelect()->where('main_table.rule_type = "'.$customrule_type.'"')->limit(1);
		
		$oRule = Mage::getModel('salesrule/rule')->load($customrule_type,'rule_type');
		$couponCode = ($oRule['coupon_code']);
 
		$templateId = $oRule['email_template'];
		
		$helperobj = Mage::Helper('voucher/customhelper');
		
		$discount_amount = $helperobj->getCouponvalue($couponCode);
		//$templateId = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/email_template');
		$iconimage = Mage::getBaseDir('media') . DS . 'voucher' . DS .$oRule['iconimage'];
		
		$vars = array(	
				'coupon_prize' => $discount_amount,
				'coupon_code' =>$couponCode,
				'coupon_image' =>$iconimage,
				'name' => $recepientName,
				'email' => $recepientEmail,
				);
		
		$translate  = Mage::getSingleton('core/translate');
		
		
		
		if($templateId)
		{
			// Send Transactional Email
			Mage::getModel('core/email_template')
			->addBcc($senderEmail)      // You can remove it if you don't need to send bcc
			->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);
		
			$translate->setTranslateInline(true);
		} 
		
		//$this->checkforreferafriend($customerdata);
		 
	}
	
	//  to check if user has any referal 
	public function checkforreferafriend($customerdata)
	{
	
		$customerEmail = $customerdata['email'];
		$customerName =  $customerdata['firstname'];

		$response = Mage::getModel('invitefriend/invitefriend')->checkcustomerbyreferal($customerEmail);
		
		if($response)
		{   
			$this->createcouponforreferal($response,$customerName);
		}
	//	echo "in 2 obeserverr";die;
	
	}
	
	public function createcouponforreferal($senderinfo,$customerName)
	{
		$friendacceptationperiod  = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/friendacceptationperiod');
		
		$offertype  = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/offertype');
		$offerprice = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/offerprice');
		$vouchervalidityperiod  = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/vouchervalidityperiod');
		
		$iconimage  = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/icon');
		$termsconditions  = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/termsconditions');

		$maximumdiscountamout  = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/maximumdiscountamout');
		$minimumpurchaseamount  = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/minimumpurchaseamount');
		
		$helperobj = Mage::Helper('voucher/data');	
		
		$helperobj->_fromdate ='';
		$helperobj->_todate = '';
		if($vouchervalidityperiod >=0)
		{
			$helperobj->_fromdate = 	date('Y-m-d');
			$helperobj->_todate =  date('Y-m-d' , strtotime('+'.$vouchervalidityperiod.' days' ));
		}
		if($offertype==1)
		{
			$helperobj->_actiontype ="by_percent";
		}
		else if($offertype==2)
		{
			$helperobj->_actiontype ="by_fixed";
		}
		
		$helperobj->_couponname = 'Invititaion Voucher';
		
		$helperobj->_ruletype = 5;
		
		$helperobj->_iconimage = 'voucher/'.$iconimage;
		$helperobj->_termsconditions = $termsconditions;
		
		$helperobj->_maximumdiscountamout = $maximumdiscountamout;
		$helperobj->_minimumpurchaseamount = $minimumpurchaseamount;
		
		$helperobj->_offerprice =$offerprice;
		
		$helperobj->_emailtemplate =Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/email_template');
		
		$helperobj->_code ='INVITE';
		$helperobj->_friendnamecode = '-'.substr($customerName, 0,6).'-';
		foreach($senderinfo as $sender)
		{ 
			 
			$couponcode = $helperobj->CreateCustomCoupon();
			$this->mailinvitevoucher($couponcode,$sender);
			Mage::getModel('voucher/voucherlistcustomer')->savecuoponinfo($sender['customer_id'],$couponcode,'',$helperobj->_fromdate,$helperobj->_todate,6);
		}
		
	}
	
	public function mailinvitevoucher($couponcode,$senderdata)
	{
		
	
		$senderName = Mage::getStoreConfig('trans_email/ident_support/name');
		$senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
		$sender = array('name' => $senderName,
				'email' => $senderEmail);
	
		$customerdata = $senderdata;
	
		$recepientEmail = $customerdata['sender_emailid'];   
		$registerdemail =  $customerdata['receiver_emailid'] ; 

		$store = Mage::app()->getStore()->getId();
		 
		$oCoupon = Mage::getModel('salesrule/coupon')->load($couponcode, 'code');
		$oRule = Mage::getModel('salesrule/rule')->load($oCoupon->getRuleId());
	
		$templateId = $oRule['email_template'];
		//$templateId = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/email_template');
		
		$vars = array(
				'coupon_prize' => $oRule['discount_amount'],
				'coupon_code' =>$couponcode,
				'name' => $recepientName,
				'email' => $recepientEmail,
		);
	
		$translate  = Mage::getSingleton('core/translate');
		
		if($templateId)
		{
			
			// Send Transactional Email
			 	Mage::getModel('core/email_template')
				->addBcc($senderEmail)      // You can remove it if you don't need to send bcc
			->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);
			 
		}
		$translate->setTranslateInline(true);
	
	 
			
	}
	
	 
	
	public function alterform(Valterform1arien_Event_Observer $observer)
	{
		$fieldset =$observer->getEvent()->getForm()->getElement('base_fieldset');
		//$observer->getEvent()->getForm()->setHtmlAttributes('enctype', 'multipart/form-data');
		$fieldset->addField('rule_type', 'select', array(
				'label'     => 'Rule Type',
				'name'      => 'rule_type',
				'required' => true,
				'options'    => array(
						0 => 'Please Selete Voucher type',
						1 => 'Admin Defined Voucher',
						2 => 'Welcome Voucher',
						3 => 'User Cumulative Voucher',
						4 => 'Order Cumulative Voucher',
						5 => 'Invitation Voucher',
				),
		));
		
		$fieldset->addField('email_template', 'select', array(
				'label'     => 'Email Template',
				'name'      => 'email_template',
				
				'values'  =>Mage::getModel('adminhtml/system_config_source_email_template')->toOptionArray() 
		));
	
	 
		$fieldset->addField('iconimage', 'image', array(
				'name' => 'iconimage',
				'title' => Mage::helper('salesrule')->__('Rule Icon'),
				'label' => Mage::helper('salesrule')->__('Rule Icon'),
		));
		$model = Mage::registry('current_promo_quote_rule');
		$observer->getEvent()->getForm()->setValues($model->getData());
	}
	public function alterform1(Varien_Event_Observer $observer)
	{
		//echo 1;die;
	}
	public function alterform2(Varien_Event_Observer $observer)
	{
		$fieldset1 =$observer->getEvent()->getForm()->getElement('action_fieldset');
		$fieldset1->addField('max_discount_amount', 'text', array(
				'name' => 'max_discount_amount',
				'label' => Mage::helper('salesrule')->__('Maximum Amount of Discount'),
				'note' =>'Total Maximume discount amount in any condition (with % or with qty) ',
		));
		
		$fieldset1->addField('purchase_days', 'text', array(
				'name' => 'purchase_days',
				'label' => Mage::helper('salesrule')->__('Purchase Period (c)'),
				'note' =>'Purchase done in days (for user cumulative vouchers)',
		));
		
		$fieldset1->addField('threshold_amount', 'text', array(
				'name' => 'threshold_amount',
				'label' => Mage::helper('salesrule')->__('Threshold Amount'),
				'note' =>'Total order amount in c days (for user cumulative vouchers)',
		));
		
		
		
	}
	
	
	// to update custom coupon 
	
	public function UpdateCustomCoupon($observer)
	{
		$quote = Mage::getSingleton('checkout/cart')->getQuote();
		$currentcouponcode =  $quote->getCouponCode();
		 //echo "D########";die;
	}
	
	public function setDiscount($observer)
    {
		$quote = Mage::getSingleton('checkout/cart')->getQuote();
		if(isset($quote))
		{
	    	$totals =  $quote->getTotals();
	    	$totalsubtotal =  $totals["subtotal"];
	    	$totaldiscount = $totals["discount"];
	    	
	    	if($totalsubtotal && $totaldiscount)
	    	{
	    		
	    		$subtotal = $totalsubtotal->getValue();
		    	//$subtotal = $totals["subtotal"]->getValue();
	    		
	    		$Discount = $totaldiscount->getValue();
	    		
		    	//$Discount = $totals["discount"]->getValue();
		    	
		    	$Discount = $Discount * -1;
		    	
		    	$currentcouponcode =  $quote->getCouponCode();
		    	
		    	if($currentcouponcode)
		    	{
		    		
			    	
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
 		}
    }
}
?>
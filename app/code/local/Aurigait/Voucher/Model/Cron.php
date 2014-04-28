<?php
class Aurigait_Voucher_Model_Cron{	
	public function settallinvitionvoucher()
	{
		//do something
		try
		{
			$configValue = Mage::getStoreConfig('welcomevoucher/gwelcomevoucher/vouchervaliditypperiod');
			
			$coupon = Mage::getModel('salesrule/rule');
			$coupon->setName('sandeep cdoe s')
			->setDescription('this is a description')
			->setFromDate(date('Y-m-d'))
			->setToDate(date('Y-m-d'))
			->setCouponType(2)
			->setCouponCode($_coupon['code'])
			->setUsesPerCoupon(1)
			->setUsesPerCustomer(1)
			->setCustomerGroupIds(array(1)) //an array of customer groupids
			->setIsActive(1)
			//serialized conditions.  the following examples are empty
			->setConditionsSerialized('a:6:{s:4:"type";s:32:"salesrule/rule_condition_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"all";}')
			->setActionsSerialized('a:6:{s:4:"type";s:40:"salesrule/rule_condition_product_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"all";}')
			->setStopRulesProcessing(0)
			->setIsAdvanced(1)
			->setProductIds('')
			->setSortOrder(0)
			->setSimpleAction('by_percent')
			->setDiscountAmount(100)
			->setDiscountQty(null)
			->setDiscountStep('0')
			->setSimpleFreeShipping('0')
			->setApplyToShipping('0')
			->setIsRss(0)
			->setWebsiteIds(array(1));
			$coupon->save();
			
		}
		catch (Exception $e) {
			$this->_getSession()->addError($this->__('Error in code'));
			Mage::logException($e);
		}
	} 
	
	
	
	
	protected $thresholdamount1;
	protected $thresholdamount2;
	protected $thresholdamount3;
	protected $voucherperiod;
	protected $offerprice1;
	protected $offerprice2;
	protected $offerprice3;
	protected $maximumdiscountamout;
	protected $minimumpurchaseamount;
	protected $offertype;
	
	protected $thresholdamoutarry =array();
	protected $thresholarr =array();
	
	
	
	public function setUsercommulativeVoucher()
	{
	
		
		$this->voucherperiod  = Mage::getStoreConfig('usercumulativevouchers/gusercumulativevouchers/voucherperiod');
		$this->thresholdamount1  = Mage::getStoreConfig('usercumulativevouchers/gusercumulativevouchers/thresholdamount1');
		$this->offerprice1  = Mage::getStoreConfig('usercumulativevouchers/gusercumulativevouchers/offerprice1');
		$this->thresholdamount2  = Mage::getStoreConfig('usercumulativevouchers/gusercumulativevouchers/thresholdamount2');
		$this->offerprice2  = Mage::getStoreConfig('usercumulativevouchers/gusercumulativevouchers/offerprice2');
		$this->thresholdamount3  = Mage::getStoreConfig('usercumulativevouchers/gusercumulativevouchers/thresholdamount3');
		$this->offerprice3  = Mage::getStoreConfig('usercumulativevouchers/gusercumulativevouchers/offerprice3');
		$this->maximumdiscountamout  = Mage::getStoreConfig('usercumulativevouchers/gusercumulativevouchers/maximumdiscountamout');
		$this->minimumpurchaseamount  = Mage::getStoreConfig('usercumulativevouchers/gusercumulativevouchers/minimumpurchaseamount');
		$this->alertamount  = Mage::getStoreConfig('usercumulativevouchers/gusercumulativevouchers/alertamount');
	
		$this->offertype = Mage::getStoreConfig('usercumulativevouchers/gusercumulativevouchers/offertype');
		
		$this->thresholarr = array($this->thresholdamount1,$this->thresholdamount2,$this->thresholdamount3);
	
		$this->thresholdamoutarry = array(
				$this->thresholdamount1 => $this->offerprice1,
				$this->thresholdamount2 =>$this->offerprice2,
				$this->thresholdamount3 =>$this->offerprice3,
		);
	
		$minimumthreshold = min($this->thresholarr);
	
	
		$todate = date('Y-m-d');
		$fromdate = date('Y-m-d' , strtotime('-'.$this->voucherperiod.' days' ));
	
	
	
		$helperobj = Mage::Helper('voucher/data');
	
		$helperobj->_couponname = 'User Cumulative Voucher';
		$helperobj->_fromdate ='';
		$helperobj->_todate = '';
		$helperobj->_actiontype ="by_fixed";
		$helperobj->_maximumdiscountamout = $this->maximumdiscountamout;
		$helperobj->_minimumpurchaseamount = $this->minimumpurchaseamount;
	
		$sql = "select customer_id , sum(base_subtotal ) as totalorderamount from sales_flat_order where status= 'complete' and created_at >'".$fromdate."' and created_at <='".$todate."'   and customer_id IS NOT NULL  group by customer_id having totalorderamount >= ".$minimumthreshold."  ";
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
	
		$data=$write->fetchAll($sql);
			
		foreach($data as $row)
		{
				
			$orderamount  = number_format( $row['totalorderamount'],2);
				
			$offeramount = $this->getOfferbyOrderamout($orderamount);
			$helperobj->_offerprice =$offeramount;
			$helperobj->_code ='CUST';
			
			//$stringData.= '##'.$row[customer_id].'@@'.$offeramount.'<br>/n';
				
			$couponcreatedalready = Mage::getModel('voucher/voucherlistcustomer')->testcoupon($row['customer_id'],$orderamount,$fromdate,$minimumthreshold);
	
			if($couponcreatedalready>=0)
			{
				if($couponcreatedalready>0)
				{
					$orderamount1  = number_format( $couponcreatedalready,2);
	
					$offeramount = $this->getOfferbyOrderamout($orderamount1);
					$helperobj->_offerprice =$offeramount;
				}
			//	$stringData.= '##'.$row[customer_id].'@@'.$offeramount.'<br>/n'.$offeramount;
				
				$stringData.=$customerEmailId.','.$customerFName;
				$customerData = Mage::getModel('customer/customer')->load($row['customer_id']);
				
				$customerEmailId = $customerData->getEmail();
				$customerFName = $customerData->getFirstname();
				//$stringData.=$customerEmailId.','.$customerFName;
				
				
				
				$couponcode = $helperobj->CreateCustomCoupon();
				
				$this->sendmail($customerEmailId,$customerFName,$offeramount,$couponcode);
				Mage::getModel('voucher/voucherlistcustomer')->savecuoponinfo($row['customer_id'],$couponcode,$orderamount,$fromdate,$todate);
					
			} 
		}
		 
			
	
	}
	
	public function getOfferbyOrderamout($orderamount)
	{
		$thresholarr = (($this->thresholarr));
		arsort($thresholarr);
	
		foreach($thresholarr as $val)
		{
	
			if((double)$orderamount>=(double)$val)
			{
				$offeramount  =  $this->thresholdamoutarry[$val];
				break;
			}
				
		}
		
		
		if($this->offertype==1)
			$retunamount = ($orderamount * $offeramount)/100;
		else
			$retunamount = $offeramount;
	
		return $retunamount ;
	
	}
	
	public function sendmail($customeremail,$customername,$amount,$couponcode)
	{
		$templateId = 1;
		
		$senderName = Mage::getStoreConfig('trans_email/ident_support/name');
		$senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
		$sender = array('name' => $senderName,
				'email' => $senderEmail);
		
		// Set recepient information
		$recepientEmail =$customeremail;
		$recepientName = $customername;
		
		// Get Store ID
		$store = Mage::app()->getStore()->getId();
		
		// Set variables that can be used in email template. To get this variable write like {{var customerEmail}} in transactional Email.
		$vars = array(	
				'coupon_prize' => $amount,
				'coupon_code' => $couponcode,
				);
		
		$translate  = Mage::getSingleton('core/translate');
		
		// Send Transactional Email
		Mage::getModel('core/email_template')
		->addBcc($senderEmail)      // You can remove it if you don't need to send bcc
		->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);
		
		$translate->setTranslateInline(true);
	}
}
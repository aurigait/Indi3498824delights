<?php 
//require_once "Aurigait/Voucher/Model/Coupongenerator.php";

class Aurigait_Voucher_TestController extends Mage_Core_Controller_Front_Action
{
	
	
	
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
	
	
	
	public function setUsercommulativeVoucherAction()
	{
	
		// user cumulative voucher code.
		$customrule_type= 3;
		$rulesCollection = Mage::getModel('salesrule/rule')->getCollection();
		$rulesCollection->getSelect()->where('main_table.rule_type = "'.$customrule_type.'"');//$rulesCollection->load(true,true);die;
		//	$oRule = Mage::getModel('salesrule/rule')->load($customrule_type,'rule_type');
	
		$customer_array =array();
		foreach($rulesCollection as $rul)
		{
			$thresholdperiod = $rul['purchase_days'];
			$thresholdamount = $rul['threshold_amount'];
			$todate = date('Y-m-d');
			$fromdate = date('Y-m-d' , strtotime('-'.$thresholdperiod.' days' ));
	
			$write = Mage::getSingleton('core/resource')->getConnection('core_write');
				
			$sql = "select customer_id , sum(grand_total ) as totalorderamount from sales_flat_order where status= 'complete' and date(created_at) >'".$fromdate."' and created_at <='".$todate."'   and customer_id IS NOT NULL  group by customer_id having totalorderamount >= ".$thresholdamount."  ";

			$data=$write->fetchAll($sql);
	 
			$templateid =  $rul['email_template'];
			$couponcode = $rul['code'];
			foreach($data as $row)
			{
				$customerData = Mage::getModel('customer/customer')->load($row['customer_id']);
					
				$customerEmailId = $customerData->getEmail();
				
				$customerFName = $customerData->getFirstname();
				
				$helperobj = Mage::Helper('voucher/customhelper');
				
				$offeramount = $helperobj->getCouponvalue($couponcode);
				if(!in_array($customerEmailId, $customer_array))
				{
					$customer_array[]=$customerEmailId;
					$this->sendmail($customerEmailId,$customerFName,$offeramount,$couponcode,$templateid);
					Mage::getModel('voucher/voucherlistcustomer')->savecuoponinfo($row['customer_id'],$couponcode,$row['totalorderamount'],$fromdate,$todate,$customrule_type);
				}
					
			}
		}
			
	
		/*
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
	
	
	
		$minimumthreshold = min($thresholdarray);
	
	
	
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
			
		*/
	
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
	
	public function sendmail($customeremail,$customername,$amount,$couponcode,$templateid)
	{
		$templateId =$templateid;
	
		//	$templateId =  Mage::getStoreConfig('usercumulativevouchers/gusercumulativevouchers/email_template');;
	
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
	
		if($templateId)
		{
			// Send Transactional Email
			Mage::getModel('core/email_template')
			->addBcc($senderEmail)      // You can remove it if you don't need to send bcc
			->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);
		}
		$translate->setTranslateInline(true);
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
	
		$helperobj->_code ='CUST';
		$helperobj->_friendnamecode = substr($customerName, 0,4);
		foreach($senderinfo as $sender)
		{
	
			$couponcode = $helperobj->CreateCustomCoupon();
			$this->mailinvitevoucher($couponcode,$sender);
		}
		//	echo "doe";die;
	
	}
	
	
	//  code for generate invitation vouchers
	
	public function setInvitationvoucherAction()
	{
			
	
		$friendacceptationperiod  = Mage::getStoreConfig('invitationvoucher2/ginvitationvoucher2/friendacceptationperiod');
		$friendpurchaseperiod = Mage::getStoreConfig('invitationvoucher2/ginvitationvoucher2/friendpurchaseperiod');
		$voucherissueperiod = Mage::getStoreConfig('invitationvoucher2/ginvitationvoucher2/voucherissueperiod');
	
		try
		{
	
			$odercompletedate = date('Y-m-d' , strtotime('-'.$voucherissueperiod.' days'));
			 
			$order = Mage::getModel('sales/order')->getCollection();
			 
			$order->getSelect('main_table.customer_id')->where('main_table.status NOT IN ( "canceled" , "holded")  and date(main_table.created_at) = "'.$odercompletedate.'" ');
			 
			foreach ($order as $orderrow)
			{
				
			 
				$customerData = Mage::getModel('customer/customer')->load($orderrow->getCustomerId())->getData();
				$registerdate = strtotime($customerData['created_at']);
	
				$response = Mage::getModel('invitefriend/invitefriend')->checkcustomerbylastreferal($customerData['email']);
				if($response)
				{
					print_r($response);
					$referaldate = strtotime($response['senddate']);
					echo $customerData['created_at'];echo "<br>";
					echo $response['senddate'];echo "<br>";
					//$friendacceptationperiod = $friendacceptationperiod;
					$friendacceptationdate  =  $referaldate + ( (24*60*60) * $friendacceptationperiod);
					echo $registerdate.'<br>'.$friendacceptationdate;
					if($registerdate<=$friendacceptationdate)
					{
						$orderinfo = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('customer_id',$orderrow->getCustomerId());
	
						$firstorderdate = $registerdate + ( (24*60*60) * $friendpurchaseperiod);
						$orderdate = $orderrow->getCreatedAtTimestamp();
						if($orderdate<=$firstorderdate)
						{
						
							$this->createcouponforreferaltype2($response['sender_id'],$orderrow->getBaseSubtotal());
						//	Mage::getModel('invitefriend/invitefriend')->updatereferaldone($response['sender_emailid'],$customerData['email'],$response['senddate']);
	
						}
	
					}
	
				}
			}
	
			//$this->CreateCouponInvit();
		}
		catch (Exception $e) {
			$this->_getSession()->addError($this->__('Error in code'));
			Mage::logException($e);
		}
	}
	
	public function createcouponforreferaltype2($customerid,$orderamount)
	{
	
		$offerprice = Mage::getStoreConfig('invitationvoucher2/ginvitationvoucher2/offerprice');
		$maximumdiscountamout = Mage::getStoreConfig('invitationvoucher2/ginvitationvoucher2/maximumdiscountamout');
		$minimumpurchaseamount = Mage::getStoreConfig('invitationvoucher2/ginvitationvoucher2/minimumpurchaseamount');
		$vouchervalidityperiod = Mage::getStoreConfig('invitationvoucher2/ginvitationvoucher2/vouchervalidityperiod');
		$offertype =  Mage::getStoreConfig('invitationvoucher2/ginvitationvoucher2/offertype');
		
		
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
		else if($offertype==3)
		{  
			$offerprice = $this->getOfferbyOrderamoutinvit($orderamount,$offerprice);
			$helperobj->_actiontype ="by_fixed";
			 
	
		}
		
		$helperobj->_offerprice =$offerprice ;
		$iconimage  = Mage::getStoreConfig('invitationvoucher2/ginvitationvoucher2/icon');
		$termsconditions  = Mage::getStoreConfig('invitationvoucher2/ginvitationvoucher2/termsconditions');
	
	
	
	
	
		$helperobj->_couponname = 'Invititaion Voucher Type 2';
	
		$helperobj->_ruletype = 5;
	
		$helperobj->_iconimage = 'voucher/'.$iconimage;
		$helperobj->_termsconditions = $termsconditions;
	
		$helperobj->_maximumdiscountamout = $maximumdiscountamout;
		$helperobj->_minimumpurchaseamount = $minimumpurchaseamount;
	
		$helperobj->_offerprice =$offerprice;
	
		$helperobj->_emailtemplate =Mage::getStoreConfig('invitationvoucher2/ginvitationvoucher2/email_template');
	
		$helperobj->_code ='INVITE';
		$helperobj->_friendnamecode = substr($customerName, 0,4);
			
		$couponcode = $helperobj->CreateCustomCoupon();
		$customerData = Mage::getModel('customer/customer')->load($customerid);
		$this->mailinvitevoucher($couponcode,$customerData);
		Mage::getModel('voucher/voucherlistcustomer')->savecuoponinfo($customerid,$couponcode,'',$helperobj->_fromdate,$helperobj->_todate,$helperobj->_ruletype);
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
			/*	Mage::getModel('core/email_template')
			 ->addBcc($senderEmail)      // You can remove it if you don't need to send bcc
			->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);
			*/
		}
		$translate->setTranslateInline(true);
	
	
			
	}
	public function getOfferbyOrderamoutinvit($orderamount,$offerprice)
	{
		$retunamount = ($orderamount * $offerprice)/100;
	
		return $retunamount ;
	
	}
}
?>
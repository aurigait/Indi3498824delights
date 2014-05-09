<?php 
//require_once "Aurigait/Voucher/Model/Coupongenerator.php";

class Aurigait_Voucher_TestController extends Mage_Core_Controller_Front_Action
{
	
	
	protected $thresholdamount1;
	protected $thresholdamount2;
	protected $thresholdamount3;
	protected $thresholdamoutarry =array();
	protected $thresholarr =array();
	
	
	protected $friendacceptationperiod;
	
	protected $friendpurchaseperiod;
	
	protected $voucherissueperiod;
	
	protected $offerprice;
	
	protected $maximumdiscountamout;
	
	protected $minimumpurchaseamount;
	
	protected $vouchervalidityperiod;
	
	protected $_serialarryinvit = array();
	
	// to set config values for invition voucher
	public function SetInvitConfigVal()
	{
		$this->friendacceptationperiod  = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/friendacceptationperiod');
		$this->friendpurchaseperiod = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/friendpurchaseperiod');
		$this->voucherissueperiod = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/voucherissueperiod');
			
	
		$this->offerprice = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/offerprice');
		$this->maximumdiscountamout = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/maximumdiscountamout');
		$this->minimumpurchaseamount = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/minimumpurchaseamount');
		$this->vouchervalidityperiod = Mage::getStoreConfig('invitationvoucher/ginvitationvoucher/vouchervalidityperiod');
			
	}
	
	// to get coupon code
	public function getCouponCode()
	{
		$friendname = "sandeep";
		
			
		$salecoupon = Mage::getModel('salesrule/coupon');
		
		do {
			$couponcode =  'INV'.strtoupper($friendname).rand(0,1000);
		}while($salecoupon->getResource()->exists($couponcode));
		return $couponcode;
	}
	
	public function SetConditionInvit()
	{
		$this->_serialarryinvit =array(
				"type" => 'salesrule/rule_condition_combine',
				"attribute" =>'',
				"operator" =>'',
				"value" => 1,
				"is_value_processed" =>'',
				"aggregator" => 'all',
				"conditions" => array
				(
						"0" => array
						(
								"type" => 'salesrule/rule_condition_address',
								"attribute" => 'base_subtotal',
								"operator" => '>',
								"value" => $this->minimumpurchaseamount,
								"is_value_processed" =>''
						)
		
				)
		
		);
		
	}
	
	public function CreateCouponInvit()
	{
		
		$this->SetConditionInvit();
		$couponcode = $this->getCouponCode();
		$todate = date('Y-m-d' , strtotime('+'.$this->vouchervalidityperiod.' days' ));
		
		
		$coupon = Mage::getModel('salesrule/rule');
		$coupon->setName('Innivation')
		->setDescription('')
		->setFromDate(date('Y-m-d'))
		->setToDate($todate)
		->setCouponType(2)
		->setCouponCode($couponcode)
		->setUsesPerCoupon(1)
		->setUsesPerCustomer(1)
		->setCustomerGroupIds(array(1)) //an array of customer groupids
		->setIsActive(1)
		->setConditionsSerialized(serialize($this->_serialarryinvit))
		->setActionsSerialized('a:6:{s:4:"type";s:40:"salesrule/rule_condition_product_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"all";}')
		->setStopRulesProcessing(0)
		->setIsAdvanced(1)
		->setProductIds('')
		->setSortOrder(0)
		->setSimpleAction('by_percent')
		->setDiscountAmount($this->offerprice)
		->setDiscountQty(null)
		->setMaxDiscountAmount($this->maximumdiscountamout)
		->setDiscountStep('0')
		->setSimpleFreeShipping('0')
		->setApplyToShipping('0')
		->setIsRss(0)
		->setWebsiteIds(array(1));
		$coupon->save();
	}
	
	public function demoAction()
	{
		$this->SetInvitConfigVal();
		try
		{
			
			$odercompletedate = date('Y-m-d' , strtotime('-'.$this->voucherissueperiod.' days'));
			
			$order = Mage::getModel('sales/order')->getCollection();
			
			$odercompletedate = '2014-04-03';
			
		 	//$order->getSelect('main_table.customer_id')->where('main_table.status = "complete" and main_table.created_at = '.$odercompletedate);
		 	$order->getSelect('main_table.customer_id')->where('main_table.status = "complete" and main_table.created_at = '.$odercompletedate);
			
			foreach ($order as $orderrow)
			{
				echo $orderrow->getCustomerId().'<br>';
			}
			$order->load(true,true);die;
	
				
			
				
				
			$this->CreateCouponInvit();
		}
		catch (Exception $e) {
			$this->_getSession()->addError($this->__('Error in code'));
			Mage::logException($e);
		}
	}
	
	
	public function demo2Action()
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

		
		$helperobj->_fromdate ='';
		$helperobj->_todate = '';
		$helperobj->_actiontype ="by_fixed";
		$helperobj->_maximumdiscountamout = $this->maximumdiscountamout;
		$helperobj->_minimumpurchaseamount = $this->minimumpurchaseamount; 		
		
		$sql = "select customer_id , sum(base_subtotal ) as totalorderamount from sales_flat_order where created_at >'".$fromdate."' and created_at <='".$todate."'   and customer_id IS NOT NULL  group by customer_id having totalorderamount >= ".$minimumthreshold."  ";
	
		
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$data=$write->fetchAll($sql);
		foreach($data as $row)
		{
			$orderamount  = number_format( $row['totalorderamount'],2);
			
			$offeramount = $this->getOfferbyOrderamout($orderamount);
			$helperobj->_offerprice =$offeramount;
			$helperobj->_code ='CUST';
			$helperobj->CreateCustomCoupon();
			
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
		$retunamount = ($orderamount * $offeramount)/100;
		
		return $retunamount ;
	
	}
	
}
?>
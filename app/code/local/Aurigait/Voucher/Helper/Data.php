<?php
class Aurigait_Voucher_Helper_Data extends Mage_Core_Helper_Abstract
{
    public $_code;
	
    public $_couponname;
    
	public $_fromdate;
	
	public $_todate;

    public $_serialarryinvit = array();
	
	public $_actiontype ;
	
	public $_minimumpurchaseamount;
	
	public $_offerprice;
	
	public $_maximumdiscountamout;
	
	
	
	public function test()	
	{
		echo "asdfhaisdfs";
		//echo $this->_code;
	}
	
	// to get coupon code
	public function getCouponCode()
	{
		$friendname = "";
	
			
		$salecoupon = Mage::getModel('salesrule/coupon');
	
		do {
			$couponcode = $this->_code .''.strtoupper($friendname).rand(0,10000);
		}while($salecoupon->getResource()->exists($couponcode));
		return $couponcode;
	}
	
	
	
	public function SetCondition()
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
								"value" => $this->_minimumpurchaseamount,
								"is_value_processed" =>''
						)
	
				)
	
		);
	
	}
	
	
	public function CreateCustomCoupon()
	{
	
		$this->SetCondition();
		$couponcode = $this->getCouponCode();
	
		
		$coupon = Mage::getModel('salesrule/rule');
		$coupon->setName($this->_couponname)
		->setDescription('')
		->setFromDate($this->_fromdate)
		->setToDate($this->_todate)
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
		->setSimpleAction($this->_actiontype)
		->setDiscountAmount($this->_offerprice)
		->setDiscountQty(null)
		->setMaxDiscountAmount($this->_maximumdiscountamout)
		->setDiscountStep('0')
		->setSimpleFreeShipping('0')
		->setApplyToShipping('0')
		->setIsRss(0)
		->setWebsiteIds(array(1));
		 
		$coupon->save();
		
		return $couponcode;
		
	}
 
	
}
	 
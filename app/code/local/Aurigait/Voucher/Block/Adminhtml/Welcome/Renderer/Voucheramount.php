<?php
class Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_Voucheramount extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Select
{
	static  $_voucheramount; 
	
	 
	
	public function render(Varien_Object $row)
	{
		if($row['voucher_amount'])
		{
			return number_format($row['voucher_amount'],2);
		}
		if($this->_voucheramount)
		{
			return $this->_voucheramount;
		}
		$oRule = Mage::getModel('salesrule/rule')->load(2,'rule_type');
		
		$couponCode = ($oRule['coupon_code']);
		
		
		$helperobj = Mage::Helper('voucher/customhelper');
		
		$discount_amount = $helperobj->getCouponvalue($couponCode);
		$this->_voucheramount =$discount_amount; 
		return $discount_amount;
	}
	public function getFilter()
	{
		return true;
	}
}
?>

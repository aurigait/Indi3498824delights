<?php
class Aurigait_Voucher_Block_Adminhtml_Sales_Order_Create_Coupons extends Mage_Adminhtml_Block_Sales_Order_Create_Coupons
{
	
	public function _construct()
	{
		
		 
	}
	public function getCouponCodeval()
	{
		//$this->getLayout()->getBlock('head')->addJs('vouhcer/sales/asdfsales.js');
		 
		$orderid = $this->_getSession()->getOrder()->getEntityId();
		
		$orders = Mage::getResourceModel('sales/order_collection')
		->addFieldToSelect('coupon_code')
		 
		->addFieldToFilter('entity_id',$orderid);
		$data = $orders->getData();
		$couponcode = $data[0]['coupon_code'];
		if($couponcode)
		{
			return $couponcode;
		}
		else
		{
			return false;
		}
	}
	
	public function getActivecouponUrl()
	{
		$couponcode = $this->getCouponCodeval();
		$customerid = $this->_getSession()->getOrder()->getCustomerId();
		
		$url = Mage::getBaseUrl().'voucher/index/setCouponcount/couponcode/'.$couponcode.'/customerid/'.$customerid;
		return $url; 
		
	}
	
}
			
<?php   
class Aurigait_Reviewproduct_Block_Index extends Mage_Core_Block_Template{   

	public function __construct()
	{
		parent::__construct();
		//$this->setTemplate('sales/order/history.phtml');
	
		$orders = Mage::getResourceModel('sales/order_collection')
		->addFieldToSelect('*')
		->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
		->addFieldToFilter('state', array('in' => array("complete")))
		->setOrder('created_at', 'desc')
		;
	
		$this->setOrders($orders);
	
		Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('root')->setHeaderTitle(Mage::helper('reviewproduct')->__('Review Products'));
	}

	public function getPagerHtml()
	{
		return $this->getChildHtml('pager');
	}

}
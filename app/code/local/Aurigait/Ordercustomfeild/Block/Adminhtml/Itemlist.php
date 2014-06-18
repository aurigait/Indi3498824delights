<?php

class Aurigait_Ordercustomfeild_Block_Adminhtml_Itemlist extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{
		
		$orderid = Mage::app()->getRequest()->getParam('order_id');
	$this->_controller = "adminhtml_itemlist";
	$this->_blockGroup = "ordercustomfeild";
	$this->_headerText = Mage::helper("ordercustomfeild")->__("Ordercustomfeild Item Manager ");
	$this->_addButtonLabel = Mage::helper("ordercustomfeild")->__("Add New Item");
	parent::__construct();
	$this->_removeButton('add');
	}

}

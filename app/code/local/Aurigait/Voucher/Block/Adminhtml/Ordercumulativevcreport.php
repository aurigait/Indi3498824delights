<?php
 

class Aurigait_Voucher_Block_Adminhtml_Ordercumulativevcreport extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_ordercumulativevcreport";
	$this->_blockGroup = "voucher";
	$this->_headerText = Mage::helper("voucher")->__("Order Cumulative Voucher Report");
	$this->_addButtonLabel = Mage::helper("voucher")->__("Add New Item");
	parent::__construct();
	$this->_removeButton('add');
	
	}

}

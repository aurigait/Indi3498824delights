<?php


class Aurigait_Voucher_Block_Adminhtml_Invitivoucherreport extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_invitivoucherreport";
	$this->_blockGroup = "voucher";
	$this->_headerText = Mage::helper("voucher")->__("Invitivoucherreport Manager");
 	$this->_addButtonLabel = Mage::helper("voucher")->__("Add New Item");
	parent::__construct();
	$this->_removeButton('add');
	}

}
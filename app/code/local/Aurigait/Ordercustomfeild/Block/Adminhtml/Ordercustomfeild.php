<?php


class Aurigait_Ordercustomfeild_Block_Adminhtml_Ordercustomfeild extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_ordercustomfeild";
	$this->_blockGroup = "ordercustomfeild";
	$this->_headerText = Mage::helper("ordercustomfeild")->__("Ordercustomfeild Manager");
	$this->_addButtonLabel = Mage::helper("ordercustomfeild")->__("Add New Item");
	parent::__construct();
	$this->_removeButton('add');
	}

}
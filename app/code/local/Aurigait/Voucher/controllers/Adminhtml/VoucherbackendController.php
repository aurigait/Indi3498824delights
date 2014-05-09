<?php
class Aurigait_Voucher_Adminhtml_VoucherbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Set up Voucher"));
	   $this->renderLayout();
    }
}
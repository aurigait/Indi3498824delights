<?php
class Aurigait_Voucher_IndexController extends Mage_Core_Controller_Front_Action{
	
	
	const WELCOMECODE    = 'WELCOME';
	
	const CUSTCODE    = 'CUST';
	
	const WELCOMERULTYPEID    = 1;

	
	
	public function IndexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Titlename"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("titlename", array(
                "label" => $this->__("Titlename"),
                "title" => $this->__("Titlename")
		   ));

      $this->renderLayout(); 
	  
    }
    
    public function VoucherlistAction()
    {
    	$this->loadLayout();
    	$this->getLayout()->getBlock("head")->setTitle($this->__("Voucher List"));
    	$breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
    	$breadcrumbs->addCrumb("home", array(
    			"label" => $this->__("Home Page"),
    			"title" => $this->__("Home Page"),
    			"link"  => Mage::getBaseUrl()
    	));
    	 
    	$breadcrumbs->addCrumb("voucherlist", array(
    			"label" => $this->__("Voucher List"),
    			"title" => $this->__("Voucher List")
    	));
    	 
    	$this->renderLayout();
    }
    
    public function ReferallistAction()
    {
    	$this->loadLayout();
    	$this->getLayout()->getBlock("head")->setTitle($this->__("Referal List"));
    	$breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
    	$breadcrumbs->addCrumb("home", array(
    			"label" => $this->__("Home Page"),
    			"title" => $this->__("Home Page"),
    			"link"  => Mage::getBaseUrl()
    	));
    	
    	$breadcrumbs->addCrumb("titlename", array(
    			"label" => $this->__("Referal List"),
    			"title" => $this->__("Referal List")
    	));
    	
    	$this->renderLayout();
    }
}
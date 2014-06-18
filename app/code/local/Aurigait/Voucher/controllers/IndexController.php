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
    
    public function setCouponcountAction()
    {
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		
		$couponcode =  Mage::app()->getRequest()->getParam('couponcode');// 'RAMRAM2';
		$customerid =  Mage::app()->getRequest()->getParam('customerid');//'54';
		
		$sql = "update salesrule_coupon_usage as a inner join salesrule_coupon as b  on a.coupon_id = b.coupon_id left join salesrule as c on c.rule_id = b.rule_id  left join salesrule_customer as d on d.rule_id = c.rule_id set a.times_used = a.times_used-1 , b.times_used = b.times_used-1 , c.times_used = c.times_used-1 , d.times_used = d.times_used-1   where a.customer_id ='".$customerid."' and b.code = '".$couponcode."'  	";
		$write->query($sql);
		echo 1;
	}
}
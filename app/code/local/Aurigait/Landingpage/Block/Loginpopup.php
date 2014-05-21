<?php
/**
 * GoMage Social Connector Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2013 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 1.1.0
 * @since        Class available since Release 1.0.0
 */

class Aurigait_Landingpage_Block_Loginpopup extends Mage_Core_Block_Template {

    protected  $place;
	
	public function __construct() {
		parent::__construct();
		
		$cookie=Mage::getModel('core/cookie')->get("landing_step");
		
		if (! $this->helper('customer')->isLoggedIn() && empty($cookie)){
			$this->setTemplate('landingpage/popup.phtml');
			Mage::getModel('core/cookie')->set("landing_step",1);
		}
	
	}
	public function isVisible() {

	
	}
	
}

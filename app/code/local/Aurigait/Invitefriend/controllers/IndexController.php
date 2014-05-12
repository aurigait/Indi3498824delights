<?php 
class Aurigait_Invitefriend_IndexController extends Mage_Core_Controller_Front_Action{
    
	public function AbcAction()
	{
		echo 22;die;
	}
    
    /**
     * Predispatch: check is enable module
     * If allow only for customer - redirect to login page
     *
     * @return Mage_Sendfriend_ProductController
     */
    
    
    /**
     * Initialize send friend model
     *
     * @return Mage_Sendfriend_Model_Sendfriend
     */
    protected function _initSendToFriendModel()
    {
    	$model  = Mage::getModel('invitefriend/sendfriend');
    	$model->setRemoteAddr(Mage::helper('core/http')->getRemoteAddr(true));
    	$model->setCookie(Mage::app()->getCookie());
    	$model->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
    
    	Mage::register('send_to_friend_model', $model);
    
    	return $model;
    }
    
    /**
     * Show Send to a Friend Form
     *
     */
    public function IndexAction()
    {
    	//$product    = $this->_initProduct();
    	$model      = $this->_initSendToFriendModel();
      
    	$this->loadLayout();
    	$this->_initLayoutMessages('catalog/session');
    
    	$this->getLayout()->getBlock("head")->setTitle($this->__("Invite Friend"));
    	$breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
    	$breadcrumbs->addCrumb("home", array(
    			"label" => $this->__("Home Page"),
    			"title" => $this->__("Home Page"),
    			"link"  => Mage::getBaseUrl()
    	));
    	
    	$breadcrumbs->addCrumb("invite friend", array(
    			"label" => $this->__("Invite Friend"),
    			"title" => $this->__("Invite Friend")
    	));
    	 
    	$data = Mage::getSingleton('catalog/session')->getSendfriendFormData();
    	if ($data) {
    		Mage::getSingleton('catalog/session')->setSendfriendFormData(true);
    		$block = $this->getLayout()->getBlock('sendfriend.send');
    		if ($block) {
    			$block->setFormData($data);
    		}
    	}
    
    	$this->renderLayout();
    }
    
    /**
     * Send Email Post Action
     *
     */
    public function sendmailAction()
    {
    	if (!$this->_validateFormKey()) {
    		return $this->_redirect('*/*/index', array('_current' => true));
    	}
  
    	 
    	$model      = $this->_initSendToFriendModel();
    	$data       = $this->getRequest()->getPost();
    
    	if ( !$data) {
    		$this->_forward('noRoute');
    		return;
    	}
    
    	$categoryId = $this->getRequest()->getParam('cat_id', null);
    	if ($categoryId) {
    		$category = Mage::getModel('catalog/category')
    		->load($categoryId);
    		$product->setCategory($category);
    		Mage::register('current_category', $category);
    	}
    
    	$model->setSender($this->getRequest()->getPost('sender'));
    	$model->setRecipients($this->getRequest()->getPost('recipients'));
    	//$model->setProduct($product);
    
    	try {
    		$validate = $model->validate();
    		if ($validate === true) {
    			$model->send();
    			Mage::getSingleton('catalog/session')->addSuccess($this->__('The link to a friend was sent.'));
    			$this->_redirectSuccess(Mage::getURL('*/*/index'));
    			return;
    		}
    		else {
    			if (is_array($validate)) {
    				foreach ($validate as $errorMessage) {
    					Mage::getSingleton('catalog/session')->addError($errorMessage);
    				}
    			}
    			else {
    				Mage::getSingleton('catalog/session')->addError($this->__('There were some problems with the data.'));
    			}
    		}
    	}
    	catch (Mage_Core_Exception $e) {
    		Mage::getSingleton('catalog/session')->addError($e->getMessage());
    	}
    	catch (Exception $e) {
    		Mage::getSingleton('catalog/session')
    		->addException($e, $this->__('Some emails were not sent.'));
    	}
    
    	// save form data
    	Mage::getSingleton('catalog/session')->setSendfriendFormData($data);
    
    	$this->_redirectError(Mage::getURL('*/*/index', array('_current' => true)));
    }
    
}

<?php 
require_once "Mage/Sendfriend/controllers/ProductController.php";  
class Aurigait_Voucher_Sendfriend_ProductController extends Mage_Sendfriend_ProductController{

	public function sendmailAction()
	{
		 
		if (!$this->_validateFormKey()) {
			return $this->_redirect('*/*/send', array('_current' => true));
		}
	
		$product    = $this->_initProduct();
		$model      = $this->_initSendToFriendModel();
		$data       = $this->getRequest()->getPost();
	//	echo 123;die;
		/*if (!$product || !$data) {
			$this->_forward('noRoute');
			return;
		}*/
		//echo 33;die;
	
	/*	$categoryId = $this->getRequest()->getParam('cat_id', null);
		if ($categoryId) {
			$category = Mage::getModel('catalog/category')
			->load($categoryId);
			$product->setCategory($category);
			Mage::register('current_category', $category);
		}
	*/
		$model->setSender($this->getRequest()->getPost('sender'));
		$model->setRecipients($this->getRequest()->getPost('recipients'));
		//$model->setProduct($product);
		//echo 123;die;
		try {
			$validate = $model->validate();
			if ($validate === true) {
				$model->send();
				Mage::getSingleton('catalog/session')->addSuccess($this->__('The link to a friend was sent.'));
				$this->_redirectSuccess($product->getProductUrl());
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
	
		$this->_redirectError(Mage::getURL('*/*/send', array('_current' => true)));
	}
}
				
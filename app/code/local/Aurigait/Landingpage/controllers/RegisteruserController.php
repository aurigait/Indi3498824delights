<?php
require_once 'Mage/Customer/controllers/AccountController.php';
class Aurigait_Landingpage_RegisteruserController extends Mage_Customer_AccountController
{
	/**
	 * Create customer account action
	 */
	public function createPostAction()
	{
		/** @var $session Mage_Customer_Model_Session */
		$session = $this->_getSession();
		if ($session->isLoggedIn()) {
			$this->_redirect('*/*/');
			return;
		}
		$session->setEscapeMessages(true); // prevent XSS injection in user input
		if (!$this->getRequest()->isPost()) {
			$errUrl = $this->_getUrl('*/*/create', array('_secure' => true));
			$this->_redirectError($errUrl);
			return;
		}
		$customer = $this->_getCustomer();
		 try{
			$errors = $this->_getCustomerErrors($customer);
			if (empty($errors)) {
				$customer->save();
				$post=$this->getRequest()->getPost();
				$write = Mage::getSingleton("core/resource")->getConnection("core_write");
				$address_attr=Mage::getSingleton('core/resource')->getTableName('customer_address_entity');
				$sql="INSERT INTO ".$address_attr." (entity_type_id, attribute_set_id,  parent_id, created_at, updated_at, is_active) VALUES (2,0,".$customer->getId().",'".time()."','".time()."',1)";
			
				$write->query($sql);
				$add_id=$write->lastInsertId();
				$address_attr_varchar=Mage::getSingleton('core/resource')->getTableName('customer_address_entity_varchar');
				
				if(!empty($post['region_id']))
				{
					$sql="select name from ".Mage::getSingleton('core/resource')->getTableName('directory_country_region_name')." where region_id=".$post["region_id"];
					$row=$write->fetchRow($sql);
						
					
					$sql="INSERT INTO ".$address_attr_varchar."(entity_type_id, attribute_id, entity_id, value) VALUES(2,28,".$add_id.",'".$row->name."')";
					$write->query($sql);
					
					$address_attr_int=Mage::getSingleton('core/resource')->getTableName('customer_address_entity_int');
					
					$sql="INSERT INTO ".$address_attr_int."(entity_type_id, attribute_id, entity_id, value) VALUES(2,29,".$add_id.",'".$post["region_id"]."')";
					$write->query($sql);
					
				}
				else 
				{
					$sql="INSERT INTO ".$address_attr_varchar."( entity_type_id, attribute_id, entity_id, value) VALUES(2,28,".$add_id.",'".$post["region"]."')";
					$write->query($sql);
				}
				$sql="INSERT INTO ".$address_attr_varchar."(entity_type_id, attribute_id, entity_id, value) VALUES(2,27,".$add_id.",'".$post["country_id"]."')";
				$write->query($sql);

				$this->_dispatchRegisterSuccess($customer);
				$this->_successProcessRegistration($customer);
				//$this->_redirectReferer($defaultUrl=null);
				return;
			} else {
				$this->_addSessionError($errors);
			}
		}catch (Mage_Core_Exception $e) {
			$session->setCustomerFormData($this->getRequest()->getPost());
			if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
				$url = $this->_getUrl('customer/account/forgotpassword');
				$message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
				$session->setEscapeMessages(false);
			} else {
				$message = $e->getMessage();
			}
			$session->addError($message);
		} catch (Exception $e) {
			
			$session->setCustomerFormData($this->getRequest()->getPost())
			->addException($e, $this->__('Cannot save the customer.'));
		}
		$errUrl = $this->_getUrl('customer/*/create', array('_secure' => true));
		$this->_redirectError(Mage::getUrl('customer/account/'));
	}
	
	protected function _getErrorsOnCustomerAddress($customer)
	{
		$errors = array();
		/* @var $address Mage_Customer_Model_Address */
		$address = Mage::getModel('customer/address');
		
		
		
		
		/* @var $addressForm Mage_Customer_Model_Form */
		$addressData = $addressForm->extractData($this->getRequest(), 'address', false);
		$addressErrors = $addressForm->validateData($addressData);
		if (is_array($addressErrors)) {
			$errors = array_merge($errors, $addressErrors);
		}
		$address->setId(null)
		->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
		->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false));
		$addressForm->compactData($addressData);
		$customer->addAddress($address);
	
		$addressErrors = $address->validate();
		if (is_array($addressErrors)) {
			$errors = array_merge($errors, $addressErrors);
		}
		return $errors;
	}
	protected function _successProcessRegistration(Mage_Customer_Model_Customer $customer)
	{
		$session = $this->_getSession();
		if ($customer->isConfirmationRequired()) {
			/** @var $app Mage_Core_Model_App */
			$app = $this->_getApp();
			/** @var $store  Mage_Core_Model_Store*/
			$store = $app->getStore();
			$customer->sendNewAccountEmail(
					'confirmation',
					$session->getBeforeAuthUrl(),
					$store->getId()
			);
			$customerHelper = $this->_getHelper('customer');
			$session->addSuccess($this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.',
					$customerHelper->getEmailConfirmationUrl($customer->getEmail())));
			$url = $this->_getUrl('*/*/index', array('_secure' => true));
		} else {
			$session->setCustomerAsLoggedIn($customer);
			$session->renewSession();
			$url = $this->_welcomeCustomer($customer);
		}
		$this->_redirectSuccess(Mage::getUrl('customer/account/'));
		return $this;
	}
}
?>
<?php
require_once 'Mage/Customer/controllers/AccountController.php';
class GoMage_Social_AjaxifiedController extends Mage_Customer_AccountController
{
    
    public function preDispatch()
    {
        Mage_Core_Controller_Front_Action::preDispatch();
        if (!$this->getRequest()->isDispatched()) {
            return;
        }

        $action = $this->getRequest()->getActionName();
        $pattern = '/^(validatEmail|create|login|createpost|loginpost|logoutSuccess|forgotpassword|forgotpasswordpost|confirm|confirmation|pass|wait|join)/i';
        if (!preg_match($pattern, $action)) {
            if (!$this->_getSession()->authenticate($this)) {
                $this->setFlag('', 'no-dispatch', true);
            }
        } else {
            $this->_getSession()->setNoReferer(true);
        }
        
    }
    
   
    
    public function loginAccountAction()
    {
    
       if ($this->_getSession()->isLoggedIn()) {
            $this->_redirectUrl(Mage::getUrl());
            return;
        }
         $errors = array();
        $session = $this->_getSession();
        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
          
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                     Mage::getModel('customer/customer')->setRememberCookie($login);
                     $session->login($login['username'], $login['password']);
                    if ($session->getCustomer()->getIsJustConfirmed()) {
                        $this->_welcomeCustomer($session->getCustomer(), true);
                        if(strtolower($session->getCustomer()->getData('firstname'))=="first name"&&strtolower($session->getCustomer()->getData('lastname'))=="last name"){
                        $session->getCustomer()->setData('firstname',$login['firstname']);
                        $session->getCustomer()->setData('lastname',$login['lastname']);
                         $session->getCustomer()->save();
                        }
                        $result['success'] = true;
                        $this->getResponse()->setBody(Zend_Json::encode($result));
                    }else{
                        if(strtolower($session->getCustomer()->getData('firstname'))=="first name"&&strtolower($session->getCustomer()->getData('lastname'))=="last name"){
                        $session->getCustomer()->setData('firstname',$login['firstname']);
                        $session->getCustomer()->setData('lastname',$login['lastname']);
                         $session->getCustomer()->save();
                        }
                        $result['success'] = true;
                        $this->getResponse()->setBody(Zend_Json::encode($result));
                    }
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $value = Mage::helper('customer')->getEmailConfirmationUrl($login['username']);
                            $errors[] = Mage::helper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                           // exit(1);
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            // $errors[] = $e->getMessage();
                            $errors[] = 'Invalid Email or Password';
                           // exit(1);
                            break;
                        default:
                            $errors[] = $e->getMessage();
                            //exit(1);
                    }
                    $session->setUsername($login['username']);
                }catch (Exception $e) {
                     $errors[] = $e->getMessage();
                     //exit(1);
               }
              if(!empty($errors)){
               
                 foreach ($errors as $message) {
                     $errorMessage .= $message."<br/>";   
                 }
                 
                 $result['success'] = false;
                  $result['message'] = $errorMessage;
                $this->getResponse()->setBody(Zend_Json::encode($result));
              }
               
            }else {
               $message = $this->__('Login and password are required.');
               $result['success'] = false;
               $result['message'] = $message;
                $this->getResponse()->setBody(Zend_Json::encode($result));
            }
        }else{
            $url = Mage::getBaseUrl();
            Mage::app()->getFrontController()->getResponse()->setRedirect($url);
        }
        
    }
    public function createAccountAction()
    {
     
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
           $this->_redirectUrl(Mage::getUrl());
            return;
        }
        $session->setEscapeMessages(true); // prevent XSS injection in user input
        if ($this->getRequest()->isPost()) {
            $errors = array();

            if (!$customer = Mage::registry('current_customer')) {
                $customer = Mage::getModel('customer/customer')->setId(null);
            }

            /* @var $customerForm Mage_Customer_Model_Form */
            $customerForm = Mage::getModel('customer/form');
            $customerForm->setFormCode('customer_account_create')
                ->setEntity($customer);

            $customerData = $customerForm->extractData($this->getRequest());

           /* if ($this->getRequest()->getParam('is_subscribed', false)) {
                $customer->setIsSubscribed(1);
            }*/
           
           //auto subscribed to news letter
            $customer->setIsSubscribed(1);
            /**
             * Initialize customer group id
             */
            $customer->getGroupId();

            if ($this->getRequest()->getPost('create_address')) {
                /* @var $address Mage_Customer_Model_Address */
                $address = Mage::getModel('customer/address');
                /* @var $addressForm Mage_Customer_Model_Form */
                $addressForm = Mage::getModel('customer/form');
                $addressForm->setFormCode('customer_register_address')
                    ->setEntity($address);

                $addressData    = $addressForm->extractData($this->getRequest(), 'address', false);
                $addressErrors  = $addressForm->validateData($addressData);
                if ($addressErrors === true) {
                    $address->setId(null)
                        ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
                        ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false));
                    $addressForm->compactData($addressData);
                    $customer->addAddress($address);

                    $addressErrors = $address->validate();
                    if (is_array($addressErrors)) {
                        $errors = array_merge($errors, $addressErrors);
                    }
                } else {
                    $errors = array_merge($errors, $addressErrors);
                }
            }

            try {
                $customerErrors = $customerForm->validateData($customerData);
                if ($customerErrors !== true) {
                    $errors = array_merge($customerErrors, $errors);
                } else {
                    $customerForm->compactData($customerData);
                    $customer->setPassword($this->getRequest()->getPost('password'));
                    $customer->setConfirmation($this->getRequest()->getPost('confirmation'));
                    $customerErrors = $customer->validate();
                    if (is_array($customerErrors)) {
                        $errors = array_merge($customerErrors, $errors);
                    }
                }

                $validationResult = count($errors) == 0;
                $arrErrors = array(); 
                if (true === $validationResult) {
                    $customer->save();

                    if ($customer->isConfirmationRequired()) {
                        $customer->sendNewAccountEmail('confirmation', $session->getBeforeAuthUrl());
                      //  $session->addSuccess($this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.', Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail())));
                        $result['success'] = true;
                        $this->getResponse()->setBody(Zend_Json::encode($result));
                        exit;
                     } else {
                        $session->setCustomerAsLoggedIn($customer);
                        $url = $this->_welcomeCustomer($customer);
                        $result['success'] = true;
                        $this->getResponse()->setBody(Zend_Json::encode($result));
                    }
                } else {
                    $session->setCustomerFormData($this->getRequest()->getPost());
                    if (is_array($errors)) {
                        foreach ($errors as $errorMessage) {
                            $arrErrors[] =$errorMessage;
                        }
                    } else {
                        $arrErrors[] = $this->__('Invalid customer data');
                       }
                }
            } catch (Mage_Core_Exception $e) {
                $message = '';
                $session->setCustomerFormData($this->getRequest()->getPost());
                if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                    $url = Mage::getUrl('customer/account/forgotpassword');
                    $message = $this->__(' An account with this email already exists. Please click "login" below');
                    $session->setEscapeMessages(false);
                } else {
                        $arrErrors[] = $e->getMessage();
                }
                $arrErrors[] = $message;
             } catch (Exception $e) {
                $arrErrors[] = $this->__('Cannot save the customer.');
               
            }
            if(!empty($arrErrors))
            {
              
              foreach ($arrErrors as $message) {
                 $errorMessage .= $message."<br/>";   
              }
             
              $result['success'] = false;
              $result['message'] = $errorMessage;
              $this->getResponse()->setBody(Zend_Json::encode($result));
            }
        }
     }
     public function forgotPasswordAction() {
        $email = $this->getRequest()->getPost('email');
        $errors = array();
        $status = false;

        if ($email) {
            if (!Zend_Validate::is($email, 'EmailAddress')) {
                $this->_getSession()->setForgottenEmail($email);
               // $this->_getSession()->addError($this->__('Invalid email address.'));
                $errors[] = $this->__('Invalid email address.');
            }
            else
            {
            $customer = Mage::getModel('customer/customer')
                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                ->loadByEmail($email);
               
            if ($customer->getId()) {
                try {
                    $newPassword = $customer->generatePassword();
                    $customer->changePassword($newPassword, false);
                    $customer->sendPasswordReminderEmail();
                   // $this->_getSession()->addSuccess($this->__('A new password has been sent.'));

                    $status = true;
                    $result['success'] = true;
                    $result['message'] =$this->__('A new password has been sent.');
                    $this->getResponse()->setBody(Zend_Json::encode($result));
                }
                catch (Exception $e){
                   // $this->_getSession()->addError($e->getMessage());
                    $errors[] = $e->getMessage();
                }
            } else {
                //$this->_getSession()->addError($this->__('Email not associated with our database.'));
                $this->_getSession()->setForgottenEmail($email);

                $errors[] = $this->__('Email not associated with our database.');
            }
            }
        } else {
           // $this->_getSession()->addError($this->__('Please enter your email.'));

            $errors[] = $this->__('Please enter your email.');
        }
        if(!empty($errors)){
           
            foreach ($errors as $message) {
                $errorMessage .= $message . '<br/>';
            }
         
            $result['success'] = false;
            $result['message'] = $errorMessage;
            $this->getResponse()->setBody(Zend_Json::encode($result));
        }
        //$this->renderAjax($status, $errorMessage);
    }
    public function validatEmailAction()
    {
        $customer_email=$this->getRequest()->getParam('mail');
        $customer = Mage::getModel("customer/customer");
        $customer->loadByEmail($customer_email);
        
        if ($customer->getId()) {
            $firstname = $customer->getFirstName();
            $lastname = $customer->getLastName();
            $cookieValue = $mail . ",,,Y,$firstname,$lastname";
            echo $cookieValue;
        } else {
            $cookieValue['response'] = "N";
            $cookieValue = implode(",", $cookieValue);
            echo $cookieValue;
        }
        exit;
    }
    
}


?>
<?php
require_once "Mage/Contacts/controllers/IndexController.php";
 
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Contacts
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Contacts index controller
 *
 * @category   Mage
 * @package    Mage_Contacts
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Aurigait_CommonIndidelights_IndexController extends Mage_Core_Controller_Front_Action
{

    const XML_PATH_EMAIL_RECIPIENT  = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'contacts/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'contacts/email/email_template';
    const XML_PATH_ENABLED          = 'contacts/contacts/enabled';

    public function preDispatch()
    {
        parent::preDispatch();

        if( !Mage::getStoreConfigFlag(self::XML_PATH_ENABLED) ) {
            $this->norouteAction();
        }
    }

    public function indexAction()
    { 
        $this->loadLayout();
        $this->getLayout()->getBlock('contactForm')
            ->setFormAction( Mage::getUrl('*/*/post') );

        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }

    public function postAction()
    {
        $post = $this->getRequest()->getPost();
        if ( $post ) {
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $postObject = new Varien_Object();
                $postObject->setData($post);

                $error = false;

                if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['subject']) , 'NotEmpty')) {
                    $error = true;
                }
                
                if (!Zend_Validate::is(trim($post['comment']) , 'NotEmpty')) {
                	$error = true;
                }

                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }

                if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                    $error = true;
                }

                if ($error) {
                    throw new Exception();
                }
                 
                $tomailid =  Mage::getStoreConfig('contactcustomsec/gcontactcustomsec/'.$post['subject']);
                if(!$tomailid)
                	$tomailid = Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT);
                
               
            //    $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                
            
                $fileName = '';
                if (isset($_FILES['uploadfile']['name']) && $_FILES['uploadfile']['name'] != '') {
                	try {
                		$fileName       = $_FILES['uploadfile']['name'];
                		$fileExt        = strtolower(substr(strrchr($fileName, ".") ,1));
                		$fileNamewoe    = rtrim($fileName, $fileExt);
                		$fileName       = preg_replace('/\s+', '', $fileNamewoe) . time() . '.' . $fileExt;
                
                		$uploader       = new Varien_File_Uploader('uploadfile');
                		//$uploader->setAllowedExtensions(array('doc', 'docx','pdf'));
                		$uploader->setAllowRenameFiles(false);
                		$uploader->setFilesDispersion(false);
                		$path = Mage::getBaseDir('media') . DS . 'contacts';
                		if(!is_dir($path)){
                			mkdir($path, 0777, true);
                		}
                		$uploader->save($path . DS, $fileName );
                
                	} catch (Exception $e) {
                		$error = true;
                	}
                }
                /**************************************************************/
                
                if ($error) {
                	throw new Exception();
                }
                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                
                /**************************************************************/
                //sending file as attachment
                $attachmentFilePath = Mage::getBaseDir('media'). DS . 'contacts' . DS . $fileName;
                if(file_exists($attachmentFilePath)){
                	$fileContents = file_get_contents($attachmentFilePath);
                	$attachment   = $mailTemplate->getMail()->createAttachment($fileContents);
                	$attachment->filename = $fileName;
                }
                
                   $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                 ->setReplyTo($post['email'])
                ->sendTransactional(
                		Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                		Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                		$tomailid,
                		null,
                		array('data' => $postObject)
                );
                
       //         $mailTemplate->getMail()->createAttachment($string,'text/UTF-8')->filename =  ;

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }

                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacts')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later'));
                $this->_redirect('*/*/');
                return;
            }

        } else {
            $this->_redirect('*/*/');
        }
    }

}

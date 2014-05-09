<?php
class Aurigait_Voucher_Model_Sendfriend_Sendfriend extends Mage_Sendfriend_Model_Sendfriend
{
	public function send()
	{
		if ($this->isExceedLimit()){
			Mage::throwException(Mage::helper('sendfriend')->__('You have exceeded limit of %d sends in an hour', $this->getMaxSendsToFriend()));
		}
	
		/* @var $translate Mage_Core_Model_Translate */
		$translate = Mage::getSingleton('core/translate');
		$translate->setTranslateInline(false);
	
		/* @var $mailTemplate Mage_Core_Model_Email_Template */
		$mailTemplate = Mage::getModel('core/email_template');
	
		$message = nl2br(htmlspecialchars($this->getSender()->getMessage()));
		$sender  = array(
				'name'  => $this->_getHelper()->escapeHtml($this->getSender()->getName()),
				'email' => $this->_getHelper()->escapeHtml($this->getSender()->getEmail())
		);
	
		$mailTemplate->setDesignConfig(array(
				'area'  => 'frontend',
				'store' => Mage::app()->getStore()->getId()
		));
	
		foreach ($this->getRecipients()->getEmails() as $k => $email) {
			
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			Mage::getModel('voucher/invitefriend')->savecustom($customer->getId(), $sender['email'],$email,date('Y-m-d'));
			
			$name = $this->getRecipients()->getNames($k);
			/*$mailTemplate->sendTransactional(
					$this->getTemplate(),
					$sender,
					$email,
					$name,
					array(
							'name'          => $name,
							'email'         => $email,
						//	'product_name'  => $this->getProduct()->getName(),
						//	'product_url'   => $this->getProduct()->getUrlInStore(),
							'message'       => $message,
							'sender_name'   => $sender['name'],
							'sender_email'  => $sender['email'],
						//	'product_image' => Mage::helper('catalog/image')->init($this->getProduct(),	'small_image')->resize(75),
					)
			); */
		}
	
		$translate->setTranslateInline(true);
		$this->_incrementSentCount();
	
		return $this;
	}
}
		
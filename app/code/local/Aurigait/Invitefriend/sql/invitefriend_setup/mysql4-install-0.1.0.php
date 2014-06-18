<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT

CREATE TABLE IF NOT EXISTS `voucher_referfriendlist` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `sender_emailid` varchar(30) NOT NULL,
  `receiver_emailid` varchar(30) NOT NULL,
  `senddate` date NOT NULL,
  `register_status` int(2) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `voucher_referfriendlist` ADD `senddatetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;

ALTER TABLE `voucher_referfriendlist` ADD `voucher_code` VARCHAR( 20 ) NOT NULL AFTER `register_status`; 
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 
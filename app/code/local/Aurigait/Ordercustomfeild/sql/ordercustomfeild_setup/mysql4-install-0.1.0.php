<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
CREATE TABLE IF NOT EXISTS `aurigait_ordercustome` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(30) NOT NULL,
  `item_id` int(11) NOT NULL,
  `delivery_date` date NOT NULL,
  `qty` int(10) NOT NULL,
  `tracking_number` varchar(30) NOT NULL,
  `trackby` varchar(30) NOT NULL,
  `status` int(11) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `aurigait_ordercustome` CHANGE `status` `status` INT( 11 ) NOT NULL DEFAULT '1';

SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 
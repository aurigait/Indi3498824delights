<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT

ALTER TABLE `salesrule` ADD `max_discount_amount` DOUBLE NULL AFTER `simple_action`
ALTER TABLE `salesrule` CHANGE `max_discount_amount` `max_discount_amount` DOUBLE NULL DEFAULT NULL COMMENT 'maximum amount of discount available on this coupon'


		
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 
<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT

ALTER TABLE `salesrule` ADD `max_discount_amount` DOUBLE NULL AFTER `simple_action`
ALTER TABLE `salesrule` CHANGE `max_discount_amount` `max_discount_amount` DOUBLE NULL DEFAULT NULL COMMENT 'maximum amount of discount available on this coupon'



CREATE TABLE IF NOT EXISTS `voucher_allcouponlist` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `voucher_code` varchar(40) NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `vc_fromdate` datetime NOT NULL,
  `vc_todate` datetime NOT NULL,
  `vc_activationperiod` date NOT NULL,
  `dateofcreation` date NOT NULL,
  `used` int(2) NOT NULL COMMENT '1:used, 2: not used',
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `voucher_allcouponlist` ADD `orderamount` DOUBLE NOT NULL AFTER `customer_id` ;

SQLTEXT;
$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 
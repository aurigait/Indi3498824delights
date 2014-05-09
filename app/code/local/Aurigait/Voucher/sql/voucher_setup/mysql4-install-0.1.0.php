<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT

ALTER TABLE `salesrule` ADD `max_discount_amount`  DOUBLE NULL COMMENT 'maximum amount of discount available on this coupon'AFTER `simple_action`;
 


ALTER TABLE `salesrule` ADD `iconimage` TEXT NOT NULL COMMENT 'icon image for coupon' AFTER `name`;
ALTER TABLE `salesrule` ADD `rule_type` INT( 4 ) NOT NULL COMMENT 'voucher type ' AFTER `iconimage` ;


ALTER TABLE `salesrule` ADD `threshold_amount` DOUBLE NOT NULL COMMENT 'Threshold amount depend on voucher type' AFTER `max_discount_amount` ,
ADD `purchase_days` INT( 11 ) NOT NULL COMMENT 'Total purchase days depend on voucher type' AFTER `threshold_amount` ;

ALTER TABLE `salesrule` ADD `email_template` INT( 11 ) NOT NULL AFTER `rule_type` ;



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
ALTER TABLE `voucher_allcouponlist` ADD `voucher_type` INT( 4 ) NOT NULL DEFAULT '1' COMMENT '3:user cumulative, 5: Invitation type 1, 6: invitation type 2' AFTER `dateofcreation` ;


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



SQLTEXT;
$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 
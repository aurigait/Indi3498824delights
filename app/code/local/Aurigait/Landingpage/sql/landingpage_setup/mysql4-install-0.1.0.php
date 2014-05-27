<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
update customer_eav_attribute set validate_rules = 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:0;}' where attribute_id = 7;
update eav_attribute set is_required = 0 where attribute_id = 7; 
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 
<?php
$installer = $this;
$installer->startSetup();
$installer->addAttribute("quote_item", "user_remark", array("type"=>"varchar"));
$installer->addAttribute("order_item", "user_remark", array("type"=>"varchar"));
$installer->endSetup();
	 
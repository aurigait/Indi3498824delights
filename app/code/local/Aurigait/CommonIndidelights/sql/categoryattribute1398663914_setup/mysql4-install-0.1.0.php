<?php
$installer = $this;
$installer->startSetup();

$installer->addAttribute('catalog_product', 'product_occasion', array(
		
		  'input'             => 'multiselect',
		  'backend'           => 'eav/entity_attribute_backend_array',
		  "frontend" => "",
		  "label"    => "Product Occasions (tiles)",
		  "class"    => "",
		  "source"   => "commonindidelights/eav_entity_attribute_source_options",
		  "global"   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
		  "visible"  => true,
		  "required" => false,
		  "is_user_defined"  => "1",
		  "default" => "0",
		  "searchable" => false,
		  "filterable" => false,
		  "comparable" => false,
		  "used_for_sort_by" => true,
		  "visible_on_front"  => false,
		  "unique"     => false,
		  "system" => true,
		  "note"       => ""
));

$installer->addAttribute("catalog_category", "is_tile",  array(
    "type"     => "int",
    "backend"  => "",
    "frontend" => "",
    "label"    => "Is Tile",
    "input"    => "select",
    "class"    => "",
    "source"   => "eav/entity_attribute_source_boolean",
    "global"   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    "visible"  => true,
    "required" => false,
    "user_defined"  => false,
    "default" => "",
    "searchable" => false,
    "filterable" => false,
    "comparable" => false,
	
    "visible_on_front"  => false,
    "unique"     => false,
    "note"       => ""

	));
/*
$installer->addAttribute("catalog_category", "home_tile_img",  array(
    "type"     => "varchar",
    "backend"  => "catalog/category_attribute_backend_image",
    "frontend" => "",
    "label"    => "Home Page Tile Image",
    "input"    => "image",
    "class"    => "",
    "source"   => "",
    "global"   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    "visible"  => true,
    "required" => false,
    "user_defined"  => false,
    "default" => "",
    "searchable" => false,
    "filterable" => false,
    "comparable" => false,
	
    "visible_on_front"  => false,
    "unique"     => false,
    "note"       => ""

	));
*/
$installer->addAttribute("catalog_category", "tile_page_image",  array(
    "type"     => "varchar",
    "backend"  => "catalog/category_attribute_backend_image",
    "frontend" => "",
    "label"    => "Tile Page Image",
    "input"    => "image",
    "class"    => "",
    "source"   => "",
    "global"   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    "visible"  => true,
    "required" => false,
    "user_defined"  => false,
    "default" => "",
    "searchable" => false,
    "filterable" => false,
    "comparable" => false,
	
    "visible_on_front"  => false,
    "unique"     => false,
    "note"       => ""

	));
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->addAttribute('customer', 'region', array(
		'label'		=> 'region',
		'type'		=> 'varchar',
		'input'		=> 'text',
		'visible'	=> true,
		'required'	=> false,
		'position'	=> 1,
));
$setup->addAttribute('customer', 'country', array(
		'label'		=> 'country',
		'type'		=> 'varchar',
		'input'		=> 'text',
		'visible'	=> true,
		'required'	=> false,
		'position'	=> 1,
));

$installer->endSetup();


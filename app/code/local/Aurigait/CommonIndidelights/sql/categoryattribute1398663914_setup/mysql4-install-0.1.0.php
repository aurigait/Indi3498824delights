<?php
$installer = $this;
$installer->startSetup();

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

$installer->endSetup();
	 
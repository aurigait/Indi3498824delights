<?php
/*------------------------------------------------------------------------
# $JA#PRODUCT_NAME$ - Version $JA#VERSION$ - Licence Owner $JA#OWNER$
# ------------------------------------------------------------------------
# Copyright (C) 2004-2009 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: J.O.O.M Solutions Co., Ltd
# Websites: http://www.joomlart.com - http://www.joomlancers.com
# This file may not be redistributed in whole or significant part.
-------------------------------------------------------------------------*/ 


class JoomlArt_JmProductsSlider_Model_System_Config_Backend_JmProductsSlider_checkMode extends Mage_Core_Model_Config_Data
{

    protected function _beforeSave(){
    	$groups = $this->getData('groups');
    	$datas = $groups['joomlart_jmproductsslider'];
    	if($datas['fields']['mode']['value']=='category' && $datas['fields']['catsid']['value']==''){
    		throw new Exception(Mage::helper('joomlart_jmproductsslider')->__('Please enter list of Categories ID.'));
    	}
       	elseif($datas['fields']['mode']['value']=='category' && $datas['fields']['leading_product']['value']=='' && $datas['fields']['intro_product']['value']=='' ){
    		throw new Exception(Mage::helper('joomlart_jmproductsslider')->__('Please enter Leading or Intro number.'));
    	}
        return $this;
    }

}

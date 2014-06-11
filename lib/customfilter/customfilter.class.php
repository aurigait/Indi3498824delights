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

    class Customfilter 
    {
		var $url = null;
		public function genListCats(){
			$currentcate= Mage::getModel('catalog/layer')->getCurrentCategory();
			$this->url = Mage::helper('core/url')->getCurrentUrl();
			
			$currentselecedcat =  Mage::app()->getRequest()->getParams('');
			$this->currentselecedcat = $currentselecedcat['cat'];
			$this->selectedone =false;
			$child_cates= $currentcate->getChildrenCategories();
			$html='<ul>';
			foreach ($child_cates as $cate){
				
				$cate=$cate->load($cate->getId());
				$subhtml=$this->genSubCats($cate);
				if ($cate->hasProduct() || $subhtml){
					
					$style="";
					if($this->currentselecedcat==$cate->getId() && !$this->selectedone )
					{
						$style="font-weight: bold;";
						$this->selectedone = true;
					}
					$html.='<li class="filter-cat">';
					$html.='<a href="'.Mage::getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true, '_query'=>array('cat'=>$cate->getId()))).'"  style="'.$style.'">'.$cate->getName().'</a>';
					
					if($subhtml)
						$html.='<div class="filter-showsub"><span>+</span></div>';

					$html.='<ul class="filter-subcat">';
					$html.= $subhtml;
					$html.='</ul>';
					$html.='</li>';
				}
				
			}
			$html.='</ul>';
			return $html;
		}
		
		public function genSubCats($cate){
			
			$subcates= $cate->getChildrenCategories();
			$html='';
			foreach ($subcates as $subcate){
				if ($subcate->getIsActive() && $subcate->getProductCount()){
				
					$html.='<li class="filter-cat">';
					$style="";
					if($this->currentselecedcat==$subcate->getId() && ! $this->selectedone )
					{
						$style="font-weight: bold;";
						$this->selectedone = true;
					}
					$html.='<a href="'.Mage::getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true, '_query'=>array('cat'=>$subcate->getId()))).'" style="'.$style.'">'.$subcate->getName().'</a>';
					
					if ($subcate->getData('children_count')){
						$subhtml.= $this->genSubCats($subcate);
						if($subhtml)
							$html.='<div class="filter-showsub"><span>+</span></div>';
						$html.='<ul class="filter-subcat">';
						$html.= $subhtml;
						$html.='</ul>';
					}
					$html.='</li>';
				}
			}
			unset ($subcates);
			return $html;
		}
    }
?>

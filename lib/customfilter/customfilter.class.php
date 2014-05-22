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
			$this->url = $currentcate->getUrl();
			
			$currentselecedcat =  Mage::app()->getRequest()->getParams('');
			$this->currentselecedcat = $currentselecedcat['cat'];
			$this->selectedone =false;
			$child_cates= $currentcate->getChildrenCategories();
			$html='<ol>';
			foreach ($child_cates as $cate){
				
				if ($cate->getData('children_count')){
					
					$style="";
					if($this->currentselecedcat==$cate->getId() && !$this->selectedone )
					{
						$style="font-weight: bold;";
						$this->selectedone = true;
					}
					$html.='<li class="filter-cat">';
					$html.='<a href="'.$this->url.'?cat='.$cate->getId().'"  style="'.$style.'">'.$cate->getName().'</a>';
					$html.='<div class="filter-showsub"><span>+</span></div>';
					$html.='<div class="filter-subcat" style="height: 0px;overflow: hidden;">';
					$html.= $this->genSubCats($cate);
					$html.='</div>';
					$html.='</li>';
				}
				
			}
			$html.='</ol>';
			return $html;
		}
		
		public function genSubCats($cate){
			
			
			$subcates= $cate->getChildrenCategories();
			$html='';
			foreach ($subcates as $subcate){
				if ($subcate->getIsActive()){
					$html.='<span>';
					$style="";
					if($this->currentselecedcat==$subcate->getId() && ! $this->selectedone )
					{
						$style="font-weight: bold;";
						$this->selectedone = true;
					}
					$html.='<a href="'.$this->url.'?cat='.$subcate->getId().'" style="'.$style.'">'.$subcate->getName().'</a>';
					$html.='</span>';
					if ($cate->getData('children_count')){
						$html.='<div class="filter-subcat-subcat-prodcut">';
						$html.= $this->genSubCats($subcate);
						$html.='</div>';
					}
				}
			}
			unset ($subcates);
			return $html;
		}
	
    }

?>
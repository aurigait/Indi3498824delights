<?php

class Aurigait_Ordercustomfeild_Block_Adminhtml_Itemlist_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("itemlistGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
			//	$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
			$orderid = Mage::app()->getRequest()->getParam('order_id');
			
				$collection = Mage::getResourceModel("sales/order_item_collection")
				->addAttributeToFilter('order_id'  ,$orderid)
				;
				 
				$this->setCollection($collection);
				
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
		 $this->addColumn('name', array(
            'header'=> Mage::helper('sales')->__('Name'),
            'width' => '120px',
            'type'  => 'text',
            'index' => 'name',
        )); 
		 
		 $this->addColumn('sku', array(
		 		'header'=> Mage::helper('sales')->__('Sku'),
		 		'width' => '50px',
		 		'type'  => 'text',
		 		'index' => 'sku',
		 ));
		 
		 $this->addColumn('qty_ordered', array(
		 		'header'=> Mage::helper('sales')->__('Qty Ordered'),
		 		 
		 		'type'  => 'number',
		 		'index' => 'qty_ordered',
		 ));
		 
		 $this->addColumn('qty_received',array(
		 		'header' =>  Mage::helper("sales")->__('Qty Received'),
		 		 
		 		'sortable' => true,
		 		'renderer' => 'Aurigait_Ordercustomfeild_Block_Adminhtml_Itemlist_Renderer_Qtyreceived',
		 		'filter' => 'Aurigait_Ordercustomfeild_Block_Adminhtml_Itemlist_Renderer_Qtyreceived',
		 		'width' => '20px',
		 
		 ));
		 
		 $this->addColumn('Custom Fields',array(
		 		'header' =>  Mage::helper("sales")->__('Custom Fields'),
		 	 
		 		'sortable' => false,
		 	    'renderer' => 'Aurigait_Ordercustomfeild_Block_Adminhtml_Itemlist_Renderer_Inputfeild',
		 		'filter' => 'Aurigait_Ordercustomfeild_Block_Adminhtml_Itemlist_Renderer_Inputfeild',
		 		'width' => '500px',
		 			
		 ));
		 

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return '#';
		}

		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('id');
			$this->getMassactionBlock()->setUseSelectAll(true);
		 
			$this->getMassactionBlock()->addItem('save_all', array(
					'label'=> Mage::helper('ordercustomfeild')->__('Save all Data'),
					'url'  => $this->getUrl('*/adminhtml_ordercustomfei	ld/massUpdate'),
					 
			));
			return $this;
		}
		

}

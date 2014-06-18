<?php

class Aurigait_Voucher_Block_Adminhtml_Invitivoucherreport_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("invitivoucherreportGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("voucher/invitivoucherreport")->getCollection();
				
				$collection->getSelect()
				->joinLeft(array('d' => 'salesrule_coupon'), 'main_table.voucher_code = d.code ', array(
			   		'coupon_id'       => 'd.rule_id',
			   ))
			   ->joinLeft(array('f' => 'salesrule'), 'f.rule_id = d.rule_id ', array(
			   		 
			   		'vc_from_date'   => 'f.from_date',
			   		'vc_to_date'    => 'f.to_date',
			   		'discount_amount' => 'f.discount_amount'
			   ))
				 ->joinLeft(array('g' => 'sales_flat_order'), 'main_table.sender_id	 = g.customer_id and g.coupon_code = main_table.voucher_code ', array(
			   		 
			   		'use_satus'   => '(if(g.increment_id is NULL,"Unused","Used") )',
			   		 
			   ));
				
			//	echo $collection->getSelect();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
					$this->addColumn("sender_id", array(
				"header" => Mage::helper("voucher")->__("Sender User ID"),
				"align" =>"left",
				"width" => "50px",
			  //  "type" => "number",
				"index" => "sender_id",
				));
                
				$this->addColumn("sender_emailid", array(
						"header" => Mage::helper("voucher")->__("Sender Email ID"),
						"align" =>"left",
						"width" => "50px",
						"index" => "sender_emailid",
				));
		 	
				$this->addColumn("receiver_emailid", array(
						"header" => Mage::helper("voucher")->__("Receiver Email ID"),
						"align" =>"left",
						"width" => "50px",
						"index" => "receiver_emailid",
				));
				 
				$this->addColumn('vc_from_date',array(
						'header' =>  Mage::helper("voucher")->__('Voucher Creation Date'),
						'type' => 'datetime',
						'sortable' => true,
						"index" => "vc_from_date",
						'width' => 190,
							
				));
				$this->addColumn('vc_to_date',array(
						'header' =>  Mage::helper("voucher")->__('Voucher Expiry Date'),
						'type' => 'datetime',
						'sortable' => true,
						"index" => "vc_to_date",
						'width' => 190,
							
				));
				$this->addColumn('Voucher Amount',array(
						'header' =>  Mage::helper("voucher")->__('Voucher Amount'),
						'type' => 'number',
						'sortable' => true,
						'renderer' => 'Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_VoucheramountDisplay',
						'filter' => 'Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_VoucheramountDisplay',
						'width' => 100,
							
				));
				$this->addColumn('discount_amount',array(
						'header' =>  Mage::helper("voucher")->__('Voucher Amount'),
						'type' => 'number',
						'sortable' => true,
						"index" => "discount_amount",
						'width' => 100,
							
				));
				$this->addColumn('use_satus',array(
						'header' =>  Mage::helper("voucher")->__('Status'),
		 
						'sortable' => true,
						"index" => "use_satus",
						'width' => 100,
							
				));
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_invitivoucherreport', array(
					 'label'=> Mage::helper('voucher')->__('Remove Invitivoucherreport'),
					 'url'  => $this->getUrl('*/adminhtml_invitivoucherreport/massRemove'),
					 'confirm' => Mage::helper('voucher')->__('Are you sure?')
				));
			return $this;
		}
			

}
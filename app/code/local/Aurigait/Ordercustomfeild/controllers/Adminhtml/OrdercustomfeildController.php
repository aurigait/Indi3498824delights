<?php

class Aurigait_Ordercustomfeild_Adminhtml_OrdercustomfeildController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("ordercustomfeild/ordercustomfeild")->_addBreadcrumb(Mage::helper("adminhtml")->__("Ordercustomfeild  Manager"),Mage::helper("adminhtml")->__("Ordercustomfeild Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Ordercustomfeild"));
			    $this->_title($this->__("Manager Ordercustomfeild"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Ordercustomfeild"));
				$this->_title($this->__("Ordercustomfeild"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("ordercustomfeild/ordercustomfeild")->load($id);
				if ($model->getId()) {
					Mage::register("ordercustomfeild_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("ordercustomfeild/ordercustomfeild");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Ordercustomfeild Manager"), Mage::helper("adminhtml")->__("Ordercustomfeild Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Ordercustomfeild Description"), Mage::helper("adminhtml")->__("Ordercustomfeild Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("ordercustomfeild/adminhtml_ordercustomfeild_edit"))->_addLeft($this->getLayout()->createBlock("ordercustomfeild/adminhtml_ordercustomfeild_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("ordercustomfeild")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Ordercustomfeild"));
		$this->_title($this->__("Ordercustomfeild"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("ordercustomfeild/ordercustomfeild")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("ordercustomfeild_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("ordercustomfeild/ordercustomfeild");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Ordercustomfeild Manager"), Mage::helper("adminhtml")->__("Ordercustomfeild Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Ordercustomfeild Description"), Mage::helper("adminhtml")->__("Ordercustomfeild Description"));


		$this->_addContent($this->getLayout()->createBlock("ordercustomfeild/adminhtml_ordercustomfeild_edit"))->_addLeft($this->getLayout()->createBlock("ordercustomfeild/adminhtml_ordercustomfeild_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						

						$model = Mage::getModel("ordercustomfeild/ordercustomfeild")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Ordercustomfeild was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setOrdercustomfeildData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setOrdercustomfeildData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("ordercustomfeild/ordercustomfeild");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}
		
		public function itemlistAction()
		{  
			$this->loadLayout()->_setActiveMenu("ordercustomfeild/ordercustomfeild")->_addBreadcrumb(Mage::helper("adminhtml")->__("Ordercustomfeild Item Manager"),Mage::helper("adminhtml")->__("Ordercustomfeild Item Manager"));
		 	$this->renderLayout();
		}
		
		// save all data
		public function massUpdateAction()
		{
			try {
				
				$orderid = Mage::app()->getRequest()->getPost('orderid');
				
				$ids = $this->getRequest()->getPost('id', array());
				$rowvals = $this->getRequest()->getPost('rowvals', array());
			 
				
				 
				$rowvalsarr = explode(',', $rowvals);
				
				foreach($rowvalsarr as $row)
				{
					$rowvalarr  = explode('###', $row);
					$idval  = $rowvalarr[0];
					$idqty  = $rowvalarr[1];
					 
					$row1valarr = explode('|', $idqty);
					Mage::getModel("ordercustomfeild/ordercustomfeild")->deletealldata($orderid,$idval);
					
					foreach($row1valarr as $row2)
					{
						$row2 = explode('::', $row2);
						
						
						$post_data = array(
								'order_id' => $orderid,
								'item_id' => $idval,
								'delivery_date' => $row2[1],
								'qty' => $row2[0],
								'tracking_number' => $row2[3],
								'trackby' => $row2[2],
								'status' => 1,
								'create_at' => date('Y-m-d h:i:s'),
						
						);
						 
						Mage::getModel("ordercustomfeild/ordercustomfeild")->savedata($post_data);
					}
						 
					
				}
			 
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully Updated"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/itemlist/order_id/'.$orderid);
		}
		
}

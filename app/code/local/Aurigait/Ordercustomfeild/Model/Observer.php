<?php
class Aurigait_Ordercustomfeild_Model_Observer{
    
    public function core_block_abstract_prepare_layout_after_javascript_init($observer)
    {
            $block = $observer->getEvent()->getBlock();
          
         // echo $block->getId().'##';
            if($block->getId() == 'itemlistGrid'){        
                 if ($block->getLayout()->getBlock('head')) {
                        $block->getLayout()->getBlock('head')->addJs('ordercustomfeild/adminhtml_grid.js');
                    }
            }
           
    }
    public function core_block_abstract_prepare_layout_after($observer)
    {
       $block = $observer->getEvent()->getBlock();
        
       
        
        if (($block instanceof Mage_Adminhtml_Block_Widget_Grid || $block instanceof Enterprise_SalesArchive_Block_Adminhtml_Sales_Order_Grid) && $block->getId() == 'aurigait_massaction_grid') {
            $block->addColumn(
                            'weights',
                            array('header' => Mage::helper('customcarrier')->__('Weights/Units(Total Weight <br/>of products purchased)'),
                                'type' => 'text',
                                'sortable' => false,
                                'renderer' => 'Aurigait_CustomCarrier_Block_Adminhtml_Sales_Order_Grid_Renderer_Weights',
                                'filter' => 'Aurigait_CustomCarrier_Block_Adminhtml_Sales_Order_Grid_Renderer_Weights',
                                'width' => 190,
                                'after'=>'tracking-input')
                        );
            $block->addColumn(
                            'dimensions',
                            array('header' => Mage::helper('customcarrier')->__('Dimensions<br/>(length/width/height)/Units'),
                                'type' => 'text',
                                'sortable' => false,
                                'renderer' => 'Aurigait_CustomCarrier_Block_Adminhtml_Sales_Order_Grid_Renderer_Dimensions',
                                'filter' => 'Aurigait_CustomCarrier_Block_Adminhtml_Sales_Order_Grid_Renderer_Dimensions',
                                'width' => 190,
                                'after'=>'weights')
                        );
            $block->addColumn(
                            'printable',
                            array('header' => Mage::helper('customcarrier')->__('Shipping Label Printable'),
                                'type' => 'text',
                                'sortable' => false,
                                'renderer' => 'Aurigait_CustomCarrier_Block_Adminhtml_Sales_Order_Grid_Renderer_Printable',
                                'filter' => 'Aurigait_CustomCarrier_Block_Adminhtml_Sales_Order_Grid_Renderer_Printable',
                                'width' => 190,
                                'after'=>'dimensions')
                        );
            
        } 
        
    }
    public function toOptionArray()
    {
        //$actions[] = array('value' => '_invoice_notify', 'label' => Mage::helper('gridactions')->__('Invoice (notify Customer)'));
       // $actions[] = array('value' => '_invoice', 'label' => Mage::helper('gridactions')->__('Invoice (don\'t notify Customer)'));
        //$actions[] = array('value' => '_invoice_forcenotification', 'label' => Mage::helper('gridactions')->__('(Re-)send invoice email (notify Customer)'));
       // $actions[] = array('value' => '_invoice_notify_print', 'label' => Mage::helper('gridactions')->__('Invoice / Print (notify Customer)'));
       // $actions[] = array('value' => '_invoice_print', 'label' => Mage::helper('gridactions')->__('Invoice / Print (don\'t notify Customer)'));
      //  $actions[] = array('value' => '_capture', 'label' => Mage::helper('gridactions')->__('Capture Payment'));
          $actions[] = array('value' => '_printlabels', 'label' => Mage::helper('core')->__('Print Shipping Labels(With Custom Carrier)'));
          $actions[] = array('value' => '_create_ship', 'label' => Mage::helper('core')->__('Ship with custom Carrier'));
      //  $actions[] = array('value' => '_ship_forcenotification', 'label' => Mage::helper('gridactions')->__('(Re-)send shipment email (notify Customer)'));
     //   $actions[] = array('value' => '_ship_notify_print', 'label' => Mage::helper('gridactions')->__('Ship / Print (notify Customer)'));
      //  $actions[] = array('value' => '_ship_print', 'label' => Mage::helper('gridactions')->__('Ship / Print (don\'t notify Customer)'));
      //  $actions[] = array('value' => '_invoice_ship_notify', 'label' => Mage::helper('gridactions')->__('Invoice / Ship (notify Customer)'));
      //  $actions[] = array('value' => '_invoice_ship', 'label' => Mage::helper('gridactions')->__('Invoice / Ship (don\'t notify Customer)'));
       // $actions[] = array('value' => '_invoice_ship_notify_print', 'label' => Mage::helper('gridactions')->__('Invoice / Ship / Print (notify Customer)'));
      //  $actions[] = array('value' => '_invoice_ship_print', 'label' => Mage::helper('gridactions')->__('Invoice / Ship / Print (don\'t notify Customer)'));
      //  $actions[] = array('value' => '_invoice_ship_complete_notify', 'label' => Mage::helper('gridactions')->__('Invoice / Ship / Complete (notify Customer)'));
      //  $actions[] = array('value' => '_invoice_ship_complete', 'label' => Mage::helper('gridactions')->__('Invoice / Ship / Complete (don\'t notify Customer)'));
       // $actions[] = array('value' => '_invoice_ship_complete_notify_print', 'label' => Mage::helper('gridactions')->__('Invoice / Ship / Complete / Print (notify Customer)'));
      //  $actions[] = array('value' => '_invoice_ship_complete_print', 'label' => Mage::helper('gridactions')->__('Invoice / Ship / Complete / Print (don\'t notify Customer)'));
      //  $actions[] = array('value' => '_complete', 'label' => Mage::helper('gridactions')->__('Complete Order'));
        
        
        return $actions;
    }
}
?>

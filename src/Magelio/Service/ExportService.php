<?php
namespace Magelio\Service;
use \Mage as Mage;

class ExportService {

  public function __construct() {
    echo "[ok]";
  }

  public function exportWebsites() {
    $websitesIterator = $this->_endpoint->getWebsitesIterator();
  }

  public function export() {
    require_once($this->endpoint);
    spl_autoload_unregister(array(\Varien_Autoload::instance(), 'autoload'));

    $mageApp = \Mage::app();
    $mageApp->setCurrentStore(0);

    $entityType = \Mage::getModel('eav/config')->getEntityType('catalog_product');
    $entityTypeId = $entityType->getEntityTypeId();

    $attributesInfo = \Mage::getResourceModel('eav/entity_attribute_collection')
      ->setEntityTypeFilter($entityTypeId)
      ->addSetInfo();
    ;

    foreach($attributesInfo as $attributeInfo) {
      $attributeData = $attributeInfo->getData();
      print_r($attributeData);
    }
      
    
    exit;


    $websites = $mageApp->getWebsites();
    foreach($websites as $website) {
      echo "website: "; print_r($website->getData());
      foreach($website->getGroups() as $group) {
        echo "group: "; print_r($group->getData());
        $stores = $group->getStores();
        foreach($stores as $store) {
          echo "store: "; print_r($store->getData());
        }
      }
    }


    // $attributes = \Mage::getModel('

    //$model = \Mage::getModel('catalog/category');

    //$collection = $model->getCollection();
    //foreach($collection as $item) {
    //  $itemData = $item->load()->getData();
    //  print_r($itemData);
    //}

  }

}

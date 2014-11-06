<?php

namespace web\plugin\shop;

use \engine\object\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use Exception;
use ArrayObject;

class plugin extends basePlugin {

    // private $_states = array(
    //     'changed:category' => false,
    //     'changed:origin' => false,
    //     'changed:order' => false,
    //     'changed:product' => false,
    //     'changed:promo' => false,
    //     'changed:features' => false,
    //     'changed:agencies' => false,
    //     'changed:settings' => false
    // );

    // public function getName () {
    //     return 'shop';
    // }

    // public function beforeRun () {
    //     // $this->_getCachedTableStatuses();
    //     // sleep(5);
    //     $this->_getCachedTableData($this->getConfiguration()->data->Table_ShopCategories);
    //     $this->_getCachedTableData($this->getConfiguration()->data->Table_ShopOrigins);
    // }

    // // -----------------------------------------------
    // // -----------------------------------------------
    // // FEATURES
    // // -----------------------------------------------
    // // -----------------------------------------------


    // // -----------------------------------------------
    // // -----------------------------------------------
    // // SESSION DATA
    // // -----------------------------------------------
    // // -----------------------------------------------


    // // -----------------------------------------------
    // // -----------------------------------------------
    // // DATA CACHING UTILS
    // // -----------------------------------------------
    // // -----------------------------------------------
    // private function _getCachedTableData ($tableName) {
    //     $self = $this;
    //     $list = array();
    //     $refreshFromDB = false;
    //     $stateKey = false;
    //     $fn = false;
    //     $options = array(
    //         'limit' => 0
    //     );

    //     if ($tableName === $this->getConfiguration()->data->Table_ShopCategories) {
    //         $stateKey = 'category';
    //         $fn = function ($options) use ($self) {
    //             return $self->getCategories_List($options);
    //         };
    //     }
    //     if ($tableName === $this->getConfiguration()->data->Table_ShopOrigins) {
    //         $stateKey = 'origin';
    //         $fn = function ($options) use ($self) {
    //             return $self->getOrigins_List($options);
    //         };
    //     }
    //     if ($tableName === $this->getConfiguration()->data->Table_ShopFeatures) {
    //         $stateKey = 'features';
    //         // var_dump($this->_states);
    //         $fn = function ($options) use ($self) {
    //             return $self->getAllAvailableFeatures($options);
    //         };
    //     }
    //     if ($tableName === $this->getConfiguration()->data->Table_ShopDeliveryAgencies) {
    //         $stateKey = 'agencies';
    //         // var_dump($this->_states);
    //         $fn = function ($options) use ($self) {
    //             return $self->getDeliveries_List($options);
    //         };
    //     }

    //     if (!empty($tableName)) {
    //         $refreshFromDB = !isset($_SESSION[$tableName]) || $this->_getOrSetCachedState('changed:' . $stateKey);
    //         if ($refreshFromDB) {
    //             $list = $fn($options);
    //         } else {
    //             $list = $_SESSION[$tableName];
    //         }
    //         $_SESSION[$tableName] = $list;
    //         $this->_getOrSetCachedState('changed:' . $stateKey, false);
    //     }

    //     return $list;
    // }

    // private function _getCachedTableStatuses ($tableName = null, $force = false) {
    //     $statuses = array();

    //     $config = $this->getConfiguration()->data->jsapiUtil_GetTableStatusFieldOptions($tableName);
    //     $data = $this->getCustomer()->fetch($config);
    //     preg_match('#^enum\((.*?)\)$#ism', $data['Type'], $matches);
    //     $statuses = str_getcsv($matches[1], ",", "'");

    //     return $statuses;
    // }

    // private function _getOrSetCachedState ($key = null, $value = null) {
    //     if (!isset($_SESSION['shop:states'])) {
    //         $_SESSION['shop:states'] = $this->_states;
    //     }
    //     $_states = $_SESSION['shop:states'];
    //     if (is_null($key)) {
    //         return;
    //     } else {
    //         if (is_null($value)) {
    //             return $_states[$key];
    //         } else {
    //             $_states[$key] = $value;
    //             $_SESSION['shop:states'] = $_states;
    //         }
    //     }
    // }

}

?>
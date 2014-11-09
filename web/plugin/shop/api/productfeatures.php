<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use Exception;
use ArrayObject;

class productfeatures extends \engine\objects\api {

    public function createFeature ($itemData) {
        $data = array();
        $data["CustomerID"] = $itemData["CustomerID"];
        $data["FieldName"] = $itemData["FieldName"];
        $data["GroupName"] = $itemData["GroupName"];
        $config = $this->getPluginConfiguration()->data->jsapiShopCreateFeature($data);
        $featureID = $this->getCustomer()->fetch($config);
    }

    public function getFeatures () {
        $tree = array();
        $config = $this->getPluginConfiguration()->data->jsapiShopGetFeatures();
        $data = $this->getCustomer()->fetch($config);
        if (!empty($data)) {
            foreach ($data as $value) {
                if (!isset($tree[$value['GroupName']])) {
                    $tree[$value['GroupName']] = array();
                }
                $tree[$value['GroupName']][$value['ID']] = $value['FieldName'];
            }
        }
        return $tree;
    }

    public function get (&$resp, $req) {
        $resp = $this->getFeatures();
    }

}

?>
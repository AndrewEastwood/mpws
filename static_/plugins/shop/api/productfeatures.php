<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class productfeatures extends API {

    public function createFeature ($itemData) {
        global $app;
        $data = array();
        $data["CustomerID"] = $itemData["CustomerID"];
        $data["FieldName"] = $itemData["FieldName"];
        $data["GroupName"] = $itemData["GroupName"];
        // var_dump($data);
        $config = $this->data->shopCreateFeature($data);
        $featureID = $app->getDB()->query($config);
        return $featureID;
    }

    public function getFeatures () {
        global $app;
        $tree = array();
        $config = $this->data->fetchFeaturesArray();
        $data = $app->getDB()->query($config);
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

    public function get ($req, $resp) {
        $resp->setResponse($this->getFeatures());
    }

}

?>
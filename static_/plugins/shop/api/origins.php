<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class origins {


    private $_statuses = array('ACTIVE', 'REMOVED');
    // -----------------------------------------------
    // -----------------------------------------------
    // ORIGINS
    // -----------------------------------------------
    // -----------------------------------------------

    private function __adjustOrigin (&$origin) {
        $origin['ID'] = intval($origin['ID']);
        $origin['_isRemoved'] = $origin['Status'] === 'REMOVED';
        return $origin;
    }

    public function getOriginByID ($originID) {
        global $app;
        if (empty($originID) || !is_numeric($originID))
            return null;
        $config = dbquery::shopGetOriginItem($originID);
        $origin = $app->getDB()->query($config);
        if (empty($origin))
            return null;
        return $this->__adjustOrigin($origin);
    }

    public function getOriginByName ($originName) {
        global $app;
        $config = dbquery::shopGetOriginItem();
        $config['condition']['Name'] = $app->getDB()->createCondition($originName);
        $origin = $app->getDB()->query($config);
        if (empty($origin))
            return null;
        return $this->__adjustOrigin($origin);
    }

    public function getOriginByExternalKey ($externalKey) {
        global $app;
        $config = dbquery::shopGetOriginItem();
        $config['condition']['ExternalKey'] = $app->getDB()->createCondition($externalKey);
        $origin = $app->getDB()->query($config);
        if (empty($origin))
            return null;
        return $this->__adjustOrigin($origin);
    }

    public function getOrigins_List (array $options = array()) {
        global $app;
        $config = dbquery::shopGetOriginList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getOriginByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $app->getDB()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function createOrigin ($reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        $OriginID = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 200),
            'Description' => array('string', 'skipIfUnset'),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $validatedValues["CustomerID"] = $app->getSite()->getRuntimeCustomerID();

                $configCreateOrigin = dbquery::shopCreateOrigin($validatedValues);

                $app->getDB()->beginTransaction();
                $OriginID = $app->getDB()->query($configCreateOrigin) ?: null;
                // var_dump($OriginID);

                if (empty($OriginID))
                    throw new Exception('OriginCreateError');

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        if ($success && !empty($OriginID))
            $result = $this->getOriginByID($OriginID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateOrigin ($OriginID, $reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'skipIfUnset', 'min' => 1, 'max' => 200),
            'Description' => array('string', 'skipIfUnset'),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300),
            'Status' => array('string', 'skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                if (count($validatedValues)) {
                    $app->getDB()->beginTransaction();
                    $configCreateCategory = dbquery::shopUpdateOrigin($OriginID, $validatedValues);
                    $app->getDB()->query($configCreateCategory);
                    $app->getDB()->commit();
                }

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getOriginByID($OriginID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        API::getAPI('shop:products')->updateProductSearchTextByOriginID($OriginID);

        return $result;
    }

    public function disableOrigin ($OriginID) {
        global $app;
        $errors = array();
        $success = false;

        try {
            $app->getDB()->beginTransaction();

            $config = dbquery::shopDeleteOrigin($OriginID);
            $app->getDB()->query($config);

            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = 'OriginUpdateError';
        }

        $result = $this->getOriginByID($OriginID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function get (&$resp, $req) {
        if (empty($req->id)) {
            $resp = $this->getOrigins_List($req->get);
        } else {
            if (is_numeric($req->id)) {
                $oringID = intval($req->id);
                $resp = $this->getOriginByID($oringID);
            } else {
                $resp = $this->getOriginByExternalKey($req->id);
            }
        }
    }

    public function post (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create'))) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createOrigin($req->data);
        // $this->_getOrSetCachedState('changed:origin', true);
    }

    public function patch (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit'))) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->id)) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $OriginID = intval($req->id);
            $resp = $this->updateOrigin($OriginID, $req->data);
            // $this->_getOrSetCachedState('changed:origin', true);
        }
    }

    public function delete (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit'))) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        if (empty($req->id)) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $OriginID = intval($req->id);
            $resp = $this->disableOrigin($OriginID);
            // $this->_getOrSetCachedState('changed:origin', true);
        }
    }
}


?>
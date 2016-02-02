<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class origins extends API {


    // private $_statuses = array('ACTIVE', 'REMOVED');
    // -----------------------------------------------
    // -----------------------------------------------
    // ORIGINS
    // -----------------------------------------------
    // -----------------------------------------------

    // private function __adjustOrigin (&$origin) {
    //     $origin['ID'] = intval($origin['ID']);
    //     $origin['_isRemoved'] = $origin['Status'] === 'REMOVED';
    //     return $origin;
    // }

    // public function getOriginByID ($originID) {
    //     global $app;
    //     if (empty($originID) || !is_numeric($originID))
    //         return null;
    //     $config = $this->data->fetchOriginByID($originID);
    //     $origin = $app->getDB()->query($config);
    //     if (empty($origin))
    //         return null;
    //     return $this->__adjustOrigin($origin);
    // }

    // public function getOriginByName ($originName) {
    //     global $app;
    //     $config = $this->data->fetchOriginByID();
    //     $config['condition']['Name'] = $app->getDB()->createCondition($originName);
    //     $origin = $app->getDB()->query($config);
    //     if (empty($origin))
    //         return null;
    //     return $this->__adjustOrigin($origin);
    // }

    // public function getOriginByExternalKey ($externalKey) {
    //     global $app;
    //     $config = $this->data->fetchOriginByID();
    //     $config['condition']['ExternalKey'] = $app->getDB()->createCondition($externalKey);
    //     $origin = $app->getDB()->query($config);
    //     if (empty($origin))
    //         return null;
    //     return $this->__adjustOrigin($origin);
    // }

    // public function getOrigins_List (array $options = array()) {
    //     global $app;
    //     $config = $this->data->fetchOriginDataList($options);
    //     $self = $this;
    //     $callbacks = array(
    //         "parse" => function ($items) use($self) {
    //             $_items = array();
    //             foreach ($items as $val)
    //                 $_items[] = $self->getOriginByID($val['ID']);
    //             return $_items;
    //         }
    //     );
    //     $dataList = $app->getDB()->queryMatchedIDs($config, $options, $callbacks);
    //     return $dataList;
    // }

    public function createOrigin ($reqData) {
        global $app;
        // $result = array();
        // $errors = array();
        // $success = false;
        // $originID = null;
        $r = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 200),
            'Description' => array('string', 'skipIfUnset'),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300)
        ));

        if ($validatedDataObj->errorsCount == 0) {
            $validatedValues = $validatedDataObj->validData;
            $validatedValues["CustomerID"] = $app->getSite()->getRuntimeCustomerID();
            $r = $this->data->createOrigin($validatedValues);
            if ($r->isEmptyResult()) {
                $r->addError("OriginCreateError");
            }
        } else {
            $r->addErrors($validatedDataObj->errorMessages);
        }

        if ($r->isSuccess() && $r->hasResult()) {
            $item = $this->data->fetchOriginByID($r->getResult());
            $r->setResult($item);
        }
        return $r->toArray();

        // if ($success && !empty($originID))
        //     $result = $this->getOriginByID($originID);
        // $result['errors'] = $errors;
        // $result['success'] = $success;

        // return $result;
    }

    public function updateOrigin ($originID, $reqData) {
        global $app;
        // $result = array();
        // $errors = array();
        // $success = false;
        $r = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'skipIfUnset', 'min' => 1, 'max' => 200),
            'Description' => array('string', 'skipIfUnset'),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300),
            'Status' => array('string', 'skipIfUnset')
        ));

        if ($validatedDataObj->errorsCount == 0) {
            $validatedValues = $validatedDataObj->validData;
            $validatedValues["CustomerID"] = $app->getSite()->getRuntimeCustomerID();
            $r = $this->data->createOrigin($validatedValues);
            if ($r->isEmptyResult()) {
                $r->addError("OriginCreateError");
            }
        } else {
            $r->addErrors($validatedDataObj->errorMessages);
        }

        $item = $this->data->fetchOriginByID($originID);
        $r->setResult($item);
        if ($r->isSuccess()) {
            API::getAPI('shop:products')->updateProductSearchTextByoriginID($originID);
        }
        return $r->toArray();

        // if ($validatedDataObj->errorsCount == 0)
        //     try {

        //         $validatedValues = $validatedDataObj->validData;

        //         if (count($validatedValues)) {
        //             $app->getDB()->beginTransaction();
        //             $configCreateCategory = $this->data->updateOrigin($originID, $validatedValues);
        //             $app->getDB()->query($configCreateCategory);
        //             $app->getDB()->commit();
        //         }

        //         $success = true;
        //     } catch (Exception $e) {
        //         $app->getDB()->rollBack();
        //         $errors[] = $e->getMessage();
        //     }
        // else
        //     $r->addErrors($validatedDataObj->errorMessages);

        // $result = $this->getOriginByID($originID);
        // $result['errors'] = $errors;
        // $result['success'] = $success;

        // return $result;
    }

    public function disableOrigin ($originID) {
        $this->data->archiveCustomer($originID);
        $item = $this->data->fetchOriginByID($originID);
        $r->setResult($item);
        return $r->toArray();
        // global $app;
        // $errors = array();
        // $success = false;

        // try {
        //     $app->getDB()->beginTransaction();

        //     $config = $this->data->deleteOrigin($originID);
        //     $app->getDB()->query($config);

        //     $app->getDB()->commit();

        //     $success = true;
        // } catch (Exception $e) {
        //     $app->getDB()->rollBack();
        //     $errors[] = 'OriginUpdateError';
        // }

        // $result = $this->getOriginByID($originID);
        // $result['errors'] = $errors;
        // $result['success'] = $success;
        // return $result;
    }

    public function get ($req, $resp) {
        // for specific customer item
        // by id
        if ($req->hasRequestedID()) {
            $resp->setResponse($this->data->fetchOriginByID($req->id));
            return;
        }
        // or by ExternalKey
        if ($req->hasRequestedExternalKey()) {
            $resp->setResponse($this->data->fetchOriginByExternalKey($req->externalKey));
            return;
        }
        $resp->setResponse($this->data->fetchOriginDataList($req->get));
        // if (empty($req->id)) {
        //     $resp->setResponse($this->data->fetchOriginDataList($req->get));
        // } else {
        //     if (is_numeric($req->id)) {
        //         $oringID = intval($req->id);
        //         $resp->setResponse($this->fetchOriginByID($oringID));
        //     } else {
        //         $resp->setResponse($this->getOriginByExternalKey($req->id));
        //     }
        // }
    }

    public function post ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCanCreateWithAllOthers('shop_CREATE_ORIGIN')) {
            return $resp->setAccessError();
        }
        // if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
        //     (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create'))) {
        //     return $resp->setAccessError();
        //     return;
        // }
        $resp->setResponse($this->createOrigin($req->data));
        // $this->_getOrSetCachedState('changed:origin', true);
    }

    public function patch ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCanEditWithAllOthers('shop_EDIT_ORIGIN')) {
            return $resp->setAccessError();
        }
        // if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
        //     (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit'))) {
        //     return $resp->setAccessError();
        //     return;
        // }
        if ($req->hasRequestedID()) {
            $resp->setResponse($this->updateOrigin($req->id, $req->data));
            return;
        }
        $resp->setWrongItemIdError();
        // if (empty($req->id)) {
        // } else {
        //     $originID = intval($req->id);
        //     $resp->setResponse($this->updateOrigin($originID, $req->data));
        //     // $this->_getOrSetCachedState('changed:origin', true);
        // }
    }

    public function delete ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCanEditWithAllOthers('shop_EDIT_ORIGIN')) {
            return $resp->setAccessError();
        }
        // if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
        //     (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit'))) {
        //     return $resp->setAccessError();
        //     return;
        // }
        if ($req->hasRequestedID()) {
            $resp->setResponse($this->disableOrigin($req->id, $req->data));
            return;
        }
        $resp->setWrongItemIdError();
        // if (empty($req->id)) {
        //     $resp->setWrongItemIdError();
        // } else {
        //     $originID = intval($req->id);
        //     $resp->setResponse($this->disableOrigin($originID));
        //     // $this->_getOrSetCachedState('changed:origin', true);
        // }
    }
}


?>
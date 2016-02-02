<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class promos extends API {

    private $_listKey_Promo = 'shop:promo';
    // -----------------------------------------------
    // -----------------------------------------------
    // PROMO
    // -----------------------------------------------
    // -----------------------------------------------
    // public function getPromoByID ($promoID) {
    //     global $app;
    //     $config = $this->data->fetchPromoByID($promoID);
    //     $data = $app->getDB()->query($config);
    //     $data['ID'] = intval($data['ID']);
    //     $data['Discount'] = floatval($data['Discount']);
    //     $data['_isExpired'] = strtotime($app->getDB()->getDate()) > strtotime($data['DateExpire']);
    //     $data['_isFuture'] = strtotime($app->getDB()->getDate()) < strtotime($data['DateStart']);
    //     $data['_isActive'] = !$data['_isExpired'] && !$data['_isFuture'];
    //     return $data;
    // }

    // public function getPromoByHash ($hash, $activeOnly = false) {
    //     global $app;
    //     $config = $this->data->fetchPromoByHash($hash, $activeOnly);
    //     $data = $app->getDB()->query($config);
    //     $data['ID'] = intval($data['ID']);
    //     $data['Discount'] = floatval($data['Discount']);
    //     return $data;
    // }

    // public function getPromoCodes_List (array $options = array()) {
    //     global $app;
    //     $config = $this->data->fetchPromoDataList($options);
    //     $self = $this;
    //     $callbacks = array(
    //         "parse" => function ($items) use($self) {
    //             $_items = array();
    //             foreach ($items as $val)
    //                 $_items[] = $self->getPromoByID($val['ID']);
    //             return $_items;
    //         }
    //     );
    //     $dataList = $app->getDB()->queryMatchedIDs($config, $options, $callbacks);
    //     return $dataList;
    // }

    public function createPromo ($reqData) {
        global $app;
        // $result = array();
        // $errors = array();
        // $success = false;
        // $promoID = null;
        $r = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'DateStart' => array('string'),
            'DateExpire' => array('string'),
            'Discount' => array('numeric')
        ));

        if ($validatedDataObj->errorsCount == 0) {

            $validatedValues = $validatedDataObj->validData;
            $validatedValues["Code"] = rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999);
            $validatedValues["CustomerID"] = $app->getSite()->getRuntimeCustomerID();

            // $configCreatePromo = $this->data->createPromo($validatedValues);

            // $app->getDB()->beginTransaction();
            // $promoID = $app->getDB()->query($configCreatePromo) ?: null;
            $r = $this->data->createPromo($validatedValues);

            if ($r->isEmptyResult()) {
                $r->addError('PromoCreateError');
            }
            // $app->getDB()->commit();
            // $success = true;
        } else {
            $r->addErrors($validatedDataObj->errorMessages);
        }

        if ($r->isSuccess() && $r->hasResult()) {
            $item = $this->data->fetchPromoByID($r->getResult());
            $r->setResult($item);
        }
        // if ($success && !empty($promoID))
        //     $result = $this->getPromoByID($promoID);
        // $result['errors'] = $errors;
        // $result['success'] = $success;

        return $r->toArray();
    }

    public function updatePromo ($promoID, $reqData) {
        $r = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'DateStart' => array('string', 'skipIfUnset'),
            'DateExpire' => array('string', 'skipIfUnset'),
            'Discount' => array('numeric')
        ));

        if ($validatedDataObj->errorsCount == 0) {
            $r = $this->data->updatePromo($promoID, $validatedDataObj->validData);
        } else {
            $r->addErrors($validatedDataObj->errorMessages);
        }

        if ($r->hasResult()) {
            $item = $this->data->fetchPromoByID($promoID);
            $r->setResult($item);
        }

        return $r->toArray();
    }

    public function expirePromo ($promoID) {
        $r = $this->data->expirePromo($promoID);
        $item = $this->data->fetchPromoByID($promoID);
        $r->setResult($item);
        return $r->toArray();
    }


    public function setSessionPromo ($promo) {
        $_SESSION[$this->_listKey_Promo] = $promo;
    }

    public function getSessionPromo () {
        if (!isset($_SESSION[$this->_listKey_Promo]))
            $_SESSION[$this->_listKey_Promo] = null;
        return $_SESSION[$this->_listKey_Promo];
    }

    public function resetSessionPromo () {
        $_SESSION[$this->_listKey_Promo] = null;
    }


    public function get ($req, $resp) {
        // if (!API::getAPI('system:auth')->ifYouCanCreateWithAllOthers('shop_MENU_PROMO')) {
        //     return $resp->setAccessError();
        // }
        // if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
        //     (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('shop_MENU_PROMO'))) {
        //     return $resp->setAccessError();
        //     return;
        // }
        // for specific item
        // by id
        if ($req->hasRequestedID()) {
            $resp->setResponse($this->data->fetchPromoByID($req->id));
            return;
        }
        // for the case when we have to fecth list with customers
        // if ($req->noRequestedItem()) {
        //     return;
        // }
        $resp->setResponse($this->data->fetchPromoDataList($req->get));
        // if (empty($req->id)) {
        //     $resp->setResponse($this->getPromoCodes_List($req->get));
        // } else {
        //     $promoID = intval($req->id);
        //     $resp->setResponse($this->getPromoByID($promoID));
        // }
    }

    public function post ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCanCreateWithAllOthers('shop_CREATE_PROMO')) {
            return $resp->setAccessError();
        }
        // if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
        //     (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('shop_CREATE_PROMO'))) {
        //     return $resp->setAccessError();
        //     return;
        // }
        $resp->setResponse($this->createPromo($req->data));
    }

    public function patch ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCanEditWithAllOthers('shop_EDIT_PROMO')) {
            return $resp->setAccessError();
        }
        // if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
        //     (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('shop_EDIT_PROMO'))) {
        //     return $resp->setAccessError();
        //     return;
        // }
        // by id
        if ($req->hasRequestedID()) {
            $resp->setResponse($this->data->updatePromo($req->id, $req->data));
            return;
        }
        $resp->setWrongItemIdError();
        // if (empty($req->id)) {
        //     $resp->setWrongItemIdError();
        // } else {
        //     $promoID = intval($req->id);
        //     $resp->setResponse($this->updatePromo($promoID, $req->data));
        // }
    }

    public function delete ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCanEditWithAllOthers('shop_EDIT_PROMO')) {
            return $resp->setAccessError();
        }
        // if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
        //     (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('shop_EDIT_PROMO'))) {
        //     return $resp->setAccessError();
        //     return;
        // }
        if ($req->hasRequestedID()) {
            $resp->setResponse($this->data->expirePromo($req->id));
            return;
        }
        $resp->setWrongItemIdError();
        // if (empty($req->id)) {
        //     $resp->setWrongItemIdError();
        // } else {
        //     $promoID = intval($req->id);
        //     $resp->setResponse($this->expirePromo($promoID));
        // }
    }






}


?>
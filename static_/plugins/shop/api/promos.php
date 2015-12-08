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
    //     $config = $this->data->fetchPromoList($options);
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
        $result = array();
        $errors = array();
        $success = false;
        $promoID = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'DateStart' => array('string'),
            'DateExpire' => array('string'),
            'Discount' => array('numeric')
        ));

        if ($validatedDataObj->errorsCount == 0)
            try {

                $validatedValues = $validatedDataObj->validData;
                $validatedValues["Code"] = rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999);
                $validatedValues["CustomerID"] = $app->getSite()->getRuntimeCustomerID();

                $configCreatePromo = $this->data->createPromo($validatedValues);

                $app->getDB()->beginTransaction();
                $promoID = $app->getDB()->query($configCreatePromo) ?: null;

                if (empty($promoID))
                    throw new Exception('PromoCreateError');

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj->errorMessages;

        if ($success && !empty($promoID))
            $result = $this->getPromoByID($promoID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updatePromo ($promoID, $reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'DateStart' => array('string', 'skipIfUnset'),
            'DateExpire' => array('string', 'skipIfUnset'),
            'Discount' => array('numeric')
        ));

        if ($validatedDataObj->errorsCount == 0)
            try {

                $validatedValues = $validatedDataObj->validData;

                if (count($validatedValues)) {
                    $app->getDB()->beginTransaction();
                    $configCreateCategory = $this->data->updatePromo($promoID, $validatedValues);
                    $app->getDB()->query($configCreateCategory);
                    $app->getDB()->commit();
                }

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj->errorMessages;

        $result = $this->getPromoByID($promoID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function expirePromo ($promoID) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        try {
            $app->getDB()->beginTransaction();
            $config = $this->data->expirePromo($promoID);
            $app->getDB()->query($config);
            $app->getDB()->commit();
            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
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
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('shop_MENU_PROMO'))) {
            $resp->setError('AccessDenied');
            return;
        }
        // for specific item
        // by id
        if (Request::hasRequestedID()) {
            $resp->setResponse($this->data->fetchPromoByID($req->id));
            return;
        }
        // for the case when we have to fecth list with customers
        if (Request::noRequestedItem()) {
            $resp->setResponse($this->data->fetchPromoList($req->get));
        }
        // if (empty($req->id)) {
        //     $resp->setResponse($this->getPromoCodes_List($req->get));
        // } else {
        //     $promoID = intval($req->id);
        //     $resp->setResponse($this->getPromoByID($promoID));
        // }
    }

    public function post ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('shop_CREATE_PROMO'))) {
            $resp->setError('AccessDenied');
            return;
        }
        $resp->setResponse($this->createPromo($req->data));
    }

    public function patch ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('shop_EDIT_PROMO'))) {
            $resp->setError('AccessDenied');
            return;
        }
        // for specific item
        // by id
        if (Request::hasRequestedID()) {
            $resp->setResponse($this->data->updatePromo($req->id, $req->data));
            return;
        }
        // for the case when we have to fecth list with customers
        if (Request::noRequestedItem()) {
            
            $resp->setError('MissedParameter_id');
        }
        // if (empty($req->id)) {
        //     $resp->setError('MissedParameter_id');
        // } else {
        //     $promoID = intval($req->id);
        //     $resp->setResponse($this->updatePromo($promoID, $req->data));
        // }
    }

    public function delete ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('shop_EDIT_PROMO'))) {
            $resp->setError('AccessDenied');
            return;
        }
        if (empty($req->id)) {
            $resp->setError('MissedParameter_id');
        } else {
            $promoID = intval($req->id);
            $resp->setResponse($this->expirePromo($promoID));
        }
    }






}


?>
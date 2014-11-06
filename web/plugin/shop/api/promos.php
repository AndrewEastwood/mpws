<?php
namespace web\plugin\shop\api;

use \engine\object\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use Exception;
use ArrayObject;

class promos extends \engine\object\api {

    private $_listKey_Promo = 'shop:promo';
    // -----------------------------------------------
    // -----------------------------------------------
    // PROMO
    // -----------------------------------------------
    // -----------------------------------------------
    public function getPromoByID ($promoID) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetPromoByID($promoID);
        $data = $this->getCustomer()->fetch($config);
        $data['ID'] = intval($data['ID']);
        $data['Discount'] = floatval($data['Discount']);
        $data['_isExpired'] = strtotime($this->getPluginConfiguration()->data->getDate()) > strtotime($data['DateExpire']);
        $data['_isFuture'] = strtotime($this->getPluginConfiguration()->data->getDate()) < strtotime($data['DateStart']);
        $data['_isActive'] = !$data['_isExpired'] && !$data['_isFuture'];
        return $data;
    }

    public function getPromoByHash ($hash, $activeOnly = false) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetPromoByHash($hash, $activeOnly);
        $data = $this->getCustomer()->fetch($config);
        $data['ID'] = intval($data['ID']);
        $data['Discount'] = floatval($data['Discount']);
        return $data;
    }

    public function getPromoCodes_List (array $options = array()) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetPromoList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getPromoByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function createPromo ($reqData) {
        $result = array();
        $errors = array();
        $success = false;
        $promoID = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'DateStart' => array('string'),
            'DateExpire' => array('string'),
            'Discount' => array('numeric')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];
                $validatedValues["Code"] = rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999);
                $validatedValues["CustomerID"] = $this->getCustomer()->getCustomerID();

                $configCreatePromo = $this->getPluginConfiguration()->data->jsapiShopCreatePromo($validatedValues);

                $this->getCustomerDataBase()->beginTransaction();
                $promoID = $this->getCustomer()->fetch($configCreatePromo) ?: null;

                if (empty($promoID))
                    throw new Exception('PromoCreateError');

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        if ($success && !empty($promoID))
            $result = $this->getPromoByID($promoID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updatePromo ($promoID, $reqData) {
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'DateStart' => array('string', 'skipIfUnset'),
            'DateExpire' => array('string', 'skipIfUnset'),
            'Discount' => array('numeric')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                if (count($validatedValues)) {
                    $this->getCustomerDataBase()->beginTransaction();
                    $configCreateCategory = $this->getPluginConfiguration()->data->jsapiShopUpdatePromo($promoID, $validatedValues);
                    $this->getCustomer()->fetch($configCreateCategory);
                    $this->getCustomerDataBase()->commit();
                }

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getPromoByID($promoID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function expirePromo ($promoID) {
        $result = array();
        $errors = array();
        $success = false;

        try {
            $this->getCustomerDataBase()->beginTransaction();
            $config = $this->getPluginConfiguration()->data->jsapiShopExpirePromo($promoID);
            $this->getCustomer()->fetch($config);
            $this->getCustomerDataBase()->commit();
            $success = true;
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
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


    public function get (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp = $this->getPromoCodes_List($req->get);
        } else {
            $promoID = intval($req->get['id']);
            $resp = $this->getPromoByID($promoID);
        }
    }

    public function post (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createPromo($req->data);
    }

    public function patch (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $promoID = intval($req->get['id']);
            $resp = $this->updatePromo($promoID, $req->data);
        }
    }

    public function delete (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $promoID = intval($req->get['id']);
            $resp = $this->expirePromo($promoID);
        }
    }






}


?>
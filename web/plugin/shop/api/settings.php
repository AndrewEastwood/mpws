<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class settings {

    public function __construct () {
        $types = array_keys(dbquery::$SETTING_TYPE_TO_DBTABLE_MAP);
        $this->SETTING_TYPE_ARRAY = $types;
        $this->SETTING_TYPE = (object)array_combine($types, $types);
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // SETTINGS
    // -----------------------------------------------
    // -----------------------------------------------

    public function getSettingsAddresses () {
        global $app;
        $items = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->ADDRESS));
        foreach ($items as &$value) {
            $value = $this->__adjustSettingItem($value);
            // phones
            $config = dbquery::shopGetSettingByType($this->SETTING_TYPE->PHONES);
            $config["condition"]["ShopAddressID"] = $app->getDB()->createCondition($value['ID']);
            $value['Phones'] = $app->getDB()->query($config) ?: array();
            // open hours
            $config = dbquery::shopGetSettingByType($this->SETTING_TYPE->OPENHOURS);
            $config["condition"]["ShopAddressID"] = $app->getDB()->createCondition($value['ID']);
            $value['OpenHours'] = $app->getDB()->query($config) ?: array();
        }
        return $items;
    }
    public function getSettingsAlerts () {
        global $app;
        $items = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->ALERTS));
        $this->__adjustSettingItem($items);
        if (empty($items))
            return $items;
        $items["AllowAlerts"] = intval($items["AllowAlerts"]) === 1;
        $items["UsePromo"] = intval($items["UsePromo"]) === 1;
        $items["NewProductAdded"] = intval($items["NewProductAdded"]) === 1;
        $items["ProductPriceGoesDown"] = intval($items["ProductPriceGoesDown"]) === 1;
        $items["PromoIsStarted"] = intval($items["PromoIsStarted"]) === 1;
        $items["AddedNewOrigin"] = intval($items["AddedNewOrigin"]) === 1;
        $items["AddedNewCategory"] = intval($items["AddedNewCategory"]) === 1;
        $items["AddedNewDiscountedProduct"] = intval($items["AddedNewDiscountedProduct"]) === 1;
        return $items;
    }
    public function getSettingsExchangeRates () {}
    public function getSettingsExchangeRatesDisplay () {}
    public function getSettingsInfo () {
        global $app;
        $items = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->INFO));
        return $this->__adjustSettingItem($items) ?: array();
    }
    public function getSettingsMisc () {
        global $app;
        $items = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->MISC));
        return $this->__adjustSettingItem($items) ?: array();
    }
    public function getSettingsProduct () {
        global $app;
        $items = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->PRODUCT));
        $this->__adjustSettingItem($items) ?: array();
        if (empty($items))
            return $items;
        $items["ShowOpenHours"] = intval($items["ShowOpenHours"]) === 1;
        $items["ShowDeliveryInfo"] = intval($items["ShowDeliveryInfo"]) === 1;
        $items["ShowPaymentInfo"] = intval($items["ShowPaymentInfo"]) === 1;
        $items["ShowSocialSharing"] = intval($items["ShowSocialSharing"]) === 1;
        $items["ShowPriceChart"] = intval($items["ShowPriceChart"]) === 1;
        $items["ShowWarrantyInfo"] = intval($items["ShowWarrantyInfo"]) === 1;
        $items["ShowContacts"] = intval($items["ShowContacts"]) === 1;
        return $items;
    }
    public function getSettingsSeo () {
        global $app;
        $items = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->SEO));
        return $this->__adjustSettingItem($items) ?: array();
    }
    public function getSettingsFormOrder () {
        global $app;
        $items = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->FORMORDER));
        $this->__adjustSettingItem($items) ?: array();
        if (empty($items))
            return $items;
        $items["ShowName"] = intval($items["ShowName"]) === 1;
        $items["ShowEMail"] = intval($items["ShowEMail"]) === 1;
        $items["ShowPhone"] = intval($items["ShowPhone"]) === 1;
        $items["ShowAddress"] = intval($items["ShowAddress"]) === 1;
        $items["ShowPOBox"] = intval($items["ShowPOBox"]) === 1;
        $items["ShowCountry"] = intval($items["ShowCountry"]) === 1;
        $items["ShowCity"] = intval($items["ShowCity"]) === 1;
        $items["ShowDeliveryAganet"] = intval($items["ShowDeliveryAganet"]) === 1;
        $items["ShowComment"] = intval($items["ShowComment"]) === 1;
        $items["SucessTextLines"] = $items["SucessTextLines"];
        $items["ShowOrderTrackingLink"] = intval($items["ShowOrderTrackingLink"]) === 1;
        return $items;
    }
    public function getSettingsWebsite () {
        global $app;
        $items = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->WEBSITE));
        return $this->__adjustSettingItem($items) ?: array();
    }
    public function getSettingByID ($type, $id) {
        global $app;
        $items = null;
        try {
            $items = $app->getDB()->query(dbquery::shopGetSettingByID($type, $id));
        } catch (Exception $ex) { }
        return $this->__adjustSettingItem($items);
    }
    public function getSettings () {
        $settings = array();
        $settings[$this->SETTING_TYPE->ADDRESS] = $this->getSettingsAddresses();
        $settings[$this->SETTING_TYPE->ALERTS] = $this->getSettingsAlerts();
        $settings[$this->SETTING_TYPE->INFO] = $this->getSettingsInfo();
        // $settings[$this->SETTING_TYPE->MISC] = $this->getSettingsMisc();
        $settings[$this->SETTING_TYPE->PRODUCT] = $this->getSettingsProduct();
        $settings[$this->SETTING_TYPE->SEO] = $this->getSettingsSeo();
        $settings[$this->SETTING_TYPE->WEBSITE] = $this->getSettingsWebsite();
        $settings[$this->SETTING_TYPE->FORMORDER] = $this->getSettingsFormOrder();
        return $settings;
    }
    private function getSettingsByType ($type) {
        global $app;
        $settings = null;
        switch ($type) {
            case 'SEO':
                $settings = $this->getSettingsSeo();
                break;
            case 'ALERTS':
                $settings = $this->getSettingsAlerts();
                break;
            case 'ADDRESS':
                $settings = $this->getSettingsAddresses();
                break;
            case 'INFO':
                $settings = $this->getSettingsInfo();
                break;
            case 'WEBSITE':
                $settings = $this->getSettingsWebsite();
                break;
            case 'FORMORDER':
                $settings = $this->getSettingsFormOrder();
                break;
            case 'PRODUCT':
                $settings = $this->getSettingsProduct();
                break;
            case 'PHONES':
                $settings = $this->getSettingsShopPhones();
                break;
            default:
                # code...
                break;
        }
        return $settings;
    }

    private function __adjustSettingItem (&$setting) {
        global $app;
        if (empty($setting)) {
            return null;
        }
        $setting['ID'] = intval($setting['ID']);
        // if (isset($setting['CustomerID'])) {
        //     unset($setting['CustomerID']);
        // }
        return $setting;
    }

    public function createOrUpdateSetting ($type, $reqData, $settingID = null) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        $isUpdate = $settingID !== null;

        try {

            $type = dbquery::getVerifiedSettingsType($type);
            if (empty($type)) {
                throw new Exception("WrongSettingsType", 1);
            }

            $count = $this->getCustomerSettingsCount($type);
            $mustBeSingle = dbquery::isOneForCustomer($type);

            if ($mustBeSingle && $count > 0 && !$isUpdate) {
                throw new Exception("PropertyAlreadyExistsUsePatchMethod", 1);
            }

            $validatedDataObj = array();
            switch ($type) {
                case 'SEO':
                    $dataRules = array(
                        'ProductKeywords' => array('string', 'skipIfUnset'),
                        'CategoryKeywords' => array('string', 'skipIfUnset'),
                        'HomePageKeywords' => array('string', 'skipIfUnset'),
                        'ProductDescription' => array('string', 'skipIfUnset'),
                        'CategoryDescription' => array('string', 'skipIfUnset'),
                        'HomePageDescription' => array('string', 'skipIfUnset'),
                        'ProductPageTitle' => array('string', 'skipIfUnset'),
                        'CategoryPageTitle' => array('string', 'skipIfUnset'),
                        'HomePageTitle' => array('string', 'skipIfUnset')
                    );
                    $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                    break;
                case 'ALERTS':
                    $dataRules = array(
                        'AllowAlerts' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'UsePromo' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'NewProductAdded' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ProductPriceGoesDown' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'PromoIsStarted' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'AddedNewOrigin' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'AddedNewCategory' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'AddedNewDiscountedProduct' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0)
                    );
                    $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                    break;
                case 'PRODUCT':
                    $dataRules = array(
                        'ShowOpenHours' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowDeliveryInfo' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowPaymentInfo' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowSocialSharing' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowPriceChart' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowWarrantyInfo' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowContacts' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0)
                    );
                    $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                    break;
                case 'ADDRESS':
                    $dataRules = array(
                        'ShopName' => array('string', 'skipIfUnset'),
                        'Country' => array('string', 'skipIfUnset'),
                        'City' => array('string', 'skipIfUnset'),
                        'AddressLine1' => array('string', 'skipIfUnset'),
                        'AddressLine2' => array('string', 'skipIfUnset'),
                        'AddressLine3' => array('string', 'skipIfUnset')
                    );
                    $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                    // $dataRules = $this->getSettingsAddresses();
                    break;
                case 'PHONES':
                    $dataRules = array(
                        'ShopAddressID' => array('int'),
                        'Label' => array('string'),
                        'Value' => array('string')
                    );
                    $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                    // $dataRules = $this->getSettingsAddresses();
                    break;
                case 'INFO':
                    // $dataRules = $this->getSettingsInfo();
                    break;
                case 'WEBSITE':
                    // $dataRules = $this->getSettingsWebsite();
                    break;
                case 'FORMORDER':
                    $dataRules = array(
                        'ShowName' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowEMail' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowPhone' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowAddress' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowPOBox' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowCountry' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowCity' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowDeliveryAganet' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowComment' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'SucessTextLines' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'ShowOrderTrackingLink' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0)
                    );
                    $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                    break;
            }

            if ($validatedDataObj["totalErrors"] == 0 && empty($errors)) {
                try {

                    $validatedValues = $validatedDataObj['values'];
                    // var_dump($validatedValues);

                    $validatedValues["CustomerID"] = $app->getSite()->getRuntimeCustomerID();

                    $app->getDB()->beginTransaction();

                    if ($isUpdate) {
                        unset($validatedValues['ID']);
                        $config = dbquery::shopUpdateSetting($type, $settingID, $validatedValues);
                        $app->getDB()->query($config);
                    } else {
                        $config = dbquery::shopCreateSetting($type, $validatedValues);
                        $settingID = $app->getDB()->query($config) ?: null;
                    }

                    if (!$isUpdate && empty($settingID)) {
                        throw new Exception('SettingCreateError');
                    }

                    $app->getDB()->commit();

                    $success = true;
                } catch (Exception $e) {
                    $app->getDB()->rollBack();
                    $errors[] = $e->getMessage();
                }
            } else {
                $errors = $validatedDataObj["errors"];
            }

            if ($success && !empty($settingID) || $isUpdate) {
                $result = $this->getSettingsByType($type);
            }

        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function remove ($type, $settingID) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        try {
            $app->getDB()->beginTransaction();
            $config = dbquery::shopRemoveSetting($settingID);
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

    private function getVerifiedSettingsTypeObj ($req) {
        $typeObj = array(
            'error' => false,
            'mustBeSingle' => false,
            'type' => null
        );
        if (!isset($req->get['type'])) {
            $typeObj['error'] = 'MissedParameter_type';
        } else {
            $type = dbquery::getVerifiedSettingsType($req->get['type']);
            $typeObj['type'] = $type;
            if (is_null($type)) {
                $typeObj['error'] = 'WrongSettingsType_' . $req->get['type'];
            } else {
                $typeObj['single'] = dbquery::isOneForCustomer($type);
            }
        }
        return (object)$typeObj;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // WRAPPERS
    // -----------------------------------------------
    // -----------------------------------------------

    public function getCustomerSettingsCount ($type) {
        global $app;
        $config = dbquery::customerSettingsCount($type);
        $sCount = $app->getDB()->query($config);
        if (empty($sCount)) {
            return null;
        }
        return intval($sCount['ItemsCount']);
    }
    // public function getSettingsMapFormOrder () {
    //     $map = array();
    //     $items = $this->getSettingsFormOrder();
    //     foreach ($items as $value) {
    //         $map[$value['Property']] = $value;
    //     }
    //     return $map;
    // }

    // -----------------------------------------------
    // -----------------------------------------------
    // REQUESTS
    // -----------------------------------------------
    // -----------------------------------------------

    public function get (&$resp, $req) {
        $typeObj = $this->getVerifiedSettingsTypeObj($req);
        if (empty($typeObj->type)) {
            $resp = $this->getSettings();
        } else {
            $resp = $this->getSettingsByType($typeObj->type);
        }
    }

    public function post (&$resp, $req) {
        $typeObj = $this->getVerifiedSettingsTypeObj($req);
        if ($typeObj->error) {
            $resp['error'] = "WrongSettingsType";
        } else {
            $resp = $this->createOrUpdateSetting($typeObj->type, $req->data);
        }
        
/*        // if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create')) {
        //     $resp['error'] = "AccessDenied";
        //     return;
        // }
        // var_dump($typeObj);
        // header('HTTP/1.1 500');
        $settingsCount = $this->getCustomerSettingsCount($typeObj->type);
        var_dump($settingsCount);
        // var_dump($req->data);
        // $settings = $this->getSettingsByType($typeObj['name']);
        // $prop = null;
        // if (isset($req->data['ID'])) {
        //     $prop = $this->findByName($req->get['type'], $req->data['Property']);
        // }
        // if (empty($prop)) {
            $resp = $this->create($typeObj->type, $req->data);
        // } else {
        //     $resp = $this->update($req->get['type'], $prop['ID'], $req->data);
        // }*/
    }

    public function patch (&$resp, $req) {
        $typeObj = $this->getVerifiedSettingsTypeObj($req);
        if ($typeObj->error) {
            $resp['error'] = "WrongSettingsType";
        } else {
            $settingID = null;
            if (isset($req->data['ID']) && is_numeric($req->data['ID'])) {
                $settingID = intval($req->data['ID']);
            }
            $resp = $this->createOrUpdateSetting($typeObj->type, $req->data, $settingID);
        }
        // if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
        //     $resp['error'] = "AccessDenied";
        //     return;
        // }
        // if (empty($req->get['type'])) {
        //     $resp['error'] = 'MissedParameter_type';
        //     return;
        // }
        // if (empty($req->get['id'])) {
        //     $resp['error'] = 'MissedParameter_id';
        // } else {
        //     $settingID = intval($req->get['id']);
        //     if (!isset($req->get['id']) && isset($req->get['name'])) {
        //         $settingID = $req->get['name'];
        //     }
        //     $resp = $this->update($req->get['type'], $settingID, $req->data);
        // }
    }

    public function delete (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } elseif (empty($req->get['type'])) {
            $resp['error'] = 'MissedParameter_type';
        } else {
            $settingID = intval($req->get['id']);
            $resp = $this->remove($req->get['type'], $settingID);
        }
    }

}

?>
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

    public function getCustomerAlerts () {}
    public function getCustomerExchangeRates () {}
    public function getCustomerAddresses () {}
    public function getCustomer () {}

    /// TODO: create function that returns settings tree
/*
ALERTS
*/


    public function getSettingByID ($type, $id) {
        global $app;
        if (empty($id) || !is_numeric($id))
            return null;
        $config = dbquery::shopGetSettingByID($type, $id);
        $setting = $app->getDB()->query($config);
        return $this->__adjustSettingItem($setting);
    }

    public function getSettingsByType ($type) {
        global $app;
        $config = dbquery::shopGetSettingByType($type);
        $settings = $app->getDB()->query($config);
        if (empty($settings))
            return null;
        foreach ($settings as $key => $value) {
            $settings[$key] = $this->__adjustSettingItem($value);
        }
        return $settings;
    }

    private function __adjustSettingItem ($setting) {
        global $app;
        if (empty($setting))
            return null;
        $setting['ID'] = intval($setting['ID']);
        // $setting['_isActive'] = $setting['Status'] === 'ACTIVE';
        // $setting['_isRemoved'] = $setting['Status'] === 'REMOVED';
        return $setting;
    }

    public function toList (array $options = array()) {
        global $app;
        $list = array();
        foreach ($this->SETTING_TYPE_ARRAY as $type) {
            // $settingsCount = $this->getCustomerSettingsCount($type);
            // if (empty($settingsCount) && dbquery::isOneForCustomer($type)) {
            //     $this->create($type, array());
            // }
            $list[$type] = $this->getSettingsByType($type);
        }
        // $list['availableConversions'] = API::getAPI('shop:exchangerates')->getAvailableConversionOptions();
        // $list['availableMutipliers'] = API::getAPI('shop:exchangerates')->getActiveRateMultipliers();
        return $list;
    }

    public function create ($type, $reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        $settingID = null;

        try {

            $reqData["CustomerID"] = $app->getSite()->getRuntimeCustomerID();
            $config = dbquery::shopCreateSetting($type, $reqData);

            $app->getDB()->beginTransaction();

            $settingID = $app->getDB()->query($config) ?: null;

            if (empty($settingID)) {
                throw new Exception('SettingCreateError');
            }

            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }

        $result = $this->getSettingByID($type, $settingID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function update ($type, $nameOrID, $reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Value' => array('skipIfUnset'),
            'Label' => array('skipIfUnset'),
            'Status' => array('skipIfUnset'),
            'Type' => array('string')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];
                if (!empty($validatedValues)) {
                    $app->getDB()->beginTransaction();
                    if (is_numeric($nameOrID)) {
                        $configSettingUpdate = dbquery::shopUpdateSetting($nameOrID, $validatedValues);
                    } else {
                        $configSettingUpdate = dbquery::shopUpdateSettingByName($nameOrID, $validatedValues);
                    }
                    $app->getDB()->query($configSettingUpdate);
                    $app->getDB()->commit();
                }
                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        if (is_numeric($nameOrID)) {
            $result = $this->findByID($type, $nameOrID);
        } else {
            $result = $this->findByName($type, $nameOrID);
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
            'single' => false,
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
    public function getSettingsFormOrder () {
        return $this->getSettingsByType($this->SETTING_TYPE->FORMORDER);
    }
    public function getSettingsMapFormOrder () {
        $map = array();
        $items = $this->getSettingsFormOrder();
        foreach ($items as $value) {
            $map[$value['Property']] = $value;
        }
        return $map;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // REQUESTS
    // -----------------------------------------------
    // -----------------------------------------------

    public function get (&$resp, $req) {
        $typeObj = $this->getVerifiedSettingsTypeObj($req);
        
        if (!empty($req->get['id'])) {
            $resp = $this->getSettingByID($typeObj->type, $req->get['id']);
        } else {
            if (empty($typeObj->type)) {
                $resp = $this->toList();
            } else {
                $resp = $this->getSettingsByType($typeObj->type);
            }
        }
    }

    public function post (&$resp, $req) {
        // if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create')) {
        //     $resp['error'] = "AccessDenied";
        //     return;
        // }
        $typeObj = $this->getVerifiedSettingsTypeObj($req);
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
        // }
    }

    public function patch (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['type'])) {
            $resp['error'] = 'MissedParameter_type';
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $settingID = intval($req->get['id']);
            if (!isset($req->get['id']) && isset($req->get['name'])) {
                $settingID = $req->get['name'];
            }
            $resp = $this->update($req->get['type'], $settingID, $req->data);
        }
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
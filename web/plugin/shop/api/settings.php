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

    public $SETTING_TYPE = array(
        'ADDRESS' => 'ADDRESS',
        'ALERTS' => 'ALERTS',
        'EXCHANAGERATES' => 'EXCHANAGERATES',
        'OPENHOURS' => 'OPENHOURS',
        'FORMORDER' => 'FORMORDER',
        'WEBSITE' => 'WEBSITE',
        'MISC' => 'MISC',
        'PRODUCT' => 'PRODUCT'
    );

    // -----------------------------------------------
    // -----------------------------------------------
    // SETTINGS
    // -----------------------------------------------
    // -----------------------------------------------
    public function findByID ($id) {
        global $app;
        if (empty($id) || !is_numeric($id))
            return null;
        $config = dbquery::shopGetSettingByID($id);
        $setting = $app->getDB()->query($config);
        return $this->__adjustSettingItem($setting);
    }

    public function findByName ($name) {
        global $app;
        $config = dbquery::shopGetSettingByName($name);
        $setting = $app->getDB()->query($config);
        return $this->__adjustSettingItem($setting);
    }

    public function getSettingsByType ($type) {
        global $app;
        $config = dbquery::shopGetSettingByType($type);
        $settings = $app->getDB()->query($config);
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
        $setting['_isActive'] = $setting['Status'] === 'ACTIVE';
        $setting['_isRemoved'] = $setting['Status'] === 'REMOVED';
        return $setting;
    }

    public function toList (array $options = array()) {
        global $app;
        $config = dbquery::shopGetSettingsList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $settingItem) {
                    $_items[] = $self->findByID($settingItem['ID']);
                }
                return $_items;
            }
        );
        $dataList = $app->getDB()->getDataList($config, $options, $callbacks);
        $dataList['availableConversions'] = API::getAPI('shop:exchangerates')->getAvailableConversionOptions();
        $dataList['availableMutipliers'] = API::getAPI('shop:exchangerates')->getActiveRateMultipliers();
        return $dataList;
    }

    public function create ($reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        $settingID = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Property' => array('string'),
            'Value' => array('skipIfUnset'),
            'Label' => array('skipIfUnset'),
            'Type' => array('string')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {
                $validatedValues = $validatedDataObj['values'];
                $CustomerID = $app->getSite()->getRuntimeCustomerID();
                $validatedValues["CustomerID"] = $CustomerID;

                $config = dbquery::shopCreateSetting($validatedValues);

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
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->findByID($settingID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function update ($nameOrID, $reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Value' => array('skipIfUnset'),
            'Label' => array('skipIfUnset'),
            'Status' => array('skipIfUnset')
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
            $result = $this->findByID($nameOrID);
        } else {
            $result = $this->findByName($nameOrID);
        }
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function remove ($settingID) {
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

    // -----------------------------------------------
    // -----------------------------------------------
    // WRAPPERS
    // -----------------------------------------------
    // -----------------------------------------------

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
        $data = $req->get;
        if (!API::getAPI('system:auth')->ifYouCan('Admin')) {
            $data['_fStatus'] = "ACTIVE";
        }
        if (!empty($data['id'])) {
            $resp = $this->findByID($data['id']);
        } else if (!empty($data['name'])) {
            $resp = $this->findByName($data['name']);
        } else {
            $resp = $this->toList($data);
        }
    }

    public function post (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $prop = null;
        if (isset($req->data['Property'])) {
            $prop = $this->findByName($req->data['Property']);
        }
        if (empty($prop)) {
            $resp = $this->create($req->data);
        } else {
            $resp = $this->update($prop['ID'], $req->data);
        }
        // $this->_getOrSetCachedState('changed:settings', true);
    }

    public function patch (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $settingID = intval($req->get['id']);
            if (!isset($req->get['id']) && isset($req->get['name'])) {
                $settingID = $req->get['name'];
            }
            $resp = $this->update($settingID, $req->data);
            // $this->_getOrSetCachedState('changed:setting', true);
        }
    }

    public function delete (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $settingID = intval($req->get['id']);
            $resp = $this->remove($settingID);
            // $this->_getOrSetCachedState('changed:setting', true);
        }
    }

}

?>
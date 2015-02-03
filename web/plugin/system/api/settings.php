<?php
namespace web\plugin\system\api;

use \engine\lib\api as API;
use \engine\lib\validate as Validate;
use Exception;

class settings {

    // public static function getNewCustomersSettings (array $customSettings = array()) {
    //     $settings = array(
    //         "plugins" => "system",
    //         "title" => "default site title",
    //         "lang" => "en-US",
    //         "locale" => "en",
    //         "host" => "localhost",
    //         "scheme" => "http",
    //         "homepage" => "http://localhost"
    //     );
    //     return array_merge($settings, $customSettings);
    // }

    public function getSettingsForRuntimeCustomer () {
        $CustomerID = API::getApi('system:customers')->getRuntimeCustomerID();
        return $this->getSettingsByCustomerID($CustomerID);
    }

    public function getSettingsByCustomerID ($CustomerID) {
        global $app;
        $config = dbquery::getCustomerSettingsByCustomerID($CustomerID);
        $setting = $app->getDB()->query($config, false);
        return $this->_adjustSettings($setting);
    }

    public function getSettingsByID ($SettingsID) {
        global $app;
        $config = dbquery::getCustomerSettingsByID($SettingsID);
        $setting = $app->getDB()->query($config, false);
        return $this->_adjustSettings($setting);
    }

    public function createSettings ($reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        $SettingsID = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'CustomerID' => array('int', 'min' => 0),
            'Title' => array('string', 'skipIfUnset', 'max' => 200),
            'Plugins' => array('string', 'skipIfUnset', 'max' => 500),
            'Lang' => array('string', 'skipIfUnset', 'max' => 50),
            'Locale' => array('string', 'skipIfUnset', 'max' => 10),
            'Host' => array('string', 'skipIfUnset', 'max' => 100),
            'Protocol' => array('string', 'skipIfUnset', 'max' => 10),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 200)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $app->getDB()->beginTransaction();

                $configCreateSettings = dbquery::createCustomerSettings($validatedValues);
                $SettingsID = $app->getDB()->query($configCreateSettings, false) ?: null;

                if (empty($SettingsID))
                    throw new Exception('SettingsCreateError');

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        if ($success && !empty($SettingsID))
            $result = $this->getSettingsByID($SettingsID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateSettings ($SettingsID, $reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Title' => array('string', 'skipIfUnset', 'max' => 200),
            'Plugins' => array('string', 'skipIfUnset', 'max' => 500),
            'Lang' => array('string', 'skipIfUnset', 'max' => 50),
            'Locale' => array('string', 'skipIfUnset', 'max' => 10),
            'Host' => array('string', 'skipIfUnset', 'max' => 100),
            'Protocol' => array('string', 'skipIfUnset', 'max' => 10),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 200)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $app->getDB()->beginTransaction();

                $configUpdateSettings = dbquery::updateCustomerSettings($SettingsID, $validatedValues);
                $app->getDB()->query($configUpdateSettings, false);

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getSettingsByID($SettingsID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    private function _adjustSettings (&$settings) {
        $settings['ID'] = intval($settings['ID']);
        $settings['CustomerID'] = intval($settings['CustomerID']);
        $settings['Plugins'] = explode(",", $settings['Plugins']);
        return $settings;
    }

    public function get (&$resp, $req) {
        if (empty($req->get['id'])) {
            $resp = $this->getSettingsForRuntimeCustomer($req->get);
        } else {
            if (is_numeric($req->get['id'])) {
                $SettingsID = intval($req->get['id']);
                $resp = $this->getCategoryByID($SettingsID);
            }
        }
        if (isset($req->get['customerid']) && is_numeric($req->get['customerid'])) {
            $SettingsID = intval($req->get['customerid']);
            $resp = $this->getCategoryByID($SettingsID);
        }
    }

    public function post (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Manage') && !API::getAPI('system:auth')->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createSettings($req->data);
    }

    public function patch (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Manage') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $SettingsID = intval($req->get['id']);
            $resp = $this->updateSettings($SettingsID, $req->data);
        }
    }
}

?>
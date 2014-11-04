<?php
namespace web\plugin\shop\api;

use \engine\lib\validate as Validate;
class settings extends \engine\object\api {

    function __construct ($customer, $plugin, $pluginName, $app) {
        parent::__construct($customer, $plugin, $pluginName, $app);
        $this->SETTING_TYPE = (object) array(
            'ADDRESS' => 'ADDRESS',
            'ALERTS' => 'ALERTS',
            'EXCHANAGERATES' => 'EXCHANAGERATES',
            'OPENHOURS' => 'OPENHOURS',
            'FORMORDER' => 'FORMORDER',
            'WEBSITE' => 'WEBSITE',
            'MISC' => 'MISC',
            'PRODUCT' => 'PRODUCT'
        );
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // SETTINGS
    // -----------------------------------------------
    // -----------------------------------------------
    public function findByID ($id) {
        if (empty($id) || !is_numeric($id))
            return null;
        $config = $this->getPluginConfiguration()->data->jsapiShopGetSettingByID($id);
        $setting = $this->getCustomer()->fetch($config);
        return $this->__adjustSettingItem($setting);
    }

    public function findByName ($name) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetSettingByName($name);
        $setting = $this->getCustomer()->fetch($config);
        return $this->__adjustSettingItem($setting);
    }

    public function getSettingsByType ($type) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetSettingByType($type);
        $settings = $this->getCustomer()->fetch($config);
        foreach ($settings as $key => $value) {
            $settings[$key] = $this->__adjustSettingItem($value);
        }
        return $settings;
    }

    private function __adjustSettingItem ($setting) {
        if (empty($setting))
            return null;
        $setting['ID'] = intval($setting['ID']);
        $setting['_isActive'] = $setting['Status'] === 'ACTIVE';
        $setting['_isRemoved'] = $setting['Status'] === 'REMOVED';
        return $setting;
    }

    public function toList (array $options = array()) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetSettingsList($options);
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
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function create ($reqData) {
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
                $CustomerID = $this->getCustomer()->getCustomerID();
                $validatedValues["CustomerID"] = $CustomerID;

                $config = $this->getPluginConfiguration()->data->jsapiShopCreateSetting($validatedValues);

                $this->getCustomerDataBase()->beginTransaction();

                $settingID = $this->getCustomer()->fetch($config) ?: null;

                if (empty($settingID)) {
                    throw new Exception('ProductCreateError');
                }

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
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
                    $this->getCustomerDataBase()->beginTransaction();
                    if (is_numeric($nameOrID)) {
                        $configSettingUpdate = $this->getPluginConfiguration()->data->jsapiShopUpdateSetting($nameOrID, $validatedValues);
                    } else {
                        $configSettingUpdate = $this->getPluginConfiguration()->data->jsapiShopUpdateSettingByName($nameOrID, $validatedValues);
                    }
                    $this->getCustomer()->fetch($configSettingUpdate);
                    $this->getCustomerDataBase()->commit();
                }
                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
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
        $result = array();
        $errors = array();
        $success = false;

        try {
            $this->getCustomerDataBase()->beginTransaction();
            $config = $this->getPluginConfiguration()->data->jsapiShopRemoveSetting($settingID);
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
        if (!$this->getCustomer()->ifYouCan('Admin')) {
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
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->create($req->data);
        // $this->_getOrSetCachedState('changed:settings', true);
    }

    public function patch (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
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
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
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
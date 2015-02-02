<?php
namespace web\plugin\system\api;

class settings {

    public static function getNewCustomersSettings (array $customSettings = array()) {
        $settings = array(
            "plugins" => "system",
            "title" => "default site title",
            "lang" => "en-US",
            "locale" => "en",
            "host" => "localhost",
            "scheme" => "http",
            "homepage" => "http://localhost"
        );
        return array_merge($settings, $customSettings);
    }

    public function getSettings ($CustomerID) {
        global $app;
        $config = dbquery::getNewCustomersSettings($CustomerID);
        $setting = $app->getDB()->query($config, false);
        return $this->_adjustSettings($setting);
    }

    public function createSettings ($CustomerID, $settings = array()) {
        global $app;
        $settings = self::getNewCustomersSettings($settings);
        $settings['CustomerID'] = $CustomerID;
        $query = dbquery::createCustomerSettings($settings);
        $app->getDB()->query($query) ?: null;
        return $this->_adjustSettings($setting);
    }

    public function updateSettings ($CustomerID, $settings = array()) {
        global $app;
        $settings = self::getNewCustomersSettings($settings);
        $query = dbquery::updateCustomerSettings($CustomerID, $settings);
        $app->getDB()->query($query) ?: null;
        return $this->_adjustSettings($setting);
    }

    private function _adjustSettings ($settingsRaw) {
        $settings = array();
        foreach ($settingsRaw as $key => $value) {
            $settings[$value['Property']] = $value['Value'];
        }
        return $settings;
    }

}

?>
<?php
namespace static_\plugins\system\api;

use \engine\lib\path as Path;
use \engine\lib\api as API;

class plugins {

    public function getInstalledPlugins () {
        $pathToPlugins = Path::getPluginDir();
        $allPluginsPaths = array_filter(glob($pathToPlugins . '*'), 'is_dir');
        $allPlugins = array();
        foreach ($allPluginsPaths as $pathToPlugin) {
            $allPlugins[] = basename($pathToPlugin);
        }
        return $allPlugins;
    }

    public function get (&$resp) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->getInstalledPlugins();
    }

    public function getPlugnisPermissons () {
        $apiCustomer = API::getAPI('system:customers');
        $customer = $apiCustomer->getRuntimeCustomer();
        $plugins = $customer['Plugins'];
        $permissions = array();
        foreach ($plugins as $plgName) {
            $plgSearchApi = API::getApi($plgName . ':permissions');
            if (!empty($plgSearchApi)) {
                $permissions[$plgName] = $plgSearchApi->getPermissions();
                foreach ($permissions[$plgName] as &$value) {
                    $value = $plgName . '_' . $value;
                }
            }
        }
        return $permissions;
    }
}

?>
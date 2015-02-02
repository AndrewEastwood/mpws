<?php
namespace web\plugin\system\api;

use \engine\lib\path as Path;

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
        $resp = $this->getInstalledPlugins();
    }
}

?>
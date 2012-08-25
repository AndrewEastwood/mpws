<?php

// PHP
// PluginManager.
// -----------------------
class libraryPluginManager
{
    protected $_pluginPath;
    protected $_defaultPath;

    /* stored data */
    private $_s_plugins;

    /* dump data info */
    private $_dump_configPaths;

    public function __construct ($doInit = true) {
        $this->_pluginPath = DR . '/web/plugin';
        $this->_defaultPath = DR . '/web/default/' . MPWS_VERSION;

        if ($doInit)
            $this->initAllPlugins();
    }

    public function checkPlugin ($name) {
        $name = strtoupper($name);
        return !empty($this->_s_plugins[$name]);
    }

    public function setPlugin($name, $key, $value) {
        $name = strtoupper($name);
        //echo '<br>| --- set plugin ' . $name;
        $this->_s_plugins[$name][$key] = $value;
        //echo '<br>| ------- added plugin ' . $name . '[  '.count($this->_s_plugins).'  ]';
    }

    public function getPlugin ($name) {
        $name = strtoupper($name);
        //echo '<br>| --------- request for plugin ' . $name;
        if (isset($this->_s_plugins[$name]))
            return $this->_s_plugins[$name];
        return false;
    }
    public function getPluginObj ($name) {
        $name = strtoupper($name);
        //echo '<br>| --------- request for plugin ' . $name;
        if (isset($this->_s_plugins[$name]['obj']))
            return $this->_s_plugins[$name]['obj'];
        return false;
    }
    public function getAllPlugins () {
        //echo '<br>| --------- request for all plugins [  '.count($this->_s_plugins).'  ]';
        return $this->_s_plugins;
    }
    public function runPlugins($action, $context) {
        $_p_results = array();
        //echo 'Executing: ' . $action;
        $_p_caller = explode('@', $action);
        if (count($_p_caller) == 1) {
            
            // move this property to config
            $startupPlugin = $this->getPlugin('TOOLBOX');
            $_p_results['TOOLBOX'] = $startupPlugin['obj']->$action($context, $startupPlugin);

            // run all other plugins
            foreach ($this->_s_plugins as $_name => $_plugin)
                if($_name != 'TOOLBOX') {
                    //echo $_name . ' with action ' . $action . '<br>';
                    $_p_results[$_name] = $_plugin['obj']->$action($context, $_plugin);
                }
            //echo ' for all|||     ';
            /*foreach ($this->_s_plugins as $_name => $_plugin)
                $_p_results[$_name] = $_plugin['obj']->$action($context, $_plugin);
            */
        } elseif (count($_p_caller) == 2) {
            $_name = strtolower($_p_caller[0]);
            $_plugin = $this->getPlugin($_name);
            //echo ' for single|||     ' . $_name;
            
            if (!empty($_plugin['obj'])) {
                $action = $_p_caller[1];
                //echo ' single action is ' . $action;
                $_p_results[$_name] = $_plugin['obj']->$action($context, $_plugin);
            }
        }
        $_results = implode('', $_p_results);
        
        //var_dump($_results);
        
        return $_results;
    }
    public function getPluginConfig ($name, $key) {
        $p = $this->getPlugin($name);
        if (isset($p['config'][$key]))
            return $p['config'][$key];
        return false;
    }
    public function getPluginTemplate ($name, $key) {
        $p = $this->getPlugin($name);
        if (isset($p['templates'][$key]))
            return $p['templates'][$key];
        return false;

    }

    public function loadPlugin ($name, $pItem = false) {
        //echo '<h4>'.$name.'</h4>';
        //echo '------====== loadPlugin 1<br>';
        $loadedObj = $this->getPluginObj($name);
        //var_dump($loadedObj);
        if (!empty($loadedObj))
            return $loadedObj;
        
        //echo '------====== loadPlugin 2<br>';
        if (!empty($name))
            $pItem = $this->_pluginPath . '/'.$name.'/plugin.'.$name.'.php';
        
        //echo '------====== loadPlugin 3<br>';
        if (!file_exists($pItem))
            return false;
        
        //echo '------====== loadPlugin 4<br>';
        include $pItem;
        $matches = null;
        preg_match('/^(\\w+).(\\w+).(\\w+)$/', basename($pItem), $matches);
        $pluginName = trim($matches[1]).trim($matches[2]);
        $pluginNameKey = strtoupper(trim($matches[2]));
        //echo '<br># adding plugin: key ' . $pluginNameKey.' with name ' . $pluginName;
        $obj = new $pluginName();
        
        $this->setPlugin($pluginNameKey, 'key', $pluginNameKey);
        $this->setPlugin($pluginNameKey, 'name', $pluginName);
        $this->setPlugin($pluginNameKey, 'path', $pItem);
        $this->setPlugin($pluginNameKey, 'dir', dirname($pItem));
        $this->setPlugin($pluginNameKey, 'obj', $obj);
        
        //var_dump($obj);
        
        
        return $obj;
    }
    
    
    public function initAllPlugins () {
        $this->loadPluginObjects();
        $this->loadPluginConfigs();
        $this->loadPluginTemplates();
    }

    private function loadPluginObjects () {
        $pFiles = glob($this->_pluginPath . '/*/plugin.*.php');
        // add all plugin
        foreach ($pFiles as $pItem) {
            //echo '<br> loading: '.$pItem;
            $this->loadPlugin(false, $pItem);
        }
        //echo '[added plugins := ' . count($this->_s_plugins);
    }

    public function getConfiguration ($pluginName, $configName) {
        $_plugin = $this->_pluginPath . '/' . strtolower($pluginName) . '/config/'.strtolower($configName).'.php';
        $_default = $this->_defaultPath . '/config/'.strtolower($configName).'.php';
        
        //echo '<br>| PLUGIN: ' . $_plugin;
        //echo '<br>| DEFAULT: ' . $_default;
        
        if (file_exists($_default))
            eval(file_get_contents($_default));
        if (file_exists($_plugin))
            eval(file_get_contents($_plugin));
        
        //echo 'CONFIG NAME: ' . $configName;
        //var_dump($default);
        
        $cfg = false;
        if (!empty($plugin))
            $cfg = array_merge($default, $plugin);
        else
            $cfg = $default;
        
        //var_dump($cfg);
        
        if (isset($cfg[$configName]))
            return $cfg[$configName];
        
        return false;
    }
    
    private function loadPluginConfigs () {
        foreach ($this->_s_plugins as $pItem) {
            // get configuration
            $pConfigFiles = glob($pItem['dir'] . '/config/*.php');
            $configs = array();
            foreach ($pConfigFiles as $_cfile) {
                $_ckey = strtoupper(basename($_cfile, '.php'));
                eval(file_get_contents($_cfile));
                $configs[$_ckey] = $plugin[$_ckey];
                //$this->setPlugin($pluginNameKey, 'obj', new $pluginName());
                //$this->_s_plugins[$pItem['key']]['config'][$_ckey] = $plugin[$_ckey];
                unset($plugin);
            }
            $this->setPlugin($pItem['key'], 'config', $configs);
        }
    }

    private function loadPluginTemplates () {
        foreach ($this->_s_plugins as $pItem) {
            // get configuration
            $pTemplatePath = $pItem['dir'] . '/templates/';
            $templates = libraryFileManager::getAllFilesFromDirectoryAsMap($pTemplatePath, '.html');
            //var_dump($templates);
            
            $this->setPlugin($pItem['key'], 'templates', $templates);
        }
    }

    public function getDump () {
        $dump = '<h2>Plugin Dump:</h2>';
        $dump .= '<br>Total PLugins: ' . count($this->_s_plugins);
        $dump .= '<br>List:';
        $dump .= '<dl>';
        foreach ($this->_s_plugins as $plugin) {
            $dump .= '<dt>' . $plugin['name'] . '</dt>';
            $dump .= '<dd>';
            $dump .= '<h3>Templates:</h3>';
            foreach ($plugin['templates'] as $tk => $tv) {
                $dump .= '<br>[' . $tk . '] ===> ' . $tv;
            }
            $dump .= '<hr size="2"/>';
            $dump .= '<h3>Configs:</h3>';
            foreach ($plugin['config'] as $tk => $tv) {
                $dump .= '<br>[' . $tk . '] ===> <pre>' . print_r($tv, true) . '</pre>';
            }
            $dump .= '</dd>';
        }
        $dump .= '</dl>';

        return $dump;
    }

}

?>

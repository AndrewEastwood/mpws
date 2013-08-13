<?php

// PHP
// PluginManager.
// -----------------------
class libraryPluginManager
{
    protected $_pluginPath;
    protected $_defaultPath;
    protected $_activePluginPattern;
    protected $_activePluginNameRegex;

    /* stored data */
    private $_s_plugins;

    public function __construct ($doInit = true) {
        //echo '__construct';
        $this->_pluginPath = glGetFullFilePath('web', 'plugin');
        $this->_defaultPath = glGetFullFilePath('web', 'default', MPWS_VERSION);
        $this->_activePluginPattern = $this->_pluginPath . glGetFilePath('*', 'config', 'object.ini');
        $this->_activePluginNameRegex = '/(\\w+).config.object\.ini$/';

        if ($doInit)
            $this->initAllPlugins();
    }

    public function setContext ($context) {
        foreach ($this->_s_plugins as $pItem){
            //var_dump($pItem);
            if (method_exists($pItem['obj'], 'setContext'))
                $pItem['obj']->setContext($context);
        }
    }
    
    public function getAvailablePluginNames () {
        $enabledPluginList = glob($this->_activePluginPattern);
        debug($enabledPluginList, 'getAvailablePluginNames');
        $plugins = array();
        for ($i = 0, $len = count($enabledPluginList); $i < $len; $i++) {
            $matches = null;
            preg_match($this->_activePluginNameRegex, $enabledPluginList[$i], $matches);
            debug ($matches, 'plugin matches');
            if (isset($matches) && isset($matches[1]))
                $plugins[] = $matches[1];
        }
        return $plugins;
    }

    public function getPluginWithContext ($name) {
        if (empty($name))
            throw new Exception('MPWS PluginManager library: empty plugin name');

        // return already loaded object
        if (!empty($this->_s_plugins[makeKey($name, true)])) {
            debug('libraryPluginManager: getPluginWithContext Return Existed Plogin Object: ' . $name);
            return $this->_s_plugins[makeKey($name, true)];
        }
        
        $pluginFileName = OBJECT_T_PLUGIN.DOT.$name.EXT_SCRIPT;
        $pluginFilePath = $this->_pluginPath . DS . $name . DS . $pluginFileName;
       
        debug('libraryPluginManager: getPluginWithContext plugin path: ' . $pluginFilePath);

        if (!file_exists($pluginFilePath))
            throw new Exception('MPWS PluginManager library: path does not exists: ' . $pluginFilePath);
        
        // load plugin
        include $pluginFilePath;
        $matches = null;
        preg_match('/^(\\w+).(\\w+).(\\w+)$/', $pluginFileName, $matches);
        $pluginObjectName = trim($matches[1]).trim($matches[2]);
        
        debug('libraryPluginManager: getPluginWithContext plugin name: ' . $pluginObjectName);
        
        // store plugin and init with plugin name
        $this->_s_plugins[makeKey($name, true)] = new $pluginObjectName($matches[2]);
        
        // return plugin
        return $this->_s_plugins[makeKey($name, true)];
    }
    
    public function runPluginAsync ($command) {
        debug($command, 'libraryPluginManager: runPluginAsync action:');
        $feedbacks = array();
        // get requested plugin name
        //list($caller, $fn) = explode('@', $action);
        $pluginNames = $this->getAvailablePluginNames();
        // wide command
        if ($command->getCaller() == '*') {
            // send broadcast message
            //echo 'BROADCAST RUN';
            foreach ($pluginNames as $name) {
                // get plugin object
                debug('libraryPluginManager: runPluginAsync => running plugin ' . $name);
                $plugin = $this->getPluginWithContext($name);
                //var_dump($plugin);
                // send message
                $feedbacks[] = $plugin->run($command);
            }
        } else {
            //echo 'SINGLE RUN';
            $_caller = $command->getCaller();
            // get specific caller (plugin)
            // skip if inactive
            if (isset($pluginNames[$_caller])) {
                // get plugin object
                debug('libraryPluginManager: runPluginAsync => running plugin ' . $_caller);
                $plugin = $this->getPluginWithContext($_caller);
                // send message
                $feedbacks[] = $plugin->run($command);
            }
        }
        //return implode('', $feedbacks);
        return $feedbacks;
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

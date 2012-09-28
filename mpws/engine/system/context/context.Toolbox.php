<?php

class contextToolbox extends objectContext  {

    /* We store plugins inside toolbox context
     * The Customer Context object has an access to current context
     */
    private $_pluginManager;

    function __construct () {
        debug('contextToolbox __construct');
        $this->_pluginManager = new libraryPluginManager(false);
    }
    
    final public function call ($command) {
        debug('contextToolbox => Running command: ' . $command[makeKey('method')]);
        return $this->_pluginManager->runPluginAsync($command);
    }
    
    // simple bridge to libraryPluginManager->getPLuginWithContext
    final public function getObject ($name) {
        debug('contextToolbox => getPlugin: ' . $name);
        return $this->_pluginManager->getPluginWithContext($name);
    }
    
    final public function getAllObjects () {
        $names = $this->_pluginManager->getAllEnabledPluginNames($name);
        $objects[] = array();
        foreach ($names as $name)
            $objects[$name] = $this->getObject($name);
        return $objects;
    }

}

?>

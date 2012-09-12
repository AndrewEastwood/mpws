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
        $this->_pluginManager->runPluginAsync($command);
    }
    
}

?>

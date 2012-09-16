<?php

class objectExtWithConfiguration extends objectExtension  {
    
    public function __construct($baseMetaInit) {
        parent::__construct($baseMetaInit);
        debug('objectExtWithConfiguration', '__construct', true);
    }


    public function getConfigurationValue ($metapath) {
        debug('objectExtWithConfiguration: getConfigurationValue: ' . $metapath);
        list($configFileName, $configKey) = explode(DOT, $metapath);
        // get config paths
        $chains = libraryConfigurationManager::getObjectConfigurationChain($configFileName, $this->_baseMeta);
        // read value by key
        
        $resValue = libraryConfigurationManager::getConfigurationValue($chains, $configKey);
        return $resValue;
    }
    
}

?>
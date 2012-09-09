<?php

class objectExtWithConfiguration  {
    /* base object */
    private $_baseMeta;
    
    public function __construct ($baseMetaInit) {
        debug('objectExtWithConfiguration', '__construct', true);
        $this->_baseMeta = $baseMetaInit[0];
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
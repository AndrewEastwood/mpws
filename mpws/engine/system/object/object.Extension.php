<?php

class objectExtension {
    /* base object */
    protected $_baseMeta;
    
    public function __construct ($baseMetaInit) {
        debug($baseMetaInit, 'objectExtension => __construct');
        $this->updateExtension($baseMetaInit);
    }
    
    public function updateExtension ($baseMetaInit) {
        debug($baseMetaInit, 'objectExtension => updateExtension');
        $this->_baseMeta = $baseMetaInit[0];
    }
    
}

?>

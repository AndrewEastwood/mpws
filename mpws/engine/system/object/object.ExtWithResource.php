<?php

class objectExtWithResource {
    
    const TEMPLATE = 'template';
    const MACRO = 'macro';
    const PROPERTY = 'property';
    
    private $_ctx;
    
    public function __construct ($context) {
        $this->_ctx = $context;
    }
    
    public function getResource ($type, $metapath) {
        $res = false;
        switch (strtolower($type)) {
            case 'template':
                $res = libraryStaticResourceManager::getTemplate();
        }
    }
    
}

?>
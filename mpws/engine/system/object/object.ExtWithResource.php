<?php

class objectExtWithResource {
    
    const TEMPLATE = 'template';
    const MACRO = 'macro';
    const PROPERTY = 'property';
    
    /* base object */
    private $_baseMeta;
    
    public function __construct ($baseMetaInit) {
        debug('objectExtWithResource', '__construct', true);
        $this->_baseMeta = $baseMetaInit[0];
    }
    
    public function getResourcePath ($type, $metapath) {
        debug('objectExtWithResource => getResource: ' . $type . ', ' . $metapath);
        debug($this->_baseMeta);
        $res = false;
        switch (strtolower($type)) {
            case 'template':
                $res = libraryStaticResourceManager::getObjectTemplatePath($metapath, $this->_baseMeta);
                break;
            case 'property':
                list($propFileName, $propKey) = explode(DOT, $metapath);
                $res = libraryStaticResourceManager::getObjectPropertyPath($propFileName, $this->_baseMeta);
                break;
        }
        return $res;
    }
    
    public function getResourceValue ($type, $metapath) {
        debug('objectExtWithResource => getResourceValue: ' . $type . ', ' . $metapath);
        $resPath = $this->getResourcePath ($type, $metapath);
        $resValue = false;
        switch (strtolower($type)) {
            case 'template':
                $resValue = libraryStaticResourceManager::getTemplateValue($resPath);
                break;
            case 'property':
                list($propFileName, $propKey) = explode(DOT, $metapath);
                $resValue = libraryStaticResourceManager::getPropertyValue($resPath, $propKey);
                break;
        }
        return $resValue;
    }

}

?>
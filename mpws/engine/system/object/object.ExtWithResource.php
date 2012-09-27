<?php

class objectExtWithResource extends objectExtension {
    
    const TEMPLATE = 'template';
    const MACRO = 'macro';
    const PROPERTY = 'property';
    
    public function __construct($baseMetaInit) {
        parent::__construct($baseMetaInit);
        debug('objectExtWithResource', '__construct', true);
    }

    public function getResourcePath ($type, $metapath) {
        debug('objectExtWithResource => getResource: ' . $type . ', ' . $metapath);
        debug($this->_baseMeta);
        list($owner, $section, $key) = explode(DOT, $metapath);
        $useStandartPath = empty($key);
        $res = false;
        switch (strtolower($type)) {
            case 'template':
                if ($useStandartPath)
                    $res = libraryStaticResourceManager::getObjectTemplatePath($metapath, $this->_baseMeta);
                else {
                    // temporary override PATH_OWN
                    //$_path_own = $this->_baseMeta['PATH_OWN'];
                    
                }
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
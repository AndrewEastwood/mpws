<?php

/* Web Object Has The Following Features:
 *   configuration
 *   templates
 *   properties
 *   setup scripts
 */

class objectBaseWeb extends objectBase {
    
    protected function objectCustomSetup() {
        // setup locale
        //$this->setObjectLocale($this->objectConfiguration['DISPLAY']['LOCALE']);

        // setup meta object
        // non "en_us" locale attribute must be changed at this point
        $this->setMeta('PATH_WEB', DR . '/web/customer/' . MPWS_CUSTOMER);
        $this->setMeta('PATH_DEF', DR . '/web/default/' . MPWS_VERSION);
        // do not use identical paths for WEB and OWN
        //if ($this->getObjectType() !== OBJECT_T_CUSTOMER)
        $this->setMeta('PATH_OWN', DR . '/web/' . $this->getObjectType() . DS . $this->getMeta('NAME'));
        
        // use storage to store
        // templates, config and properties
        $this->setExtender('objectExtWithStorage', '_ex_store');
        $this->setExtender('objectExtWithResource', '_ex_resource');
        $this->setExtender('objectExtWithConfiguration', '_ex_config');

        //var_dump($this->getMeta());
        
        // different versions
        if (MPWS_VERSION != $this->objectConfiguration_customer_version) {
            $this->setMeta('PATH_DEF', DR . '/web/default/' . $this->objectConfiguration_customer_version);
            $this->updateExtenders();
        }
        
       
    }

    /* extender overrides */
    final protected function objectCustomProperty($name) {
        if (startsWith($name, 'objectConfiguration'))
            return $this->getConfiguration(str_replace(array('objectConfiguration_', '_'), array('', '.'), $name));
        if (startsWith($name, 'objectTemplatePath'))
            return $this->getTemplatePath(str_replace(array('objectTemplatePath_', '_'), array('', '.'), $name));
        if (startsWith($name, 'objectProperty'))
            return $this->getProperty(str_replace(array('objectProperty_', '_'), array('', '.'), $name));

        return parent::objectCustomProperty($name);
    }

    /* resource */
    protected function getConfiguration ($metapath) {
        debug('objectBaseWeb: getConfiguration: ' . $metapath);
        $_kp = $this->_ex_store__keyPathConfiguration($metapath);
        $_cache = $this->_ex_store__storeGet($_kp);
        if (!empty($_cache)) {
            debug('objectBaseWeb: getConfiguration: Get Configuration From Cache: ' . $metapath);
            return $_cache;
        }
        $resValue = $this->_ex_config__getConfigurationValue($metapath);
        debug('objectBaseWeb: getConfiguration: Downloaded Configuration: ' . $metapath);
        $this->_ex_store__storeSet($_kp, $resValue);
        return $resValue;
    }
    protected function getTemplatePath ($metapath) {
        debug('objectBaseWeb: getTemplatePath: ' . $metapath);
        $_kp = $this->_ex_store__keyPathTemplate($metapath);
        $_cache = $this->_ex_store__storeGet($_kp);
        if (!empty($_cache)) {
            debug('objectBaseWeb: getTemplatePath: Get Template From Cache: ' . $metapath);
            return $_cache;
        }
        $resPath = $this->_ex_resource__getResourcePath(objectExtWithResource::TEMPLATE, $metapath);
        debug('objectBaseWeb: getTemplatePath: Downloaded Template: ' . $metapath);
        $this->_ex_store__storeSet($_kp, $resPath);
        return $resPath;
    }
    protected function getProperty ($metapath) {
        debug('objectBaseWeb: getProperty: ' . $metapath);
        $_kp = $this->_ex_store__keyPathProperty($metapath);
        $_cache = $this->_ex_store__storeGet($_kp);
        if (!empty($_cache)) {
            debug('objectBaseWeb: getProperty: Get Property From Cache: ' . $metapath);
            return $_cache;
        }
        $resValue = $this->_ex_resource__getResourceValue(objectExtWithResource::PROPERTY, $metapath);
        debug('objectBaseWeb: getProperty: Read Property: ' . $resValue . ' from ' . $metapath);
        $this->_ex_store__storeSet($_kp, $resValue);
        return $resValue;
    }
    
}

?>
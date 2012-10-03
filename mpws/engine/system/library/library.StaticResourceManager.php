<?php

class libraryStaticResourceManager {

    // get merged styling for specific customer
    public function GetStaticContent ($realm) {

        $c = MPWS_CUSTOMER;
        $v = MPWS_VERSION;
        $p = strtolower($_SESSION['MPWS_PLUGIN_ACTIVE']);
        
        // requested file name
        $name = $_GET['name'] . DOT . $_GET['type'];
        
        // saved resource path
        $resourceFileDir = DR . '/data/bin';
        $resourceFilePath = $resourceFileDir . '/'.$realm.'_'.$name;
        
        // get already saved file
        if (!file_exists($resourceFileDir))
            mkdir($resourceFileDir, 0777, true);
        elseif (file_exists($resourceFilePath) && !(isset($_GET['force']) || MPWS_ENV === 'DEV'))
            return file_get_contents($resourceFilePath);
        
        // get configurtion
        $cfg = false;
        switch ($realm) {
            case 'toolbox':
                $obj = new libraryPluginManager(false);
                $cfg = $obj->getConfiguration($p, 'RESOURCES');
                //echo 'use toolbox resources';
                break;
            case 'mpws':
                //echo 'use customer resources';
                $obj = new libraryCustomerManager($c, false);//getConfig('RESOURCES');
                $cfg = $obj->getCustomerConfiguration('RESOURCES');
                break;
        }
        
        
        //echo '<br>| Loading: ' . $name;
        //var_dump($cfg);
        //var_dump($cfg['STATIC'][$name]);
        //echo $_SESSION['MPWS_PLUGIN_ACTIVE'];
        
        if (empty($cfg['STATIC']) || empty($cfg['STATIC'][$name]))
            return false;
        
        $filesToLoad = array();
        $filesToImport = array();
        
        // walk by resource
        foreach ($cfg['STATIC'][$name] as $origin => $resFiles) {
            switch (strtoupper($origin)) {
                case 'DEFAULT':
                    foreach ($resFiles as $filePath)
                        if (file_exists(DR . '/web/default/'.$v.'/resource/'.$filePath))
                            $filesToLoad[] = DR . '/web/default/'.$v.'/resource/'.$filePath;
                    break;
                case 'OWNER':
                    //echo '<br>|Customer file ' . print_r($resFiles, true) ;
                    if ($realm == 'mpws') {
                    foreach ($resFiles as $filePath)
                        if (file_exists(DR . '/web/customer/'.$c.'/resource/'.$filePath))
                            $filesToLoad[] = DR . '/web/customer/'.$c.'/resource/'.$filePath;
                    } elseif ($realm == 'toolbox') {
                    foreach ($resFiles as $filePath)
                        if (file_exists(DR . '/web/plugin/'.$p.'/resource/'.$filePath))
                            $filesToLoad[] = DR . '/web/plugin/'.$p.'/resource/'.$filePath;
                    }
                    break;
                case 'AUTO':
                    foreach ($resFiles as $filePath) {
                        if (file_exists(DR . '/web/customer/'.$c.'/resource/'.$filePath))
                            $filesToLoad[] = DR . '/web/customer/'.$c.'/resource/'.$filePath;
                        elseif (file_exists(DR . '/web/default/'.$v.'/resource/'.$filePath))
                            $filesToLoad[] = DR . '/web/default/'.$v.'/resource/'.$filePath;
                    }
                    break;
                case 'IMPORT':
                    $filesToImport = $resFiles;
                    break;
            }
        }
        
        //var_dump($filesToLoad);
        
        // set metainfo
        $metainfo = '/* ['.date('Y-m-d H:i:s').'] MPWS Packages: ' . PHP_EOL . ' * ' . 
                ((MPWS_ENV === 'DEV')?implode(';' . PHP_EOL . ' * ', $filesToLoad):'') . PHP_EOL . ' */' . 
                PHP_EOL . PHP_EOL . PHP_EOL;
        $lineBreak = PHP_EOL.'/*'.str_pad('', 25, '*').' line break '.str_pad('', 25, '*').'*/'.PHP_EOL;
        
        // read all files
        $data = '';
        foreach ($filesToLoad as $item) {
            //echo '/*' . $item . '*/';
            $data .= (file_get_contents($item) . $lineBreak);
        }
        
        // add import files (CSS only !!!!)
        if (!empty($filesToImport))
            foreach ($filesToImport as $item)
                $metainfo .= PHP_EOL . '@import url(\'' . $item . '\');';
        
        // compress data and store package
        if (MPWS_ENV === 'PROD') {
            if ($_GET['type'] == 'js') {
                $data = libraryMinifyJSCompressor::minify($data);
                
                file_put_contents($resourceFilePath, $metainfo  . $data);
            }
            if ($_GET['type'] == 'css') {
                $data = libraryMinifyCSSCompressor::process($data);
                file_put_contents($resourceFilePath, $metainfo  . $data);
            }
        }
        
        
        return $metainfo . $data;
    }

    public function GetContent ($name, $realm, $owner = '') {

        $c = MPWS_CUSTOMER;
        $v = MPWS_VERSION;
        
        //$owner = empty($owner)? MPWS_CUSTOMER : $owner;
        
        // default files
        $default = DR . '/web/default/'.$v.'/resource/' . $name;
        //if ($realm === 'toolbox') 
        //    $realmSource = DR . '/web/plugin/'.$owner.'/resources/' . $name;
        //if ($realm === 'mpws')
            $realmSource = DR . '/web/customer/'.$c.'/resource/' . $name;

        //echo $realm;
        //echo $realmSource;
        // echo $default;
        
        if (file_exists($realmSource))
            return $realmSource;
        if (file_exists($default))
            return $default;
        return null;
    }
    
    public static function getObjectTemplatePath ($resourceName, $objectMeta) {
        if (empty($objectMeta['TYPE']) &&
            empty($objectMeta['NAME']) &&
            empty($objectMeta['PATH_WEB']) &&
            empty($objectMeta['PATH_OWN']) &&
            empty($objectMeta['PATH_DEF']))
        throw new Exception('libraryStaticResourceManager: getObjectTemplatePath: Wrong $objectMeta passed');
        return self::getTemplatePath($objectMeta['TYPE'], $objectMeta['NAME'], $resourceName, $objectMeta);
    }
    
    public static function getObjectPropertyPath ($resourceName, $objectMeta) {
        if (empty($objectMeta['TYPE']) &&
            empty($objectMeta['NAME']) &&
            empty($objectMeta['LOCALE']) &&
            empty($objectMeta['PATH_WEB']) &&
            empty($objectMeta['PATH_OWN']) &&
            empty($objectMeta['PATH_DEF']))
        throw new Exception('libraryStaticResourceManager: getObjectPropertyPath: Wrong $objectMeta passed');
        return self::getPropertyPath($objectMeta['TYPE'], $objectMeta['NAME'], $resourceName, $objectMeta['LOCALE'], $objectMeta);
    }

    public static function getTemplatePath ($owner, $name, $resourceName, $preDefinedPaths = array()) {
        debug('libraryStaticResourceManager', 'getTemplatePath', true);
        $useParent = false;
        if (strstr($resourceName, COLON))
            list($resourceName, $useParent) = explode(COLON, $resourceName);
        $resPath = 'template' . DS . str_replace(DOT, DS, $resourceName) . '.html';
        $_default  = DR . '/web/default/' . MPWS_VERSION . DS . $resPath;
        if (isset($preDefinedPaths['PATH_DEF']))
            $_default = $preDefinedPaths['PATH_DEF'] . DS . $resPath;
        $_owner = DR . '/web/' . $owner . DS . $name . DS . $resPath;
        if (isset($preDefinedPaths['PATH_OWN']))
            $_owner = $preDefinedPaths['PATH_OWN'] . DS . $resPath;
        $_web = false;
        if (isset($preDefinedPaths['PATH_WEB']))
            $_web = $preDefinedPaths['PATH_WEB'] . DS . $resPath;
        
        switch(makeKey($useParent)) {
            case 'DEFAULT': {
                if (file_exists($_default))
                    return $_default;
                break;
            }
            case 'WEB': {
                if (file_exists($_web))
                    return $_web;
                break;
            }
            case 'OWNER': {
                if (file_exists($_owner))
                    return $_owner;
                break;
            }
            case 'AUTO':
            default: {
                if (file_exists($_owner))
                    return $_owner;
                if (file_exists($_web))
                    return $_web;
                if (file_exists($_default))
                    return $_default;
                break;
            }
        }
        
        
        var_dump($_owner);
        var_dump($_web);
        var_dump($_default);
        
        throw new Exception('libraryStaticResourceManager: getTemplatePath: requrested template does not exsist: ' . $resourceName);
    }
    public static function getPropertyPath ($owner, $name, $resourceName, $locale = 'en_us', $preDefinedPaths = array()) {
        debug('libraryStaticResourceManager', 'getPropertyPath', true);
        $resPath = 'property' . DS . $locale . DS . str_replace(DOT, DS, $resourceName) . '.property';
        $_default  = DR . '/web/default/' . MPWS_VERSION . DS . $resPath;
        if (isset($preDefinedPaths['PATH_DEF']))
            $_default = $preDefinedPaths['PATH_DEF'] . DS . $resPath;
        $_owner = DR . '/web/' . $owner . DS . $name . DS . $resPath;
        if (isset($preDefinedPaths['PATH_OWN']))
            $_owner = $preDefinedPaths['PATH_OWN'] . DS . $resPath;
        $_web = false;
        if (isset($preDefinedPaths['PATH_WEB']))
            $_web = $preDefinedPaths['PATH_WEB'] . DS . $resPath;
        
        
        $propFiles = array();
        
        //echo $_owner;
        if (file_exists($_owner))
            $propFiles[] = $_owner;
        
        //echo $_web;
        if (file_exists($_web))
            $propFiles[] =  $_web;
        
        //echo $_default;
        if (file_exists($_default))
            $propFiles[] = $_default;

        if (count($propFiles) > 0)
            return $propFiles;
        
        var_dump($_owner);
        var_dump($_web);
        var_dump($_default);
        
        throw new Exception('libraryStaticResourceManager: getPropertyPath: requrested property does not exsist: ' . $resourceName);
    }
    
    public static function getTemplateValue ($templateFilePath, $propKey) {
        debug('libraryStaticResourceManager', 'getTemplateValue', true);
        if (!file_exists($templateFilePath))
            throw new Exception('libraryStaticResourceManager: getTemplateValue: Template file does not exsist: ' . $templateFilePath);
        return file_get_contents($templateFilePath);
    }

    public static function getPropertyValue ($propertyFilePath, $propKey, $fromArray = false) {
        debug('libraryStaticResourceManager', 'getPropertyValue', true);
        // chain props
        if (is_array($propertyFilePath)) {
            foreach ($propertyFilePath as $propertySingleFilePath) {
                 $propertyValue = self::getPropertyValue($propertySingleFilePath, $propKey, true);
                 if ($propertyValue != null)
                     return $propertyValue;
            }
            throw new Exception('libraryStaticResourceManager: getPropertyValue: Requested property key does not exist: ' . $propKey . ' in <pre>' . print_r($propertyFilePath, true) . '</pre>');
        }
        elseif (is_string($propertyFilePath)) {
            if (!file_exists($propertyFilePath))
                throw new Exception('libraryStaticResourceManager: getPropertyValue: Property file does not exsist: ' . $propertyFilePath);
            $props = parse_ini_file($propertyFilePath);
            //debug($props);
            //echo '<br> Reading property file: ' . $propertyFilePath;
            if (isset($props[$propKey]))
                return $props[$propKey];
            if (!$fromArray)
                throw new Exception('libraryStaticResourceManager: getPropertyValue: Requested property key does not exist: ' . $propKey . '; filepath: ' . $propertyFilePath);
            return null;
        } else
            throw new Exception('libraryStaticResourceManager: getPropertyValue: Wrong property file path.');
        
    }
    
}


?>

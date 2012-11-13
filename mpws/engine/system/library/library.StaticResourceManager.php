<?php

class libraryStaticResourceManager {

    // get merged styling for specific customer
    public function GetStaticContent () {

        $c = MPWS_CUSTOMER;
        $v = MPWS_VERSION;
        $p = libraryRequest::getPlugin(false);
        
        // request type
        $isPLugin = !empty($p);
        
        // requested file name
        $name = $_GET['name'] . DOT . $_GET['type'];
        
        // saved resource path
        $resourceFileDir = DR . '/data/bin';
        $resourceFilePath = $resourceFileDir . '/_'.$name;
        
        // get already saved file
        if (!file_exists($resourceFileDir))
            mkdir($resourceFileDir, 0777, true);
        elseif (file_exists($resourceFilePath) && !(isset($_GET['force']) || MPWS_ENV === 'DEV'))
            return file_get_contents($resourceFilePath);
        
        // get context
        $ctx = contextMPWS::instance();
        $resourceEntry = array();
        $requestObjectOwner = false;
        if ($isPLugin)
            $requestObjectOwner = $ctx->contextToolbox->getObject($p);
        else 
            $requestObjectOwner = $ctx->contextCustomer->getObject();
        

        $resourceNamesMap = $requestObjectOwner->objectConfiguration_resources_staticResources;
        //var_dump($resourceNamesMap);
        $resourceEntry = $resourceNamesMap[$name];
        if (empty($resourceEntry))
            return false;
        
        //echo DR;
        
        $filesToLoad = array();
        $filesToImport = array();
        
        // walk by resource
        foreach ($resourceEntry as $origin => $resFiles) {
            switch (strtoupper($origin)) {
                case 'DEFAULT':
                    foreach ($resFiles as $filePath)
                        if (file_exists(DR . 'web/default/'.$v.'/resource/'.$filePath))
                            $filesToLoad[] = DR . 'web/default/'.$v.'/resource/'.$filePath;
                    break;
                case 'OWNER':
                    if ($isPLugin)
                        foreach ($resFiles as $filePath)
                            if (file_exists(DR . '/web/plugin/'.$p.'/resource/'.$filePath))
                                $filesToLoad[] = DR . '/web/plugin/'.$p.'/resource/'.$filePath;
                    else 
                        foreach ($resFiles as $filePath)
                            if (file_exists(DR . 'web/customer/'.$c.'/resource/'.$filePath))
                                $filesToLoad[] = DR . 'web/customer/'.$c.'/resource/'.$filePath;
                    break;
                case 'AUTO':
                    foreach ($resFiles as $filePath) {
                        if ($isPLugin && file_exists(DR . 'web/plugin/'.$p.'/resource/'.$filePath))
                            $filesToLoad[] = DR . 'web/plugin/'.$p.'/resource/'.$filePath;
                        elseif (file_exists(DR . 'web/customer/'.$c.'/resource/'.$filePath))
                            $filesToLoad[] = DR . 'web/customer/'.$c.'/resource/'.$filePath;
                        elseif (file_exists(DR . 'web/default/'.$v.'/resource/'.$filePath))
                            $filesToLoad[] = DR . 'web/default/'.$v.'/resource/'.$filePath;
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
        
        
        // get build mode (for CSS only)
        if ($_GET['type'] == 'css') {
            $buildMode = $requestObjectOwner->objectConfiguration_resources_buildMode;
            //if (isset($_SESSION['MPWS_PLUGIN_ACTIVE']))
            //    echo 'ACTIVE PLUGIN IS = ' . $_SESSION['MPWS_PLUGIN_ACTIVE'];
            switch ($buildMode) {
                case "CSS" : {
                    break;
                }
                case "LESS" : {
                    $libLess = new libraryLessc();
                    $data = $libLess->compile($data);
                    break;
                }
            }
        }
        
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

    public function GetContent () {

        $c = MPWS_CUSTOMER;
        $v = MPWS_VERSION;
        $p = libraryRequest::getPlugin(false);
        
        // requested file name
        $name = $_GET['name'] . DOT . $_GET['type'];
        
        //$owner = empty($owner)? MPWS_CUSTOMER : $owner;
        
        // default files
        $chains[] = DR . 'web/default/'.$v.'/resource/' . $name;
        $chains[] = DR . 'web/customer/'.$c.'/resource/' . $name;
        if (!empty($p))
            $chains[] = DR . 'web/plugin/'.$p.'/resource/' . $name;

        // return first existed file
        foreach ($chains as $resourceFile)
            if (file_exists($resourceFile))
                return $resourceFile;

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
        $_default  = DR . 'web/default/' . MPWS_VERSION . DS . $resPath;
        if (isset($preDefinedPaths['PATH_DEF']))
            $_default = $preDefinedPaths['PATH_DEF'] . DS . $resPath;
        $_owner = DR . 'web/' . $owner . DS . $name . DS . $resPath;
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
        
        
        //var_dump($_owner);
        //var_dump($_web);
        //var_dump($_default);
        
        $_pathTrace = array($_owner, $_web, $_default);
        
        throw new Exception('libraryStaticResourceManager: getTemplatePath: requrested template does not exsist: <pre>' . print_r($_pathTrace, true).'</pre>');
    }
    public static function getPropertyPath ($owner, $name, $resourceName, $locale = 'en_us', $preDefinedPaths = array()) {
        debug('libraryStaticResourceManager', 'getPropertyPath', true);
        $resPath = 'property' . DS . $locale . DS . str_replace(DOT, DS, $resourceName) . '.property';
        $_default  = DR . 'web/default/' . MPWS_VERSION . DS . $resPath;
        if (isset($preDefinedPaths['PATH_DEF']))
            $_default = $preDefinedPaths['PATH_DEF'] . DS . $resPath;
        $_owner = DR . 'web/' . $owner . DS . $name . DS . $resPath;
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
        
        //var_dump($_owner);
        //var_dump($_web);
        //var_dump($_default);
        
        $_pathTrace = array($_owner, $_web, $_default);
        
        throw new Exception('libraryStaticResourceManager: getPropertyPath: requrested property file does not exsist: <pre>' . print_r($_pathTrace, true).'</pre>');
    }
    
    public static function getTemplateValue ($templateFilePath, $propKey) {
        debug('libraryStaticResourceManager', 'getTemplateValue', true);
        if (!file_exists($templateFilePath))
            throw new Exception('libraryStaticResourceManager: getTemplateValue: Template file does not exsist: <b>' . $templateFilePath.'</b>');
        return file_get_contents($templateFilePath);
    }

    public static function getPropertyValue ($propertyFilePath, $propKey, $fromArray = false) {
        debug('libraryStaticResourceManager', 'getPropertyValue', true);
        //echo print_r($propertyFilePath, true);
        // chain props
        
        //var_dump($propertyFilePath);
        if (is_array($propertyFilePath)) {
            foreach ($propertyFilePath as $propertySingleFilePath) {
                //echo $propertySingleFilePath;
                 $propertyValue = self::getPropertyValue($propertySingleFilePath, $propKey, true);
                 if ($propertyValue != null)
                     return $propertyValue;
            }
            echo '%'.$propKey.'%';
            //echo $propertyValue;
            //throw new Exception('libraryStaticResourceManager: getPropertyValue(array): Requested property key does not exist: <b>' . $propKey . '</b> in <pre>' . print_r($propertyFilePath, true) . '</pre>');
        }
        elseif (is_string($propertyFilePath)) {
            if (!file_exists($propertyFilePath))
                throw new Exception('libraryStaticResourceManager: getPropertyValue(string): Property file does not exsist: <b>' . $propertyFilePath . '</b>');
            $props = parse_ini_file($propertyFilePath);
            //debug($props);
            //var_dump($props);
            //echo '<br> Reading property file: ' . $propertyFilePath . ' to get the key: ' . $propKey;
            if (isset($props[$propKey]))
                return $props[$propKey];
            if (!$fromArray)
                throw new Exception('libraryStaticResourceManager: getPropertyValue(string): Requested property key does not exist: <b>' . $propKey . '</b>; filepath: ' . $propertyFilePath);
            return null;
        } else
            throw new Exception('libraryStaticResourceManager: getPropertyValue(undefined): Wrong property file path: <b>'.$propertyFilePath.'</b>');
        
    }
    
}


?>

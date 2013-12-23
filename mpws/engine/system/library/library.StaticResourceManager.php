<?php

class libraryStaticResourceManager {

    // get merged styling for specific customer
    public function GetStaticContent () {

        // $c = MPWS_CUSTOMER;
        // $v = MPWS_VERSION;
        // $p = $_GET['object'] === 'plugin' ? $_GET['name'] : false;
        // echo "TEST";


        $objectType = $_GET['object']; // plugin OR customer
        $objectName = $_GET['name']; // plugin OR customer
        $requestedResourceName = $_GET['resource'];
        $access = 'restricted';

        switch ($_GET['access']) {
            case "i":
                $access = 'internal';
                break;
            case "p":
                $access = "public";
                break;
        }

        // request type
        $isPLugin = $objectType === 'plugin';
        $isCustomer = $objectType === 'customer';

        // requested file name
        $fileNameToRespond = $requestedResourceName  . DOT . $_GET['type'];

        // echo 'looking for file name:' . $fileNameToRespond;

        // saved resource path
        $resourceFileDir = DR . '/data/bin';
        $resourceFilePath = $resourceFileDir . '/_'.$fileNameToRespond;
        
        // get already saved file
        if (!file_exists($resourceFileDir))
            mkdir($resourceFileDir, 0777, true);
        elseif (file_exists($resourceFilePath) && !(isset($_GET['force']) || MPWS_ENV === 'DEV'))
            return file_get_contents($resourceFilePath);
        
        // use overriden file insted of requests file
        // get context and objectaccording to request
        // echo "dsfsdfsdf" . $isCustomer. '----'. $objectName;
        $ctx = contextMPWS::instance();
        $resourceEntry = array();
        $requestObjectOwner = false;
        if ($isPLugin)
            $requestObjectOwner = $ctx->contextToolbox->getObject($objectName);
        if ($isCustomer)
            $requestObjectOwner = $ctx->contextCustomer->getObject($objectName);


        // var_dump($requestObjectOwner);


        // set overriden files
        if (!empty($requestObjectOwner)) {
            $resourceNamesMap = $requestObjectOwner->objectConfiguration_resources_staticResourceOverrides;
            // var_dump($resourceNamesMap);
            $resourceEntry = $resourceNamesMap[$fileNameToRespond];
        }

        // or just use requsted file
        if (empty($resourceEntry))
            $resourceEntry = array($fileNameToRespond);

        //echo DR;
        // echo 'is plugin:' . ($isPLugin ? 'yes' : 'no' ). '<br>';
        // echo 'is customer:' .( $isCustomer ? 'yes' : 'no') . '<br>';
        // var_dump($requestObjectOwner);
        // var_dump($requestObjectOwner->objectConfiguration_resources_staticResourceOverrides);
        // var_dump('resourceEntry =====>');
        // var_dump($resourceEntry);
        
        $filesToLoad = array();
        // $filesToImport = array();
        $includeMetaInfo = false;
        $metaCommentStart = '/*';
        $metaCommentEnd = ' */';

        // walk by resource
        foreach ($resourceEntry as $filePath) {
            // set resource dir
            if ($_GET['type'] == 'js')
                $filePath = 'resource' . DS . 'js' . DS . $filePath;
            if ($_GET['type'] == 'css')
                $filePath = 'resource' . DS . 'style' . DS . $filePath;
            if ($_GET['type'] == 'hbs') {
                $filePath = 'template' . DS . $filePath;
                $metaCommentStart = '<!--';
                $metaCommentEnd = ' -->';
            }

            // echo DR . 'web/customer/' . $objectName . DS . $filePath;

            // include existed file
            if ($isPLugin && file_exists(DR . 'web/plugin/' . $objectName . DS . $filePath))
                $filesToLoad[] = DR . 'web/plugin/' . $objectName . DS . $filePath;
            elseif ($isCustomer && file_exists(DR . 'web/customer/' . $objectName . DS . $filePath))
                $filesToLoad[] = DR . 'web/customer/' . $objectName . DS . $filePath;
            elseif (file_exists(DR . 'web/default/' . MPWS_VERSION . DS . $filePath))
                $filesToLoad[] = DR . 'web/default/' . MPWS_VERSION . DS . $filePath;
        }

        // var_dump($filesToLoad);

        if (count($filesToLoad) === 0)
            return false;
        
        // set metainfo
        $metainfo = $metaCommentStart . '['.date('Y-m-d H:i:s').'] MPWS Packages: ' . PHP_EOL . ' * ' . 
                ((MPWS_ENV === 'DEV')?implode(';' . PHP_EOL . ' * ', $filesToLoad):'') . PHP_EOL . $metaCommentEnd . 
                PHP_EOL . PHP_EOL . PHP_EOL;
        $lineBreak = PHP_EOL.$metaCommentStart.str_pad('', 25, '*').' line break '.str_pad('', 25, '*').$metaCommentEnd.PHP_EOL;
        
        // read all files
        $data = '';
        $filesToLoadCount = count($filesToLoad);
        foreach ($filesToLoad as $item) {
            //echo '/*' . $item . '*/';
            if ($filesToLoadCount === 1)
                $data .= file_get_contents($item);
            else
                $data .= (file_get_contents($item) . $lineBreak);
        }
        
        // add import files (CSS only !!!!)
        // if (!empty($filesToImport))
        //     foreach ($filesToImport as $item)
        //         $metainfo .= PHP_EOL . '@import url(\'' . $item . '\');';
        
        
        // get build mode (for CSS only)
        if ($_GET['type'] == 'css') {
            $buildMode = $requestObjectOwner->objectConfiguration_resources_buildMode;
            // echo '<br> build mode: ' . $buildMode;
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
            if ($_GET['type'] == 'hbs') {
                file_put_contents($resourceFilePath, $data);
            }
        }

        return ($includeMetaInfo ? $metainfo : '') . $data;
    }

    public function GetContent () {

        $objectType = $_GET['object']; // plugin OR customer
        $objectName = $_GET['name']; // plugin OR customer
        $requestedResourceName = $_GET['resource'];
        $access = 'restricted';

        switch ($_GET['access']) {
            case "i":
                $access = 'internal';
                break;
            case "p":
                $access = "public";
                break;
        }

        // request type
        $isPLugin = $objectType === 'plugin';
        $isCustomer = $objectType === 'customer';

        // requested file name
        $fileNameToRespond = $requestedResourceName  . DOT . $_GET['type'];

        // set resource dir
        $resourceDir = false;
        switch ($_GET['type']) {
            case 'jpg':
            case 'png':
            case 'gif':
                $resourceDir = 'resource' . DS . 'img';
                break;
            case 'woff':
            case 'svg':
            case 'eot':
            case 'ttf':
                $resourceDir = 'resource' . DS . 'font';
                break;
        }

        // default files
        $chains[] = DR . 'web/default/' . MPWS_VERSION . DS . $resourceDir . DS . $fileNameToRespond;

        if ($isCustomer)
            $chains[] = DR . 'web/customer/' . $objectName. DS . $resourceDir . DS . $fileNameToRespond;
        if ($isPLugin)
            $chains[] = DR . 'web/plugin/' . $objectName. DS . $resourceDir . DS . $fileNameToRespond;
        // var_dump($chains);
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
    public static function getPropertyPath ($owner, $name, $resources, $locale = 'en_us', $preDefinedPaths = array()) {
        debug('libraryStaticResourceManager', 'getPropertyPath', true);

        $propFiles = array();

        $resourceNames = libraryUtils::convValue($resources);

        if (!is_array($resourceNames))
            $resourceNames = array($resourceNames);

        $lookupFiles = array();

        foreach ($resourceNames as $resourceSingleName) {

            $resPath = 'property' . DS . $locale . DS . str_replace(DOT, DS, $resourceSingleName) . '.property';
            // $resPathDef= 'property' . DS . $locale . DS . str_replace(DOT, DS, $resourceNames[0]) . '.property';

            $_default  = DR . 'web/default/' . MPWS_VERSION . DS . $resPath;
            if (isset($preDefinedPaths['PATH_DEF']))
                $_default = $preDefinedPaths['PATH_DEF'] . DS . $resPath;
            $_owner = DR . 'web/' . $owner . DS . $name . DS . $resPath;
            if (isset($preDefinedPaths['PATH_OWN']))
                $_owner = $preDefinedPaths['PATH_OWN'] . DS . $resPath;
            $_web = false;
            if (isset($preDefinedPaths['PATH_WEB']))
                $_web = $preDefinedPaths['PATH_WEB'] . DS . $resPath;

            //echo $_owner;
            if (file_exists($_owner))
                $propFiles[] = $_owner;
            
            //echo $_web;
            if (file_exists($_web))
                $propFiles[] =  $_web;
            
            //echo $_default;
            if (file_exists($_default))
                $propFiles[] = $_default;

            $lookupFiles[] = $_owner;
            $lookupFiles[] = $_web;
            $lookupFiles[] = $_default;

        }

        // var_dump($propFiles);

        // $propFiles = array_unique($propFiles);

        // if (count($propFiles) > 0)
            return $lookupFiles;

        //var_dump($_owner);
        //var_dump($_web);
        //var_dump($_default);
        
        // $_pathTrace = array($_owner, $_web, $_default);
        
        throw new Exception('libraryStaticResourceManager: getPropertyPath: requrested property file does not exists. <br>Make sure that you have even one of the following files:<pre>'.print_r($lookupFiles, true).'</pre>');
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
                 // echo $propertySingleFilePath;
                 $propertyValue = self::getPropertyValue($propertySingleFilePath, $propKey, true);
                 if (isset($propertyValue))
                     return $propertyValue;
            }
            //echo '%'.$propKey.'%';
            //echo $propertyValue;
            throw new Exception('libraryStaticResourceManager: getPropertyValue(array): Requested property key does not exist: <b>' . $propKey . '</b> in <pre>' . print_r($propertyFilePath, true) . '</pre>');
        }
        elseif (is_string($propertyFilePath)) {
            if (!file_exists($propertyFilePath) && !$fromArray)
                throw new Exception('libraryStaticResourceManager: getPropertyValue(string): Property file does not exsist: <b>' . $propertyFilePath . '</b>');
            // echo "xxxx>" . $propertyFilePath;
            $props = parse_ini_file($propertyFilePath);
            // debug($props, 'parse_ini_file');
            // var_dump($props);
            // echo '<br> Reading property file: ' . $propertyFilePath . ' to get the key: ' . $propKey;
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

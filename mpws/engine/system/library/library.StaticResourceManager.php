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
                        if (file_exists(DR . '/web/default/'.$v.'/resources/'.$filePath))
                            $filesToLoad[] = DR . '/web/default/'.$v.'/resources/'.$filePath;
                    break;
                case 'OWNER':
                    //echo '<br>|Customer file ' . print_r($resFiles, true) ;
                    if ($realm == 'mpws') {
                    foreach ($resFiles as $filePath)
                        if (file_exists(DR . '/web/customer/'.$c.'/resources/'.$filePath))
                            $filesToLoad[] = DR . '/web/customer/'.$c.'/resources/'.$filePath;
                    } elseif ($realm == 'toolbox') {
                    foreach ($resFiles as $filePath)
                        if (file_exists(DR . '/web/plugin/'.$p.'/resources/'.$filePath))
                            $filesToLoad[] = DR . '/web/plugin/'.$p.'/resources/'.$filePath;
                    }
                    break;
                case 'AUTO':
                    foreach ($resFiles as $filePath) {
                        if (file_exists(DR . '/web/customer/'.$c.'/resources/'.$filePath))
                            $filesToLoad[] = DR . '/web/customer/'.$c.'/resources/'.$filePath;
                        elseif (file_exists(DR . '/web/default/'.$v.'/resources/'.$filePath))
                            $filesToLoad[] = DR . '/web/default/'.$v.'/resources/'.$filePath;
                    }
                    break;
                case 'IMPORT':
                    $filesToImport = $resFiles;
                    break;
            }
        }
        
        //var_dump($filesToLoad);
        
        // set metainfo
        $metainfo .= '/* Packages: ' . PHP_EOL . ' * ' . 
                implode(';' . PHP_EOL . ' * ', $filesToLoad) . PHP_EOL . ' */' . 
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
            $data = libraryMinifyCSSCompressor::process($data);
            file_put_contents($resourceFilePath, $metainfo  . $data);
        }
        
        
        return $metainfo . $data;
    }

    public function GetContent ($name, $realm, $owner = '') {

        $c = MPWS_CUSTOMER;
        $v = MPWS_VERSION;
        
        //$owner = empty($owner)? MPWS_CUSTOMER : $owner;
        
        // default files
        $default = DR . '/web/default/'.$v.'/resources/' . $name;
        //if ($realm === 'toolbox') 
        //    $realmSource = DR . '/web/plugin/'.$owner.'/resources/' . $name;
        //if ($realm === 'mpws')
            $realmSource = DR . '/web/customer/'.$c.'/resources/' . $name;

        //echo $realm;
        //echo $realmSource;
        // echo $default;
        
        if (file_exists($realmSource))
            return $realmSource;
        if (file_exists($default))
            return $default;
        return null;
    }

}


?>

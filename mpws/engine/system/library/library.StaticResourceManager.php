<?php



class libraryStaticResourceManager {

    // get merged styling for specific customer
    public function GetStylesheetContent ($realm) {

        $c = MPWS_CUSTOMER;
        $v = MPWS_VERSION;
        //require_once "global.site.php";
        $resourceFilePath = DR . '/data/bin/'.$c.'_'.$realm.'.css';
        $resourceFileDir = DR . '/data/bin';
        if (!file_exists($resourceFileDir))
            mkdir($resourceFileDir, 0777, true);

        if (file_exists($resourceFilePath) && !(isset($_GET['force']) || MPWS_ENV === 'DEV'))
            return file_get_contents($resourceFilePath);

        //echo $v;
        //echo DR . '---'.$c.'---';

        //echo 'getting CSS for realm ' . $realm;

        if ($realm === 'toolbox') {
            // customer files
            $realmSource = glob(DR . '/web/plugin/*/resources/*.css');
            $realmSourceP = glob(DR . '/web/plugin/*/resources/plugins/*/*.css');
        }
        if ($realm === 'mpws') {
            // customer files
            $realmSource = glob(DR . '/web/customer/'.$c.'/resources/*.css');
            $realmSourceP = glob(DR . '/web/customer/'.$c.'/resources/plugins/*/*.css');
        }
        // default files
        $default = glob(DR . '/web/default/'.$v.'/resources/*.css');
        $defaultP = glob(DR . '/web/default/'.$v.'/resources/plugins/*/*.css');

        $container = array_merge($default, $defaultP, $realmSource, $realmSourceP);
        $metainfo = '/* MPWS CSS PACKAGE */'.PHP_EOL.'/* mpws published on '.date('M d, Y H:i:s').' */'.PHP_EOL;
        $lineBreak = PHP_EOL.'/* join '.str_pad('', 50, '=').' */'.PHP_EOL;

        //var_dump($container);

        $packages = array();
        $data = '';
        foreach ($container as $item) {
            //echo '/*' . $item . '*/';
            $data .= (file_get_contents($item) . $lineBreak);
            $packages[] = basename($item);
        }
        //$data = str_replace('%SITEPATH%', SITEURL . "web/resources/".strtolower(TITLE)."/" ,$metainfo);

        $metainfo .= '/* Packages: ' . implode('; ', $packages). ' */' . PHP_EOL . PHP_EOL . PHP_EOL;
        // compress data and store package
        if (MPWS_ENV === 'PROD') {
            $data = libraryMinifyCSSCompressor::process($data);
            file_put_contents($resourceFilePath, $metainfo  . $data);
        }

        return $metainfo . $data;
    }


    // get merged styling for specific customer
    public function GetJavascriptContent ($realm) {

        $c = MPWS_CUSTOMER;
        $v = MPWS_VERSION;
        //require_once "global.site.php";
        $resourceFilePath = DR . '/data/bin/'.$c.'_'.$realm.'.js';
        $resourceFileDir = DR . '/data/bin';
        if (!file_exists($resourceFileDir))
            mkdir($resourceFileDir, 0777, true);

        if (file_exists($resourceFilePath) && !(isset($_GET['force']) || MPWS_ENV === 'DEV'))
            return file_get_contents($resourceFilePath);

        if ($realm === 'toolbox') {
            // customer files
            $realmSource = glob(DR . '/web/plugin/*/resources/lib/*.js');
            $realmSourceP = glob(DR . '/web/plugin/*/resources/plugins/*/*.js');
        }
        if ($realm === 'mpws') {
            // customer files
            $realmSource = glob(DR . '/web/customer/'.$c.'/resources/lib/*.js');
            $realmSourceP = glob(DR . '/web/customer/'.$c.'/resources/plugins/*/*.js');
        }
        // default files
        $default = glob(DR . '/web/default/'.$v.'/resources/lib/*.js');
        $defaultP = glob(DR . '/web/default/'.$v.'/resources/plugins/*/*.js');

        $container = array_merge($default, $defaultP, $realmSource, $realmSourceP);
        $metainfo = '/* MPWS JS PACKAGE */'.PHP_EOL.'/* mpws published on '.date('M d, Y H:i:s').' */'.PHP_EOL;
        $lineBreak = PHP_EOL.'/* join '.str_pad('', 50, '=').' */'.PHP_EOL;

        $packages = array();
        $data = '';
        foreach ($container as $item) {
            $data .= (file_get_contents($item) . $lineBreak);
            $packages[] = basename($item);
        }
        //$data = str_replace('%SITEPATH%', SITEURL . "web/resources/".strtolower(TITLE)."/" ,$metainfo);

        $metainfo .= '/* Packages: ' . implode('; ', $packages). ' */' . PHP_EOL . PHP_EOL . PHP_EOL;

        // compress data and store package
        if (MPWS_ENV === 'PROD') {
            $data = libraryMinifyJSCompressor::minify($data);
            file_put_contents($resourceFilePath, $metainfo . $data);
        }

        return $metainfo . $data;
    }

    public function GetImageContent ($name, $realm, $owner = '') {

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

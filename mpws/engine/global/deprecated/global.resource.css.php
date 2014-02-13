<?php

    header("Content-type: text/css");
    date_default_timezone_set('Europe/Kiev');
    
    require_once "global.site.php";
    
    if (file_exists(SITEPATH . "web/resources/".strtolower(TITLE)."/bin/pkg.css") && !isset($_GET['force']))
    {
        echo file_get_contents(SITEPATH . "web/resources/".strtolower(TITLE)."/bin/pkg.css");
        exit;
    }
    
    //$container = array();
    /*
    function getConfiguration ($configDir = '', $type, &$container) {
        $d = new DirectoryIterator($configDir);
        foreach ($d as $dirEntry) {
            if ($dirEntry->isDot())
                continue;

            if ($dirEntry->isDir()) {
                $subdata = getConfiguration($configDir . "\\" . $dirEntry->getFilename(), $type, &$container);
                //$container = array_merge($container, $subdata);
                continue;
            }
                
            $pi = pathinfo($dirEntry->getFilename());
            //var_dump($pi);
            if ($dirEntry->isFile() && $pi['extension'] == $type)
                $container[$pi['basename']] = file_get_contents($configDir . "\\" . $dirEntry->getFilename());
        }
        //return $container;
    }
    */
    
    $general = glob(SITEPATH . "web/resources/".strtolower(TITLE)."/*css");
    $plugins = glob(SITEPATH . "web/resources/".strtolower(TITLE)."/plugins/*/*css");
    $container = array_merge($general, $plugins);
    $data = '/* mpws published on '.date('M d, Y H:i:s').' */';
    foreach ($container as $item)
        $data .= (file_get_contents($item) . PHP_EOL . PHP_EOL);
    
    //var_dump($general);
    //var_dump($plugins);
    //var_dump($container);
    
    //getConfiguration(SITEPATH . "web/resources", 'css', &$container);
    
    //$data = implode('', $container);
    $data = str_replace('%SITEPATH%', SITEURL . "web/resources/".strtolower(TITLE)."/" ,$data);
    
    file_put_contents(SITEPATH . "web/resources/".strtolower(TITLE)."/bin/pkg.css", '/* MPWS CSS PACKAGE */' . PHP_EOL . $data);
    
    echo $data;

?>

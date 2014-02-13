<?php

    header("Content-type: text/javascript");
    $cache_expire = 60*60*24*365;
    header("Pragma: public");
    header("Cache-Control: max-age=".$cache_expire);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$cache_expire) . ' GMT');
    date_default_timezone_set('Europe/Kiev');
    
    require_once "global.site.php";
    
    if (file_exists(SITEPATH . "web/resources/".strtolower(TITLE)."/bin/pkg.js") && !isset($_GET['force']))
    {
        echo file_get_contents(SITEPATH . "web/resources/".strtolower(TITLE)."/bin/pkg.js");
        exit;
    }
    
    /*
    function getConfiguration ($configDir = '', $type) {
        $container = array();
        $d = new DirectoryIterator($configDir);
        foreach ($d as $dirEntry) {
            if ($dirEntry->isDot())
                continue;
                
            $pi = pathinfo($dirEntry->getFilename());
            //var_dump($pi);
            if ($dirEntry->isFile() && $pi['extension'] == $type)
                $container[$pi['basename']] = file_get_contents($configDir . "//" . $dirEntry->getFilename());
        }
        return $container;
    }
    */
    
    
    
    $general = glob(SITEPATH . "web/resources/".strtolower(TITLE)."/lib/*js");
    $plugins = glob(SITEPATH . "web/resources/".strtolower(TITLE)."/plugins/*/*js");
    $container = array_merge($general, $plugins);
    $data = '/* mpws published on '.date('M d, Y H:i:s').' */';
    foreach ($container as $item)
        $data .= (file_get_contents($item) . PHP_EOL . PHP_EOL);
    
    
    //echo SITEPATH . "web/resources/lib";
    //$data = implode(PHP_EOL.PHP_EOL, getConfiguration(SITEPATH . "web/resources/lib", 'js'));
    $data = str_replace('%SITEPATH%', SITEPATH . "web/resources/".strtolower(TITLE)."/lib" ,$data);
    $data = str_replace('%SITEWEB%', SITEURL . "web/"  ,$data);
    $data = str_replace('%SITEURL%', SITEURL, $data);
    
    file_put_contents(SITEPATH . "web/resources/".strtolower(TITLE)."/bin/pkg.js", '/* MPWS JS PACKAGE */' . PHP_EOL . $data);
    
    echo $data;

?>

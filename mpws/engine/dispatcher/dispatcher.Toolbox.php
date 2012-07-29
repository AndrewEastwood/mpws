<?php

    //Set no caching
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
    header("Cache-Control: no-store, no-cache, must-revalidate"); 
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    /* dispatcher init */
    // set request realm
    define ('MPWS_REQUEST', 'TOOLBOX');
    // start session
    session_start();
    // document root path reference
    $DR = $_SERVER['DOCUMENT_ROOT'];
    // bootstrap
    include $DR . '/engine/bootstrap.php';
    // include global files
    $globals = glob($DR . '/engine/global/global.*.php');
    foreach ($globals as $globalFile)
        require_once $globalFile;
    // include system objects
    $objects = glob($DR . '/engine/system/object/object.*.php');
    foreach ($objects as $objectFile)
        require_once $objectFile;
    // include all configs
    $configs = glob($DR . '/engine/system/config/config.*.php');
    foreach ($configs as $configFile)
        require_once $configFile;

    /* prepare public environment */

    // set headers
    foreach ($config['TOOLBOX']['HEADERS'] as $header)
        header($header);
    // set encoding
    foreach ($config['TOOLBOX']['ICONV-ENCODING'] as $type => $charset)
        iconv_set_encoding($type, $charset);
    // timezone
    date_default_timezone_set($config['TOOLBOX']['TIMEZONE']);


    /* controller */

    $_SESSION['MPWS-REALM'] = MPWS_REQUEST;

    $start_time = mktime();
    //echo '<p style="display:none">Start Time: ' . mktime() . '</p>';
    $controller = new controllerToolbox();
    $controller->processRequests();
    //echo '<p style="display:none">End Time: ' . mktime() . '</p>';
    //echo '<p style="display:none">Script Time: ' . (mktime() - $start_time) . '</p>';
?>

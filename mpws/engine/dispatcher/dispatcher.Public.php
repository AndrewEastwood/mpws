<?php

    /* dispatcher init */
    // set request realm
    define ('MPWS_REQUEST', 'PUBLIC');
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
    // $configs = glob($DR . '/engine/system/config/config.*.php');
    // foreach ($configs as $configFile)
        // require_once $configFile;

    /* prepare public environment */
    // set headers
    // foreach ($config['WEB']['HEADERS'] as $header)
        // header($header);
    // set encoding
    // foreach ($config['WEB']['ICONV-ENCODING'] as $type => $charset)
        // iconv_set_encoding($type, $charset);
    // timezone
    // date_default_timezone_set($config['WEB']['TIMEZONE']);

    /* controller */
    $_SESSION['MPWS-REALM'] = MPWS_REQUEST;
    //$start_time = mktime();
    //echo '<p style="display:none">Start Time: ' . mktime() . '</p>';
    $controller = new controllerPublic();
    $controller->processRequests();


?>

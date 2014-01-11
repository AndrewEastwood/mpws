<?php

    header('Content-type: text/html');

    /* dispatcher init */
    // document root path reference
    $DR = $_SERVER['DOCUMENT_ROOT'];
    // bootstrap
    include $DR . '/engine/bootstrap.php';
    // include global files
    $globals = glob($DR . '/engine/global/global.*.php');
    foreach ($globals as $globalFile)
        require_once $globalFile;

    // get customer or default index layout
    if (MPWS_ENV === 'DEV') {
        $layoutCustomer = $DR . DS . 'web' . DS . 'customer' . DS . MPWS_CUSTOMER . DS . 'static' . DS . 'hbs' . DS . 'layout.html';
        $layoutDefault = $DR . DS . 'web' . DS . 'default' . DS . MPWS_VERSION . DS . 'static' . DS . 'hbs' . DS . 'layout.html';
    } else {
        $layoutCustomer = $DR . DS . 'web' . DS . 'build' . DS . 'customer' . DS . MPWS_CUSTOMER . DS . 'hbs' . DS . 'layout.html';
        $layoutDefault = $DR . DS . 'web' . DS . 'build' . DS . 'default' . DS . MPWS_VERSION . DS . 'hbs' . DS . 'layout.html';
    }

    $staticPath = (MPWS_ENV === 'DEV' ? 'static_dev' : 'static');
    $initialJS = "{
        BUILD: " . (MPWS_ENV === 'DEV' ? 'null' : file_get_contents($DR . DS . 'version.txt')) . ",
        ISDEV: " . (MPWS_ENV === 'DEV' ? 'true' : 'false') . ",
        MPWS_VERSION: '" . MPWS_VERSION . "',
        MPWS_CUSTOMER: '" . MPWS_CUSTOMER . "',
        PATH_STATIC_BASE: '" . DS . $staticPath . DS . "',
        URL_STATIC_CUSTOMER: '" . DS . $staticPath . DS . 'customer' . DS . MPWS_CUSTOMER . DS . "',
        URL_STATIC_PLUGIN: '" . DS . $staticPath . DS . 'plugin' . DS . "',
        URL_STATIC_DEFAULT: '" . DS . $staticPath . DS . 'default' . DS . MPWS_VERSION . DS . "'
    }";
    $initialJS = str_replace(array("\r","\n", ' '), '', $initialJS);

    $responce = '';
    if (file_exists($layoutCustomer))
        $responce = file_get_contents($layoutCustomer);
    else if (file_exists($layoutDefault))
        $responce = file_get_contents($layoutDefault);

    // add system data
    $responce = str_replace("{{SYSTEMJS}}", $initialJS, $responce);
    $responce = str_replace("{{MPWS_VERSION}}", MPWS_VERSION, $responce);
    $responce = str_replace("{{MPWS_CUSTOMER}}", MPWS_CUSTOMER, $responce);
    $responce = str_replace("{{PATH_STATIC}}", $staticPath, $responce);

    echo $responce;
?>
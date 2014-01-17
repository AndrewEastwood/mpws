<?php

    header('Content-type: text/html');
    // set request realm
    define ('MPWS_REQUEST', 'DISPLAY');
    // start session
    // session_start();
    // bootstrap
    include $_SERVER['DOCUMENT_ROOT'] . '/engine/bootstrap.php';

    // get customer or default index layout
    if (MPWS_ENV === 'DEV') {
        $layoutCustomer = glGetFullPath('web', 'customer', MPWS_CUSTOMER, 'static', 'hbs', 'layout.html');
        $layoutDefault = glGetFullPath('web', 'default', MPWS_VERSION, 'static', 'hbs', 'layout.html');
    } else {
        $layoutCustomer = glGetFullPath('web', 'build', 'customer', MPWS_CUSTOMER, 'static', 'hbs', 'layout.html');
        $layoutDefault = glGetFullPath('web', 'build', 'default', MPWS_VERSION, 'static', 'hbs', 'layout.html');
    }

    debug($layoutCustomer, 'layoutCustomer');
    debug($layoutDefault, 'layoutDefault');

    $staticPath = (MPWS_ENV === 'DEV' ? 'static_dev' : 'static');
    $initialJS = "{
        BUILD: " . (MPWS_ENV === 'DEV' ? 'null' : file_get_contents($DR . DS . 'version.txt')) . ",
        ISDEV: " . (MPWS_ENV === 'DEV' ? 'true' : 'false') . ",
        TOKEN: '" . libraryRequest::getOrValidatePageSecurityToken() . "',
        ISTOOLBOX: " . (glIsToolbox() ? 'true' : 'false') . ",
        MPWS_VERSION: '" . MPWS_VERSION . "',
        MPWS_CUSTOMER: '" . MPWS_CUSTOMER . "',
        PATH_STATIC_BASE: '" . glGetPath($staticPath) . "',
        URL_STATIC_CUSTOMER: '" . glGetPath($staticPath, 'customer', MPWS_CUSTOMER) . "',
        URL_STATIC_PLUGIN: '" . glGetPath($staticPath, 'plugin') . "',
        URL_STATIC_DEFAULT: '" . glGetPath($staticPath, 'default', MPWS_VERSION) . "'
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
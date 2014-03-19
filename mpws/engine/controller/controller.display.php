<?php

    header('Content-Type: text/html; charset=utf-8');
    // set request realm
    define ('MPWS_REQUEST', 'DISPLAY');
    // start session
    session_start();
    // bootstrap
    include $_SERVER['DOCUMENT_ROOT'] . '/engine/bootstrap.php';

    $layout = glIsToolbox() ? 'toolbox/layout' : 'site/layout';
    // get customer or default index layout
    if (MPWS_ENV === 'DEV') {
        $layoutCustomer = glGetFullPath('web', 'customer', MPWS_CUSTOMER, 'static', 'hbs', $layout . '.hbs');
        $layoutDefault = glGetFullPath('web', 'default', MPWS_VERSION, 'static', 'hbs', $layout . '.hbs');
    } else {
        $layoutCustomer = glGetFullPath('web', 'build', 'customer', MPWS_CUSTOMER, 'static', 'hbs', $layout . '.hbs');
        $layoutDefault = glGetFullPath('web', 'build', 'default', MPWS_VERSION, 'static', 'hbs', $layout . '.hbs');
    }

    debug($layoutCustomer, 'layoutCustomer');
    debug($layoutDefault, 'layoutDefault');

    $staticPath = 'static'; // (MPWS_ENV === 'DEV' ? 'static_dev' : 'static');
    $initialJS = "{
        LOCALE: '" . configurationCustomerDisplay::$Locale . "',
        BUILD: " . (MPWS_ENV === 'DEV' ? 'null' : file_get_contents(DR . DS . 'version.txt')) . ",
        ISDEV: " . (MPWS_ENV === 'DEV' ? 'true' : 'false') . ",
        TOKEN: '" . libraryRequest::getOrValidatePageSecurityToken(configurationCustomerDisplay::$MasterJsApiKey) . "',
        ISTOOLBOX: " . (glIsToolbox() ? 'true' : 'false') . ",
        PLUGINS: ['" . implode("', '", configurationCustomerDisplay::$Plugins) . "'],
        MPWS_VERSION: '" . MPWS_VERSION . "',
        MPWS_CUSTOMER: '" . MPWS_CUSTOMER . "',
        PATH_STATIC_BASE: '/',
        URL_API: '" . (glIsToolbox() ? '/toolbox/api.js' : '/api.js' ) . "',
        URL_STATIC_CUSTOMER: '/" . glGetPath($staticPath, 'customer', MPWS_CUSTOMER) . "',
        URL_STATIC_PLUGIN: '/" . glGetPath($staticPath, 'plugin') . "',
        URL_STATIC_DEFAULT: '/" . glGetPath($staticPath, 'default', MPWS_VERSION) . "'
    }";
    $initialJS = str_replace(array("\r","\n", ' '), '', $initialJS);

    $responce = '';
    if (file_exists($layoutCustomer))
        $responce = file_get_contents($layoutCustomer);
    else if (file_exists($layoutDefault))
        $responce = file_get_contents($layoutDefault);

    // add system data
    $responce = str_replace("{{LANG}}", configurationCustomerDisplay::$Lang, $responce);
    $responce = str_replace("{{SYSTEMJS}}", $initialJS, $responce);
    $responce = str_replace("{{MPWS_VERSION}}", MPWS_VERSION, $responce);
    $responce = str_replace("{{MPWS_CUSTOMER}}", MPWS_CUSTOMER, $responce);
    $responce = str_replace("{{PATH_STATIC}}", $staticPath, $responce);

    // TODO: save output into file
    // and reuse it when production mode is on

    echo $responce;
?>
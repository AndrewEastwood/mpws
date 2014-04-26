<?php

    header('Content-Type: text/html; charset=utf-8');
    // set request realm
    define ('MPWS_REQUEST', 'DISPLAY');
    // start session
    session_start();
    // bootstrap
    include $_SERVER['DOCUMENT_ROOT'] . '/engine/bootstrap.php';

    $displayCustomer = glIsToolbox() ? MPWS_TOOLBOX : MPWS_CUSTOMER;
    $customerStaticSource = glIsToolbox() ? 'toolbox' : 'site';
    $customerStaticCommonSource = 'common';
    $layout = 'layout.hbs';

    // get customer or default index layout
    if (MPWS_ENV === 'DEV') {
        $layoutCustomer = glGetFullPath('web', 'customer', $displayCustomer, 'static', $customerStaticSource, 'hbs', $layout);
        $layoutCustomerCommon = glGetFullPath('web', 'customer', $displayCustomer, 'static', $customerStaticCommonSource, 'hbs', $layout);
        $layoutDefault = glGetFullPath('web', 'default', MPWS_VERSION, 'static', 'hbs', $layout);
    } else {
        $layoutCustomer = glGetFullPath('web', 'build', 'customer', $displayCustomer, 'static', $customerStaticSource, 'hbs', $layout);
        $layoutCustomerCommon = glGetFullPath('web', 'build', 'customer', $displayCustomer, 'static', $customerStaticCommonSource, 'hbs', $layout);
        $layoutDefault = glGetFullPath('web', 'build', 'default', MPWS_VERSION, 'static', 'hbs', $layout);
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
        PLUGINS: ['" . implode("', '", glFilteredPlugins(configurationCustomerDisplay::$Plugins)) . "'],
        MPWS_VERSION: '" . MPWS_VERSION . "',
        MPWS_CUSTOMER: '" . $displayCustomer . "',
        PATH_STATIC_BASE: '/',
        URL_API: '/api.js',
        URL_STATIC_CUSTOMER: '/" . glGetPath($staticPath, 'customer', $displayCustomer) . "',
        URL_STATIC_WEBSITE: '/" . glGetPath($staticPath, 'customer', MPWS_CUSTOMER) . "',
        URL_STATIC_PLUGIN: '/" . glGetPath($staticPath, 'plugin') . "',
        URL_STATIC_DEFAULT: '/" . glGetPath($staticPath, 'default', MPWS_VERSION) . "',
        ROUTER: '" . join(DS, array('customer', 'js', 'router')) . "'
    }";
    $initialJS = str_replace(array("\r","\n", ' '), '', $initialJS);
        // URL_API: '" . (glIsToolbox() ? '/toolbox/api.js' : '/api.js' ) . "',

    $responce = '';
    if (file_exists($layoutCustomer))
        $responce = file_get_contents($layoutCustomer);
    else if (file_exists($layoutDefault))
        $responce = file_get_contents($layoutDefault);

    // add system data
    $responce = str_replace("{{LANG}}", configurationCustomerDisplay::$Lang, $responce);
    $responce = str_replace("{{SYSTEMJS}}", $initialJS, $responce);
    $responce = str_replace("{{MPWS_VERSION}}", MPWS_VERSION, $responce);
    $responce = str_replace("{{MPWS_CUSTOMER}}", $displayCustomer, $responce);
    $responce = str_replace("{{PATH_STATIC}}", $staticPath, $responce);

    // TODO: save output into file
    // and reuse it when production mode is on

    echo $responce;
?>
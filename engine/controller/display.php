<?php
    namespace engine\controller;

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
    $layoutBody = 'layoutBody.hbs';

    // get customer or default index layout
    if (MPWS_ENV === 'DEV') {
        // get layout
        $layoutCustomer = glGetFullPath('web', 'customer', $displayCustomer, 'static', 'hbs', $layout);
        // $layoutCustomerCommon = glGetFullPath('web', 'customer', $displayCustomer, 'static', $customerStaticCommonSource, 'hbs', $layout);
        $layoutDefault = glGetFullPath('web', 'default', MPWS_VERSION, 'static', 'hbs', $layout);
        // get layout body
        $layoutBodyCustomer = glGetFullPath('web', 'customer', $displayCustomer, 'static', 'hbs', $layoutBody);
        // $layoutBodyCustomerCommon = glGetFullPath('web', 'customer', $displayCustomer, 'static', $customerStaticCommonSource, 'hbs', $layoutBody);
        $layoutBodyDefault = glGetFullPath('web', 'default', MPWS_VERSION, 'static', 'hbs', $layoutBody);
    } else {
        // get layout
        $layoutCustomer = glGetFullPath('web', 'build', 'customer', $displayCustomer, 'static', 'hbs', $layout);
        // $layoutCustomerCommon = glGetFullPath('web', 'build', 'customer', $displayCustomer, 'static', $customerStaticCommonSource, 'hbs', $layout);
        $layoutDefault = glGetFullPath('web', 'build', 'default', MPWS_VERSION, 'static', 'hbs', $layout);
        // get layout body
        $layoutBodyCustomer = glGetFullPath('web', 'build', 'customer', $displayCustomer, 'static', 'hbs', $layoutBody);
        // $layoutBodyCustomerCommon = glGetFullPath('web', 'build', 'customer', $displayCustomer, 'static', $customerStaticCommonSource, 'hbs', $layoutBody);
        $layoutBodyDefault = glGetFullPath('web', 'build', 'default', MPWS_VERSION, 'static', 'hbs', $layoutBody);
    }

    debug($layoutCustomer, 'layoutCustomer');
    debug($layoutDefault, 'layoutDefault');

    $staticPath = 'static'; // (MPWS_ENV === 'DEV' ? 'static_dev' : 'static');
    $initialJS = "{
        LOCALE: '" . configurationCustomerDisplay::$Locale . "',
        BUILD: " . (MPWS_ENV === 'DEV' ? 'null' : file_get_contents(DR . DS . 'version.txt')) . ",
        ISDEV: " . (MPWS_ENV === 'DEV' ? 'true' : 'false') . ",
        TOKEN: '" . \engine\lib\request::getOrValidatePageSecurityToken(configurationCustomerDisplay::$MasterJsApiKey) . "',
        ISTOOLBOX: " . (glIsToolbox() ? 'true' : 'false') . ",
        PLUGINS: ['" . implode("', '", configurationCustomerDisplay::$Plugins) . "'],
        MPWS_VERSION: '" . MPWS_VERSION . "',
        MPWS_CUSTOMER: '" . $displayCustomer . "',
        PATH_STATIC_BASE: '/',
        URL_PUBLIC_HOMEPAGE: '" . configurationCustomerDisplay::$Homepage . "',
        URL_PUBLIC_TITLE: '" . configurationCustomerDisplay::$Title . "',
        URL_API: '/api.js',
        URL_AUTH: '/auth.js',
        URL_UPLOAD: '/upload.js',
        URL_STATIC_CUSTOMER: '/" . glGetPath($staticPath, 'customer', $displayCustomer) . "',
        URL_STATIC_WEBSITE: '/" . glGetPath($staticPath, 'customer', MPWS_CUSTOMER) . "',
        URL_STATIC_PLUGIN: '/" . glGetPath($staticPath, 'plugin') . "',
        URL_STATIC_DEFAULT: '/" . glGetPath($staticPath, 'default', MPWS_VERSION) . "',
        ROUTER: '" . join(DS, array('customer', 'js', 'router')) . "'
    }";
    $initialJS = str_replace(array("\r","\n", '  '), '', $initialJS);
        // URL_API: '" . (glIsToolbox() ? '/toolbox/api.js' : '/api.js' ) . "',

    $response = '';
    $layoutBodyContent = '';

    // init response with layout content
    if (file_exists($layoutCustomer))
        $response = file_get_contents($layoutCustomer);
    else if (file_exists($layoutDefault))
        $response = file_get_contents($layoutDefault);

    // get layout body content
    if (file_exists($layoutBodyCustomer))
        $layoutBodyContent = file_get_contents($layoutBodyCustomer);
    else if (file_exists($layoutBodyDefault))
        $layoutBodyContent = file_get_contents($layoutBodyDefault);

    // add system data
    $response = str_replace("{{BODY}}", $layoutBodyContent, $response);
    $response = str_replace("{{LANG}}", configurationCustomerDisplay::$Lang, $response);
    $response = str_replace("{{SYSTEMJS}}", $initialJS, $response);
    $response = str_replace("{{MPWS_VERSION}}", MPWS_VERSION, $response);
    $response = str_replace("{{MPWS_CUSTOMER}}", $displayCustomer, $response);
    $response = str_replace("{{PATH_STATIC}}", $staticPath, $response);

    // TODO: save output into file
    // and reuse it when production mode is on

    echo $response;
?>
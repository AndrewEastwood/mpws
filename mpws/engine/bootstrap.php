<?php

    // detect running customer name
    define('DR', getDocumentRoot());
    // detect running customer name
    define('MPWS_CUSTOMER', getCustomer());
    // evironment version
    define('MPWS_VERSION', 'atlantis');
    // evironment mode
    // set PROD | DEV
    define('MPWS_ENV', 'DEV');
    //
    define('MPWS_ENABLE_EMAILS', false);
    //
    define('MPWS_ENABLE_REDIRECTS', false);
    //
    define('MPWS_LOG_LEVEL', 0);

    //var_dump(parse_url($_SERVER['HTTP_HOST']));

    //error_reporting(E_ERROR | E_WARNING | E_PARSE);
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    // include global files
    $globals = glob(DR . '/engine/global/global.*.php');
    foreach ($globals as $globalFile)
        require_once $globalFile;

    function getDocumentRoot () {
        $_dr = strtolower($_SERVER['DOCUMENT_ROOT']);
        if ($_dr[strlen($_dr) - 1] != '/')
            $_dr .= '/';
        return $_dr;
    }
    
    function getCustomer () {
        $h = current(explode(':', $_SERVER['HTTP_HOST']));
        $h = strtolower($h);
        
        $host_parts = explode ('.', $h);
        $customerName = '';
        
        if ($host_parts[0] == 'toolbox')
            $customerName = 'toolbox';
        else if ($host_parts[0] == 'www')
            $customerName = implode('.', array_splice($host_parts, 1));
        else
            $customerName = $h;

        $customerName = str_replace('.', '_', $customerName);

        return $customerName;
    }
    
    function getDebugLevel (){
        return MPWS_LOG_LEVEL;
    }

    function glIsToolbox () {
        return MPWS_CUSTOMER === 'toolbox';
    }

    function glIsCustomer ($customerName) {
        return strcasecmp(MPWS_CUSTOMER, $customerName) === 0;
    }

?>

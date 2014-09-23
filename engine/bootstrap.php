<?php

    // detect running customer name
    define('DR', glGetDocumentRoot());
    // detect running customer name
    define('MPWS_TOOLBOX', 'toolbox');
    define('MPWS_CUSTOMER', glGetCustomerName());
    // framework version
    define('MPWS_VERSION', 'atlantis');
    // evironment mode
    define('MPWS_ENV', 'DEV'); // [PROD | DEV]
    // how to show output of the debug function
    // see: global/global.methods.php
    define('MPWS_LOG_LEVEL', 2); // 
    //error_reporting(E_ERROR | E_WARNING | E_PARSE);
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    $PHP_INPUT = file_get_contents('php://input');

    // include global files
    $globals = glob(DR . '/engine/global/global.*.php');
    foreach ($globals as $globalFile)
        require_once $globalFile;

    // returns root path
    function glGetDocumentRoot () {
        $_dr = strtolower($_SERVER['DOCUMENT_ROOT']);
        if ($_dr[strlen($_dr) - 1] != '/')
            $_dr .= '/';
        return $_dr;
    }

    // get customer key (name)
    function glGetCustomerName () {
        $h = current(explode(':', $_SERVER['HTTP_HOST']));
        $h = strtolower($h);
        $host_parts = explode ('.', $h);
        $customerName = '';
        if ($host_parts[0] === 'www' || $host_parts[0] === MPWS_TOOLBOX)
            $customerName = implode('.', array_splice($host_parts, 1));
        else
            $customerName = $h;
        $customerName = str_replace('.', '_', $customerName);
        return $customerName;
    }

    // get debug level
    function glGetDebugLevel (){
        return MPWS_LOG_LEVEL;
    }

    // returns true whether current view is toolbox
    function glIsToolbox () {
        return preg_match("/^" . MPWS_TOOLBOX . "\./", $_SERVER['HTTP_HOST']) > 0;
        // return preg_match("/^\/toolbox\//", $_SERVER['REQUEST_URI']) > 0;
    }

?>
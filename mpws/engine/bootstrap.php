<?php

    // detect running customer name


    define('DR', getDocumentRoot());
    
    
    // detect running customer name
    define('MPWS_CUSTOMER', getCustomer());
    // evironment version
    define('MPWS_VERSION', 'v1.0');
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
    ini_set("display_errors", 2);
    
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
        
        if ($host_parts[0] == 'toolbox')
            return 'toolbox';
        
        if ($host_parts[0] == 'www')
            return implode('.', array_splice($host_parts, 1));
        
        return $h;
    }
    

?>

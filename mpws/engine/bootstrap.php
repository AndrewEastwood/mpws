<?php

    // detect running customer name
    define('DR', strtolower($_SERVER['DOCUMENT_ROOT']));
    // detect running customer name
    define('MPWS_CUSTOMER', current(explode(':', $_SERVER['HTTP_HOST'])));
    // evironment version
    define('MPWS_VERSION', 'v1.0');
    // evironment mode
    // set PROD | DEV
    define('MPWS_ENV', 'DEV');
    //
    define('MPWS_ENABLE_EMAILS', false);
    
    
    //var_dump(parse_url($_SERVER['HTTP_HOST']));
    
    
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set("display_errors", 1);

?>

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
    // include web config
    include $DR . '/engine/system/config/config.web.php';
    include $DR . '/engine/system/config/config.paths.php';

    /* prepare public environment */

    // set headers
    foreach ($config['WEB']['HEADERS'] as $header)
        header($header);
    // set encoding
    foreach ($config['WEB']['ICONV-ENCODING'] as $type => $charset)
        iconv_set_encoding($type, $charset);
    // timezone
    date_default_timezone_set($config['WEB']['TIMEZONE']);


    /* controller */

    $_SESSION['MPWS-REALM'] = MPWS_REQUEST;

    $controller = new controllerPublic();
    $controller->processRequests();



    //echo 'done';


    /* create mostly used objects */
    //$core_lib = new Core;
    //$fman_lib = new FileManager;
    //$pmgr_lib = new PluginManager;
    //$strn_lib = new String;
    //$view_lib = new View;


    // define loader
        //session_save_path(realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
    //echo realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session');
    //$a = session_id();
    //if(empty($a)) session_start();
    //echo 'session id' . $a;
    //header("Content-type: text/html; charset=windows-1251");
    //date_default_timezone_set('UTC');  
    // PHP Документ
    //
    // Глобальний файл спільного використання
    // для веб-сторінки управління.
    //
    // Автор: Іваськевич Андрій
    // © Ivaskevych Andriy, 2009
    // soulcor@narod.ru

    // Підключення автозавантажувача бібліотек
    //require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/globals/global.const.php";
    //require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/globals/global.loader.php";
    //require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/globals/global.eventhandlers.php";

    // Підключення глобальних конфігурації
    //require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/configs/cfg.db.php";
    //require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/configs/cfg.path.php";
    //require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/configs/cfg.sys.php";

    //set_error_handler('ErrorHandler');

    //iconv_set_encoding("internal_encoding", "UTF-8");
    //iconv_set_encoding("output_encoding", "ISO-8859-1");


?>

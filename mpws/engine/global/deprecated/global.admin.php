<?php
    //session_save_path(realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
    //echo realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session');
    $a = session_id();
    if(empty($a)) session_start();
    //echo 'session id' . $a;
    header("Content-type: text/html; charset=windows-1251");
    date_default_timezone_set('UTC');  
    // PHP Документ
    //
    // Глобальний файл спільного використання
    // для веб-сторінки управління.
    //
    // Автор: Іваськевич Андрій
    // © Ivaskevych Andriy, 2009
    // soulcor@narod.ru

    // Підключення автозавантажувача бібліотек
    require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/globals/global.const.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/globals/global.loader.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/globals/global.eventhandlers.php";

    // Ініціалізація глобальних бібліотек
    $core_lib = new Core;
    $fman_lib = new FileManager;
    $pmgr_lib = new PluginManager;
    $strn_lib = new String;
    $view_lib = new View;

    // Підключення глобальних конфігурації
    require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/configs/cfg.db.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/configs/cfg.path.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/configs/cfg.sys.php";

    //set_error_handler('ErrorHandler');

    iconv_set_encoding("internal_encoding", "UTF-8");
    iconv_set_encoding("output_encoding", "ISO-8859-1");



?>

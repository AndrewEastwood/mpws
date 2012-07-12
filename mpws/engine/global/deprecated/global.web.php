<?php


    // PHP Документ
    //
    // Глобальний файл спільного використання
    // для веб-сторінки клієнтської частини.
    //
    // Автор: Іваськевич Андрій.
    // Author: Andriy Ivaskevych.

    // © Ivaskevych Andriy, 2009
    // E-mail: soulcor@narod.ru

    // Виклик функції з обробника записів сторінки користувача.
    // $functionName - коротка назва функції.
    // $className - слас обробки. (по замовчуванню WebDriver)
    // Повертає HTML-результат виконання функції.

    //session_start();
    //session_regenerate_id(true);
    //date_default_timezone_set('Europe/Kiev');        //                 echo "e";

    header("Content-type: text/html; charset=windows-1251");
    date_default_timezone_set('UTC');  
    $a = session_id();
    if(empty($a))
        session_start();

/*
    function htmlcode($functionName, $param = false, $className = 'WebInternal')
    {
        global $web_lib;
        //if (empty($className))
        //$className = get_called_class();
        //return call_user_func(array($className, 'GetCode'), $functionName, $param);
        return $web_lib->GetCode($functionName, $param);
    }*/

    // echo "a";
    // Підключення автозавантажувача бібліотек
    require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/globals/global.const.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/globals/global.loader.php";
    //require_once "libs/globals/global.eventhandlers.php";

    //   echo "b";

    // Підключення глобальних конфігурації
    require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/configs/cfg.path.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/libs/configs/cfg.db.php";

    // Ініціалізація глобальних бібліотек
    $core_lib = new Core;
    $view_lib = new View;
    $fman_lib = new FileManager;
    $strn_lib = new String;
    $db_lib = new DataBase();
    //echo "c";

    //echo "d";
    //set_error_handler('ErrorHandler');

    //date_default_timezone_set('UTC');        //                 echo "e";

?>

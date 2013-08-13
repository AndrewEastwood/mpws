<?php
    // Ідентифікатор сайту
    $cfg_admin['NAME'] = 'myPhpWebSite';
    // Заголовок розділу
    $cfg_admin['TITLE'] = 'Адміністрування';
    // Розділи меню
    $cfg_admin['MENU'] = Array(
        'exit' => 'Вихід',
        'home' => 'Головна');
    // Файл з паролем
    $cfg_admin['PFILE'] = US.DS.'libs'.DS.'configs'.DS.'users.pwd';
    // Час життя сесії, хв
    $cfg_admin['TSESSION'] = '20';
    // Шаблон сторінки адміністрування
    $cfg_admin['TPL'] = 'modern';
    // Режим показу календаря
    $cfg_admin['CALENDAR'] = true;
    // Додатки, які використовуються сайтом
    $cfg_admin['PLUGINS'] = Array(
        //0 => 'mynews',
        1 => 'ishop',
        //2 => 'cpanel',
        2 => 'polls',
        3 => 'gallery'//,
        //4 => 'mynews'
        );
    // Формат дати
    $cfg_admin['DATEMASK'] = '%1$04d-%2$02d-%3$02d';
    $cfg_admin['NOCACHELINK'] = FALSE;

    // create config object
    $config['TOOLBOX'] = array();
    $config['TOOLBOX']['HEADERS'] = array(
        'Content-type: text/html; charset=utf8'
    );
    $config['TOOLBOX']['TIMEZONE'] = 'UTC';
    $config['TOOLBOX']['ICONV-ENCODING'] = array(
        'internal_encoding' => 'UTF-8',
        'output_encoding' => 'ISO-8859-1'
    );


?>

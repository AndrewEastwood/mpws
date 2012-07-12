<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        // Кореневий фізичний шлях до вмісту системи
        $cfg_path['DOC'] = $_SERVER['DOCUMENT_ROOT'];
        // Кореневий серверний шлях до вмісту системи
        $cfg_path['HOST'] = $_SERVER['HTTP_HOST'];
    }
    else {
        // Кореневий фізичний шлях до вмісту системи
        $cfg_path['DOC'] = $_SERVER['DOCUMENT_ROOT'] . 'komis';
        // Кореневий серверний шлях до вмісту системи
        $cfg_path['HOST'] = $_SERVER['HTTP_HOST'] . 'komis';
    }
    // Папка біліотек
    $cfg_path['LIBS'] = 'libs';
    // Папка java - скриптів
    $cfg_path['JAVASCRIPTS'] = $cfg_path['LIBS'].DS.'jscripts';
    // Папка плагінів
    $cfg_path['PLUGINS'] = $cfg_path['LIBS'].DS.'plugins';
    // Папка користної інформації (для сторінки адміністрування)
    $cfg_path['DATA'] = 'data';
    // Папка шаблонів сторінок для сторінки адміністрування)
    $cfg_path['TEMPLATES'] = 'admin'.DS.'templates';
    // Папка сторінки сайту
    $cfg_path['WEB'] = 'web';
?>
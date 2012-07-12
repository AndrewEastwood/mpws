<?php
echo $_SERVER['DOCUMENT_ROOT'];
    // Кореневий фізичний шлях до вмісту системи
    $cfg_path['DOC'] = "D:/hshome/marihooan/pobutteh.com.ua/";
    // Кореневий серверний шлях до вмісту системи
    $cfg_path['HOST'] = "pobutteh.com.ua";
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
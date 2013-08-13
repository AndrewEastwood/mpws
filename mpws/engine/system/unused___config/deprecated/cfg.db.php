<?php

    if ($_SERVER['HTTP_HOST'] == 'rc1.mpws.com') {
        // хост БД
        $cfg_db['DB_HOST'] = 'localhost';
        // юзер БД
        $cfg_db['DB_USERNAME'] = 'root';
        // пароль БД
        $cfg_db['DB_PASSWORD'] = '1111';
    } else {
        // хост БД
        $cfg_db['DB_HOST'] = 'mysqluk.ukrhosting.com';
        // юзер БД
        $cfg_db['DB_USERNAME'] = 'komislv_admin';
        // пароль БД
        $cfg_db['DB_PASSWORD'] = 'Q1w2E3r4';
        // назва БД
    }

    // назва БД
    $cfg_db['DB_NAME'] = 'mpws_' . strtolower($GLOBALS['SITE']['TITLE']);
    // назва кодування для роботи з даними
    $cfg_db['DB_CHARSET'] = 'utf8';

?>

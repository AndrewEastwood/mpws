<?php

    if ($_SERVER['HTTP_HOST'] == 'rc1.mpws.com') {
        // ���� ��
        $cfg_db['DB_HOST'] = 'localhost';
        // ���� ��
        $cfg_db['DB_USERNAME'] = 'root';
        // ������ ��
        $cfg_db['DB_PASSWORD'] = '1111';
    } else {
        // ���� ��
        $cfg_db['DB_HOST'] = 'mysqluk.ukrhosting.com';
        // ���� ��
        $cfg_db['DB_USERNAME'] = 'komislv_admin';
        // ������ ��
        $cfg_db['DB_PASSWORD'] = 'Q1w2E3r4';
        // ����� ��
    }

    // ����� ��
    $cfg_db['DB_NAME'] = 'mpws_' . strtolower($GLOBALS['SITE']['TITLE']);
    // ����� ��������� ��� ������ � ������
    $cfg_db['DB_CHARSET'] = 'utf8';

?>

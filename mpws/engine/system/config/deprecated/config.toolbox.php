<?php
    // ������������� �����
    $cfg_admin['NAME'] = 'myPhpWebSite';
    // ��������� ������
    $cfg_admin['TITLE'] = '�������������';
    // ������ ����
    $cfg_admin['MENU'] = Array(
        'exit' => '�����',
        'home' => '�������');
    // ���� � �������
    $cfg_admin['PFILE'] = US.DS.'libs'.DS.'configs'.DS.'users.pwd';
    // ��� ����� ���, ��
    $cfg_admin['TSESSION'] = '20';
    // ������ ������� �������������
    $cfg_admin['TPL'] = 'modern';
    // ����� ������ ���������
    $cfg_admin['CALENDAR'] = true;
    // �������, �� ���������������� ������
    $cfg_admin['PLUGINS'] = Array(
        //0 => 'mynews',
        1 => 'ishop',
        //2 => 'cpanel',
        2 => 'polls',
        3 => 'gallery'//,
        //4 => 'mynews'
        );
    // ������ ����
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

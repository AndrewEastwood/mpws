<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        // ��������� �������� ���� �� ����� �������
        $cfg_path['DOC'] = $_SERVER['DOCUMENT_ROOT'];
        // ��������� ��������� ���� �� ����� �������
        $cfg_path['HOST'] = $_SERVER['HTTP_HOST'];
    }
    else {
        // ��������� �������� ���� �� ����� �������
        $cfg_path['DOC'] = $_SERVER['DOCUMENT_ROOT'] . 'komis';
        // ��������� ��������� ���� �� ����� �������
        $cfg_path['HOST'] = $_SERVER['HTTP_HOST'] . 'komis';
    }
    // ����� ������
    $cfg_path['LIBS'] = 'libs';
    // ����� java - �������
    $cfg_path['JAVASCRIPTS'] = $cfg_path['LIBS'].DS.'jscripts';
    // ����� ������
    $cfg_path['PLUGINS'] = $cfg_path['LIBS'].DS.'plugins';
    // ����� �������� ���������� (��� ������� �������������)
    $cfg_path['DATA'] = 'data';
    // ����� ������� ������� ��� ������� �������������)
    $cfg_path['TEMPLATES'] = 'admin'.DS.'templates';
    // ����� ������� �����
    $cfg_path['WEB'] = 'web';
?>
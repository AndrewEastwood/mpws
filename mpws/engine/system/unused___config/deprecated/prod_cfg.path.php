<?php
echo $_SERVER['DOCUMENT_ROOT'];
    // ��������� �������� ���� �� ����� �������
    $cfg_path['DOC'] = "D:/hshome/marihooan/pobutteh.com.ua/";
    // ��������� ��������� ���� �� ����� �������
    $cfg_path['HOST'] = "pobutteh.com.ua";
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
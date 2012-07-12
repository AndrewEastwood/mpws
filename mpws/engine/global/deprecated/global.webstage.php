<?php


	// PHP ��������
	//
	// ���������� ���� �������� ������������
	// ��� ���-������� �볺������ �������.
	//
	// �����: ���������� �����.
	// Author: Andriy Ivaskevych.

	// � Ivaskevych Andriy, 2009
	// E-mail: soulcor@narod.ru

	// ������ ������� � ��������� ������ ������� �����������.
	// $functionName - ������� ����� �������.
	// $className - ���� �������. (�� ������������ WebDriver)
	// ������� HTML-��������� ��������� �������.

	//session_start();
	session_regenerate_id(true);
	date_default_timezone_set('Europe/Kiev');        //                 echo "e";
	function htmlcode($functionName, $param = false, $className = 'WebDriverStage')	{
		if (empty($className))
			$className = get_called_class();

		return call_user_func(array($className, 'GetCode'), $functionName, $param);
	}
                      //        echo "a";
	// ϳ��������� ����������������� �������
	require_once "global.const.php";
	require_once "global.loader.php";
	//require_once "libs/globals/global.eventhandlers.php";
                                                 //   echo "b";
	// ����������� ���������� �������
	$core_lib = new Core;
	$view_lib = new View;
	$fman_lib = new FileManager;
	$strn_lib = new String;
                                                //     echo "c";
	// ϳ��������� ���������� ������������
	require_once "libs/configs/cfg.path.php";
	require_once "libs/configs/cfg.db.php";
                                                   //       echo "d";
	//set_error_handler('ErrorHandler');

	//date_default_timezone_set('UTC');        //                 echo "e";

?>
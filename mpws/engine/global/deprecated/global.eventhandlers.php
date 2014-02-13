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

	//
	function ErrorHandler($errno, $errstr, $file, $line)
	{		$errOpenBlock = "<div style=\"border:1px solid #000000;width:50%;margin-left:10px;padding:5px;background:#91B0E3;\">";
		$errOpenBlock .= "<small style=\"color:#FFFFFF\"><sub><b>myPhpWebSite</b> � ����� ��� ������� ����������</sub></small><br><br>\n";
		$errCloseBlock = "</div>";
		$separator = "<hr size=\"0\" style=\"border:1 solid #FFFFFF;\">"."\n";

		//echo "ERROR Handler : has::  " .  . " | " . $errstr . "<br>";

		echo $errOpenBlock;
		switch ($errno)
		{
			case E_USER_ERROR:
				switch ($errstr)
				{
					case "(SQL)":
						// handling an sql error
						echo "<b>SQL �������</b>: " . eMESSAGE . "<br />"."\n";
						echo "<b>SQL �������</b>: " . eQUERY . "<br />"."\n";
						echo "<b>����</b>: " . eFILE . " <br />"."\n";
						echo "<b>����� ����� ����</b>: " . eLINE . "<br />"."\n";
						echo "<b>����</b>: " . eCLASS . "<br />"."\n";
						echo "<b>�����</b>: " . eMETHOD . " <br />"."\n";
						echo $separator;
						echo "PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />"."\n";
						break;
					case "(OPERATION)":
						echo "<b>�������</b>: " . eMESSAGE . "<br />"."\n";
						echo "<b>�������</b>: " . eQUERY . "<br />"."\n";
						echo "<b>����</b>: " . eFILE . " <br />"."\n";
						echo "<b>����� ����� ����</b>: " . eLINE . "<br />"."\n";
						echo "<b>����</b>: " . eCLASS . "<br />"."\n";
						echo "<b>�����</b>: " . eMETHOD . " <br />"."\n";
						echo $separator;
						echo "PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
						break;
					default:
						echo "<b>�������</b>: $errno<br />\n";
						echo "$errstr<br />\n";
						echo "<hr size=\"0\" style=\"border:1 solid #FFFFFF;\">"."\n";
						echo "PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
						break;
				}
				break;
			case E_USER_WARNING:
			case E_USER_NOTICE:
			default:
				echo "<b>������� [$errno]</b>: $errstr <br />"."\n";
				echo "<b>����</b>: $file <br />\n";
				echo "<b>����� ����� ����</b>: $line <br />"."\n";
				echo "<hr size=\"0\" style=\"border:1 solid #FFFFFF;\">"."\n";
				echo "PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
				break;
		}
		echo $errCloseBlock;
		exit(1);

		/* Don't execute PHP internal error handler */
		return true;
	}

	// function to test the error handling
	function sqlErrorHandler($ERROR, $QUERY, $PHPFILE, $LINE, $METHOD, $CLASS)
	{		define("eMESSAGE", $ERROR);		define("eQUERY", $QUERY);
		define("eFILE", $PHPFILE);
		define("eLINE", $LINE);
		define("eMETHOD", $METHOD);
		define("eCLASS", $CLASS);

		trigger_error("(SQL)", E_USER_ERROR);
	}

	function operationErrorHandler($ERROR, $PHPFILE, $LINE, $METHOD, $CLASS)
	{
		echo "hello";		define("eMESSAGE", $ERROR);
		define("eFILE", $PHPFILE);
		define("eLINE", $LINE);
		define("eMETHOD", $METHOD);
		define("eCLASS", $CLASS);
		trigger_error("(OPERATION)", E_USER_ERROR);
	}

?>
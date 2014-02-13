<?php

// PHP ��������.
// ��������� CodeImage.
// -----------------------
//
// ����������� ��� ������� HTML-����.
//
// ��� ������������ ����� ��������� ��� ����
// ������ ���� ������� ������� ���������
// ����� � ������ ��������� ����� ��������
// ����, ��� �������������� � ����� ���������.
//
// �����: � 2009, ����� ����������.
// Author: � 2009, Andriy Ivaskevych.
//
// E-mail: soulcor@narod.ru

class CodeImage
{	// ��������������� ��������� �����
		// �����������
		var $_key;
		// ������������ �������
		var $_showcode;

    // _�����������
	function __construct()
	{		$_key = "php smaple";
	}

	// �������� ��������� ������������� ��� � ���������.
	// $check_code - ���, �� ��� ��������.
	// $hidden_code - ���, ���� ��� ������������ ���������.
	// ������� true ,���� ���� ��������� ������ false.
	function validate($check_code, $hidden_code)
	{
	    $validated = false;

	    /*echo $check_code . '&nbsp;&nbsp;&nbsp;';
	    echo $hidden_code; */

	    if (!empty($check_code))
	    {
			// $hidden_code is the encrypted 4 digit code
			$hidden_code = urldecode($hidden_code);
	        $hidden_code = $this->html_decrypt($hidden_code);
	        /*echo '<br> decrypted = '.$hidden_code;
	        echo '<br> check = '.$check_code ;*/
			// If the decrypted version matches the inputted text,
			// we are good to go!
	        if ($hidden_code == $check_code)
	            $validated = true;
	    }

	    return $validated;
	}

	// ������ ���������� ���.
	// ������� ������������ ���.
	function makeCode()
	{		$chars = "ABCDEFGHJKMNPRSTUVWXYZabcdefghzkmnprstuvwxyz23456789";
		$len   = strlen($chars)-1;
		$_code = '';

		for ($i = 0; $i < 4; $i++)
			$_code .= substr($chars, rand(0, $len) ,1);

		$this->_showcode = $this->html_encrypt($_code);

        /*echo '<br><br>';
		echo 'code = '.$_code;
		echo '<br>';
		echo 'encrypted = '.$this->_showcode;
		echo '<br>';
		echo 'uelencoded encrypted = '.urlencode($this->_showcode);
		echo '<br>';
		echo 'decrypted = '.$this->html_decrypt($this->_showcode);
		echo '<br>';
		echo 'ueldecoded decrypted = '.$this->html_decrypt(urlencode($this->_showcode)); */

		return $_code;
	}

	// �������� ����� �������������� ����.
	// $txt - �����.
	// ������� ����������� �����.
	function keyED($txt)
	{
		$encrypt_key = md5($this->_key);
		$ctr = 0;
		$tmp = "";

		for ($i = 0; $i < strlen($txt); $i++)
		{
			if ($ctr == strlen($encrypt_key))
				$ctr=0;
			$tmp .= substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1);
			$ctr++;
		}

		return $tmp;
    }

	// ���� ����� � ���������� HTML ����.
	// $txt - �����.
	// ������� ����������� ����.
	function html_encrypt($txt)
	{
    	return urlencode($this->encrypt($txt));
    }

	// �������� �����.
	// $txt - �����.
	// ������� ����������� �����.
	function encrypt($txt)
	{
		$encrypt_key = md5(microtime()); // Public key
		$ctr = 0;
		$tmp = "";

		for ($i = 0; $i < strlen($txt); $i++)
		{
			 if ($ctr == strlen($encrypt_key))
			 	$ctr = 0;
			 $tmp .= substr($encrypt_key,$ctr,1) . (substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1));
			 $ctr++;
		 }

		return $this->keyED($tmp);
    }

	// ��������� ����� �� ��� ���������� � HTML ����.
	// $txt - �����.
	// ������� ������������ ����.
	function html_decrypt($txt)
	{
	    return $this->decrypt(urldecode($txt));
    }

	// ��������� �����.
	// $txt - �����.
	// ������� ������������ �����.
	function decrypt($txt)
	{
		$txt = $this->keyED($txt);
		$tmp = "";

		for ($i = 0; $i < strlen($txt); $i++)
		{
			 $md5 = substr($txt, $i, 1);
			 $i++;
			 $tmp .= (substr($txt, $i, 1) ^ $md5);
		}

		return $tmp;
    }
}

?>

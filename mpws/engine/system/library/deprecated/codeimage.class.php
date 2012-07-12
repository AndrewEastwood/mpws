<?php

// PHP Документ.
// Бібліотека CodeImage.
// -----------------------
//
// Призначений для захисту HTML-форм.
//
// Для використання цього документу або його
// частин коду потрібно вказати авторське
// право в вигляді коментаря перед частиною
// коду, яка використовуєтья з цього документу.
//
// Автор: © 2009, Андрій Іваськевич.
// Author: © 2009, Andriy Ivaskevych.
//
// E-mail: soulcor@narod.ru

class CodeImage
{	// Загальнодоступні параметри класу
		// повідомлення
		var $_key;
		// конфігурація додатку
		var $_showcode;

    // _конструктор
	function __construct()
	{		$_key = "php smaple";
	}

	// Перевіряє співпадіння згенерованого код з отриманим.
	// $check_code - код, що був введений.
	// $hidden_code - код, який був згенерований програмою.
	// Повертає true ,якщо коди співпадють інакше false.
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

	// Генерує випадковий код.
	// Повертає згенерований код.
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

	// Закодовує текст використовуючи ключ.
	// $txt - текст.
	// Повертає закодований текст.
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

	// Кодує текст в закодованй HTML формі.
	// $txt - текст.
	// Повертає закодований текс.
	function html_encrypt($txt)
	{
    	return urlencode($this->encrypt($txt));
    }

	// Закодовує текст.
	// $txt - текст.
	// Повертає закодований текст.
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

	// Розкодовує текст що був закодованй в HTML формі.
	// $txt - текст.
	// Повертає розкодований текс.
	function html_decrypt($txt)
	{
	    return $this->decrypt(urldecode($txt));
    }

	// Розкодовує текст.
	// $txt - текст.
	// Повертає розкодований текст.
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

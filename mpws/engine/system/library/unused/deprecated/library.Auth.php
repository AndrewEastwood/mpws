<?php

// PHP Документ.
// Бібліотека FileManager.
// -----------------------
//
// Призначений для роботи з користувачами системи myPhpWebSite.
// та іх авторизації.
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

class Auth
{
	// _конструктор
	function __construct()
	{
		if (!isset($_SESSION['aOK']))
		{
			//session_register('aOK');
			$_SESSION['aOK'] = false;
		}
	}

	// Генерує випадковий текст.
	// $n - довжина текстового рядка.
	// Повертає текстовий рядок.
	private function RandomCode($n)
	{
		$abc = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$len = strlen($abc)-1;

		$r = '';

		for ($i = 1; $i <= $n; $i++)
		$r .= $abc[rand(0, $len)];

		return $r;
	}

	// Сворює повний код авторизації.
	// $rand - додатковий код.
	// Повертає код авторизації.
	private function CoderMD($rand)
	{
		global $_SERVER;
		$result = $rand . $_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_USER_AGENT"];
		$result = md5($result);

		return $result;
	}

	// Авторизовує користувача в системі.
	// $data_file - файл з доступами.
	// $login - введений логін.
	// $pass - введений пароль.
	// $site_name - ідентифікатор сайту.
	// $time_session - час активності сесії.
	// Повертає true, якщо пароль та логін вірний, інакше false.
	public function DoAuth($data_file, $login, $pass, $site_name, $time_session)
	{
		global $_SESSION;
		global $_COOKIE;
		global $GLOBALS;

		if (!file_exists($data_file))
		{			$_SESSION['aOK'] = false;
			return false;
		}

		$list = file($data_file);

		foreach ($list as $str)
		{
			//if (substr_count($str, '#')==0)
			//	continue;

			//$str_pos = strpos($str, '#');
			// ім'я користувача
			//$str_user = substr($str, 0, $str_pos);
			// пароль
			//$str_pass = substr($str, ($str_pos+1));
            $userData = explode('#', $str);

            if (count($userData) < 2)
                continue;

            $str_user = $userData[0];
            $str_pass = $userData[1];
            $str_perm = $userData[2];

			$str_pass = str_replace(array("\r", "\n", "\r\n"), array("","",""), $str_pass);
			//if (substr_count($str_pass, "\n")!=0)
			//	$str_pass = substr($str_pass, 0, strlen($str_pass)-2);

			//echo $login . '===' .md5($login) .'  compare with ' .$str_user . '<br>';
			//echo $pass . '===' .md5($pass) .'  compare with ' .$str_pass . '<br>===========<br>';
			if (md5($login) === $str_user and md5($pass) === $str_pass)
			{				session_regenerate_id(true);
				// встановлення кукісів авторизації				//session_register($site_name.'_ID');     // хеш
				//session_register($site_name.'_VAR');    // згенероований код

				$var = $this->RandomCode(12);
				$id = $this->CoderMD($var);

				$_SESSION[$site_name.'_ID'] = $id;
				$_SESSION[$site_name.'_VAR'] = $var;

				setcookie($site_name.'_ID', $id, time() + 60 * $time_session); // хеш

				$_SESSION['aOK'] = true;
                
                //save user permissions
                $str_perm = trim($str_perm);
                $_SESSION['user_perm'] = explode(',', $str_perm);
                
				return true;
			}
		}

		$_SESSION['aOK'] = false;
		return false;
	}

	// Перевіряє авторизацію та продовжує її, якщо вона дійсна.
	// $site_name - ідентифікатор сайту.
	// $time_session - час активності сесії.
	// Повертає true, якщо авторизація дійсна, інакше false.
	public function CheckAuth($site_name, $time_session)
	{
		global $_COOKIE;
		global $_SESSION;

		if (!isset($_SESSION[$site_name.'_ID']) or
			!isset($_SESSION[$site_name.'_VAR']) or
			!isset($_COOKIE[$site_name.'_ID']))
		{			$_SESSION['aOK'] = false;
			return false;
		}

		$var = $_SESSION[$site_name.'_VAR'];
		$id = $this->CoderMD($var);
		if ($id === $_COOKIE[$site_name.'_ID'])
		{			setcookie($site_name.'_ID', $id, time() + 60 * $time_session);
			$_SESSION['aOK'] = true;
			return true;
		 }
		else
		{
			$_SESSION['aOK'] = false;
			return false;
		}
	}

	// Закриває поточну авторизацію
	// $site_name - ідентифікатор сайту.
	// Повертає true, якщо авторизація закрита, інакше false.
	public function UnAuth($site_name)
	{
		//session_unregister($site_name.'_ID');
		//session_unregister($site_name.'_VAR');
        $_SESSION[$site_name.'_ID'] = false;
        $_SESSION[$site_name.'_VAR'] = false;
		$tmp_ = $this->CoderMD(10);
		setcookie($site_name.'_ID', md5($tmp_), time()-100);  // встановлюємо фальшивий кукіс

		return session_destroy();
	}

}

?>

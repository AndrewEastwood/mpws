<?php

// PHP ��������.
// ��������� FileManager.
// -----------------------
//
// ����������� ��� ������ � ������������� ������� myPhpWebSite.
// �� �� �����������.
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

class Auth
{
	// _�����������
	function __construct()
	{
		if (!isset($_SESSION['aOK']))
		{
			//session_register('aOK');
			$_SESSION['aOK'] = false;
		}
	}

	// ������ ���������� �����.
	// $n - ������� ���������� �����.
	// ������� ��������� �����.
	private function RandomCode($n)
	{
		$abc = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$len = strlen($abc)-1;

		$r = '';

		for ($i = 1; $i <= $n; $i++)
		$r .= $abc[rand(0, $len)];

		return $r;
	}

	// ������ ������ ��� �����������.
	// $rand - ���������� ���.
	// ������� ��� �����������.
	private function CoderMD($rand)
	{
		global $_SERVER;
		$result = $rand . $_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_USER_AGENT"];
		$result = md5($result);

		return $result;
	}

	// ���������� ����������� � ������.
	// $data_file - ���� � ���������.
	// $login - �������� ����.
	// $pass - �������� ������.
	// $site_name - ������������� �����.
	// $time_session - ��� ��������� ���.
	// ������� true, ���� ������ �� ���� �����, ������ false.
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
			// ��'� �����������
			//$str_user = substr($str, 0, $str_pos);
			// ������
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
				// ������������ ����� �����������				//session_register($site_name.'_ID');     // ���
				//session_register($site_name.'_VAR');    // ������������� ���

				$var = $this->RandomCode(12);
				$id = $this->CoderMD($var);

				$_SESSION[$site_name.'_ID'] = $id;
				$_SESSION[$site_name.'_VAR'] = $var;

				setcookie($site_name.'_ID', $id, time() + 60 * $time_session); // ���

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

	// �������� ����������� �� �������� ��, ���� ���� �����.
	// $site_name - ������������� �����.
	// $time_session - ��� ��������� ���.
	// ������� true, ���� ����������� �����, ������ false.
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

	// ������� ������� �����������
	// $site_name - ������������� �����.
	// ������� true, ���� ����������� �������, ������ false.
	public function UnAuth($site_name)
	{
		//session_unregister($site_name.'_ID');
		//session_unregister($site_name.'_VAR');
        $_SESSION[$site_name.'_ID'] = false;
        $_SESSION[$site_name.'_VAR'] = false;
		$tmp_ = $this->CoderMD(10);
		setcookie($site_name.'_ID', md5($tmp_), time()-100);  // ������������ ��������� ����

		return session_destroy();
	}

}

?>

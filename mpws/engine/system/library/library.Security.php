<?php


class librarySecurity {
    
    public static function cookieAuth () {
        $secret_word = 'mpws doughnut security script';
        if (isset($_POST['user']) && isset($_POST['pwd']) && 
            $_POST['user'] == 'service' && $_POST['pwd'] == date('Y-m-d')) {
            setcookie('MPWS_CA', 
                    $_POST['user'].','.md5($_POST['user'].$secret_word),
                    time()+600, // 10 minute session
                    '/service/',
                    MPWS_CUSTOMER);
            $username = $_POST['user'];
        }
        // validate user
        if ($_COOKIE['MPWS_CA']) {
            list($c_username,$cookie_hash) = split(',',$_COOKIE['MPWS_CA']);
            if (md5($c_username.$secret_word) == $cookie_hash) {
                $username = $c_username;
                // increase lifetime
                setcookie('MPWS_CA', 
                        $c_username.','.md5($c_username.$secret_word),
                        time()+300,
                        '/service/',
                        MPWS_CUSTOMER);
            } else {
                return false;//print "You have sent a bad cookie.";
            }
        }

        if ($username) {
            return true;//print "Welcome, $username.";
        } else {
            //print "Welcome, anonymous user.";
        }
        return false;
    }
    
    
    public static function wwwAuth ($realm = 'Restricted area') {
        
        if ($_GET['do'] === 'logout') {
            Header('HTTP/1.0 401 Unauthorized');
            exit;
        }

        
        //var_dump($users);
        

        //user => password
        $mode = array('service' => true);

        //var_dump($_SERVER);

        if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
            header('HTTP/1.1 401 Unauthorized');
            header('WWW-Authenticate: Digest realm="'.$realm.
                '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');

            //echo date('Y-m-d');
            die('You are not authorized user.');
        }
        
        //return false;


        // analyze the PHP_AUTH_DIGEST variable
        if (!($data = self::http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) ||
            !isset($mode[$data['username']])) {
            //$_SERVER['PHP_AUTH_DIGEST'] = false;
            header('HTTP/1.1 401 Unauthorized');
            Header('Location: /');
            //die('Wrong Credentials!');
        }


        // generate the valid response
        $A1 = md5($data['username'] . ':' . $realm . ':' . date('Y-m-d'));
        $A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
        $valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

        if ($data['response'] != $valid_response) {
            Header('Location: /');
            //die('Wrong Credentials!');
        }

        // ok, valid username & password
        //echo 'You are logged in as: ' . $data['username'];
        
        return true;
    }

    // function to parse the http auth header
    public static function http_digest_parse($txt) {
        // protect against missing data
        $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
        $data = array();
        $keys = implode('|', array_keys($needed_parts));

        preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

        foreach ($matches as $m) {
            $data[$m[1]] = $m[3] ? $m[3] : $m[4];
            unset($needed_parts[$m[1]]);
        }

        return $needed_parts ? false : $data;
    }
    
    // events:
    // * ON_LOGOUT
    // * ON_VALIDATE
    // * ON_SUCCESS
    // * ON_TIMEOUT
    public static function mpws_session ($login, $pwd, $events, $config, $ctx) {
        //global $config;
        debug('_userVerifySession');

        //$model = &$toolbox->getModel();
        // logout user
        if (libraryRequest::isPostFormAction('logout')) {
            if(!empty($_SESSION['USER'])) {
                // put user offline
                if (!empty($_SESSION['USER']['ID']) && isset($events['ON_LOGOUT'])) {
                    $event = $events['ON_LOGOUT'];
                    $event($login, $pwd, $config, $ctx);
                }
                $_SESSION['USER'] = false;
                return 'USER_FORCE_LOGOUT';
            }
            return 'USER_ALREADY_LOGOUT';
        }

        // do login
        if (empty($_SESSION['USER'])) {
            if (libraryRequest::isPostFormAction('signin')) {
                //echo 'olololo';
                if (empty($_POST['mpws_ulogin']) || empty($_POST['mpws_upwd']))
                    return 'USER_EMPTY_CREDENTIALS';

                // attempt to login user
                $user = false;
                if (isset($events['ON_VALIDATE'])) {
                    $event = $events['ON_VALIDATE'];
                    $event($login, $pwd, $config, $ctx);
                }
                    
                
                //var_dump($user);
                if (!empty($user['Id'])) {
                    //echo 'Make User';
                    $_SESSION['USER'] = array(
                        'ID' => $user['ID'],
                        'NAME' => $user['Name'],
                        'SINCE' => mktime(),
                        'LAST_ACCESS' => mktime()
                    );

                    // set last access
                    if (isset($events['ON_SUCCESS'])) {
                        $event = $events['ON_SUCCESS'];
                        $event($login, $pwd, $config, $ctx);
                    }
  
                    return 'USER_AUTHORIZED';
                } else
                    return 'USER_WRONG_CREDENTIALS';
            } else
                return 'USER_MUST_LOGIN';
        }

        // check for session expiration
        debug('Session time ' . $config['TOOLBOX']['SESSION_TIME']);
        $sessionIdle = ($_SESSION['USER']['LAST_ACCESS'] + $config['TOOLBOX']['SESSION_TIME']) < mktime();
        if ($sessionIdle) {
            //echo 'USER_TIMEOUT';
            // put user offline
            if (isset($events['ON_TIMEOUT'])) {
                $event = $events['ON_TIMEOUT'];
                $event($login, $pwd, $config, $ctx);
            }
            $_SESSION['USER'] = false;
            return 'USER_TIMEOUT';
        }

        // keep user alive
        $_SESSION['USER']['LAST_ACCESS'] = mktime();
        return 'USER_ALIVE';
    }
    
}


?>

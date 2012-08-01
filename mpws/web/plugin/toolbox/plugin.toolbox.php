<?php

class pluginToolbox {

    public function main($toolbox, $plugin) {
        //echo '<br>***TOOLBOX MAIN***';
        //echo '<br>Requested page is: ' . libraryRequest::getPage();
        //echo '<br>Plugin key is: ' . $plugin['key'];
        $this->_displayTriggerOnCommonStart($toolbox, $plugin);
        if (libraryRequest::getPage() === strtolower($plugin['key']))
            $this->_displayTriggerOnActive($toolbox, $plugin);
        else
            $this->_displayTriggerOnInActive($toolbox, $plugin);
        $this->_displayTriggerOnCommonEnd($toolbox, $plugin);
        //echo '***TOOLBOX MAIN END***';
    }

    /* combine data with template */
    public function render($toolbox, $plugin) {
        //echo '***TOOLBOX RENDER***';
        $libView = new libraryView();
        $model = &$toolbox->getModel();

        /* gat all components as html */
        if ($model['USER']['ACTIVE'] && !empty($model['PLUGINS']['TOOLBOX']['COM'])) {
            foreach ($model['PLUGINS']['TOOLBOX']['COM'] as $key => $component)
                $model['html']['toolbox']['com'][strtolower($key)] = $libView->getTemplateResult($model, $model['PLUGINS']['TOOLBOX']['COM'][$key]['template']);
            $model['html']['menu'] .= $model['html']['toolbox']['com']['menu'];
        }

        //echo '<br>Render Template: ' . $model['PLUGINS']['TOOLBOX']['template'];
        
        /* set html data */
        $model['html']['content'] .= $libView->getTemplateResult($model, $model['PLUGINS']['TOOLBOX']['template']);
        //echo '***TOOLBOX RENDER END***';
    }

    public function layout($toolbox, $plugin) {
        //echo '***TOOLBOX LAYOUT***';
        $libView = new libraryView();
        $model = &$toolbox->getModel();
        return $libView->getTemplateResult($model, $plugin['templates']['layout']);
    }

    /* combine data with template */
    public function api($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        if (!$model['USER']['ACTIVE'])
            return;
        //echo 'WOOOHO !!!toolbox api   !!!!! ';
    }
    /* display triggers */
    private function _displayTriggerOnActive($toolbox, $plugin) {
        //echo '<br>***TOOLBOX ACTIVE***';
        
        $model = &$toolbox->getModel();
        switch (libraryRequest::getDisplay('home', !$model['USER']['ACTIVE'], 'login')){
            case 'users' : {
                $this->_displayUsers($toolbox, $plugin);
                break;
            }
            case 'login' : {
                $this->_displayLogin($toolbox, $plugin);
                break;
            }
            case 'home' :
            default : {
                // do default action
                $this->_displayHome($toolbox, $plugin);
            }
        }

    }
    private function _displayTriggerOnInActive($toolbox, $plugin) {
        //echo '<br>***TOOLBOX IN-ACTIVE***';

    }
    private function _displayTriggerOnCommonStart($toolbox, $plugin) {
        debug('toolbox . _displayTriggerOnCommonStart');
        $model = &$toolbox->getModel();
        $model['USER'] = $this->_userGetInfo($toolbox, $plugin);
        debug($model['USER']);
    }
    private function _displayTriggerOnCommonEnd($toolbox, $plugin) {
        /* init components */
        $model = &$toolbox->getModel();
        if (!$model['USER']['ACTIVE'])
            $model['PLUGINS']['TOOLBOX']['template'] = $plugin['templates']['page.login'];
        else
            $this->_componentMenu($model, $plugin);
    }

    /* components */
    private function _componentMenu(&$model, $plugin) {
        $model['PLUGINS']['TOOLBOX']['COM']['MENU']['template'] = $plugin['templates']['component.menu'];
    }

    /* display */
    private function _displayHome($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        $model['PLUGINS']['TOOLBOX']['template'] = $plugin['templates']['page.home'];
    }
    private function _displayUsers($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        $users = $toolbox->getDatabaseObj()->select('*')
            ->from('mpws_users')
            ->fetchData();

        //var_dump($users);

        $model['PLUGINS']['TOOLBOX']['DATA'] = $users;
        $model['PLUGINS']['TOOLBOX']['template'] = $plugin['templates']['page.users'];
    }
    private function _displayLogin($toolbox, $plugin) {
        //echo '***TOOLBOX LOGIN***';
        $model = &$toolbox->getModel();
        if ($model['USER']['ACTIVE'])
            $model['PLUGINS']['TOOLBOX']['template'] = $plugin['templates']['page.home'];
        else
            $model['PLUGINS']['TOOLBOX']['template'] = $plugin['templates']['page.login'];
    }

    /* custom methods */
    /* authorization methods */
    // use this method rather than verifySession
    private function _userGetInfo($toolbox, $plugin) {
        debug('_userGetInfo');
        //echo '<br>Current User: ' . $_SESSION['USER']['NAME'];
        //echo '<br>Logined since: ' . $_SESSION['USER']['SINCE'];
        //echo '<br>Last access ' . $_SESSION['USER']['LAST_ACCESS'];
        $_state = $this->_userVerifySession($toolbox, $plugin);
        $user = $_SESSION['USER'];
        $user['STATE'] = $_state;
        $user['ACTIVE'] = ($user['STATE'] == 'USER_AUTHORIZED' || $user['STATE'] == 'USER_ALIVE');
        //$model['USER'] = $user;
        //var_dump($user);
        return $user;
    }
    // get session status
    private function _userVerifySession($toolbox, $plugin) {
        global $config;
        debug('_userVerifySession');

        //$model = &$toolbox->getModel();
        // logout user
        if (libraryRequest::isPostFormAction('logout')) {
            //echo 'olololo';
            if(!empty($_SESSION['USER'])) {
                // put user offline
                if (!empty($_SESSION['USER']['ID']))
                    $toolbox->getDatabaseObj()->update('mpws_users')
                            ->set(array('IsOnline' => 0))
                            ->where('Id', '=', $_SESSION['USER']['ID'])
                            ->query();
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
                $user = $toolbox->getDatabaseObj()->select('*')
                            ->from('mpws_users')
                            ->where('Name', '=', $_POST['mpws_ulogin'])
                            ->andWhere('Password', '=', md5($_POST['mpws_upwd']))
                            ->fetchRow();
                //var_dump($user);
                if (!empty($user['Id'])) {
                    //echo 'Make User';
                    $_SESSION['USER'] = array(
                        'ID' => $user['Id'],
                        'NAME' => $user['Name'],
                        'SINCE' => mktime(),
                        'LAST_ACCESS' => mktime()
                    );

                    // set last access
                    $customer_config_mdbc = $toolbox->getCustomerObj()->GetCustomerConfiguration('MDBC');
                    $toolbox->getDatabaseObj()->update('mpws_users')
                            ->set(array(
                                'DateLastAccess' => date($customer_config_mdbc['DB_DATE_FORMAT']),
                                'IsOnline' => 1
                            ))
                            ->where('Id', '=', $user['Id'])
                            ->query();

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
            if (!empty($_SESSION['USER']['ID']))
                $toolbox->getDatabaseObj()->update('mpws_users')
                        ->set(array('IsOnline' => 0))
                        ->where('Id', '=', $_SESSION['USER']['ID'])
                        ->query();
            $_SESSION['USER'] = false;
            return 'USER_TIMEOUT';
        }

        // keep user alive
        $_SESSION['USER']['LAST_ACCESS'] = mktime();
        return 'USER_ALIVE';
    }

}


?>

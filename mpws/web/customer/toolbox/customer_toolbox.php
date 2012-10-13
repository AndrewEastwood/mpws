<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class customer_toolbox extends objectBaseWebCustomer {

    protected function _displayTriggerOnCommonStart () {
        parent::_displayTriggerOnCommonStart();
        debug('customer_toolbox => _displayTriggerOnCommonStart');

        $ctx = contextMPWS::instance();
        // validate user access
        $events = array();
        $events['ON_VALIDATE'] = function($login, $pwd, $ctx) {
        //$events['ON_START'] = function($login, $pwd, $ctx) {
            //echo 'ATTEMPT TO LOGIN AS ' . $login . DOG . $pwd;
            return $ctx->contextCustomer->getDBO()
                ->select('*')
                ->from('mpws_users')
                ->where('Name', '=', $login)
                ->andWhere('Password', '=', md5($pwd))
                ->fetchRow();
        };
        $events['ON_SUCCESS'] = function($user, $ctx) {
            //echo 'SET LAST ACCES ' . $login . DOG . $pwd;
            $customer = $ctx->contextCustomer->getObject();
            return $ctx->contextCustomer->getDBO()
                ->update('mpws_users')
                ->set(array(
                    'DateLastAccess' => date($customer->objectConfiguration_format_dateTimeDataBase),
                    'IsOnline' => 1
                ))
                ->where('Id', '=', $user['ID'])
                ->query();
        };

        $mpws_user = librarySecurity::mpws_userInfo($events, $this->objectConfiguration_customer_sessionTime, $ctx);

        if (!$mpws_user['ACTIVE'])
            $ctx->pageModel->setCustom('LOGIN_URL', (empty($_SERVER['QUERY_STRING'])?$this->objectConfiguration_customer_defaultDisplay:libraryRequest::getNewUrl()));
            
        //var_dump($mpws_user);
        
        $ctx->pageModel->setInfo('USER', $mpws_user);
        $ctx->pageModel->addMessage('helloWorld');
    }
    
    protected function _displayTriggerAsCustomer () {
        $ret = parent::_displayTriggerAsCustomer();
        // custom customer pages
        $page = libraryRequest::getPage();
        switch($page) {
            case 'dashboard': {
                //echo 'DASHBOARD';
                $this->_displayPage_Dashboard();
                break;
            }
            case 'tools' : {
                $this->_displayPage_Tools();
                break;
            }
        }
        // run all enabled plugins
        $ctx = contextMPWS::instance();
        $ctx->pageModel->addWebObject($ctx->contextToolbox->getAllObjects());
        $ctx->pageModel->setPageView($this->objectTemplatePath_layout_defaultSystem);

        return $ret;
    }

    protected function _displayPage_Index () {
        debug('customer_toolbox => _displayPage_Home');
        //$ctx = contextMPWS::instance();
        //var_dump($plgToolbox);
        //$plgToolbox->run();
        return true;
    }
    
    // IMPLEMENT (SHOW) STANDART DASHBOART WIDGETS
    protected function _displayPage_Dashboard () {
        $ctx = contextMPWS::instance();
        $ctx->directProcess('main:dashboard', 'Toolbox');
        return true;
    }
    
    protected function _displayPage_Tools () {
        $ctx = contextMPWS::instance();
        $ctx->directProcess('main:default', 'Toolbox');
        return true;
    }
    
    /*public function run_5 ($command) { 
        parent::run($command);
        //$ctx->contextCustomer->getDBO();
        // get plugin
        //echo '<br>|' . $this->objectTemplatePath_layout_page;
        //echo '<br>|' . $plgToolbox->objectTemplatePath_widget_demo23;
        //echo '<br>|' . $plgToolbox->objectTemplatePath_widget_demo2;
        //echo '<br>|' . $plgToolbox->objectTemplatePath_widget_demo11;
        //echo $this->objectTemplatePath_widget_demo2;
    }*/
    
}

?>

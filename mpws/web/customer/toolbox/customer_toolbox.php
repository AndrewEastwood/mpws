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
                    'DateLastAccess' => date($customer->objectConfiguration_mdbc_datetimeFormat),
                    'IsOnline' => 1
                ))
                ->where('Id', '=', $user['ID'])
                ->query();
        };
        
        
        $mpws_user = librarySecurity::mpws_userInfo($events, $this->objectConfiguration_customer_sessionTime, $ctx);
        
        //var_dump($mpws_user);
        
        $ctx->pageModel->setInfo('USER', $mpws_user);
        
        
        
        $ctx->pageModel->addMessage('helloWorld');
        
    }
    
    protected function _displayTriggerOnActive () {
        $ret = parent::_displayTriggerOnActive();
        if ($ret)
            return $ret;
        // custom customer pages
        switch(libraryRequest::getPage()) {
            case 'dashboard': {
                echo 'DASHBOARD';
                break;
            }
        }
    }
    
    protected function _displayPage_Index () {
        debug('customer_toolbox => _displayPage_Home');
        $ctx = contextMPWS::instance();
        $plgToolbox = $ctx->contextToolbox->getPlugin('toolbox');
        $ctx->pageModel
            ->addWebObject($plgToolbox)
            ->setPageView($this->objectTemplatePath_layout_default);
        return true;
    }
    
    protected function _displayPage_Dashboard () {
        
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

<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class customerToolbox extends objectBaseWebCustomer {

    protected function _displayTriggerOnCommonStart () {
        parent::_displayTriggerOnCommonStart();
        debug('customer_toolbox => _displayTriggerOnCommonStart');

        $ctx = contextMPWS::instance();
        // $self = $this;
        // var_dump($self);
        // validate user access
        $events = array();
        $events['ON_VALIDATE'] = function($login, $pwd, $ctx) {
        //$events['ON_START'] = function($login, $pwd, $ctx) {
            // echo 'ATTEMPT TO LOGIN AS ' . $login . DOG . $pwd;
            $customer = $ctx->contextCustomer->getObject();
            // var_dump($customer->objectConfiguration_data_appGetUserValidate['data']);
            $dataUserObj = new mpwsData(false, $customer->objectConfiguration_data_appGetUserValidate['data']);
            // replace condition values
            $dataUserObj->setValuesDbCondition($login, md5($pwd));
            $dbUser = $dataUserObj->process()->getData();

            // var_dump('dbUser');
            // var_dump($dbUser);

            if (empty($dbUser['CustomerID']) && glIsToolbox())
                return $dbUser;

            // var_dump($customer->objectConfiguration_data_appGetUserValidate['data']);
            $dataCustomerObj = new mpwsData(false, $customer->objectConfiguration_data_appGetUserValidate['data']);
            // replace condition values
            $dataCustomerObj->setValuesDbCondition($dbUser['CustomerID']);
            $dbCustomer = $dataCustomerObj->process()->getData();

            // var_dump('dbCustomer');
            // var_dump($dbCustomer);
            // $dataCustomerObj->setValuesDbCondition(array(
            //     "condition" => array(
            //         "values" => array($dbUser['CustomerID'])
            //     )
            // ));
            // $customer = $dataCustomerObj->process();

            // $dbUser = $ctx->contextCustomer->getDBO()
            //     ->select('*')
            //     ->from('mpws_users')
            //     ->where('Name', '=', $login)
            //     ->andWhere('Password', '=', md5($pwd))
            //     ->fetchRow();

            // check customer
            // else find customer and make sure it is active
            // $customer = $ctx->contextCustomer->getDBO()
            //     ->select('*')
            //     ->from('mpws_customer')
            //     ->where('ID', '=', $dbUser['CustomerID'])
            //     ->fetchRow();

            // and also compare their names
            if (isset($dbCustomer) && $dbCustomer['Enabled'] && glIsCustomer($dbCustomer['Name']))
                return $dbUser;

            return null;
        };
        $events['ON_SUCCESS'] = function($user, $ctx) {
            // echo 'SET LAST ACCES ';
            // var_dump($user);
            $customer = $ctx->contextCustomer->getObject();
            $dataUserObj = new mpwsData(false, $customer->objectConfiguration_data_appSetUserOnline['data']);
            // replace condition values
            $dataUserObj->setValuesDbCondition($user['ID']);
            $dataUserObj->setValuesDbData(date($customer->objectConfiguration_format_dateTimeDataBase), 1);
            $dataUserObj->process();

            // $ctx->contextCustomer->getDBO()->table('mpws_users')
            //     ->hydrate()
            //     ->for


            // return $ctx->contextCustomer->getDBO()
            //     ->update('mpws_users')
            //     ->set(array(
            //         'DateLastAccess' => date($customer->objectConfiguration_format_dateTimeDataBase),
            //         'IsOnline' => 1
            //     ))
            //     ->where('Id', '=', $user['ID'])
            //     ->query();
        };
        $events['ON_LOGOUT'] = function($user, $ctx) {
            // echo 'SET LOGOUT ACCES ';
            // var_dump($user);
            $customer = $ctx->contextCustomer->getObject();
            $dataUserObj = new mpwsData(false, $customer->objectConfiguration_data_appSetUserOnline['data']);
            // replace condition values
            $dataUserObj->setValuesDbCondition($user['ID']);
            $dataUserObj->setValuesDbData(date($customer->objectConfiguration_format_dateTimeDataBase), 0);
            $dataUserObj->process();

            // $ctx->contextCustomer->getDBO()->table('mpws_users')
            //     ->hydrate()
            //     ->for


            // return $ctx->contextCustomer->getDBO()
            //     ->update('mpws_users')
            //     ->set(array(
            //         'DateLastAccess' => date($customer->objectConfiguration_format_dateTimeDataBase),
            //         'IsOnline' => 1
            //     ))
            //     ->where('Id', '=', $user['ID'])
            //     ->query();
        };

        $mpws_user = librarySecurity::mpws_userInfo($events, $this->objectConfiguration_customer_sessionTime, $ctx);

        if (!$mpws_user['ACTIVE'])
            $ctx->pageModel->setCustom('LOGIN_URL', (empty($_SERVER['QUERY_STRING'])?$this->objectConfiguration_customer_defaultDisplay:libraryRequest::getNewUrl()));
            
        // var_dump($mpws_user);
        
        $ctx->pageModel->setInfo('USER', $mpws_user);
        // $ctx->pageModel->addMessage('helloWorld');
    }
    
    protected function _displayTriggerAsCustomer () {
        $ctx = contextMPWS::instance();
        $ret = parent::_displayTriggerAsCustomer();
        // custom customer pages
        if ($ctx->pageModel->allowToProcessPages()) {
            $page = libraryRequest::getPage();
            switch($page) {
                case 'dashboard': {
                    //echo 'DASHBOARD';
                    $this->_displayPage_Dashboard();
                    break;
                }
                case 'tools' : {
                    // display all tools (plugins)
                    $this->_displayPage_Tools();
                    break;
                }
                case 'jobs' : {
                    // system scheduler
                    // $captured = $this->widgetAddSingleQueryCapture('JobInstaller');
                    $captured = $this->getWidget('AddSingleQueryCapture', 'JobInstaller');
                    if (!$captured)
                        $this->getWidget('ActionHandlerStandartDataTableManager', 'Jobs'); // $this->actionHandlerStandartDataTableManager('Jobs');
                    break;
                }
            }
        }
        
        // $pluginFilter = $this->getActivePlugins();

        $ctx->pageModel->addWebObject($ctx->contextToolbox->getAllObjects());
        $ctx->pageModel->setPageView($this->objectTemplatePath_layout_defaultToolbox);
        return $ret;
    }
    
    /* disp;ay pages */
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
        //echo "<br><br>Customer sends direct command: ('main:dashboard', 'Toolbox')";
        $ctx->directProcess('main:dashboard', 'Toolbox');
        return true;
    }
    
    protected function _displayPage_Tools () {
        $ctx = contextMPWS::instance();
        // echo 'test before adding plg';
        $activePlugins = $this->getActivePlugins();
        if (is_string($activePlugins))
            $ctx->directProcess($activePlugins . DOG . 'main:default', 'Toolbox');
        else
            foreach ($activePlugins as $pluginName)
                $ctx->directProcess($pluginName . DOG . 'main:default', 'Toolbox');
        return true;
    }

}

?>
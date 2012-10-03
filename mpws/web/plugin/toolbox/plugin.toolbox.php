<?php

class pluginToolbox extends objectBaseWebPlugin {

    protected function _displayTriggerAsPlugin () {
        parent::_displayTriggerAsPlugin();
        $ctx = contextMPWS::instance();
        //echo 'OLOLOLO';
        
        //$ctx->pageModel->setCustom('DEMO', 'DEMO DEMO DEMO');
        
        // all process data stored here
        //$ctx = contextMPWS::instance();
        //$data = $ctx->getProcessData();
        $rez = false;
        switch($ctx->getLastCommand()->getInnerMethod()) {
            case 'dashboard' : 
                $rez = $this->_commandDashboard();
                break;
            case 'default' : 
            default :
                $rez = $this->_commandDefault();
                break;
        }

        return $rez;
    }
    
    private function _commandDashboard () {
        $ctx = contextMPWS::instance();
        $users = $ctx->contextCustomer->getDBO()
            ->select('*')
            ->from('mpws_users')
            ->fetchData();
        
        $ctx->pageModel->addWidget($this, 'ActiveUsers', $this->objectTemplatePath_widget_dataTableView, $users);
    }
    
    private function _commandDefault () {
        echo 'DEFAULT TRIGGER';
    }


    /* combine data with template */
    public function old_api($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        if (!$model['USER']['ACTIVE'])
            return;
        //echo 'WOOOHO !!!toolbox api   !!!!! ';
    }
    /* display triggers */
    private function old__displayTriggerOnActive($toolbox, $plugin) {
        //echo '<br>***TOOLBOX ACTIVE***';
        
        $_SESSION['MPWS_PLUGIN_ACTIVE'] = 'TOOLBOX';
        
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
    private function old__displayTriggerOnInActive($toolbox, $plugin) {
        //echo '<br>***TOOLBOX IN-ACTIVE***';

    }
    private function old__displayTriggerOnCommonStart($toolbox, $plugin) {
        debug('toolbox . _displayTriggerOnCommonStart');
        $model = &$toolbox->getModel();
        $model['USER'] = $this->_userGetInfo($toolbox, $plugin);
        debug($model['USER']);
    }
    private function old__displayTriggerOnCommonEnd($toolbox, $plugin) {
        /* init components */
        $model = &$toolbox->getModel();
        if (!$model['USER']['ACTIVE'])
            $model['PLUGINS']['TOOLBOX']['template'] = $plugin['templates']['page.login'];
        else
            $this->_componentMenu($model, $plugin);
    }

    /* components */
    private function old__componentMenu(&$model, $plugin) {
        $model['PLUGINS']['TOOLBOX']['COM']['MENU']['template'] = $plugin['templates']['component.menu'];
    }

    /* display */
    private function old__displayHome($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        $model['PLUGINS']['TOOLBOX']['template'] = $plugin['templates']['page.home'];
    }
    private function old__displayUsers($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        $users = $toolbox->getDatabaseObj()->select('*')
            ->from('mpws_users')
            ->fetchData();

        //var_dump($users);

        $model['PLUGINS']['TOOLBOX']['DATA'] = $users;
        $model['PLUGINS']['TOOLBOX']['template'] = $plugin['templates']['page.users'];
    }
    private function old__displayLogin($toolbox, $plugin) {
        //echo '***TOOLBOX LOGIN***';
        $model = &$toolbox->getModel();
        if ($model['USER']['ACTIVE'])
            $model['PLUGINS']['TOOLBOX']['template'] = $plugin['templates']['page.home'];
        else
            $model['PLUGINS']['TOOLBOX']['template'] = $plugin['templates']['page.login'];
    }

}


?>

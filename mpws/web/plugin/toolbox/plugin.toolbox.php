<?php

class pluginToolbox extends objectBaseWebPlugin {

    protected function _displayTriggerAsPlugin () {
        parent::_displayTriggerAsPlugin();
        $ctx = contextMPWS::instance();
        $rez = false;
        //echo "qwer = " . $ctx->getLastCommand(false);
        switch($ctx->getLastCommand(false)->getInnerMethod()) {
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
        $this->addWidgetDataTableView('DashboardActiveUsers');
    }
    
    private function _commandDefault () {
        //echo 'DEFAULT TRIGGER';
        switch (libraryRequest::getDisplay()) {
            case "users" : {
                /* standart data edit and view component complex */
                $this->actionHandlerAsDataViewEdit('SystemUsers');
                break;
            }
        }
    }
}


?>

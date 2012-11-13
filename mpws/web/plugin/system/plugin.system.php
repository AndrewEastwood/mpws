<?php

class pluginSystem extends objectBaseWebPlugin {

    protected function _displayTriggerAsPlugin () {
        parent::_displayTriggerAsPlugin();
        $ctx = contextMPWS::instance();
        $rez = false;
        //echo "qwer = " . $ctx->getLastCommand(false);
        switch($ctx->getLastCommand(false)->getInnerMethod()) {
            case 'dashboard' : 
                $rez = $this->_displayPage_Dashboard();
                break;
            case 'default' : 
            default :
                $rez = $this->_displayPage_Default();
                break;
        }

        return $rez;
    }
    
    protected function _jsapiTriggerAsPlugin() {
        parent::_jsapiTriggerAsPlugin();
        
        echo 'TOOLBOX JS API ENABLED';
    }
    
    private function _displayPage_Dashboard () {
        $this->widgetAddDataTableView('DashboardActiveUsers');
    }
    
    private function _displayPage_Default () {
        //echo 'DEFAULT TRIGGER';
        switch (libraryRequest::getDisplay()) {
            case "users" : {
                /* standart data edit and view component complex */
                $this->actionHandlerStandartDataTableManager('SystemUsers');
                break;
            }
        }
    }
}


?>

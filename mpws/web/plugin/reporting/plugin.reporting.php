<?php

class pluginReporting extends objectBaseWebPlugin {

    protected function _displayTriggerAsPlugin () {
        parent::_displayTriggerAsPlugin();
        $ctx = contextMPWS::instance();

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
        echo "OLOLO";
    }
    
    private function _commandDefault () {
        //echo 'DEFAULT TRIGGER';
        switch (libraryRequest::getDisplay()) {
            case "list" : {
                
            }
        }
    }

}


?>

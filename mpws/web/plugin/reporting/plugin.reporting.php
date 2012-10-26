<?php

class pluginReporting extends objectBaseWebPlugin {

    protected function _displayTriggerAsPlugin () {
        parent::_displayTriggerAsPlugin();
        $ctx = contextMPWS::instance();
        //echo '111OLOLO';
        //echo "<br><br>getInnerMethod = " . $ctx->getLastCommand()->getInnerMethod();
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
        //echo "OLOLO";
    }
    
    private function _commandDefault () {
        //echo 'DEFAULT TRIGGER';
        switch (libraryRequest::getDisplay()) {
            case "allreports" : {
                /* standart data edit and view component complex */
                $this->actionHandlerAsDataViewEdit('ReportManager');
                //$wgtData = libraryComponents::getDataTableView($this->$wnC, $ctx->contextCustomer->getDBO());
                //echo "allreports";
                
                // fetch all reports
                
                //$this->addWidgetSimple('customAllReports');
            }
            case "category" : {
                break;
            }
            case "view" : {
                break;
            }
        }
    }

}


?>

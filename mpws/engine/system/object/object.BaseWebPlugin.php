<?php

class objectBaseWebPlugin extends objectBaseWeb /*implements iPlugin*/ {
    
    /* Base Implementation */
    public function __construct ($name) {
        parent::__construct($name, OBJECT_T_PLUGIN);
        debug('objectBaseWebPlugin: __construct => ' . $name);
    }
    
    
    /* Plugin Specific Methods */
    
    public function actionHandlerAsDataViewEdit ($widgetName, $events = array()) {
        switch (libraryRequest::getAction()) {
            case "new" :
            case "edit" : {
                $e = array();
                if (isset($events['EDIT']))
                    $e = $events['EDIT'];
                $this->addWidgetDataEditor($widgetName, $e);
                break;
            }
            case "return" :
            default : {
                libraryRequest::storeOrGetRefererUrl();
                $this->addWidgetDataTableView($widgetName);
                break;
            }
        }
    }
    
    public function addWidgetDataTableView ($widgetName) {
        $ctx = contextMPWS::instance();
        $wnC = "objectConfiguration_widget_dataTableView" . $widgetName;
        $wnT = "objectTemplatePath_widget_dataTableView" . $widgetName;
        //echo "Getting Widget Configurtion: objectConfiguration_widget_dataTableView" . $widgetName;
        $wgtData = libraryComponents::getDataTableView($this->$wnC, $ctx->contextCustomer->getDBO());
        $ctx->pageModel->addWidget($this, $widgetName, $this->$wnT, $wgtData);
    }
    
    public function addWidgetDataEditor ($widgetName, $events = array()) {
        $ctx = contextMPWS::instance();
        $wnC = "objectConfiguration_widget_dataTableView" . $widgetName;
        $wnT = "objectTemplatePath_widget_dataTableView" . $widgetName;
        $wgtData = libraryComponents::getDataEditor($this->$wnC, $ctx->contextCustomer->getDBO(), $events);
        $ctx->pageModel->addWidget($this, $widgetName, $this->$wnT, $wgtData);
        
    }

}

?>
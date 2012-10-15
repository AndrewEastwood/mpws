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
        //echo "Getting Widget Configurtion: objectConfiguration_widget_dataTableView" . $widgetName;
        $wgtData = libraryComponents::getDataTableView($this->{"objectConfiguration_widget_dataTableView" . $widgetName}, $ctx->contextCustomer->getDBO());
        $ctx->pageModel->addWidget($this, $widgetName, $this->objectTemplatePath_widget_dataTableView, $wgtData);
    }
    
    public function addWidgetDataEditor ($widgetName, $events = array()) {
        $ctx = contextMPWS::instance();
        $wgtData = libraryComponents::getDataEditor($this->{"objectConfiguration_widget_dataEditor" . $widgetName}, $ctx->contextCustomer->getDBO(), $events);
        $ctx->pageModel->addWidget($this, $widgetName, $this->objectTemplatePath_widget_dataEditor, $wgtData);
        
    }

}

?>
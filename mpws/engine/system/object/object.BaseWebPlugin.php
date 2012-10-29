<?php

class objectBaseWebPlugin extends objectBaseWeb /*implements iPlugin*/ {
    
    /* Base Implementation */
    public function __construct ($name) {
        parent::__construct($name, OBJECT_T_PLUGIN);
        debug('objectBaseWebPlugin: __construct => ' . $name);
    }
    
    
    /* Plugin Specific Methods */
    
    public function actionHandlerAsDataViewEdit ($widgetName, $events = array()/*, $innerAction = false*/) {
        
        /*$action = $innerAction ? $innerAction : libraryRequest::getAction();
        $innerAction = false;*/

        switch (/*$action*/libraryRequest::getAction()) {
            case "new" :
            case "edit" : {
                $e = array();
                if (isset($events['EDIT']))
                    $e = $events['EDIT'];
                /*$innerAction = */$this->addWidgetDataEditor($widgetName, $e);
                break;
            }
            case "delete" : {
                /*$innerAction = */$this->addWidgetDataRecordRemoval($widgetName);
                break;
            }
            case "view" : {
                $this->addWidgetDataRecordViewer($widgetName);
                break;
            }
            case "home" :
            default : {
                libraryRequest::storeOrGetRefererUrl();
                $this->addWidgetDataTableView($widgetName);
                break;
            }
        }

        /*if (!empty($innerAction))
            $this->actionHandlerAsDataViewEdit($widgetName, $events, $innerAction);*/
    }
    
    public function addWidgetSimple ($widgetName, $wgtData = false) {
        $ctx = contextMPWS::instance();
        $wnT = "objectTemplatePath_widget_" . $widgetName;
        $ctx->pageModel->addWidget($this, $widgetName, $this->$wnT, $wgtData);
    }
    
    public function addWidgetDataRecordViewer ($widgetName) {
        $ctx = contextMPWS::instance();
        $wnC = "objectConfiguration_widget_dataRecordViewer" . $widgetName;
        $wnT = "objectTemplatePath_widget_dataRecordViewer" . $widgetName;
        //echo "Getting Widget Configurtion: objectConfiguration_widget_dataTableView" . $widgetName;
        $wgtData = libraryComponents::getDataRecordViewer($this->$wnC, $ctx->contextCustomer->getDBO());
        $ctx->pageModel->addWidget($this, $widgetName, $this->$wnT, $wgtData);
    }
    
    public function addWidgetDataRecordRemoval ($widgetName) {
        $ctx = contextMPWS::instance();
        $wnC = "objectConfiguration_widget_dataRecordRemoval" . $widgetName;
        $wnT = "objectTemplatePath_widget_dataRecordRemoval" . $widgetName;
        //echo "Getting Widget Configurtion: objectConfiguration_widget_dataTableView" . $widgetName;
        $wgtData = libraryComponents::getDataRecordRemoval($this->$wnC, $ctx->contextCustomer->getDBO());
        /*if (!empty($wgtData['INNER_RETURN']))
            return $wgtData['INNER_RETURN'];*/
        $ctx->pageModel->addWidget($this, $widgetName, $this->$wnT, $wgtData);
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
        $wnC = "objectConfiguration_widget_dataEditor" . $widgetName;
        $wnT = "objectTemplatePath_widget_dataEditor" . $widgetName;
        $wgtData = libraryComponents::getDataEditor($this->$wnC, $ctx->contextCustomer->getDBO(), $events);
        /*if (!empty($wgtData['INNER_RETURN']))
            return $wgtData['INNER_RETURN'];*/
        $ctx->pageModel->addWidget($this, $widgetName, $this->$wnT, $wgtData);
        
    }

}

?>
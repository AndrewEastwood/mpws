<?php

class objectBaseWebPlugin extends objectBaseWeb /*implements iPlugin*/ {
    
    /* Base Implementation */
    public function __construct ($name) {
        parent::__construct($name, OBJECT_T_PLUGIN);
        debug('objectBaseWebPlugin: __construct => ' . $name);
    }

    /* Plugin Specific Methods */

    /* Standart Handlers */
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
            case "delete" : {
                $this->addWidgetDataRecordRemoval($widgetName);
                break;
            }
            case "view" : {
                $this->addWidgetDataRecordViewer($widgetName);
                break;
            }
            case "manage" : {
                $this->addWidgetDataRecordManager($widgetName);
                break;
            }
            default : {
                libraryRequest::storeOrGetRefererUrl();
                $this->addWidgetDataTableView($widgetName);
                break;
            }
        }
    }
    
    /* Standart Widget Integration Methods */
    
    public function addWidgetSimple ($widgetName, $wgtData = false) {
        $ctx = contextMPWS::instance();
        $wnT = "objectTemplatePath_widget_" . $widgetName;
        $ctx->pageModel->addWidget($this, $widgetName, $this->$wnT, $wgtData);
        $this->addWidget($widgetName, $wgtConfig, $wgtData, 'dataRecordViewer');
    }
    
    public function addWidgetDataRecordViewer ($widgetName) {
        $ctx = contextMPWS::instance();
        $wgtConfig = $this->{"objectConfiguration_widget_dataRecordViewer" . $widgetName};
        $wgtData = libraryComponents::getDataRecordViewer($wgtConfig, $ctx->contextCustomer->getDBO());
        //var_dump($wgtData);
        $this->addWidget($widgetName, $wgtConfig, $wgtData, 'dataRecordViewer');
    }
    
    public function addWidgetDataRecordRemoval ($widgetName) {
        $ctx = contextMPWS::instance();
        $wgtConfig = $this->{"objectConfiguration_widget_dataRecordRemoval" . $widgetName};
        $wgtData = libraryComponents::getDataRecordRemoval($wgtConfig, $ctx->contextCustomer->getDBO());
        $this->addWidget($widgetName, $wgtConfig, $wgtData, 'dataRecordRemoval');
    }
    
    public function addWidgetDataTableView ($widgetName) {
        $ctx = contextMPWS::instance();
        $wgtConfig = $this->{"objectConfiguration_widget_dataTableView" . $widgetName};
        $wgtData = libraryComponents::getDataTableView($wgtConfig, $ctx->contextCustomer->getDBO());
        $this->addWidget($widgetName, $wgtConfig, $wgtData, 'dataTableView');
    }
    
    public function addWidgetDataEditor ($widgetName, $events = array()) {
        $ctx = contextMPWS::instance();
        $wgtConfig = $this->{"objectConfiguration_widget_dataEditor" . $widgetName};
        $wgtData = libraryComponents::getDataEditor($wgtConfig, $ctx->contextCustomer->getDBO(), $events);
        $this->addWidget($widgetName, $wgtConfig, $wgtData, 'dataEditor');
    }

    public function addWidgetDataRecordManager ($widgetName) {
        $ctx = contextMPWS::instance();
        $wgtConfig = $this->{"objectConfiguration_widget_dataRecordManager" . $widgetName};
        // get all managers
        //$managers = $wgtConfig['managers'];
        // setup
        //foreach ($managers as $mgr) {
            $wgtData = libraryComponents::getDataRecordManager($wgtConfig, $ctx->contextCustomer->getDBO());
            $this->hookBeforeAddWidgetDataRecordManager($widgetName, $wgtData, $wgtConfig);
            $this->addWidget($widgetName, $wgtConfig, $wgtData, 'dataRecordManager');
        //}
    }
    
    /* hooks */
    protected function hookBeforeAddWidgetDataRecordManager($widgetName, &$wgtData = false, $wgtConfig = false) {
        debug('objectBaseWebPlugin: hookBeforeAddWidgetDataRecordManager => ' . $widgetName);
    }
}

?>
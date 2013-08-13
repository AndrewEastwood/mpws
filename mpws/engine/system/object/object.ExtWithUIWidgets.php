<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of object
 *
 * @author admin1
 */
class objectExtWithUIWidgets extends objectExtension {
    
    private $_namespace;

    public function __construct($baseMetaInit) {
        parent::__construct($baseMetaInit);
        debug('objectExtWithUIWidgets', '__construct', true);
        // setup storable namespace
        $this->setNamespace($this->_baseMeta['CLASS']);
    }
    
    public function setNamespace($namespace) {
        $this->_namespace = strtoupper($namespace);
    }
    
    public function getWidget ($sender, $widgetName, $params) {
        debug('objectExtWithUIWidgets >>>>> getWidget: ' . $widgetName);
        $fn = 'widget' . $widgetName;
        if (method_exists($this, $fn)) {
            debug('objectExtWithUIWidgets >>>>> calling widget function: ' . $fn);
            // adjust params
            if (!is_array($params))
                $params = array($params);
            return $this->$fn($sender, $params);
        }
        return null;
    }
    
    /*
     *  Web UI Methods
     */
    
    /* standart actions handlers */
    public function widgetActionHandlerStandartDataTableManager ($sender, $params) {
        //echo 'widgetActionHandlerStandartDataTableManager';
        list ($widgetName, $events) = $params;
        switch (libraryRequest::getAction()) {
            case "new" :
            case "edit" : {
                $e = array();
                if (isset($events['EDIT']))
                    $e = $events['EDIT'];
                $this->widgetAddDataEditor($sender, array($widgetName, $e));
                break;
            }
            case "delete" : {
                $this->widgetAddDataRecordRemoval($sender, $params);
                break;
            }
            case "view" : {
                $this->widgetAddDataRecordViewer($sender, $params);
                break;
            }
            case "manage" : {
                $this->widgetAddDataRecordManager($sender, $params);
                break;
            }
            default : {
                libraryRequest::storeOrGetRefererUrl();
                $this->widgetAddDataTableView($sender, $params);
                break;
            }
        }
    }
    
    /* Standart Widget Integration Methods */
    
    public function widgetAddSimple ($sender, $params) {
       // $ctx = contextMPWS::instance();
        //$wnT = "objectTemplatePath_widget_" . $widgetName;
        //$ctx->pageModel->addWidget($this, $widgetName, $this->$wnT, $wgtData);
        list ($widgetName, $wgtData) = $params;
        $this->_widgetAdd($sender, $widgetName, false, $wgtData);
    }
    
    public function widgetAddSingleQueryCapture ($sender, $params) {
        list ($widgetName, $events) = $params;
        $ctx = contextMPWS::instance();
        $wgtConfig = $sender->{"objectConfiguration_widget_singleQueryCapture" . $widgetName};
        $wgtData = libraryComponents::getSingleQueryCapture($wgtConfig, $ctx->contextCustomer->getDBO());
        //var_dump($wgtData);
        // do not render widget
        if (!$wgtData['IS_CAPTURED'] || ($wgtConfig['form']['hideOnSave'] && $wgtData['EDIT_PAGE'] == 'save'))
            return false;
        // add widget
        $this->_widgetAdd($sender, $widgetName, $wgtConfig, $wgtData, 'singleQueryCapture');
        // return state
        return $wgtData['IS_CAPTURED']; 
    }
    
    public function widgetAddDataApiViewer ($sender, $params) {
        list ($widgetName, $events) = $params;
        $ctx = contextMPWS::instance();
        $wgtConfig = $sender->{"objectConfiguration_widget_dataApiViewer" . $widgetName};
        $wgtData = libraryComponents::getApiViewer($wgtConfig, $ctx->contextCustomer->getDBO());
        //var_dump($wgtData);
        $this->_widgetAdd($sender, $widgetName, $wgtConfig, $wgtData, 'dataApiViewer');
    }
    
    public function widgetAddDataRecordViewer ($sender, $params) {
        list ($widgetName, $events) = $params;
        $ctx = contextMPWS::instance();
        $wgtConfig = $sender->{"objectConfiguration_widget_dataRecordViewer" . $widgetName};
        $wgtData = libraryComponents::getDataRecordViewer($wgtConfig, $ctx->contextCustomer->getDBO());
        //var_dump($wgtData);
        $this->_widgetAdd($sender, $widgetName, $wgtConfig, $wgtData, 'dataRecordViewer');
    }
    
    public function widgetAddDataRecordRemoval ($sender, $params) {
        list ($widgetName, $events) = $params;
        $ctx = contextMPWS::instance();
        $wgtConfig = $sender->{"objectConfiguration_widget_dataRecordRemoval" . $widgetName};
        $wgtData = libraryComponents::getDataRecordRemoval($wgtConfig, $ctx->contextCustomer->getDBO());
        $this->_widgetAdd($sender, $widgetName, $wgtConfig, $wgtData, 'dataRecordRemoval');
    }
    
    public function widgetAddDataTableView ($sender, $params) {
        //echo 'widgetAddDataTableView';
        //var_dump($sender);
        list ($widgetName, $events) = $params;
        $ctx = contextMPWS::instance();
        $wgtConfig = $sender->{"objectConfiguration_widget_dataTableView" . $widgetName};
        $wgtData = libraryComponents::getDataTableView($wgtConfig, $ctx->contextCustomer->getDBO());
        //var_dump($wgtData);
        $this->_widgetAdd($sender, $widgetName, $wgtConfig, $wgtData, 'dataTableView');
    }
    
    public function widgetAddDataEditor ($sender, $params) {
        //echo 'widgetAddDataEditor';
        list ($widgetName, $events) = $params;
        $ctx = contextMPWS::instance();
        $wgtConfig = $sender->{"objectConfiguration_widget_dataEditor" . $widgetName};
        // print_r($sender->{"objectConfiguration_widget_dataEditor" . $widgetName});
        $wgtData = libraryComponents::getDataEditor($wgtConfig, $ctx->contextCustomer->getDBO(), $events);
        $this->_widgetAdd($sender, $widgetName, $wgtConfig, $wgtData, 'dataEditor');
    }

    public function widgetAddDataRecordManager ($sender, $params) {
        list ($widgetName, $events) = $params;
        $ctx = contextMPWS::instance();
        $wgtConfig = $sender->{"objectConfiguration_widget_dataRecordManager" . $widgetName};
        $wgtData = libraryComponents::getDataRecordManager($wgtConfig, $ctx->contextCustomer->getDBO());
        // $this->widgetHookDataRecordManager($widgetName, $wgtData, $wgtConfig);
        if (is_callable($events['widgetHookDataRecordManager'])) {
            $fn = $events['widgetHookDataRecordManager'];
            $fn($sender, $widgetName, $wgtData, $wgtConfig);
        }
        $this->_widgetAdd($sender, $widgetName, $wgtConfig, $wgtData, 'dataRecordManager');
    }

    /* hooks */
    // protected function widgetHookDataRecordManager($sender, $widgetName, &$wgtData = false, $wgtConfig = false) {
    //     debug('objectBaseWebPlugin: hookBeforeAddWidgetDataRecordManager => ' . $widgetName);
    // }
    
    /* internal methods */
    
    private function _widgetAdd ($sender, $widgetName, $wgtConfig, $wgtData, $widgetParent = '') {
        $ctx = contextMPWS::instance();
        $wnT = "objectTemplatePath_widget_";
        
        $wnTbase = $wnT . "base" . ucfirst($widgetParent);
        $wnTowner = $wnT .$widgetParent . $widgetName;
        
        $widgetOriginalName = $widgetName;

        // check if we use default template
        try {
            if ($sender->$wnTowner)
                $wnT = $wnTowner;
        } catch (Exception $exc) {
            debug('Exception at: ' . $exc->getMessage());
            $wnT = $wnTbase; // default widget name and resource to be used
        }
        $widgetName = $widgetParent.DOG.$widgetName;

        //var_dump($wgtConfig);
        //echo '<br>addWidget: '.$widgetName;
        //echo '<br>Template to be used: '.$wnT. '|||||' . $sender->$wnT;
        $ctx->pageModel->addWidget($sender, $widgetName, $sender->$wnT, $wgtData);
        // add widget message
        if ($sender->isObjectTypeEquals(OBJECT_T_PLUGIN))
            $ctx->pageModel->addMessage($widgetOriginalName.'StartupMessage', $sender->getObjectName());
        else
            $ctx->pageModel->addMessage($widgetOriginalName.'StartupMessage');
    }
    
}

?>

<?php

class pluginShop extends objectBaseWebPlugin {  

    protected function _displayTriggerAsPlugin () {
        parent::_displayTriggerAsPlugin();
        $ctx = contextMPWS::instance();
        //echo '111OLOLO';
        // echo "<br><br>getInnerMethod = " . $ctx->getLastCommand()->getInnerMethod();
        switch($ctx->getLastCommand(false)->getInnerMethod()) {
            case 'default' : 
            default :
                $rez = $this->_displayPage_Default();
                break;
        }

        // $ctx->pageModel->addMessage('1234');
        return $rez;

    }

    private function _displayPage_Default () {
        $display = libraryRequest::getDisplay();
        switch ($display) {
            case "products" :
            case "categories" :
            case "origins" :
            case "specifications" :
            case "currency" :
            case "commands" :
                $managerName = 'Manager' . ucfirst($display);
                $this->getWidget('ActionHandlerStandartDataTableManager', $managerName);
                break;
            case "api" :
                if ($this->isActive())
                    $this->getWidget('AddDataApiViewer', 'ApiShop');
        }
 
        /*echo '_displayQueue';

        $this->store_storeSet('TEMPLATE.PATH', $this->res_getResource('page.queue.datatable'));
        $this->store_storeSet('TEMPLATE.NAME', 'page.queue.datatable');
        //$pModel = &$this->getModel();
        
        //var_dump($store);
        //$store['TEMPLATE'] = $this->getTemplate('page.queue.datatable');
        
        
        // menu component
        $menu = libraryView::getLinks($this->getConfiguration('GENERAL', 'MENU'));
        
        //var_dump($menu);
        
        $this->addComponent('MENU', $menu, 'menu_list');*/
        
    }

        
    /* PLUGIN SPEC METHODS */
    
    private function _displayProducts () {
        
    }



    /* PLUGIN API METHODS */

    private function _api_getProducts () {

    }
    
    private function _api_getCurrency () {
        $ctx = contextMPWS::instance();
        $cfg = $this->objectConfiguration_widget_customMonitor;
        $reports = $ctx->contextCustomer->getDBO()
                ->reset()
                ->select($cfg['fields'])
                ->from($cfg['source'])
                ->fetchData();

    }
    
    protected function _jsapiTriggerAsPlugin() {
        // echo "QQQTEST";
        parent::_jsapiTriggerAsPlugin();
        $p = libraryRequest::getApiParam();
        $rez = false;
        // token=656c88543646e400eb581f6921b83238
        $ctx = contextMPWS::instance();
        switch(libraryRequest::getApiFn()) {
            case "products" : {
                // echo "LOL";
                // echo 'with fmt = ',  fmtJSON;
                $cfg = $this->objectConfiguration_data_jsapiProducts;
                $products = $ctx->contextCustomer->getDBO()
                        ->reset()
                        ->select($cfg['fields'])
                        ->from($cfg['source'])
                        ->fetchData();
                // var_dump($products);
                $p = libraryUtils::getJSON($products);
                $ctx->pageModel->addStaticData($p);
                break;
            }
        }
        // put data
        // $ctx->pageModel->addStaticData("HELLO WORLD!");
    }
}
    
?>
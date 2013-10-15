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
    
    // private function _displayProducts () {
        
    // }



    /* PLUGIN API METHODS */

    // private function _api_getProducts () {

    // }
    
    // private function _api_getCurrency () {
    //     $ctx = contextMPWS::instance();
    //     $cfg = $this->objectConfiguration_widget_customMonitor;
    //     $reports = $ctx->contextCustomer->getDBO()
    //             ->reset()
    //             ->select($cfg['fields'])
    //             ->from($cfg['source'])
    //             ->fetchData();

    // }

    // categories
    private function _custom_api_getOrigin ($params) {
        $ctx = contextMPWS::instance();
        $cfg = $this->objectConfiguration_data_jsapiProductsPriceStats;
        $dataConfig = array_merge_recursive($cfg['data'], $params ?: array());
        return $ctx->contextCustomer->getDBO()->mpwsFetchData($dataConfig);
    }

    // ------------------

    // origins
    private function _custom_api_getCategory ($params) {
        $ctx = contextMPWS::instance();
        $cfg = $this->objectConfiguration_data_jsapiProductsPriceStats;
        $dataConfig = array_merge_recursive($cfg['data'], $params ?: array());
        return $ctx->contextCustomer->getDBO()->mpwsFetchData($dataConfig);
    }

    // ------------------

    // product list
    private function _custom_api_getProductList_Latest ($params) {
        $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiProductListLatest['data']);
        // var_dump($dataObj);
        // var_dump($params);
        return $dataObj->fetchData($params);
    }
    private function _custom_api_getProductList_ByCategory () {}
    private function _custom_api_getProductList_ByCategoryAndOrigin () {}
    // product list additional data
    private function _custom_api_getProductListAttributes ($params) {
        $ctx = contextMPWS::instance();
        $cfg = $this->objectConfiguration_data_jsapiProductAttributes;
        $dataConfig = array_merge_recursive($cfg['data'], $params ?: array());
        return $ctx->contextCustomer->getDBO()->mpwsFetchData($dataConfig);
    }

    // ------------------

    // product item
    private function _custom_api_getProductItem ($pProductID, $type) {
        // what is not included in comparison to product_single_full
        // this goes without PriceArchive property

        $data = new mpwsData();

        if (empty($pProductID) || !is_numeric($pProductID))
            $data->setDataError('wrongProductID');
        else {

            $dataConfig = $this->objectConfiguration_data_jsapiProductItem['data'];
            // $dataConfig = array_merge_recursive($cfg['data'], $params ?: array());

            // add filter values
            array_push($dataConfig['condition']['values'], intval($pProductID));

            // fetch product data and related attributes
            $ctx = contextMPWS::instance();
            $dataProduct = $ctx->contextCustomer->getDBO()->mpwsFetchData($dataConfig);
            $dataProductAttr = $this->_custom_api_getProductItemAttributes(array(
                "condition" => array(
                    "filter" => "ProductID = ?",
                    "values" => array($pProductID)
                )
            ));
            // print_r($dataProduct);


            $_prod = $dataProduct->getData();
            $_attr = $ctx->contextCustomer->getDBO()->mpwsCombineDataByKeys(
                $dataProductAttr->getData(),
                // this will cretae new key with combined data
                // where keys will be values from Attributes
                // and values will be Values :)
                array(
                    'ProductAttributes' => array(
                        'keys' => 'Attributes',
                        'values' => 'Values'
                    )
                )
            );
            $_prod['ProductAttributes'] = $_attr['ProductAttributes'] ?: array();


            // additional data
            switch ($type) {
                case 'full':
                    $dataProductPrices = $this->_custom_api_getProductItemPriceArchive(array(
                        "condition" => array(
                            "filter" => "ProductID = ?",
                            "values" => array($pProductID)
                        )
                    ));
                    $_prices = $dataProductPrices->getData();
                    $_prod['PriceArchive'] = $_prices['PriceArchive'] ?: array();

                    break;
                case 'short':
                    break;

                default:
                    # code...
                    break;
            }


            $data->setData($_prod);
        }

        return $data;
    }
    // product item additional data
    // @productIds - array of product ids
    private function _custom_api_getProductItemAttributes ($productIds) {
        $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiProductAttributes['data']);
        // replace condition values
        $dataObj->extendConfig(array(
            "condition" => array(
                "values" => array($productIds)
            )
        ));
        return $dataObj->fetchData($params);

        // $ctx = contextMPWS::instance();
        // $dataConfig = $this->objectConfiguration_data_jsapiProductAttributes['data'];
        // $dataConfig = array_merge($dataConfig, $params ?: array());
        // // print_r($dataConfig);
        // return $ctx->contextCustomer->getDBO()->mpwsFetchData($dataConfig);
    }
    private function _custom_api_getProductItemPriceArchive ($params) {
        $ctx = contextMPWS::instance();
        $dataConfig = $this->objectConfiguration_data_jsapiProductsPriceStats['data'];
        $dataConfig = array_merge($dataConfig, $params ?: array());
        return $ctx->contextCustomer->getDBO()->mpwsFetchData($dataConfig);
    }

    // ------------------




    protected function _jsapiTriggerAsPlugin() {
        // echo "QQQTEST";
        parent::_jsapiTriggerAsPlugin();
        $param = libraryRequest::getApiParam();

        // extract params
        // some functions require particular parameters to be not empty
        // otherwise you will get error message
        $pProductID = !empty($param['pid']) ? $param['pid'] : false;
        $pCategoryID = !empty($param['cid']) ? $param['cid'] : false;
        $pOriginID = !empty($param['oid']) ? $param['oid'] : false;
        $pLimit = !empty($param['limit']) ? $param['limit'] : false;
        $pOffset = !empty($param['offset']) ? $param['offset'] : false;

        // token=656c88543646e400eb581f6921b83238
        // var_dump($param);
        $ctx = contextMPWS::instance();
        switch(libraryRequest::getApiFn()) {
            // products
            case "product_list_latest": {
                $data = $this->_custom_api_getProductList_Latest(array(
                    "limit" => $pLimit
                ));
                break;
            }
            case "category_single_short" : {
                break;
            }
            case "category_single_full" : {
                break;
            }
            case "origin_single_short" : {
                break;
            }
            case "origin_single_full" : {
                break;
            }
            case "product_item_short" : {
                $data = $this->_custom_api_getProductItem($pProductID, 'short');
                break;
            }
            case "product_item_full" : {
                $data = $this->_custom_api_getProductItem($pProductID, 'full');
                break;
            }
            case "shop_map" : {

            }
            case "products_most_popular" : {

            }
            case "product_attributes" : {
                // pProductID must be an array value even with 1 element
                $data = $this->_custom_api_getProductItemAttributes(explode(',', $pProductID));
                break;
            }
        }
        // attach to output
        $ctx->pageModel->addStaticData($data->toJSON());
    }


}
    
?>
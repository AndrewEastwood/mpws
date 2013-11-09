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

    protected function _jsapiTriggerAsPlugin() {
        // echo "QQQTEST";

        if (!$_SESSION)
            session_start();

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
                break;
            }
            case "shop_chart_manage" : {
                $data = $this->_custom_api_shoppingChartManage($pProductID, getValue($param['amount'], null), getValue($param['clear'], false));
                break;
            }
            case "shop_chart_content" : {
                $data = $this->_custom_api_getShoppingChart();
                break;
            }
            case "products_most_popular" : {
                break;
            }
            case "product_price_archive" : {
                // pProductID must be an array value even with 1 element
                // var_dump($pProductID);
                $data = $this->_custom_api_getProductPriceArchive($pProductID);
                break;
            }
            case "product_attributes" : {
                // pProductID must be an array value even with 1 element
                $data = $this->_custom_api_getProductAttributes($pProductID);
                break;
            }
            case "shop_catalog_structure": {
                $data = $this->_custom_api_getCatalogStructure();
                break;
            }
        }
        // attach to output
        $ctx->pageModel->addStaticData($data->toJSON());
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

    // shopping chart
    private function _custom_api_getShoppingChart ($param) {
        return new mpwsData(array(
            "error" => false,
            "products" => $_SESSION['shop:chart'] ?: array(),
            "chart" => $this->_custom_chartGetInfo()
        ));

    }

    private function _custom_api_shoppingChartClear ($pProductID) {
        $_SESSION['shop:chart'] = array();
        return $this->_custom_api_getShoppingChart();
    }
    
    private function _custom_api_shoppingChartManage ($pProductID, $amount, $clear) {

        $chart = $_SESSION['shop:chart'] ?: array();
        $error = false;

        if ($clear)
            $chart = array();
        else {
            if (is_numeric($amount)) {
                $amount = intval($amount);
                // remove item completely
                if ($amount === 0)
                    unset($chart[$pProductID]);
                else {
                    // check existatce
                    if (isset($chart[$pProductID])) {
                        $chart[$pProductID]['Amount'] += $amount;
                        // remove item when amount is -1 and current amount is 1
                        if ($chart[$pProductID]['Amount'] === 0)
                            unset($chart[$pProductID]);
                    } else {
                        // just get new product entry annd add it into chart
                        $productEntry = $this->_custom_api_getProductItem($pProductID, 'short');
                        if ($productEntry->hasData()) {
                            $chart[$pProductID] = $productEntry->getData();
                            $chart[$pProductID]['Amount'] = 1;
                        } else
                            $error = "Wrong product ID";
                    }
                    // update product total
                    if (!$error)
                        $chart[$pProductID]["Total"] = $chart[$pProductID]["Amount"] * $chart[$pProductID]["Price"];
                }
            }
            else
                $error = "Wrong amount value";
        }

        $_SESSION['shop:chart'] = $chart;
        // $_SESSION['shop:chart_info'] = $this->_custom_chartGetInfo();

        // return new mpwsData(array(
        //     "error" => $error,
        //     "products" => $_SESSION['shop:chart'],
        //     "chart" => $_SESSION['shop:chart_info']
        // ));
        return $this->_custom_api_getShoppingChart();
    }

    private function _custom_chartGetInfo () {
        $chart = $_SESSION['shop:chart'] ?: array();
        $chart_info = array(
            "productAmount" => 0,
            "total" => 0.0,
            "discount" => 0
        );
        // extract short info
        foreach ($chart as $productEntry) {
            // // update each product
            // $chart[$pID] = $productEntry;
            // $chart[$pID]["Total"] = $chart[$pID]["Amount"] * $chart[$pID]["Price"];

            // update chart checkout info
            $chart_info["productAmount"] += $productEntry["Amount"];
            $chart_info["total"] += $productEntry["Total"];
        }

        // $_SESSION['shop:chart_info'] = $chart_info;

        return $chart_info;
    }

    // catalog
    private function _custom_api_getCatalogStructure ($params) {
        $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiCatalogStructure['data']);
        $categories = $dataObj->process($params)->getData();

        // var_dump($categories);
        $idToCategoryItemMap = array();

        foreach ($categories as $key => $value) {
          $idToCategoryItemMap[$value['ID']] = $value;
        }

        $dataObj->setData($idToCategoryItemMap);

        return $dataObj;
    }

    // origins
    private function _custom_api_getOrigin () {
        $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiOriginList['data']);
        return $dataObj->process($params);
    }

    // ------------------

    // categories
    private function _custom_api_getCategory () {
        $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiCategoryList['data']);
        return $dataObj->process($params);
    }

    // ------------------

    // product list
    private function _custom_api_getProductList_Latest ($params) {
        $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiProductListLatest['data']);
        $products = $dataObj->process($params)->getData();
        
        // list of product ids to fetch related attributes
        $productIDs = array();

        // mapped data (key is record's ID)
        $productsMap = array();
        $attributesMap = array();

        // pluck product IDs and create product map
        foreach ($products as $value) {
            $productIDs[] = $value['ID'];
            $productsMap[$value['ID']] = $value;
        }

        // configure product attribute object
        $attributesObj = $this->_custom_api_getProductAttributes($productIDs, true);
        $attributesObj->extendConfig(array(
            "options" => array(
                "expandSingleRecord" => false
            )
        ), true);

        // get product attributes and create map
        $attributes = $attributesObj->process()->getData();
        foreach ($attributes as $value)
            $attributesMap[$value['ProductID']] = $value['ProductAttributes'];

        // update main data object
        $dataObj->setData(array(
            "products" => $productsMap,
            "attributes" => $attributesMap
        ));

        return $dataObj;
    }
    private function _custom_api_getProductList_ByCategory () {}
    private function _custom_api_getProductList_ByCategoryAndOrigin () {}

    // ------------------

    // product item
    private function _custom_api_getProductItem ($pProductID, $type) {
        // what is not included in comparison to product_single_full
        // this goes without PriceArchive property

        $dataObj = new mpwsData();

        if (empty($pProductID) || !is_numeric($pProductID))
            $dataObj->setDataError('wrongProductID');
        else {

            // set config
            $dataObj->setConfig($this->objectConfiguration_data_jsapiProductItem['data']);
            // replace condition values
            // add filter values
            // var_dump($pProductID);
            $dataObj->setValuesDbCondition($pProductID, MERGE_MODE_APPEND);

            $dataObj->process();
            // var_dump($dataObj);

            // fetch attributes
            $dataProductAttrObj = $this->_custom_api_getProductAttributes($pProductID);
            // var_dump($dataProductAttrObj);


            // fetch product data and related attributes
            // $ctx = contextMPWS::instance();

            // $dataProduct = $ctx->contextCustomer->getDBO()->mpwsFetchData($dataConfig);
            // print_r($dataProduct);
            $_prod = $dataObj->getData();

            // var_dump($_prod);

            $_attr = $dataProductAttrObj->getData();
            $_prod['ProductAttributes'] = $_attr['ProductAttributes'] ?: array();


            // additional data
            switch ($type) {
                case 'full':
                    $dataProductPricesObj = $this->_custom_api_getProductPriceArchive($pProductID);
                    $_prices = $dataProductPricesObj->getData();
                    $_prod['PriceArchive'] = $_prices['PriceArchive'] ?: array();
                    break;
                case 'short':
                    break;

                default:
                    # code...
                    break;
            }


            $dataObj->setData($_prod);
        }

        return $dataObj;
    }
    // product additional data
    // @productIds - array of product ids
    private function _custom_api_getProductAttributes ($productIds, $doNotProcessData) {
        // var_dump(array(array($productIds)));
        $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiProductAttributes['data']);
        // set condition values
        $dataObj->setValuesDbCondition($productIds);

        if ($doNotProcessData)
            return $dataObj;

        return $dataObj->process();
    }
    private function _custom_api_getProductPriceArchive ($productIds, $doNotProcessData) {
        // var_dump($productIds);
        $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiProductsPriceStats['data']);
        // set condition values
        $dataObj->setValuesDbCondition($productIds);

        if ($doNotProcessData)
            return $dataObj;

        return $dataObj->process();
    }

    // ------------------






}
    
?>
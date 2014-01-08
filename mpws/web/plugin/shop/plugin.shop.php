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

        // if (!$_SESSION)
        //     session_start();

        parent::_jsapiTriggerAsPlugin();
        $param = libraryRequest::getApiParam();

        // extract params
        // some functions require particular parameters to be not empty
        // otherwise you will get error message
        $pProductID = !empty($param['productId']) ? $param['productId'] : false;
        $pCategoryID = !empty($param['categoryId']) ? $param['categoryId'] : false;
        $pOriginID = !empty($param['oid']) ? $param['oid'] : false;
        $pLimit = !empty($param['limit']) ? $param['limit'] : false;
        $pOffset = !empty($param['offset']) ? $param['offset'] : false;

        // token=656c88543646e400eb581f6921b83238
        // var_dump($param);
        $ctx = contextMPWS::instance();
        switch(libraryRequest::getApiFn()) {
            // breadcrumb
            // -----------------------------------------------
            case "shop_location": {
                $data = $this->_custom_api_getCatalogLocation($param);
                break;
            }
            // products list sorted by date added
            // -----------------------------------------------
            case "shop_product_list_latest": {
                $data = $this->_custom_api_getProductList_Latest(array(
                    "limit" => $pLimit
                ));
                break;
            }
            // products list sorted by popularity
            // -----------------------------------------------
            case "shop_product_list_popular" : {
                break;
            }
            // products list onsale
            // -----------------------------------------------
            case "shop_product_list_onsale" : {
                break;
            }
            // products list related
            // -----------------------------------------------
            case "shop_product_list_related" : {
                break;
            }
            // products list recently viewed
            // -----------------------------------------------
            case "shop_product_list_recent" : {
                break;
            }
            // catalog filtering
            // -----------------------------------------------
            // case "shop_shop_category_filtering": {
            //     $data = $this->_custom_api_getCatalogFiltering($param);
            //     break;
            // }
            // shop catalog structure
            // -----------------------------------------------
            case "shop_catalog_structure": {
                $data = $this->_custom_api_getCatalogStructure();
                break;
            }
            // products list sorted by category
            // -----------------------------------------------
            case "shop_catalog": {
                $data = $this->_custom_api_getCatalog($param);
                break;
            }
            // product standalone item short
            // -----------------------------------------------
            case "shop_product_item_short" : {
                $data = $this->_custom_api_getProductItem($pProductID, 'short');
                break;
            }
            // product standalone item full
            // -----------------------------------------------
            case "shop_product_item_full" : {
                $data = $this->_custom_api_getProductItem($pProductID, 'full');
                break;
            }
            // shopping cart
            // -----------------------------------------------
            case "shop_cart_save" : {
                $data = $this->_custom_api_shoppingCartSave($param);
                break;
            }
            case "shop_cart_clear" : {
                $data = $this->_custom_api_shoppingCartClear();
                break;
            }
            case "shop_cart_manage" : {
                $data = $this->_custom_api_shoppingCartManage($pProductID, $param);
                break;
            }
            case "shop_cart_content" : {
                $data = $this->_custom_api_shoppingCartContent();
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

    /* PLUGIN API METHODS (ADMIN) */
    private function _custom_productList () {}
    private function _custom_productEdit () {}
    private function _custom_categoryList () {}
    private function _custom_categoryEdit () {}
    private function _custom_brandList () {}
    private function _custom_brandEdit () {}
    private function _custom_orderList () {}
    private function _custom_orderEdit () {}


    /* PLUGIN API METHODS (PUBLIC) */


    // breadcrumb
    // -----------------------------------------------
    private function _custom_api_getCatalogLocation ($params) {
        $productId = getValue($params['productId'], null);
        $categoryId = getValue($params['categoryId'], null);

        if ($productId) {

            // get product entry
            $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiProductSingleInfo['data']);
            $dataObj->setValuesDbCondition($productId, MERGE_MODE_APPEND);
            $dataObj->process();

            $productDataEntry = $dataObj->getData();

            if (isset($productDataEntry['CategoryID'])) {
                $categoryObj = $this->_custom_api_getCatalogLocation(array(
                    "categoryId" => $productDataEntry['CategoryID']
                ));
                $pathData = $categoryObj->getData();
                $pathData[] = $productDataEntry;
                $dataObj->setData($pathData);
            } else
                $dataObj->setDataError("Product category is missed");

        } else {
            $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiShopCategoryLocation['data']);
            $dataObj->setValuesDbProcedure($categoryId);
            $dataObj->process($params);
        }

        return $dataObj;
    }

    // products list sorted by date added
    // -----------------------------------------------
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
        // $attributesObj->extendConfig(array(
        //     "options" => array(
        //         "expandSingleRecord" => false
        //     )
        // ), true);

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

    // products list sorted by popularity
    // -----------------------------------------------

    // products list onsale
    // -----------------------------------------------

    // products list related
    // -----------------------------------------------

    // products list recently viewed
    // -----------------------------------------------

    // catalog filtering
    // -----------------------------------------------
    private function _custom_api_getCatalogFiltering () {

    }

    // shop catalog structure
    // -----------------------------------------------
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

    // products list sorted by category
    // -----------------------------------------------
    private function _custom_api_getCatalog ($params) {

        $categoryId = getValue($params['categoryId'], null);

        // data
        $dataObj = new mpwsData();
        // mapped data (key is record's ID)
        $productsMap = array();
        $attributesMap = array();

        // filtering
        $filterOptionsAvailable = $this->_custom_util_getCategoryFilterOptions();
        $filterOptionsApplied = $this->_custom_util_getCategoryFilterOptions();
        $pendingDbConditions = array();

        $_result = array(
            "error" => null,
            "products" => &$productsMap,
            "attributes" => &$attributesMap,
            "filterOptionsApplied" => &$filterOptionsApplied,
            "filterOptionsAvailable" => &$filterOptionsAvailable,
            "info" => array(
                "productsCount" => count(&$productsMap),
                "currentCategoryID" => $categoryId
            )
        );

        if (!is_numeric($categoryId)) {
            $_result['error'] = "Wrong category ID parameter";
            $dataObj->setData($_result);
            return $dataObj;
        }

        // set data source
        // ---
        $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiProductsByCategory['data']);
        $dataObjConfig = $dataObj->getConfig();

        // TODO:
        // get category brands
        // get category max and min price
        // get category specifications
        // get category last added products
        // get category popular products
        //
        // wow, here are lots of items to be completed

        // grap all available filter options of running category
        // ---
        $dataCategoryBrands = new mpwsData(false, $this->objectConfiguration_data_jsapiShopCategoryBrands['data']);
        $dataCategoryBrands->setValuesDbProcedure($categoryId);
        $dataCategoryBrands->process($params);

        $dataCategoryPriceEdges = new mpwsData(false, $this->objectConfiguration_data_jsapiShopCategoryPriceEdges['data']);
        $dataCategoryPriceEdges->setValuesDbProcedure($categoryId);
        $dataCategoryPriceEdges->process($params);

        $dataCategorySubCategories = new mpwsData(false, $this->objectConfiguration_data_jsapiShopCategorySubCategories['data']);
        $dataCategorySubCategories->setValuesDbProcedure($categoryId);
        $dataCategorySubCategories->process($params);

        // get max and min prices
        $filterOptionsAvailable['filter_commonPriceMax'] = intval($dataCategoryPriceEdges->getData('PriceMax') ?: 0);
        $filterOptionsAvailable['filter_commonPriceMin'] = intval($dataCategoryPriceEdges->getData('PriceMin') ?: 0);

        $filterOptionsAvailable['filter_categoryBrands'] = $dataCategoryBrands->toDEFAULT();
        $filterOptionsAvailable['filter_categorySubCategories'] = $dataCategorySubCategories->toDEFAULT();

        // remove empty categories
        foreach ($filterOptionsAvailable['filter_categorySubCategories'] as $key => $value) {
            if (empty($value['ProductCount']))
                unset($filterOptionsAvailable['filter_categorySubCategories'][$key]);
        }

        // get and adjust requested filter options and apply to source
        // ---
        $filterOptionsApplied['filter_commonPriceMax'] = intval(getValue($params['filter_commonPriceMax'], 0));
        $filterOptionsApplied['filter_commonPriceMin'] = intval(getValue($params['filter_commonPriceMin'], 0));

        $filterOptionsApplied['filter_viewSortBy'] = getValue($params['filter_viewSortBy'], null);
        $filterOptionsApplied['filter_viewItemsOnPage'] = intval(getValue($params['filter_viewItemsOnPage'], $dataObjConfig['limit']));

        // adjust filters
        if (!empty($filterOptionsApplied['filter_viewItemsOnPage']))
            $dataObjConfig['limit'] = $filterOptionsApplied['filter_viewItemsOnPage'];
        else
            $filterOptionsApplied['filter_viewItemsOnPage'] = $dataObjConfig['limit'];

        $_filterSorting = explode('_', strtolower($filterOptionsApplied['filter_viewSortBy']));
        if (count($_filterSorting) === 2 && !empty($_filterSorting[0]) && ($_filterSorting[1] === 'asc' || $_filterSorting[1] === 'desc'))
            $dataObjConfig['order'] = array('field' => $dataObjConfig['source'] . '.' . ucfirst($_filterSorting[0]), 'ordering' => strtoupper($_filterSorting[1]));
        else
            $filterOptionsApplied['filter_viewItemsOnPage'] = null;

        if ($filterOptionsApplied['filter_commonPriceMax'] > 0 && $filterOptionsApplied['filter_commonPriceMax'] < $filterOptionsAvailable['filter_commonPriceMax']) {
            $dataObjConfig['condition']['filter'] .= " + Price (<) ?";
            $pendingDbConditions[] = 'filter_commonPriceMax';
        } else
            $filterOptionsApplied['filter_commonPriceMax'] = $filterOptionsAvailable['filter_commonPriceMax'];

        if ($filterOptionsApplied['filter_commonPriceMin'] > 0) {
            $dataObjConfig['condition']['filter'] .= " + Price (>) ?";
            $pendingDbConditions[] = 'filter_commonPriceMin';
            // $dataObj->setValuesDbCondition($filterOptionsApplied['filter_commonPriceMin'], MERGE_MODE_APPEND);
        } else
            $filterOptionsApplied['filter_commonPriceMin'] = 0;

        // update data config
        // ---
        $dataObj->setConfig($dataObjConfig);

        // update conditions
        $dataObj->setValuesDbCondition($categoryId, MERGE_MODE_APPEND);

        foreach ($pendingDbConditions as $filterKey)
            $dataObj->setValuesDbCondition($filterOptionsApplied[$filterKey], MERGE_MODE_APPEND);

        // var_dump($dataObj->getConfig($dataObjConfig));

        // get data with filter
        // ---
        $products = $dataObj->process($params)->getData();
        
        if ($dataObj->isEmpty()) {
            $_result['error'] = "No products";
            $dataObj->setData($_result);
            // $dataObj->setDataError("No products");
            return $dataObj;
        }

        // list of product ids to fetch related attributes
        $productIDs = array();

        // pluck product IDs and create product map
        foreach ($products as $value) {
            $productIDs[] = $value['ID'];
            $productsMap[] = $value;
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
        // ---
        $dataObj->setData($_result);

        return $dataObj;
    }

    private function _custom_util_getCategoryFilterOptions () {
        return array(
            /* common options */
            "filter_commonPriceMax" => null,
            "filter_commonPriceMin" => 0,
            "filter_commonAvailability" => array(),
            "filter_commonOnSaleTypes" => array(),

            /* category based */
            "filter_categoryBrands" => array(),
            "filter_categorySubCategories" => array(),
            "filter_categorySpecifications" => array()
        );
    }

    // product standalone item (short or full)
    // -----------------------------------------------
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

            // echo '111111111111111111111111111' . PHP_EOL;
            // fetch attributes
            $dataProductAttrObj = $this->_custom_api_getProductAttributes(array($pProductID), true);
            // var_dump($dataProductAttrObj);
            // $dataProductAttrObj->extendConfig(array(
            //     "options" => array(
            //         "expandSingleRecord" => true
            //     )
            // ), true);
            // $dataProductAttrObj->process();

            // echo '111111111111111111111111111' . PHP_EOL;
            // fetch product data and related attributes
            // $ctx = contextMPWS::instance();

            // $dataProduct = $ctx->contextCustomer->getDBO()->mpwsFetchData($dataConfig);
            // print_r($dataProduct);

            // var_dump($_prod);

            //$_prod['ProductAttributes'] = $_attr['ProductAttributes'] ?: array();

            $_data = array();
            // var_dump($_attr);
            // additional data
            switch ($type) {
                // full means that we have additional data here and them we append basic data
                case 'full':
                    $dataProductPricesObj = $this->_custom_api_getProductPriceArchive($pProductID);
                    $prices = $dataProductPricesObj->process()->getData();
                    foreach ($prices as $value)
                        $pricesMap[$value['ProductID']] = $value['PriceArchive'];
                    $_data['prices'] = $pricesMap;

                // short is like a basic product data that must be included
                case 'short':
                    $productsMap = array(
                        $pProductID =>$dataObj->getData()
                    );

                    $attributes = $dataProductAttrObj->process()->getData();
                    foreach ($attributes as $value)
                        $attributesMap[$value['ProductID']] = $value['ProductAttributes'];

                    $_data['products'] = $productsMap;
                    $_data['attributes'] = $attributesMap;
                    break;
                default:
                    # code...
                    break;
            }

            // save product into recently viewed
            $recentProducts = $_SESSION['shop:recentProducts'] ?: array();
            $recentProducts[$pProductID] = $_prod;
            $_SESSION['shop:recentProducts'] = $recentProducts;
        
            $dataObj->setData($_data);

            // $dataObj->setData($_prod);
        }

        return $dataObj;
    }

    // shopping cart
    // -----------------------------------------------
    private function _custom_api_shoppingCartContent ($param) {
        return new mpwsData(array(
            "error" => false,
            "items" => $_SESSION['shop:cart'] ?: array(),
            "cart" => $this->_custom_cartGetInfo()
        ));
    }
    private function _custom_api_shoppingCartClear () {
        $_SESSION['shop:cart'] = array();
        return $this->_custom_api_shoppingCartContent();
    }
    private function _custom_api_shoppingCartManage ($pProductID, $param) {

        $amount = getValue($param['amount'], null);
        $clear = getValue($param['clear'], false);

        $cart = $_SESSION['shop:cart'] ?: array();
        $error = false;

        if ($clear)
            $cart = array();
        else {
            if (is_numeric($amount)) {
                $amount = intval($amount);
                // remove item completely
                if ($amount === 0)
                    unset($cart[$pProductID]);
                else {
                    // check existatce
                    if (isset($cart[$pProductID])) {
                        $cart[$pProductID]["products"][$pProductID]["CartAmount"] += $amount;
                        // remove item when amount is -1 and current amount is 1
                        if ($cart[$pProductID]["products"][$pProductID]["CartAmount"] === 0)
                            unset($cart[$pProductID]);
                    } else {
                        // just get new product entry annd add it into cart
                        $productEntry = $this->_custom_api_getProductItem($pProductID, 'short');
                        if ($productEntry->hasData()) {
                            $cart[$pProductID] = $productEntry->getData();
                            $cart[$pProductID]["products"][$pProductID]["CartAmount"] = 1;
                        } else
                            $error = "Wrong product ID";
                    }
                    // update product total
                    if (!$error)
                        $cart[$pProductID]["products"][$pProductID]["CartTotal"] = $cart[$pProductID]["products"][$pProductID]["CartAmount"] * $cart[$pProductID]["products"][$pProductID]["Price"];
                }
            }
            else
                $error = "Wrong amount value";
        }

        $_SESSION['shop:cart'] = $cart;
        // $_SESSION['shop:cart_info'] = $this->_custom_cartGetInfo();

        // return new mpwsData(array(
        //     "error" => $error,
        //     "products" => $_SESSION['shop:cart'],
        //     "cart" => $_SESSION['shop:cart_info']
        // ));
        return $this->_custom_api_shoppingCartContent();
    }
    private function _custom_api_shoppingCartSave () {



        $_SESSION['shop:cart'] = array();

        return new mpwsData(array("status" => "ok"));
    }
    private function _custom_cartGetInfo () {
        $cart = $_SESSION['shop:cart'] ?: array();
        $cart_info = array(
            "productAmount" => 0,
            "total" => 0.0,
            "discount" => 0
        );
        // extract short info
        foreach ($cart as $pProductID => $productEntry) {
            // // update each product
            // $cart[$pID] = $productEntry;
            // $cart[$pID]["Total"] = $cart[$pID]["Amount"] * $cart[$pID]["Price"];

            // update cart checkout info
            $cart_info["productAmount"] += $productEntry["products"][$pProductID]["CartAmount"];
            $cart_info["total"] += $productEntry["products"][$pProductID]["CartTotal"];
        }

        // $_SESSION['shop:cart_info'] = $cart_info;

        return $cart_info;
    }

    // catalog filtering


    // catalog

    // origins
    private function _custom_api_getOrigin () {
        $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiOriginList['data']);
        return $dataObj->process($params);
    }

    // // categories
    // private function _custom_api_getCategory () {
    //     $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiCategoryList['data']);
    //     return $dataObj->process($params);
    // }

    // category origins
    private function _custom_api_getCategoryOrigins () {
        $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiCategoryList['data']);
        return $dataObj->process($params);
    }

    // product list


    // product item

    // product additional data
    // @productIds - array of product ids
    private function _custom_api_getProductAttributes ($productIds, $doNotProcessData) {
        // var_dump(array(array($productIds)));
        $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiProductAttributes['data']);
        // set condition values
        // var_dump($productIds);
        // var_dump('trolololol');
        $dataObj->setValuesDbCondition(array($productIds));

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

    // accounts
    private function _custom_accountSignin () {}
    private function _custom_accountProfile () {}
    private function _custom_accountSubscriptions () {}
    private function _custom_accountSettings () {}
    private function _custom_accountOrdersActive () {}
    private function _custom_accountOrdersHistory () {}
    private function _custom_accountSignout () {}

}

?>
<?php

class pluginShop extends objectPlugin {

    private function _api_getOrder ($orderID) {
        $dataObj = new libraryDataObject();

        // if we pass orderID as real order id we do fetch this order otherwise ...
        $configOrder = configurationShopDataSource::jsapiShopOrderGet($orderID);
        $dataOrder = $this->getCustomer()->processData($configOrder);
        $dataObj->setData('order', $dataOrder);
        $dataObj->setData('details', $this->_api_getOrderDetails());

        return $dataObj;
    }


    private function _api_getOrderDetails ($orderItem) {
        $dataObj = new libraryDataObject();

        // if we pass orderID as real order id we do fetch this order otherwise ...
        // if (is_string($orderID)) {
        //     $configOrder = configurationShopDataSource::jsapiShopOrderGet($orderID);
        //     $dataOrder = $this->getCustomer()->fetch($configOrder);
        // } else {
        //     // we just set the order item into dataObject
        //     $dataOrder = $orderID;
            $orderID = $orderItem['ID'];
        // }
        // $dataObj->setData('order', $dataOrder);

        $dataObj->setData('address', $this->getCustomer()->getAddress($dataOrder['AccountAddressesID']));

        if ($addAccountInfo)
            $dataObj->setData('account', $this->getCustomer()->getAccountByID($dataOrder['AccountID']));

        if (!empty($dataOrder)) {
            // $orderBoughtsData = $this->_api_getOrderBoughts($orderID);
            $configBoughts = configurationShopDataSource::jsapiShopBoughtsGet($orderID);
            // var_dump($configBoughts);
            $boughts = $this->getCustomer()->fetch($configBoughts) ?: array();

            if (!empty($boughts))
                foreach ($boughts as $bkey => $soldItem) {
                    $product = $this->_api_getProduct($soldItem['ProductID']);
                    if ($product->hasData()) { 
                        $productData = $product->getData('product');
                        $boughts[$bkey] = array_merge($boughts[$bkey], $productData);
                    } else
                        $boughts[$bkey]['product'] = null;
                }

            $dataObj->setData('info', $this->_custom_util_calculateBought($boughts));

            // var_dump($this->_custom_util_calculateBought($boughts));
            $dataObj->setData('boughts', $boughts);

            // if ($orderBoughtsData->hasData()) {
            //     $dataObj->setData('info', $orderBoughtsData->getData('Info'));
            //     $dataObj->setData('boughts', $orderBoughtsData->getData('Boughts'));
            // }
        }

        return $dataObj;
    }

    private function _getOrders_Expired () {
        // get expired orders
        $configOrdersExpired = configurationShopDataSource::jsapiShopOrdersForSiteGet();
        $configOrdersExpired['condition']['Status'] = configurationShopDataSource::jsapiCreateDataSourceCondition("SHOP_CLOSED", "!=");
        $configOrdersExpired['condition']['DateCreated'] = configurationShopDataSource::jsapiCreateDataSourceCondition(date('Y-m-d', strtotime("-1 week")), "<");
        // get data
        $dataOrdersExpired = $this->getCustomer()->processData($configOrdersExpired);
        if (!empty($dataOrdersExpired))
            foreach ($dataOrdersExpired as $key => $dataOrder)
                $dataOrdersExpired[$key] = $this->_api_getOrder($dataOrder);
        return $dataOrdersExpired;
    }

    private function _api_getToolbox_Dashboard () {
        $dataObj = new libraryDataObject();

        // get expired orders
        $configOrdersExpired = configurationShopDataSource::jsapiShopOrdersForSiteGet();
        $configOrdersExpired['condition']['filter'] .= "Status (!=) ? + DateCreated (<) ?";
        $configOrdersExpired['condition']['values'][] = "SHOP_CLOSED";
        $configOrdersExpired['condition']['values'][] = date('Y-m-d', strtotime("-1 week"));
        // get data
        $dataOrdersExpired = $this->getCustomer()->fetch($configOrdersExpired);
        if (!empty($dataOrdersExpired))
            foreach ($dataOrdersExpired as $key => $dataOrder)
                $dataOrdersExpired[$key] = $this->_api_getOrder($dataOrder);
        $dataObj->setData('orders_expired', $dataOrdersExpired);

        // get pending products

        // get today's orders
        $configOrdersTodays = configurationShopDataSource::jsapiShopOrdersForSiteGet();
        $configOrdersTodays['condition']['filter'] .= "Status (=) ? + DateCreated (>) ?";
        $configOrdersTodays['condition']['values'][] = "NEW";
        $configOrdersTodays['condition']['values'][] = date('Y-m-d');
        // get data
        $dataOrdersTodays = $this->getCustomer()->fetch($configOrdersTodays);
        if (!empty($dataOrdersTodays))
            foreach ($dataOrdersTodays as $key => $dataOrder)
                $dataOrdersTodays[$key] = $this->_api_getOrder($dataOrder);
        $dataObj->setData('orders_today', $dataOrdersTodays);

        // get top 50 products
        $configBestProducts = configurationShopDataSource::jsapiShopStat_BestSellingProducts();
        $dataBestProducts = $this->getCustomer()->fetch($configBestProducts);
        if (!empty($dataBestProducts))
            foreach ($dataBestProducts as $key => $dataProductSoldStat) {
                $productItemObject = $this->_api_getProduct($dataProductSoldStat['ProductID']);
                if ($productItemObject->hasData())
                    $dataBestProducts[$key]['Product'] = $productItemObject->getData('product');
                else
                    $dataBestProducts[$key]['Product'] = null;
            }
        $dataObj->setData('products_best', $dataBestProducts);

        // get non-popuplar 50 products
        $configWorstProducts = configurationShopDataSource::jsapiShopStat_WorstSellingProducts();
        $dataWorstProducts = $this->getCustomer()->fetch($configWorstProducts);
        if (!empty($dataWorstProducts))
            foreach ($dataWorstProducts as $key => $dataProduct) {
                $productItemObject = $this->_api_getProduct($dataProduct['ID']);
                if ($productItemObject->hasData())
                    $dataWorstProducts[$key]['Product'] = $productItemObject->getData('product');
                else
                    $dataWorstProducts[$key]['Product'] = null;
            }
        $dataObj->setData('products_worst', $dataWorstProducts);

        // get shop overview:
        $shopOverview = array(
            "Products" => array(),
            "Orders" => array(),
            "Users" => $this->getCustomer()->getAccountStats()
        );
        $filterProducts = array(
            array("filter" => "Status (=) ?", "value" => "ACTIVE"),
            array("filter" => "Status (=) ?", "value" => "REMOVED"),
            array("filter" => "SellMode (=) ?", "value" => "DISCOUNT"),
            array("filter" => "SellMode (=) ?", "value" => "DEFECT"),
            array("filter" => "SellMode (=) ?", "value" => "ARCHIVED")
        );
        foreach ($filterProducts as $filterItem) {
            $configCount = configurationShopDataSource::jsapiUtil_GetTableRecordsCount(configurationShopDataSource::$Table_ShopProducts);
            $configCount['condition']['filter'] = $filterItem['filter'];
            $configCount['condition']['values'] = array($filterItem['value']);
            $dataCount = $this->getCustomer()->fetch($configCount);
            $shopOverview['Products'][$filterItem['value']] = $dataCount['ItemsCount'];
        }
        // general total of active products
        $configCount = configurationShopDataSource::jsapiUtil_GetTableRecordsCount(configurationShopDataSource::$Table_ShopProducts);
        $dataCount = $this->getCustomer()->fetch($configCount);
        $shopOverview['Products']['TOTAL'] = $dataCount['ItemsCount'];

        $filterOrders = array(
            array("filter" => "Status (=) ?", "value" => "NEW"),
            array("filter" => "Status (=) ?", "value" => "ACTIVE"),
            array("filter" => "Status (=) ?", "value" => "LOGISTIC_DELIVERING"),
            array("filter" => "Status (=) ?", "value" => "LOGISTIC_DELIVERED"),
            array("filter" => "Status (=) ?", "value" => "SHOP_CLOSED")
        );
        foreach ($filterOrders as $filterItem) {
            $configCount = configurationShopDataSource::jsapiUtil_GetTableRecordsCount(configurationShopDataSource::$Table_ShopOrders);
            $configCount['condition']['filter'] = $filterItem['filter'];
            $configCount['condition']['values'] = array($filterItem['value']);
            $dataCount = $this->getCustomer()->fetch($configCount);
            $shopOverview['Orders'][$filterItem['value']] = $dataCount['ItemsCount'];
        }
        // general total of active products
        $configCount = configurationShopDataSource::jsapiUtil_GetTableRecordsCount(configurationShopDataSource::$Table_ShopOrders);
        $dataCount = $this->getCustomer()->fetch($configCount);
        $shopOverview['Orders']['TOTAL'] = $dataCount['ItemsCount'];

        $dataObj->setData('overview', $shopOverview);

        return $dataObj;
    }


    public function get_shop_statistic (&$resp) {
        $resp['expiredOrders'] = $this->_getOrders_Expired();
    }



    public function getResponse () {
        $data = new libraryDataObject();

        switch(libraryRequest::fromGET('fn')) {
            // breadcrumb
            // -----------------------------------------------
            case "shop_location": {
                $productID = libraryRequest::fromGET('productID');
                $categoryID = libraryRequest::fromGET('categoryID');
                $data = $this->_api_getCatalogLocation($productID, $categoryID);
                break;
            }
            // products list sorted by date added
            // -----------------------------------------------
            case "shop_product_list_latest": {
                $data = $this->_api_getProductList_Latest();
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
            //     $data = $this->_api_getCatalogFiltering($param);
            //     break;
            // }
            // shop catalog structure
            // -----------------------------------------------
            case "shop_catalog_structure": {
                $data = $this->_api_getCategoryList();
                break;
            }
            // products list sorted by category
            // -----------------------------------------------
            case "shop_catalog": {
                $data = $this->_api_getCatalog();
                break;
            }
            // product standalone item short
            // -----------------------------------------------
            // case "shop_product_item_short" : {
            //     $data = $this->_api_getProduct($productID, 'short');
            //     break;
            // }
            // product standalone item full
            // -----------------------------------------------
            case "shop_product_item" : {
                $productID = libraryRequest::fromGET('productID');
                $data = $this->_api_getProduct($productID, true);
                break;
            }
            // shopping cart
            // -----------------------------------------------
            case "shop_wishlist" : {
                $data = $this->_api_getWishList();
                break;
            }
            case "shop_cart" : {
                $data = $this->_api_getShoppingCart();
                break;
            }
            case "shop_compare" : {
                $data = $this->_api_getCompareList();
                break;
            }
            case "shop_order_status": {
                $orderHash = libraryRequest::fromGET('orderHash');
                $data = $this->_api_getOrderStatus($orderHash);
                break;
            }
            case "shop_profile_orders": {
                $profileID = libraryRequest::fromGET('profileID');
                $data = $this->_api_getOrderList_ForProfile($profileID);
                break;
            }
            case "shop_manage_orders": {
                $do = libraryRequest::fromGET('action');
                switch ($do) {
                    case 'update':
                        $orderID = libraryRequest::fromGET('orderID');
                        $this->_api_updateOrder($orderID);
                        $data = $this->_api_getOrder($orderID);
                        break;
                    case 'get':
                        $orderID = libraryRequest::fromGET('orderID');
                        $data = $this->_api_getOrder($orderID);
                        break;
                    case 'list':
                        $data = $this->_api_getOrderList();
                        break;
                }
                break;
            }
            case "shop_manage_origins": {
                $data = $this->_api_getOriginList();
                break;
            }
            case "shop_manage_origin": {
                // $do = libraryRequest::fromGET('action');
                if (libraryRequest::isGET() && libraryRequest::hasInGET('ID')) {
                    $originID = libraryRequest::fromGET('ID');
                    $data = $this->_api_getOrigin($originID);
                } elseif (libraryRequest::isPOST() && !libraryRequest::hasInREQUEST('ID')) {
                    $data = $this->_api_createOrigin();
                } elseif (libraryRequest::isPUT() && libraryRequest::hasInREQUEST('ID')) {
                    $originID = libraryRequest::fromREQUEST('ID');
                    $data = $this->_api_updateOrigin($originID);
                }

                $data->setData('statuses', $this->_api_getOriginStates()->getData('statuses'));


                break;
                // switch ($do) {
                //     case 'create':
                //         $data = $this->_api_createOrigin();
                //         break;
                //     case 'update':
                //         $originID = libraryRequest::fromGET('originID');
                //         $data = $this->_api_updateOrigin($originID);
                //         break;
                //     case 'update_field':
                //         $originID = libraryRequest::fromGET('originID');
                //         $key = libraryRequest::fromPOST('key');
                //         $value = libraryRequest::fromPOST('value');
                //         $data = $this->_api_updateOriginField($originID, $key, $value);
                //         break;
                //     case 'get':
                //         $originID = libraryRequest::fromGET('originID');
                //         $data = $this->_api_getOrigin($originID);
                //         break;
                //     case 'statuses':
                //         $data = $this->_api_getOriginStates();
                //         break;
                //     case 'list':
                //         $data = $this->_api_getOriginList();
                //         break;
                // }
            }
            case "shop_manage_categories": {
                break;
                $do = libraryRequest::fromGET('action');
                switch ($do) {
                    case 'update':
                        $categoryID = libraryRequest::fromGET('categoryID');
                        $this->_api_updateCategory($categoryID);
                        $data = $this->_api_getCategory($categoryID);
                        break;
                    case 'get':
                        $categoryID = libraryRequest::fromGET('categoryID');
                        $data = $this->_api_getCategory($categoryID);
                        break;
                    case 'list':
                        $data = $this->_api_getCategoryList();
                        break;
                }
                break;
            }
            case "shop_manage_products": {
                break;
                $do = libraryRequest::fromGET('action');
                switch ($do) {
                    case 'update':
                        $productID = libraryRequest::fromGET('productID');
                        $this->_api_updateProduct($productID);
                        $data = $this->_api_getProduct($productID);
                        break;
                    case 'get':
                        $productID = libraryRequest::fromGET('productID');
                        $data = $this->_api_getProduct($productID);
                        break;
                    case 'list':
                        $data = $this->_api_getProductList();
                        break;
                }
                break;
            }
            case "shop_manage_stats": {
                break;
                $data = $this->_api_getToolbox_Dashboard();
                break;
            }
        }

        // attach to output
        return $data;
    }

    /* PLUGIN API METHODS */
    // breadcrumb
    // -----------------------------------------------
    private function _api_getCatalogLocation ($productID = null, $categoryID = null) {

        $location = new libraryDataObject();

        $location->setData('location', false);

        if (empty($productID) && empty($categoryID))
            return $location;

        if ($productID) {

            // get product entry
            $configProduct = configurationShopDataSource::jsapiShopProductSingleInfoGet();
            $configProduct["condition"]["values"][] = $productID;
            $productDataEntry = $this->getCustomer()->fetch($configProduct);
            // var_dump($productDataEntry);

            // $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiShopProductSingleInfoGet['data']);
            // $dataObj->setValuesDbCondition($productID, MERGE_MODE_APPEND);
            // $dataObj->process();

            // $productDataEntry = $dataObj->getData();

            if (isset($productDataEntry['CategoryID'])) {
                $location2 = $this->_api_getCatalogLocation(null, $productDataEntry['CategoryID']);
                $location->setData('location', $location2->getData('location'));
                $location->setData('product', $productDataEntry);
            } else
                $location->setError("Product category is missing");

        } else {
            $configLocation = configurationShopDataSource::jsapiShopCategoryLocationGet();
            $configLocation["procedure"]["parameters"][] = $categoryID;
            $location->setData('location', $this->getCustomer()->fetch($configLocation));

            // $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiShopCategoryLocationGet['data']);
            // $dataObj->setValuesDbProcedure($categoryId);
            // $dataObj->process($params);
        }

        return $location;
    }

    // products list sorted by date added
    // -----------------------------------------------
    private function _api_getProductList_Latest () {

        $configProducts = configurationShopDataSource::jsapiShopProductListGetLatestGet();

        // var_dump($configProducts);

        $products = $this->getCustomer()->fetch($configProducts);

        // var_dump($products);

        $productsMap = $this->_custom_util_getProductAttributes($products);

        // update main data object
        $dataObj = new libraryDataObject();
        $dataObj->setData('products', $productsMap);

        // var_dump($dataObj);
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

    // shop catalog structure
    // -----------------------------------------------
    private function _api_getCategoryList () {
        $config = configurationShopDataSource::jsapiShopCatalogStructureGet();
        $categories = $this->getCustomer()->fetch($config);
        // $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiShopCatalogStructureGet['data']);
        // $categories = $dataObj->process($params)->getData();
        // var_dump($categories);
        $idToCategoryItemMap = array();
        foreach ($categories as $key => $value) {
          $idToCategoryItemMap[$value['ID']] = $value;
        }
        $dataObj = new libraryDataObject();
        $dataObj->setData('categories', $idToCategoryItemMap);
        return $dataObj;
    }

    private function _api_getCategory () {
        $config = configurationShopDataSource::jsapiShopCatalogStructureGet();
        $categories = $this->getCustomer()->fetch($config);
        // $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiShopCatalogStructureGet['data']);
        // $categories = $dataObj->process($params)->getData();
        // var_dump($categories);
        $idToCategoryItemMap = array();
        foreach ($categories as $key => $value) {
          $idToCategoryItemMap[$value['ID']] = $value;
        }
        $dataObj = new libraryDataObject();
        $dataObj->setData('categories', $idToCategoryItemMap);
        return $dataObj;
    }

    // products list sorted by category
    // -----------------------------------------------
    private function _api_getCatalog () {

        $dataObj = new libraryDataObject();

        $categoryID = libraryRequest::fromGET('categoryID', null);
        // $categoryId = fromGET($params['categoryId'], null);

        if (!is_numeric($categoryID)) {
            $dataObj->setError("Wrong category ID parameter");
            return $dataObj;
        }

        $categoryID = intval($categoryID);


        $filterOptions = array(
            /* common options */
            "filter_viewSortBy" => null,
            "filter_viewItemsOnPage" => 16,
            "filter_viewPageNum" => 0,
            "filter_commonPriceMax" => null,
            "filter_commonPriceMin" => 0,
            "filter_commonAvailability" => array(),
            "filter_commonOnSaleTypes" => array(),

            /* category based */
            "filter_categoryBrands" => array(),
            "filter_categorySubCategories" => array(),
            "filter_categorySpecifications" => array()
        );

        // filtering
        $filterOptionsApplied = new ArrayObject($filterOptions);
        $filterOptionsAvailable = new ArrayObject($filterOptions);

        // init filter
        $filterOptionsAvailable['filter_commonAvailability'] = array("ACTIVE", "OUTOFSTOCK", "COMINGSOON");
        $filterOptionsAvailable['filter_commonOnSaleTypes'] = array('SHOP_CLEARANCE','SHOP_NEW','SHOP_HOTOFFER','SHOP_BESTSELLER','SHOP_LIMITED');
        foreach ($filterOptionsApplied as $key => $value)
            $filterOptionsApplied[$key] = libraryRequest::fromGET($key) ?: $filterOptions[$key];

        // set data source
        // ---
        $dataConfigCategoryInfo = configurationShopDataSource::jsapiShopProductListGetCategoryGetInfoGet();
        $dataConfigProducts = configurationShopDataSource::jsapiShopProductListGetCategoryGet();
        $dataConfigCategoryPriceEdges = configurationShopDataSource::jsapiShopCategoryPriceEdgesGet();
        $dataConfigCategoryAllBrands = configurationShopDataSource::jsapiShopCategoryAllBrandsGet();
        $dataConfigCategoryAllSubCategories = configurationShopDataSource::jsapiShopCategoryAllSubCategoriesGet();

        // update configs using user filter
        // ---
        $dataConfigCategoryPriceEdges['procedure']['parameters'][] = $categoryID;
        $dataConfigCategoryAllBrands['procedure']['parameters'][] = $categoryID;
        $dataConfigCategoryAllSubCategories['procedure']['parameters'][] = $categoryID;

        //filter: get category price edges
        $dataCategoryPriceEdges = $this->getCustomer()->fetch($dataConfigCategoryPriceEdges);
        $filterOptionsAvailable['filter_commonPriceMax'] = intval($dataCategoryPriceEdges['PriceMax'] ?: 0);
        $filterOptionsAvailable['filter_commonPriceMin'] = intval($dataCategoryPriceEdges['PriceMin'] ?: 0);

        // filter: display intems count
        if (!empty($filterOptionsApplied['filter_viewItemsOnPage']))
            $dataConfigProducts['limit'] = $filterOptionsApplied['filter_viewItemsOnPage'];
        else
            $filterOptionsApplied['filter_viewItemsOnPage'] = $dataConfigProducts['limit'];

        if (!empty($filterOptionsApplied['filter_viewPageNum']))
            $dataConfigProducts['offset'] = $filterOptionsApplied['filter_viewPageNum'] * $dataConfigProducts['limit'];
        else
            $filterOptionsApplied['filter_viewPageNum'] = $dataConfigProducts['offset'];

        // filter: items sorting
        $_filterSorting = explode('_', strtolower($filterOptionsApplied['filter_viewSortBy']));
        if (count($_filterSorting) === 2 && !empty($_filterSorting[0]) && ($_filterSorting[1] === 'asc' || $_filterSorting[1] === 'desc'))
            $dataConfigProducts['order'] = array('field' => $dataConfigProducts['source'] . '.' . ucfirst($_filterSorting[0]), 'ordering' => strtoupper($_filterSorting[1]));
        else
            $filterOptionsApplied['filter_viewSortBy'] = null;

        // get category sub-categories and origins
        $dataCategoryAllBrands = $this->getCustomer()->fetch($dataConfigCategoryAllBrands);
        $dataCategoryAllSubCategories = $this->getCustomer()->fetch($dataConfigCategoryAllSubCategories);

        // filter: update filters
        $filterOptionsAvailable['filter_categoryBrands'] = $dataCategoryAllBrands ?: array();
        $filterOptionsAvailable['filter_categorySubCategories'] = $dataCategoryAllSubCategories ?: array();

        $cetagorySubIDs = array($categoryID);
        if (!empty($dataCategoryAllSubCategories))
            foreach ($dataCategoryAllSubCategories as $value)
                $cetagorySubIDs[] = $value['ID'];

        // fetch data with filter options
        $dataConfigProducts['condition']['values'][] = $cetagorySubIDs;

        // filter: price 
        if ($filterOptionsApplied['filter_commonPriceMax'] > 0 && $filterOptionsApplied['filter_commonPriceMax'] < $filterOptionsAvailable['filter_commonPriceMax']) {
            $dataConfigProducts['condition']['filter'] .= " + Price (<=) ?";
            $dataConfigProducts['condition']['values'][] = $filterOptionsApplied['filter_commonPriceMax'];
        } else
            $filterOptionsApplied['filter_commonPriceMax'] = $filterOptionsAvailable['filter_commonPriceMax'];

        if ($filterOptionsApplied['filter_commonPriceMin'] > 0) {
            $dataConfigProducts['condition']['filter'] .= " + Price (>=) ?";
            $dataConfigProducts['condition']['values'][] = $filterOptionsApplied['filter_commonPriceMin'];
        } else
            $filterOptionsApplied['filter_commonPriceMin'] = 0;

        // filter: brands
        if (!empty($filterOptionsApplied['filter_categoryBrands'])) {
            $dataConfigProducts['condition']['filter'] .= " + OriginID (IN) ?";
            if (!is_array($filterOptionsApplied['filter_categoryBrands']))
                $filterOptionsApplied['filter_categoryBrands'] = array($filterOptionsApplied['filter_categoryBrands']);
            $dataConfigProducts['condition']['values'][] = $filterOptionsApplied['filter_categoryBrands'];
        } else
            $filterOptionsApplied['filter_categoryBrands'] = array();

        // var_dump($filterOptionsApplied);

        // get products
        $dataProducts = $this->getCustomer()->fetch($dataConfigProducts);

        // get category info according to product filter
        $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
        $dataCategoryInfo = $this->getCustomer()->fetch($dataConfigCategoryInfo);

        // get origins\sub-categories according to product filter
        $uniqueBrands = array();
        $uniqueSubCategories = array();
        if ($dataCategoryInfo)
            foreach ($dataCategoryInfo as $obj) {
                if (isset($obj['OriginID'])) {
                    if (empty($uniqueBrands[$obj['OriginID']]))
                        $uniqueBrands[$obj['OriginID']] = array(
                            "ID" => $obj['OriginID'],
                            "Name" => $obj['OriginName'],
                            "ProductCount" => 1,
                            "IsSelected" => false
                        );
                    else
                        $uniqueBrands[$obj['OriginID']]["ProductCount"]++;

                    if (in_array($obj['OriginID'], $filterOptionsApplied['filter_categoryBrands']))
                        $uniqueBrands[$obj['OriginID']]["IsSelected"] = true;
                }

                if (isset($obj['CategoryID']))
                    if (empty($uniqueSubCategories[$obj['CategoryID']]))
                        $uniqueSubCategories[$obj['CategoryID']] = array(
                            "ID" => $obj['CategoryID'],
                            "Name" => $obj['CategoryName'],
                            "ProductCount" => 1
                        );
                    else
                        $uniqueSubCategories[$obj['CategoryID']]["ProductCount"]++;

            }
        $filterOptionsApplied['filter_categoryBrands'] = $uniqueBrands;
        $filterOptionsApplied['filter_categorySubCategories'] = $uniqueSubCategories;

        // var_dump($dataConfigProducts);
        // attach attributes
        $productsMap = $this->_custom_util_getProductAttributes($dataProducts);
        // store data
        $dataObj->setData('products', $productsMap);
        $dataObj->setData('info', array(
            "count" => count($dataCategoryInfo)
        ));
        $dataObj->setData('filter', array(
            'filterOptionsAvailable' => $filterOptionsAvailable,
            'filterOptionsApplied' => $filterOptionsApplied
        ));
        // return data object
        return $dataObj;
    }

    // product standalone item (short or full)
    // -----------------------------------------------
    private function _api_getProduct ($productID, $saveIntoRecent = false) {
        // what is not included in comparison to product_single_full
        // this goes without PriceArchive property

        // update main data object
        $dataObj = new libraryDataObject();

        if (empty($productID) || !is_numeric($productID))
            $dataObj->setError('wrongProductID');
        else {

            // set config
            $config = configurationShopDataSource::jsapiShopProductItemGet();
            $config["condition"]["values"][] = $productID;
            $product = $this->getCustomer()->fetch($config);

            $productsMap = $this->_custom_util_getProductAttributes(array($product));

            $productItem = $productsMap[$productID];
            $dataObj->setData('product', $productItem);

            // save product into recently viewed list
            if ($saveIntoRecent && !glIsToolbox()) {
                $recentProducts = isset($_SESSION['shop:recentProducts']) ? $_SESSION['shop:recentProducts'] : array();
                $recentProducts[$productID] = $productItem;
                $_SESSION['shop:recentProducts'] = $recentProducts;
            }
        }

        return $dataObj;
    }

    // shopping wishlist
    // -----------------------------------------------
    private function _api_getWishList () {
        return $this->_custom_util_manageStoredProducts('shopWishList');
    }

    // shopping products compare
    // -----------------------------------------------
    private function _api_getCompareList () {
        $do = libraryRequest::fromGET('action');
        $productID = libraryRequest::fromGET('productID');
        $dataObj = $this->_custom_util_manageStoredProducts('shopProductsCompare');

        $products = $dataObj->getData();

        if ($do == 'ADD' && count($products['products']) > 10) {
            unset($products['products'][$productID]);
            $dataObj->setError("MaxProductsAdded");
            $dataObj->setData('products', $products);
        }

        return $dataObj;
    }

    // shopping cart
    // -----------------------------------------------
    private function _api_getShoppingCart () {

        $sessionKey = 'shopCartProducts';
        $cartActions = array('ADD', 'REMOVE', 'CLEAR', 'INFO', 'SAVE');
        $do = libraryRequest::fromGET('action');
        $dataObj = $this->_custom_util_manageStoredProducts($sessionKey, $cartActions);

        $errors = array();
        // var_dump($dataObj);

        $productData = $dataObj->getData();

        // adjust product id and quantity
        $productID = intval(libraryRequest::fromGET('productID'));
        $productQuantity = intval(libraryRequest::fromGET('productQuantity'));

        // $_getInfoFn = function (&$_products = array()) {

        // };

        // create/add product
        if ($do == 'ADD' && $productQuantity) {
            if (empty($productData['products'][$productID]['Quantity']))
                $productData['products'][$productID]['Quantity'] = 0;

            $productData['products'][$productID]['Quantity'] += $productQuantity;

            // we keep product until REMOVE action is invoked
            if ($productData['products'][$productID]['Quantity'] <= 0)
                $productData['products'][$productID]['Quantity'] = 1;

            $_SESSION['shopCartProducts'] = $productData['products'];
        }

        // get shopping cart info
        if ($do == 'SAVE') {

            // cartUser data
            // -----------------------
            // shopCartUserName
            // shopCartUserEmail
            // shopCartUserPhone
            // shopCartUserDelivery
            // shopCartUserCity
            // shopCartLogistic
            // shopCartWarehouse
            // shopCartComment
            // shopCartCreateAccount
            $cartUser = libraryRequest::fromPOST('user');

            $accountID = null;
            $addressID = null;

            if (!isset($cartUser['shopCartProfileID']) || $cartUser['shopCartProfileID'] !== $_SESSION['Account:ProfileID']) {

                // save account
                // -----------------------
                // AccountID
                // Shipping
                // Warehouse
                // Comment
                // Status
                // TrackingLink
                // DateCreate
                // DateUpdate
                $dataAccount = array();
                $dataAccount["FirstName"] = $cartUser["shopCartUserName"];
                $dataAccount["LastName"] = "";
                $dataAccount["EMail"] = $cartUser["shopCartUserEmail"];
                $dataAccount["Phone"] = $cartUser["shopCartUserPhone"];
                $dataAccount["Password"] = "1234";
                $accountID = $this->getCustomer()->addAccount($dataAccount);

                // add account address
                $dataAccountAddress = array();
                $dataAccountAddress["AccountID"] = $accountID;
                $dataAccountAddress["Address"] = $cartUser["shopCartUserAddress"];
                $dataAccountAddress["POBox"] = $cartUser["shopCartUserPOBox"];
                $dataAccountAddress["Country"] = $cartUser["shopCartUserCountry"];
                $dataAccountAddress["City"] = $cartUser["shopCartUserCity"];

                $addressID = $this->getCustomer()->addAccountAddress($dataAccountAddress);

            } else {

                $accountID = $_SESSION['Account:ProfileID'];


                if (empty($cartUser["shopCartProfileAddressID"])) {

                    // check for custom address
                    $dataAccountAddress = array();
                    if (!empty($cartUser["shopCartUserAddress"]))
                        $dataAccountAddress["Address"] = $cartUser["shopCartUserAddress"];
                    if (!empty($cartUser["shopCartUserPOBox"]))
                        $dataAccountAddress["POBox"] = $cartUser["shopCartUserPOBox"];
                    if (!empty($cartUser["shopCartUserCountry"]))
                        $dataAccountAddress["Country"] = $cartUser["shopCartUserCountry"];
                    if (!empty($cartUser["shopCartUserCity"]))
                        $dataAccountAddress["City"] = $cartUser["shopCartUserCity"];

                    // set error or add new address
                    if (count($dataAccountAddress) == 0)
                        $dataObj->setError('EmptyShippingAddress');
                    else
                        $addressID = $this->getCustomer()->addAccountAddress($dataAccountAddress);
                } else {

                    // validate account address id
                    $accountAddressEntry = $this->getCustomer()->getAccountAddress($accountID, $cartUser["shopCartProfileAddressID"]);

                    if (empty($accountAddressEntry['ID']))
                        $dataObj->setError('WrongProfileAddressID');
                    else
                        $addressID = $accountAddressEntry['ID'];
                }

            }

            if (!$dataObj->hasError() && $addressID != null && $accountID != null) {
                // save order
                // -----------------------
                // AccountID
                // Shipping
                // Warehouse
                // Comment
                // Hash
                // DateCreated
                // DateUpdated
                $configOrder = configurationShopDataSource::jsapiShopOrderCreate();
                $dataOrder["AccountID"] = $accountID;
                $dataOrder["AccountAddressesID"] = $addressID;
                $dataOrder["CustomerID"] = $this->getCustomer()->getCustomerID();
                $dataOrder["Shipping"] = $cartUser['shopCartLogistic'];
                $dataOrder["Warehouse"] = $cartUser['shopCartWarehouse'];
                $dataOrder["Comment"] = $cartUser['shopCartComment'];
                $dataOrder["Hash"] = md5(time() . md5(time()));
                $dataOrder['DateCreated'] = date('Y-m-d H:i:s');
                $dataOrder['DateUpdated'] = date('Y-m-d H:i:s');
                $configOrder['data'] = array(
                    "fields" => array_keys($dataOrder),
                    "values" => array_values($dataOrder)
                );

                $this->getCustomer()->fetch($configOrder);

                $orderID = $this->getCustomerDataBase()->getLastInsertId();

                // save products
                // -----------------------
                // ProductID
                // OrderID
                // ProductPrice
                // Quantity
                foreach ($productData['products'] as $_item) {
                    $configProduct = configurationShopDataSource::jsapiShopOrderProductsCreate();
                    $dataProduct = array();
                    $dataProduct["ProductID"] = $_item["ID"];
                    $dataProduct["OrderID"] = $orderID;
                    $dataProduct["ProductPrice"] = $_item["ProductPrice"];
                    $dataProduct["Quantity"] = $_item["Quantity"];
                    $configProduct['data'] = array(
                        "fields" => array_keys($dataProduct),
                        "values" => array_values($dataProduct)
                    );
                    $this->getCustomer()->fetch($configProduct);
                }

                // clear products
                $dataObj = $this->_custom_util_manageStoredProducts('shopCartProducts', $cartActions, 'CLEAR');
                $productData = $dataObj->getData();

                // need to shop order id and status link
                // and send email
                $dataObj->setData('status', array(
                    'orderID' => $orderID,
                    'orderHash'=> $dataOrder["Hash"]
                ));
            }
        }

        $dataObj->setData('info', $this->_custom_util_calculateBought($productData['products']));
        $dataObj->setData('products', $productData['products']);
        $_SESSION[$sessionKey] = $productData['products'];

        return $dataObj;
    }

    // orders
    // -----------------------------------------------
    private function _api_addOrder () {

    }


    // private function _api_getOrderBoughts ($OrderID) {
    //     $dataObj = new libraryDataObject();

    //     $configBoughts = configurationShopDataSource::jsapiShopBoughtsGet($OrderID);
    //     // var_dump($configBoughts);
    //     $boughts = $this->getCustomerDataBase()->getData($configBoughts) ?: array();

    //     if (!empty($boughts))
    //         foreach ($boughts as $bkey => $soldItem) {
    //             $product = $this->_api_getProduct($soldItem['ProductID']);
    //             if ($product->hasData()) { 
    //                 $productData = $product->getData('Product');
    //                 $boughts[$bkey] = array_merge($boughts[$bkey], $productData);
    //             } else
    //                 $boughts[$bkey]['Product'] = null;
    //         }

    //     // var_dump($boughts);

    //     $dataObj->setData('info', $this->_custom_util_calculateBought($boughts));

    //     // var_dump($this->_custom_util_calculateBought($boughts));
    //     $dataObj->setData('boughts', $boughts);

    //     return $dataObj;
    // }

    // profile orders
    // -----------------------------------------------
    private function _api_getOrderList_ForProfile ($profileID) {

        $dataObj = new libraryDataObject();

        if (!isset($profileID)) {
            $dataObj->setError('WrongProfileID');
            return $dataObj;
        }

        if (!$this->getCustomer()->hasPlugin('account')) {
            $dataObj->setError('WrongRequest');
            return $dataObj;
        }

        if ($_SESSION['Account:ProfileID'] != $profileID) {
            $dataObj->setError('AccessDenied');
            return $dataObj;
        }

        // get orders
        $configOrders = configurationShopDataSource::jsapiShopOrdersForProfileGet($profileID);
        $dataOrders = $this->getCustomer()->fetch($configOrders);

        // var_dump($dataOrders);
        foreach ($dataOrders as $key => $order)
            $dataOrders[$key] = $this->_api_getOrder($order)->getData();

        // get order boughts
        // foreach ($dataOrders as $key => $order) {
        //     $orderBoughtsData = $this->_api_getOrderBoughts($order['ID']);
        //     if ($orderBoughtsData->hasData()) {
        //         $dataOrders[$key]['Info'] = $orderBoughtsData->getData('Info');
        //         $dataOrders[$key]['Boughts'] = $orderBoughtsData->getData('Boughts');
        //     }
        // }

        $dataObj->setData('orders', $dataOrders);

        return $dataObj;
    }

    // toolbox orders
    // -----------------------------------------------
    private function _api_getOrderStatusList () {
        return array("NEW", "ACTIVE", "LOGISTIC_DELIVERING", "LOGISTIC_DELIVERED", "SHOP_CLOSED");
    }

    private function _api_getOrderStatus ($orderHash) {
        // $orderHash
        $dataObj = new libraryDataObject();

        $config = configurationShopDataSource::jsapiShopOrdersGetStatus($orderHash);
        $orderStatus = $this->getCustomer()->fetch($config);

        $dataObj->setData('orderStatus', $orderStatus);

        return $dataObj;
    }

    private function _api_getOrderList () {
        // in toolbox methods we must check it's permission
        // offset
        // limit
        $dataObj = new libraryDataObject();

        // $limit = 2;

        // if (!$this->getCustomer()->getAccess()) {
        //     $dataObj->setError('AccessDenied');
        //     return $dataObj;
        // }

        $configOrders = configurationShopDataSource::jsapiShopOrdersForSiteGet();
        $configCount = configurationShopDataSource::jsapiUtil_GetTableRecordsCount(configurationShopDataSource::$Table_ShopOrders);

        // pagination
        $page = libraryRequest::fromGET('page');
        $per_page = libraryRequest::fromGET('per_page');

        $configOrders['limit'] = $per_page;

        if (!empty($page) && !empty($per_page)) {
            $configOrders['offset'] = ($page - 1) * $per_page;
        }

        // sorting
        $sort = libraryRequest::fromGET('sort');
        $order = libraryRequest::fromGET('order');

        if (!empty($sort) && !empty($order)) {
            $configOrders["order"] = array(
                "field" =>  'shop_orders' . DOT . $sort,
                "ordering" => strtoupper($order)
            );
        }

        // filtering
        $filterBy_status = libraryRequest::fromGET('status');
        if (!empty($filterBy_status)) {
            $configOrders['condition']['filter'] .= "Status (=) ?";
            $configOrders['condition']['values'][] = $filterBy_status;
        }

        // var_dump($configOrders);

        // get valid orders count
        $configCount['condition'] = new ArrayObject($configOrders['condition']);

        // get data
        $dataOrders = $this->getCustomer()->fetch($configOrders);
        $dataObj->setData('orders', $dataOrders);

        $dataCount = $this->getCustomer()->fetch($configCount);
        $dataObj->setData('total_count', $dataCount['ItemsCount']);

        $availableStatuses = $this->_api_getOrderStatusList();
        $dataObj->setData('statuses', $availableStatuses);
        
        return $dataObj;
    }

    private function _api_updateOrder ($orderID) {
        $dataObj = new libraryDataObject();

        if (!$this->getCustomer()->isAdminActive()) {
            $dataObj->setError('AccessDenied');
            return $dataObj;
        }

        // get fields to update
        $Status = libraryRequest::fromPOST('Status');
        // $fieldValue = libraryRequest::fromPOST('value');
        if (empty($Status)) {
            $dataObj->setError('EmptyFieldName');
            return $dataObj;
        }

        // Allow to update the Status filed only
        // if ($fieldName !== "Status") {
        //     $dataObj->setError('WrongFieldName');
        //     return $dataObj;
        // }

        $data['Status'] = $Status;
        $data['DateUpdated'] = date('Y-m-d H:i:s');

        $configOrder = configurationShopDataSource::jsapiShopOrderUpdate($orderID, $data);
        // var_dump($configOrder);
        $this->getCustomer()->fetch($configOrder);
        return $dataObj;
    }

    private function _api_getProductList () {
        $listType = strtolower(libraryRequest::fromGET('type'));

        // send list of types
        switch ($listType) {
            case 'active':
                return $this->_api_getProductList_Active();
            case 'inactive':
                return $this->_api_getProductList_Inactive();
            case 'uncompleted':
                return $this->_api_getProductList_Uncompleted();
            case 'sales':
                return $this->_api_getProductList_Sales();
            case 'defects':
                return $this->_api_getProductList_Defects();
            case 'popular':
                return $this->_api_getProductList_Popular();
            case 'notpopular':
                return $this->_api_getProductList_NotPopular();
            case 'archived':
                return $this->_api_getProductList_Archived();
            default:
                return $this->_api_getProductList_All();
        }
    }

    // custom product lists with custom filers and conditions applied
    private function _api_getProductList_All () {
        return $this->_api_getProductList_Base();
    }

    private function _api_getProductList_Inactive () {
        // echo 1111;
        return $this->_api_getProductList_Base(function($dataConfigProducts){
            $dataConfigProducts['condition']['values'][0] = "REMOVED";
            return $dataConfigProducts;
        });
    }

    private function _api_getProductList_Uncompleted () {
        return $this->_api_getProductList_Base(function($dataConfigProducts){
            $dataConfigProducts['condition']['filter'] .= " + shop_products.Name (=) ?";
            $dataConfigProducts['condition']['filter'] .= " + shop_products.Description (=) ?";
            $dataConfigProducts['condition']['filter'] .= " + shop_products.Model (=) ?";
            $dataConfigProducts['condition']['values'][] = "";
            $dataConfigProducts['condition']['values'][] = "";
            $dataConfigProducts['condition']['values'][] = "";
            return $dataConfigProducts;
        });
    }

    private function _api_getProductList_Archived () {
        return $this->_api_getProductList_Base(function($dataConfigProducts){
            $dataConfigProducts['condition']['filter'] .= " + shop_products.SellMode (=) ?";
            $dataConfigProducts['condition']['values'][] = "ARCHIVED";
            return $dataConfigProducts;
        });
    }

    private function _api_getProductList_Defects () {
        return $this->_api_getProductList_Base(function($dataConfigProducts){
            $dataConfigProducts['condition']['filter'] .= " + shop_products.SellMode (=) ?";
            $dataConfigProducts['condition']['values'][] = "DEFECT";
            return $dataConfigProducts;
        });
    }

    private function _api_getProductList_Sales () {
        return $this->_api_getProductList_Base(function($dataConfigProducts){
            $dataConfigProducts['condition']['filter'] .= " + shop_products.SellMode (IN) ?";
            $dataConfigProducts['condition']['values'][] = array("DISCOUNT", "BESTSELLER");
            return $dataConfigProducts;
        });
    }

    private function _api_getProductList_Popular () {
        return $this->_api_getProductList_Base();
        // get top 50 products
        $configBestProducts = configurationShopDataSource::jsapiShopStat_BestSellingProducts();
        $dataBestProducts = $this->getCustomer()->fetch($configBestProducts);
        if (!empty($dataBestProducts))
            foreach ($dataBestProducts as $key => $dataProductSoldStat) {
                $productItemObject = $this->_api_getProduct($dataProductSoldStat['ProductID']);
                if ($productItemObject->hasData())
                    $dataBestProducts[$key]['Product'] = $productItemObject->getData('product');
                else
                    $dataBestProducts[$key]['Product'] = null;
            }
        $dataObj->setData('products_best', $dataBestProducts);

        // get non-popuplar 50 products
        $configWorstProducts = configurationShopDataSource::jsapiShopStat_WorstSellingProducts();
        $dataWorstProducts = $this->getCustomer()->fetch($configWorstProducts);
        if (!empty($dataWorstProducts))
            foreach ($dataWorstProducts as $key => $dataProduct) {
                $productItemObject = $this->_api_getProduct($dataProduct['ID']);
                if ($productItemObject->hasData())
                    $dataWorstProducts[$key]['Product'] = $productItemObject->getData('product');
                else
                    $dataWorstProducts[$key]['Product'] = null;
            }
        $dataObj->setData('products_worst', $dataWorstProducts);
    }

    private function _api_getProductList_NotPopular () {
        return $this->_api_getProductList_Base();
    }

    private function _api_getProductList_Active () {
        return $this->_api_getProductList_Base(function($dataConfigProducts){
            $dataConfigProducts['condition']['values'][0] = "ACTIVE";
            return $dataConfigProducts;
        });
    }

    // this is the base function that builds product list witout any conditions
    // and returns whole list with all products types
    private function _api_getProductList_Base ($customConfigurator = null) {
        // jsapiShopProductListGet
        // in toolbox methods we must check it's permission
        // offset
        // limit
        $dataObj = new libraryDataObject();

        // if (!$this->getCustomer()->isAdminActive()) {
        //     $dataObj->setError('AccessDenied');
        //     return $dataObj;
        // }

        // $limit = 25;

        // if (!$this->getCustomer()->getAccess()) {
        //     $dataObj->setError('AccessDenied');
        //     return $dataObj;
        // }

        // $dataConfigCategoryInfo = configurationShopDataSource::jsapiShopProductListGetCategoryGetInfoGet();
        $dataConfigProducts = configurationShopDataSource::jsapiShopProductListGet();
        $configCount = configurationShopDataSource::jsapiUtil_GetTableRecordsCount(configurationShopDataSource::$Table_ShopProducts);


        // $dataConfigCategoryPriceEdges = configurationShopDataSource::jsapiShopCategoryPriceEdgesGet();
        // $dataConfigCategoryAllBrands = configurationShopDataSource::jsapiShopCategoryAllBrandsGet();
        // $dataConfigCategoryAllSubCategories = configurationShopDataSource::jsapiShopCategoryAllSubCategoriesGet();

        // $dataConfigProducts['limit'] = $limit;

        // pagination
        $page = libraryRequest::fromGET('page');
        $per_page = libraryRequest::fromGET('per_page');

        $dataConfigProducts['limit'] = $per_page;

        if (!empty($page) && !empty($per_page)) {
            $dataConfigProducts['offset'] = ($page - 1) * $per_page;
        }

        // sorting
        $sort = libraryRequest::fromGET('sort');
        $order = libraryRequest::fromGET('order');

        if (!empty($sort) && !empty($order)) {
            $dataConfigProducts["order"] = array(
                "field" =>  'shop_products' . DOT . $sort,
                "ordering" => strtoupper($order)
            );
        }

        // filtering
        // $filterBy_status = libraryRequest::fromGET('status');
        // if (!empty($filterBy_status)) {
        //     $configOrders['condition']['filter'] .= "Status (=) ?";
        //     $configOrders['condition']['values'][] = $filterBy_status;
        // }

        // var_dump($dataConfigProducts);
        if ($customConfigurator)
            $dataConfigProducts = $customConfigurator($dataConfigProducts);

        // var_dump($dataConfigProducts);

        // set managed customer id
        $configCount['additional'] = new ArrayObject($dataConfigProducts['additional']);
        $configCount['condition'] = new ArrayObject($dataConfigProducts['condition']);

        // get data
        $dataProducts = $this->getCustomer()->fetch($dataConfigProducts);
        $dataObj->setData('products', $dataProducts);

        $dataCount = $this->getCustomer()->fetch($configCount);
        $dataObj->setData('total_count', $dataCount['ItemsCount']);

        return $dataObj;
    }

    private function _api_getOriginStates () {
        $dataObj = new libraryDataObject();
        $dataObj->setData('statuses', $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopOrigins));
        return $dataObj;
    }

    private function _api_getOrigin ($originID) {
        $dataObj = new libraryDataObject();
        if (!empty($originID)) {
            $configOrigin = configurationShopDataSource::jsapiShopOriginGet($originID);
            $dataOrigin = $this->getCustomer()->fetch($configOrigin);
            $dataObj->setData('origin', $dataOrigin);
        }
        return $dataObj;
    }

    private function _api_getOriginList () {
        $dataObj = new libraryDataObject();

        $configOrigins = configurationShopDataSource::jsapiShopOriginListGet();
        $configCount = configurationShopDataSource::jsapiUtil_GetTableRecordsCount(configurationShopDataSource::$Table_ShopOrigins);

        // pagination
        if (libraryRequest::hasInGet('page', 'per_page')) {
            $page = libraryRequest::fromGET('page');
            $per_page = libraryRequest::fromGET('per_page');

            $configOrigins['limit'] = $per_page;

            if (!empty($page) && !empty($per_page)) {
                $configOrigins['offset'] = ($page - 1) * $per_page;
            }
        }

        // sorting
        if (libraryRequest::hasInGet('sort', 'order')) {
            $sort = libraryRequest::fromGET('sort');
            $order = libraryRequest::fromGET('order');

            if (!empty($sort) && !empty($order)) {
                $configOrigins["order"] = array(
                    "field" =>  'shop_origins' . DOT . $sort,
                    "ordering" => strtoupper($order)
                );
            }
        }

        $configCount['additional'] = new ArrayObject($configOrigins['additional']);
        $configCount['condition'] = new ArrayObject($configOrigins['condition']);

        $dataOrders = $this->getCustomer()->fetch($configOrigins);
        $dataObj->setData('origins', $dataOrders);

        $dataCount = $this->getCustomer()->fetch($configCount);
        $dataObj->setData('total_count', $dataCount['ItemsCount']);

        return $dataObj;
    }

    private function _api_createOrigin () {
        $dataObj = new libraryDataObject();
        $dataOrigin = libraryRequest::getObjectFromREQUEST("Name", "Description", "Status", "HomePage");
        $dataOrigin["ExternalKey"] = libraryUtils::url_slug($dataOrigin['Name'], array("delimiter" => "_", 'lowercase' => true));
        // $dataOrigin["Name"] = libraryRequest::fromGET('Name');
        // $dataOrigin["Description"] = libraryRequest::fromGET('Description');
        // $dataOrigin["Status"] = libraryRequest::fromGET('Status');
        // $dataOrigin["HomePage"] = libraryRequest::fromGET('HomePage');
        $dataOrigin["CustomerID"] = $this->getCustomer()->getCustomerID();
        $dataOrigin['DateCreated'] = configurationShopDataSource::getDate();
        $dataOrigin['DateUpdated'] = configurationShopDataSource::getDate();
        $configOrigin = configurationShopDataSource::jsapiShopOriginCreate($dataOrigin);
        $this->getCustomer()->fetch($configOrigin);
        $dataObj->setData('origin', $dataOrigin);
        return $dataObj;
    }

    private function _api_updateOrigin ($originID) {
        $dataObj = new libraryDataObject();

        $dataOrigin = array();

        // update only one field
        if (libraryRequest::hasInREQUEST("field", "value")) {
            $field = libraryRequest::fromREQUEST('field');
            $value = libraryRequest::fromREQUEST('value');
            $dataOrigin[$field] = $value;
        } else // update whole item
            $dataOrigin = libraryRequest::getObjectFromREQUEST("Name", "Description", "Status", "HomePage");

        // update external value
        if (isset($dataOrigin['Name']))
            $dataOrigin["ExternalKey"] = libraryUtils::url_slug($dataOrigin['Name'], array("delimiter" => "_", 'lowercase' => true));

        // change update time
        $dataOrigin['DateUpdated'] = configurationShopDataSource::getDate();

        $configOrigin = configurationShopDataSource::jsapiShopOriginUpdate($originID, $dataOrigin);
        $this->getCustomer()->fetch($configOrigin);
        $dataObj->setData('origin', $dataOrigin);
        return $dataObj;
    }


    // product additional data
    // @products - array with product(s)
    private function _custom_util_getProductAttributes ($products) {

        if (empty($products)) {
            return null;
        }
        // list of product ids to fetch related attributes
        $productIDs = array();

        // mapped data (key is record's ID)
        $productsMap = array();
        $attributesMap = array();
        $pricesMap = array();

        // pluck product IDs and create product map
        foreach ($products as $value) {
            $productIDs[] = $value['ID'];
            $productsMap[$value['ID']] = $value;
        }

        $configProductsAttr = configurationShopDataSource::jsapiShopProductAttributesGet();
        $configProductsAttr["condition"]["values"][] = $productIDs;
        // var_dump($configProductsAttr);

        $configProductsPrice = configurationShopDataSource::jsapiShopProductPriceStatsGet();
        $configProductsPrice["condition"]["values"][] = $productIDs;

        // configure product attribute object
        $attributes = $this->getCustomer()->fetch($configProductsAttr);
        $prices = $this->getCustomer()->fetch($configProductsPrice);

        // pluck product IDs and create product map
        if (!empty($attributes))
            foreach ($attributes as $value)
                $attributesMap[$value['ProductID']] = $value['ProductAttributes'];

        // pluck product IDs and create product map
        if (!empty($prices))
            foreach ($prices as $value) 
                $pricesMap[$value['ProductID']] = $value['PriceArchive'];

        foreach ($productsMap as $key => $value) {

            $productsMap[$key]['Attributes'] = array();
            $productsMap[$key]['Prices'] = array();

            if (!empty($attributesMap[$key]))
                $productsMap[$key]['Attributes'] = $attributesMap[$key];

            if (!empty($pricesMap[$key]))
                $productsMap[$key]['Prices'] = $pricesMap[$key];
        }

        // var_dump($productsMap);
        return $productsMap;
    }

    private function _custom_util_calculateBought (&$_products) {

        // get cartInfo
        $cartInfo = array(
            "subTotal" => 0.0,
            "discount" => 0,
            "total" => 0.0,
            "productCount" => 0,
            "productUniqueCount" => 0
        );

        if (empty($_products))
            return $cartInfo;

        $cartInfo['productUniqueCount'] = count($_products);

        foreach ($_products as &$_item) {
            if (!isset($_item['ProductPrice']))
                $_item['ProductPrice'] = $_item['Price'];
            $_item["Total"] = $_item['ProductPrice'] * $_item['Quantity'];
            $cartInfo["subTotal"] += $_item['Total'];
            $cartInfo["productCount"] += $_item['Quantity'];
        }
        $cartInfo["total"] = (($cartInfo['discount'] / 100) ?: 1) *  $cartInfo['subTotal'];

        // update money formats
        $cartInfo["discount"] = money_format('%.2n%%', $cartInfo["discount"]);
        $cartInfo["subTotal"] = money_format('%.2n', $cartInfo["subTotal"]);
        $cartInfo["total"] = money_format('%.2n', $cartInfo["total"]);

        return $cartInfo;
    }

    private function _custom_util_manageStoredProducts ($sessionKey, $userActions = array(), $action = null) {
        $dataObj = new libraryDataObject();
        $productID = libraryRequest::fromGET('productID');
        $do = empty($action) ? libraryRequest::fromGET('action') : $action;
        $actions = array('ADD', 'REMOVE', 'CLEAR', 'INFO');

        if (!empty($userActions) && is_array($userActions))
            $actions = array_merge($actions, $userActions);

        if (empty($do) || !in_array($do, $actions)) {
            $dataObj->setError("UnknownAction");
            return $dataObj;
        }

        // adjust product id and quantity
        $productID = intval($productID);

        if (empty($productID) && $do == 'ADD') {
            $dataObj->setError("EmptyProductID");
            return $dataObj;
        }

        $products = isset($_SESSION[$sessionKey]) ? $_SESSION[$sessionKey] : array();

        // create/add product
        if ($do == 'ADD') {
            // create
            if (!isset($products[$productID])) {
                $productEntry = $this->_api_getProduct($productID);
                if ($productEntry->hasError()) {
                    $dataObj->setError($productEntry->getError());
                    return $dataObj;
                } else {
                    $products[$productID] = $productEntry->getData('Product');
                    $_SESSION[$sessionKey] = $products;
                }
            }

            $dataObj->setData('products', $products);

            return $dataObj;
        }

        // remove product
        if ($do == 'REMOVE') {
            if (isset($products[$productID]))
                unset($products[$productID]);

            $dataObj->setData('products', $products);
            $_SESSION[$sessionKey] = $products;

            return $dataObj;
        }

        // truncate shopping cart
        if ($do == 'CLEAR') {
            unset($_SESSION[$sessionKey]);
            $dataObj->setData('products', null);
            return $dataObj;
        }

        // get shopping cart info
        if ($do == 'INFO') {
            $dataObj->setData('products', $products);
            return $dataObj;
        }

        $dataObj->setData('products', $products);
        return $dataObj;
    }

}

?>
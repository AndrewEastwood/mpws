<?php

class pluginShop extends objectPlugin {

    public function getResponse () {
        $data = new libraryDataObject();

        switch(libraryRequest::getValue('fn')) {
            // breadcrumb
            // -----------------------------------------------
            case "shop_location": {
                $productID = libraryRequest::getValue('productID');
                $categoryID = libraryRequest::getValue('categoryID');
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
                $data = $this->_api_getCatalogStructure();
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
            //     $data = $this->_api_getEntryProduct($productID, 'short');
            //     break;
            // }
            // product standalone item full
            // -----------------------------------------------
            case "shop_product_item" : {
                $productID = libraryRequest::getValue('productID');
                $data = $this->_api_getEntryProduct($productID);
                break;
            }
            // shopping cart
            // -----------------------------------------------
            case "shop_wishlist" : {
                $data = $this->_api_getListWishes();
                break;
            }
            case "shop_cart" : {
                $data = $this->_api_getShoppingCart();
                break;
            }
            case "shop_compare" : {
                $data = $this->_api_getListProductsToCompare();
                break;
            }
            case "shop_order_status": {
                $orderHash = libraryRequest::getValue('orderHash');
                $data = $this->_api_getOrderStatus($orderHash);
                break;
            }
            case "shop_profile_orders": {
                $profileID = libraryRequest::getValue('profileID');
                $data = $this->_api_getListOrders_Profile($profileID);
                break;
            }
            case "shop_managed_orders_list": {
                $data = $this->_api_getListOrders_Managed();
                break;
            }
            case "shop_managed_order_entry": {
                $orderID = libraryRequest::getValue('orderID');
                $do = libraryRequest::getValue('action');
                switch ($do) {
                    case 'orderUpdate':
                        // var_dump($do);
                        $this->_api_updateEntryOrder($orderID);
                        break;
                }
                $data = $this->_api_getEntryOrder($orderID);
                break;
            }
            case "shop_managed_products": {
                $data = $this->_api_getListProducts_Managed();
                break;
            }
            case "toolbox_dashboard": {
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
            $configProduct = configurationShopDataSource::jsapiProductSingleInfo();
            $configProduct["condition"]["values"][] = $productID;
            $productDataEntry = $this->getCustomer()->processData($configProduct);
            // var_dump($productDataEntry);

            // $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiProductSingleInfo['data']);
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
            $configLocation = configurationShopDataSource::jsapiShopCategoryLocation();
            $configLocation["procedure"]["parameters"][] = $categoryID;
            $location->setData('location', $this->getCustomer()->processData($configLocation));

            // $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiShopCategoryLocation['data']);
            // $dataObj->setValuesDbProcedure($categoryId);
            // $dataObj->process($params);
        }

        return $location;
    }

    // products list sorted by date added
    // -----------------------------------------------
    private function _api_getProductList_Latest () {

        $configProducts = configurationShopDataSource::jsapiProductListLatest();

        // var_dump($configProducts);

        $products = $this->getCustomer()->processData($configProducts);

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
    private function _api_getCatalogStructure () {

        $config = configurationShopDataSource::jsapiCatalogStructure();
        $categories = $this->getCustomer()->processData($config);

        // $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiCatalogStructure['data']);
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

        $categoryID = libraryRequest::getValue('categoryID', null);
        // $categoryId = getValue($params['categoryId'], null);

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
            $filterOptionsApplied[$key] = libraryRequest::getValue($key) ?: $filterOptions[$key];

        // set data source
        // ---
        $dataConfigCategoryInfo = configurationShopDataSource::jsapiProductListCategoryInfo();
        $dataConfigProducts = configurationShopDataSource::jsapiProductListCategory();
        $dataConfigCategoryPriceEdges = configurationShopDataSource::jsapiShopCategoryPriceEdges();
        $dataConfigCategoryAllBrands = configurationShopDataSource::jsapiShopCategoryAllBrands();
        $dataConfigCategoryAllSubCategories = configurationShopDataSource::jsapiShopCategoryAllSubCategories();

        // update configs using user filter
        // ---
        $dataConfigCategoryPriceEdges['procedure']['parameters'][] = $categoryID;
        $dataConfigCategoryAllBrands['procedure']['parameters'][] = $categoryID;
        $dataConfigCategoryAllSubCategories['procedure']['parameters'][] = $categoryID;

        //filter: get category price edges
        $dataCategoryPriceEdges = $this->getCustomer()->processData($dataConfigCategoryPriceEdges);
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
        $dataCategoryAllBrands = $this->getCustomer()->processData($dataConfigCategoryAllBrands);
        $dataCategoryAllSubCategories = $this->getCustomer()->processData($dataConfigCategoryAllSubCategories);

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
        $dataProducts = $this->getCustomer()->processData($dataConfigProducts);

        // get category info according to product filter
        $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
        $dataCategoryInfo = $this->getCustomer()->processData($dataConfigCategoryInfo);

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
    private function _api_getEntryProduct ($productID) {
        // what is not included in comparison to product_single_full
        // this goes without PriceArchive property

        // update main data object
        $dataObj = new libraryDataObject();

        if (empty($productID) || !is_numeric($productID))
            $dataObj->setError('wrongProductID');
        else {

            // set config
            $config = configurationShopDataSource::jsapiProductItem();
            $config["condition"]["values"][] = $productID;
            $product = $this->getCustomer()->processData($config);

            $productsMap = $this->_custom_util_getProductAttributes(array($product));

            $dataObj->setData('products', $productsMap);

            // save product into recently viewed
            $recentProducts = isset($_SESSION['shop:recentProducts']) ? $_SESSION['shop:recentProducts'] : array();
            $recentProducts[$productID] = $productsMap[$productID];
            $_SESSION['shop:recentProducts'] = $recentProducts;
        }

        return $dataObj;
    }

    // shopping wishlist
    // -----------------------------------------------
    private function _api_getListWishes () {
        return $this->_custom_util_manageStoredProducts('shopWishList');
    }

    // shopping products compare
    // -----------------------------------------------
    private function _api_getListProductsToCompare () {
        $do = libraryRequest::getValue('action');
        $productID = libraryRequest::getValue('productID');
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
        $do = libraryRequest::getValue('action');
        $dataObj = $this->_custom_util_manageStoredProducts($sessionKey, $cartActions);

        $errors = array();
        // var_dump($dataObj);

        $productData = $dataObj->getData();

        // adjust product id and quantity
        $productID = intval(libraryRequest::getValue('productID'));
        $productQuantity = intval(libraryRequest::getValue('productQuantity'));

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
            $cartUser = libraryRequest::getPostValue('user');

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

                $this->getCustomer()->processData($configOrder);

                $orderID = $this->getDataBase()->getLastInsertId();

                // save products
                // -----------------------
                // ProductID
                // OrderID
                // ProductPrice
                // Quantity
                foreach ($productData['products'] as $_item) {
                    $configProduct = configurationShopDataSource::jsapiShopOrderProductsSave();
                    $dataProduct = array();
                    $dataProduct["ProductID"] = $_item["ID"];
                    $dataProduct["OrderID"] = $orderID;
                    $dataProduct["ProductPrice"] = $_item["ProductPrice"];
                    $dataProduct["Quantity"] = $_item["Quantity"];
                    $configProduct['data'] = array(
                        "fields" => array_keys($dataProduct),
                        "values" => array_values($dataProduct)
                    );
                    $this->getCustomer()->processData($configProduct);
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
    private function _api_getEntryOrder ($orderID, $addAccountInfo = true) {
        $dataObj = new libraryDataObject();
        $configOrder = configurationShopDataSource::jsapiShopOrderEntry($orderID);
        $dataOrder = $this->getCustomer()->processData($configOrder);

        $dataObj->setData('order', $dataOrder);
        $dataObj->setData('address', $this->getCustomer()->getAddress($dataOrder['AccountAddressesID']));

        if ($addAccountInfo)
            $dataObj->setData('account', $this->getCustomer()->getAccountByID($dataOrder['AccountID']));

        if (!empty($dataOrder)) {
            $orderBoughtsData = $this->_api_getOrderBoughts($orderID);
            if ($orderBoughtsData->hasData()) {
                $dataObj->setData('info', $orderBoughtsData->getData('Info'));
                $dataObj->setData('boughts', $orderBoughtsData->getData('Boughts'));
            }
        }

        return $dataObj;
    }

    private function _api_getOrderBoughts ($OrderID) {
        $dataObj = new libraryDataObject();

        $configBoughts = configurationShopDataSource::jsapiShopBoughts($OrderID);
        // var_dump($configBoughts);
        $boughts = $this->getDataBase()->getData($configBoughts) ?: array();

        if (!empty($boughts))
            foreach ($boughts as $bkey => $soldItem) {
                $product = $this->_api_getEntryProduct($soldItem['ProductID']);
                if ($product->hasData()) { 
                    $productData = $product->getData();
                    $boughts[$bkey] = array_merge($boughts[$bkey], $productData['products'][$soldItem['ProductID']]);
                } else
                    $boughts[$bkey]['Product'] = null;
            }

        // var_dump($boughts);

        $dataObj->setData('Info', $this->_custom_util_calculateBought($boughts));

        // var_dump($this->_custom_util_calculateBought($boughts));
        $dataObj->setData('Boughts', $boughts);

        return $dataObj;
    }

    // profile orders
    // -----------------------------------------------
    private function _api_getListOrders_Profile ($profileID) {

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
        $configOrders = configurationShopDataSource::jsapiShopProfileOrders($profileID);
        $dataOrders = $this->getCustomer()->processData($configOrders);

        // var_dump($dataOrders);

        // get order boughts
        foreach ($dataOrders as $key => $order) {
            $orderBoughtsData = $this->_api_getOrderBoughts($order['ID']);
            if ($orderBoughtsData->hasData()) {
                $dataOrders[$key]['Info'] = $orderBoughtsData->getData('Info');
                $dataOrders[$key]['Boughts'] = $orderBoughtsData->getData('Boughts');
            }
        }

        $dataObj->setData('orders', $dataOrders);

        return $dataObj;
    }

    // toolbox orders
    // -----------------------------------------------
    private function _api_getOrderAvailableStatusList () {
        return array("NEW", "ACTIVE", "LOGISTIC_DELIVERING", "LOGISTIC_DELIVERED", "SHOP_CLOSED");
    }

    private function _api_getOrderStatus ($orderHash) {
        // $orderHash
        $dataObj = new libraryDataObject();

        $config = configurationShopDataSource::jsapiShopOrderStatus($orderHash);
        $orderStatus = $this->getCustomer()->processData($config);

        $dataObj->setData('orderStatus', $orderStatus);

        return $dataObj;
    }

    private function _api_getListOrders_Managed () {
        // in toolbox methods we must check it's permission
        // offset
        // limit
        $dataObj = new libraryDataObject();

        // $limit = 2;

        // if (!$this->getCustomer()->getAccess()) {
        //     $dataObj->setError('AccessDenied');
        //     return $dataObj;
        // }

        $configOrders = configurationShopDataSource::jsapiShopSiteOrders();
        $configCount = configurationShopDataSource::jsapiGetTableRecordsCount(configurationShopDataSource::$Table_ShopOrders);

        // pagination
        $page = libraryRequest::getValue('page');
        $per_page = libraryRequest::getValue('per_page');

        $configOrders['limit'] = $per_page;

        if (!empty($page) && !empty($per_page)) {
            $configOrders['offset'] = ($page - 1) * $per_page;
        }

        // sorting
        $sort = libraryRequest::getValue('sort');
        $order = libraryRequest::getValue('order');

        if (!empty($sort) && !empty($order)) {
            $configOrders["order"] = array(
                "field" =>  'shop_orders' . DOT . $sort,
                "ordering" => strtoupper($order)
            );
        }

        // filtering
        $filterBy_status = libraryRequest::getValue('status');
        if (!empty($filterBy_status)) {
            $configOrders['condition']['filter'] .= "Status (=) ?";
            $configOrders['condition']['values'][] = $filterBy_status;
        }

        // var_dump($configOrders);

        // get valid orders count
        $configCount['condition'] = new ArrayObject($configOrders['condition']);

        // get data
        $dataOrders = $this->getCustomer()->processData($configOrders);
        $dataObj->setData('orders', $dataOrders);

        $dataCount = $this->getCustomer()->processData($configCount);
        $dataObj->setData('total_count', $dataCount['ItemsCount']);

        $availableStatuses = $this->_api_getOrderAvailableStatusList();
        $dataObj->setData('statuses', $availableStatuses);
        
        return $dataObj;
    }

    private function _api_updateEntryOrder ($orderID) {
        $dataObj = new libraryDataObject();

        if (!$this->getCustomer()->isAdminActive()) {
            $dataObj->setError('AccessDenied');
            return $dataObj;
        }

        // get fields to update
        $Status = libraryRequest::getPostValue('Status');
        // $fieldValue = libraryRequest::getPostValue('value');
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

        $configOrder = configurationShopDataSource::jsapiShopUpdateOrderEntry($orderID, $data);
        // var_dump($configOrder);
        $this->getCustomer()->processData($configOrder);
        return $dataObj;
    }

    private function _api_getListProducts_Managed () {
        $listType = strtolower(libraryRequest::getValue('type'));

        switch ($listType) {
            case 'active':
                return $this->_api_getListProducts_Managed_Active();
            case 'inactive':
                return $this->_api_getListProducts_Managed_Inactive();
            case 'uncompleted':
                return $this->_api_getListProducts_Managed_Uncompleted();
            case 'sales':
                return $this->_api_getListProducts_Managed_Sales();
            case 'defects':
                return $this->_api_getListProducts_Managed_Defects();
            case 'popular':
                return $this->_api_getListProducts_Managed_Popular();
            case 'notpopular':
                return $this->_api_getListProducts_Managed_NotPopular();
            case 'archived':
                return $this->_api_getListProducts_Managed_Archived();
            default:
                return $this->_api_getListProducts_Managed_All();
        }
    }

    // custom product lists with custom filers and conditions applied
    private function _api_getListProducts_Managed_All () {
        return $this->_api_getListProducts_Managed_Base();
    }

    private function _api_getListProducts_Managed_Inactive () {
        // echo 1111;
        return $this->_api_getListProducts_Managed_Base(function($dataConfigProducts){
            $dataConfigProducts['condition']['values'][0] = "REMOVED";
            return $dataConfigProducts;
        });
    }

    private function _api_getListProducts_Managed_Uncompleted () {
        return $this->_api_getListProducts_Managed_Base(function($dataConfigProducts){
            $dataConfigProducts['condition']['filter'] .= " + shop_products.Name (=) ?";
            $dataConfigProducts['condition']['filter'] .= " + shop_products.Description (=) ?";
            $dataConfigProducts['condition']['filter'] .= " + shop_products.Model (=) ?";
            $dataConfigProducts['condition']['values'][] = "";
            $dataConfigProducts['condition']['values'][] = "";
            $dataConfigProducts['condition']['values'][] = "";
            return $dataConfigProducts;
        });
    }

    private function _api_getListProducts_Managed_Archived () {
        return $this->_api_getListProducts_Managed_Base(function($dataConfigProducts){
            $dataConfigProducts['condition']['filter'] .= " + shop_products.SellMode (=) ?";
            $dataConfigProducts['condition']['values'][] = "ARCHIVED";
            return $dataConfigProducts;
        });
    }

    private function _api_getListProducts_Managed_Defects () {
        return $this->_api_getListProducts_Managed_Base(function($dataConfigProducts){
            $dataConfigProducts['condition']['filter'] .= " + shop_products.SellMode (=) ?";
            $dataConfigProducts['condition']['values'][] = "DEFECT";
            return $dataConfigProducts;
        });
    }

    private function _api_getListProducts_Managed_Sales () {
        return $this->_api_getListProducts_Managed_Base(function($dataConfigProducts){
            $dataConfigProducts['condition']['filter'] .= " + shop_products.SellMode (IN) ?";
            $dataConfigProducts['condition']['values'][] = array("DISCOUNT", "BESTSELLER");
            return $dataConfigProducts;
        });
    }

    private function _api_getListProducts_Managed_Popular () {
        return $this->_api_getListProducts_Managed_Base();
    }

    private function _api_getListProducts_Managed_NotPopular () {
        return $this->_api_getListProducts_Managed_Base();
    }

    private function _api_getListProducts_Managed_Active () {
        return $this->_api_getListProducts_Managed_Base(function($dataConfigProducts){
            $dataConfigProducts['condition']['values'][0] = "ACTIVE";
            return $dataConfigProducts;
        });
    }

    // this is the base function that builds product list witout any conditions
    // and returns whole list with all products types
    private function _api_getListProducts_Managed_Base ($customConfigurator = null) {
        // jsapiProductList
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

        // $dataConfigCategoryInfo = configurationShopDataSource::jsapiProductListCategoryInfo();
        $dataConfigProducts = configurationShopDataSource::jsapiProductList();
        $configCount = configurationShopDataSource::jsapiGetTableRecordsCount(configurationShopDataSource::$Table_ShopProducts);


        // $dataConfigCategoryPriceEdges = configurationShopDataSource::jsapiShopCategoryPriceEdges();
        // $dataConfigCategoryAllBrands = configurationShopDataSource::jsapiShopCategoryAllBrands();
        // $dataConfigCategoryAllSubCategories = configurationShopDataSource::jsapiShopCategoryAllSubCategories();

        // $dataConfigProducts['limit'] = $limit;

        // pagination
        $page = libraryRequest::getValue('page');
        $per_page = libraryRequest::getValue('per_page');

        $dataConfigProducts['limit'] = $per_page;

        if (!empty($page) && !empty($per_page)) {
            $dataConfigProducts['offset'] = ($page - 1) * $per_page;
        }

        // sorting
        $sort = libraryRequest::getValue('sort');
        $order = libraryRequest::getValue('order');

        if (!empty($sort) && !empty($order)) {
            $dataConfigProducts["order"] = array(
                "field" =>  'shop_products' . DOT . $sort,
                "ordering" => strtoupper($order)
            );
        }

        // filtering
        // $filterBy_status = libraryRequest::getValue('status');
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
        $dataProducts = $this->getCustomer()->processData($dataConfigProducts);
        $dataObj->setData('products', $dataProducts);

        $dataCount = $this->getCustomer()->processData($configCount);
        $dataObj->setData('total_count', $dataCount['ItemsCount']);

        return $dataObj;
    }

    private function _api_getToolbox_Dashboard () {
        $dataObj = new libraryDataObject();

        // get expired orders
        $configOrders = configurationShopDataSource::jsapiShopSiteOrders();
        $configOrders['condition']['filter'] .= "Status (!=) ? + DateCreated (<) ?";
        $configOrders['condition']['values'][] = "SHOP_CLOSED";
        $configOrders['condition']['values'][] = date('Y-m-d', strtotime("-1 week"));
        // get data
        $dataOrders = $this->getCustomer()->processData($configOrders);
        if (!empty($dataOrders))
            foreach ($dataOrders as $key => $dataOrder) {
                $orderBoughtsData = $this->_api_getOrderBoughts($dataOrder['ID']);
                $dataOrders[$key]['account'] = $this->getCustomer()->getAccountByID($dataOrder['AccountID']);
                if ($orderBoughtsData->hasData()) {
                    $dataOrders[$key]['info'] = $orderBoughtsData->getData('Info');
                    $dataOrders[$key]['boughts'] = $orderBoughtsData->getData('Boughts');
                }
            }
        $dataObj->setData('orders_expired', $dataOrders);

        // get pending products

        // get today's orders
        $configOrders = configurationShopDataSource::jsapiShopSiteOrders();
        $configOrders['condition']['filter'] .= "Status (=) ? + DateCreated (>) ?";
        $configOrders['condition']['values'][] = "NEW";
        $configOrders['condition']['values'][] = date('Y-m-d');
        // get data
        $dataOrders = $this->getCustomer()->processData($configOrders);
        if (!empty($dataOrders))
            foreach ($dataOrders as $key => $dataOrder) {
                $orderBoughtsData = $this->_api_getOrderBoughts($dataOrder['ID']);
                $dataOrders[$key]['account'] = $this->getCustomer()->getAccountByID($dataOrder['AccountID']);
                if ($orderBoughtsData->hasData()) {
                    $dataOrders[$key]['info'] = $orderBoughtsData->getData('Info');
                    $dataOrders[$key]['boughts'] = $orderBoughtsData->getData('Boughts');
                }
            }
        $dataObj->setData('orders_today', $dataOrders);

        // get top 50 products

        // get non-popuplar 50 products

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

        $configProductsAttr = configurationShopDataSource::jsapiProductAttributes();
        $configProductsAttr["condition"]["values"][] = $productIDs;
        // var_dump($configProductsAttr);

        $configProductsPrice = configurationShopDataSource::jsapiProductPriceStats();
        $configProductsPrice["condition"]["values"][] = $productIDs;

        // configure product attribute object
        $attributes = $this->getCustomer()->processData($configProductsAttr);
        $prices = $this->getCustomer()->processData($configProductsPrice);

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
        $productID = libraryRequest::getValue('productID');
        $do = empty($action) ? libraryRequest::getValue('action') : $action;
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
                $productEntry = $this->_api_getEntryProduct($productID);
                if ($productEntry->hasError()) {
                    $dataObj->setError($productEntry->getError());
                    return $dataObj;
                } else {
                    $_tmp = $productEntry->getData();
                    $products[$productID] = $_tmp['products'][$productID];
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
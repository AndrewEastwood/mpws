<?php

class pluginShop extends objectPlugin {

    public function getResponse () {

        switch(libraryRequest::getValue('fn')) {
            // breadcrumb
            // -----------------------------------------------
            case "shop_location": {
                $productID = libraryRequest::getValue('productID');
                $categoryID = libraryRequest::getValue('categoryID');
                $data = $this->_custom_api_getCatalogLocation($productID, $categoryID);
                break;
            }
            // products list sorted by date added
            // -----------------------------------------------
            case "shop_product_list_latest": {
                $data = $this->_custom_api_getProductList_Latest();
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
                $data = $this->_custom_api_getCatalog();
                break;
            }
            // product standalone item short
            // -----------------------------------------------
            // case "shop_product_item_short" : {
            //     $data = $this->_custom_api_getProductItem($productID, 'short');
            //     break;
            // }
            // product standalone item full
            // -----------------------------------------------
            case "shop_product_item" : {
                $productID = libraryRequest::getValue('productID');
                $data = $this->_custom_api_getProductItem($productID);
                break;
            }
            // shopping cart
            // -----------------------------------------------
            case "shop_wishlist" : {
                $data = $this->_custom_api_shoppingWishList();
                break;
            }
            case "shop_cart" : {
                $data = $this->_custom_api_shoppingCart();
                break;
            }
            case "shop_compare" : {
                $data = $this->_custom_api_productsCompare();
                break;
            }
        }

        // attach to output
        return $data;
    }

    /* PLUGIN API METHODS (ADMIN) */
    // private function _custom_productList () {}
    // private function _custom_productEdit () {}
    // private function _custom_categoryList () {}
    // private function _custom_categoryEdit () {}
    // private function _custom_brandList () {}
    // private function _custom_brandEdit () {}
    // private function _custom_orderList () {}
    // private function _custom_orderEdit () {}


    /* PLUGIN API METHODS (PUBLIC) */
    // breadcrumb
    // -----------------------------------------------
    private function _custom_api_getCatalogLocation ($productID = null, $categoryID = null) {

        $location = new libraryDataObject();

        $location->setData('location', false);

        if (empty($productID) && empty($categoryID))
            return $location;

        if ($productID) {

            // get product entry
            $configProduct = configurationShopDataSource::jsapiProductSingleInfo();
            $configProduct["condition"]["values"][] = $productID;
            $productDataEntry = $this->getDataBase()->getData($configProduct);
            // var_dump($productDataEntry);

            // $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiProductSingleInfo['data']);
            // $dataObj->setValuesDbCondition($productID, MERGE_MODE_APPEND);
            // $dataObj->process();

            // $productDataEntry = $dataObj->getData();

            if (isset($productDataEntry['CategoryID'])) {
                $location2 = $this->_custom_api_getCatalogLocation(null, $productDataEntry['CategoryID']);
                $location->setData('location', $location2->getData('location'));
                $location->setData('product', $productDataEntry);
            } else
                $location->setError("Product category is missing");

        } else {
            $configLocation = configurationShopDataSource::jsapiShopCategoryLocation();
            $configLocation["procedure"]["parameters"][] = $categoryID;
            $location->setData('location', $this->getDataBase()->getData($configLocation));

            // $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiShopCategoryLocation['data']);
            // $dataObj->setValuesDbProcedure($categoryId);
            // $dataObj->process($params);
        }

        return $location;
    }

    // products list sorted by date added
    // -----------------------------------------------
    private function _custom_api_getProductList_Latest () {

        $configProducts = configurationShopDataSource::jsapiProductListLatest();

        $products = $this->getDataBase()->getData($configProducts);

        $productsMap = $this->_custom_util_getProductAttributes($products);

        // update main data object
        $dataObj = new libraryDataObject();
        $dataObj->setData('products', $productsMap);

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
    // // -----------------------------------------------
    // private function _custom_api_getCatalogFiltering () {

    // }

    // shop catalog structure
    // -----------------------------------------------
    private function _custom_api_getCatalogStructure () {

        $config = configurationShopDataSource::jsapiCatalogStructure();
        $categories = $this->getDataBase()->getData($config);

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
    private function _custom_api_getCatalog () {

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
        $filterOptionsAvailable['filter_commonAvailability'] = array("AVAILABLE", "OUTOFSTOCK", "COMINGSOON");
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
        $dataCategoryPriceEdges = $this->getDataBase()->getData($dataConfigCategoryPriceEdges);
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
        $dataCategoryAllBrands = $this->getDataBase()->getData($dataConfigCategoryAllBrands);
        $dataCategoryAllSubCategories = $this->getDataBase()->getData($dataConfigCategoryAllSubCategories);

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
        $dataProducts = $this->getDataBase()->getData($dataConfigProducts);

        // get category info according to product filter
        $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
        $dataCategoryInfo = $this->getDataBase()->getData($dataConfigCategoryInfo);

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

        // pagination
        $_pagination = array(
            "TotalItemsCount" => count($dataCategoryInfo),
            "ItemsOnPage" => $filterOptionsApplied['filter_viewItemsOnPage'],
            "PagesCount" => count($dataCategoryInfo) / $filterOptionsApplied['filter_viewItemsOnPage'],
            "PageNext" => 2,
            "PagePrev" => 2,
            "Pages" => 2
        );

        // var_dump($dataConfigProducts);
        // attach attributes
        $productsMap = $this->_custom_util_getProductAttributes($dataProducts);
        // store data
        $dataObj->setData('products', $productsMap);
        $dataObj->setData('pagination', $_pagination);
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
    private function _custom_api_getProductItem ($productID) {
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
            $product = $this->getDataBase()->getData($config);

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
    private function _custom_api_shoppingWishList () {
        return $this->_custom_util_manageStoredProducts('shopWishList');
    }

    // shopping products compare
    // -----------------------------------------------
    private function _custom_api_productsCompare () {
        $productID = libraryRequest::getValue('productID');
        $dataObj = $this->_custom_util_manageStoredProducts('shopProductsCompare');

        $products = $dataObj->getData();

        if (count($products['products']) > 10) {
            unset($products['products'][$productID]);
            $dataObj->setError("MaxProductsAdded");
            $dataObj->setData('products', $products);
        }

        return $dataObj;
    }

    // shopping cart
    // -----------------------------------------------
    private function _custom_api_shoppingCart () {

        $do = libraryRequest::getValue('action');
        $dataObj = $this->_custom_util_manageStoredProducts('shopCartProducts');

        // var_dump($dataObj);

        $productData = $dataObj->getData();

        // adjust product id and quantity
        $productID = intval(libraryRequest::getValue('productID'));
        $productQuantity = intval(libraryRequest::getValue('productQuantity'));

        $_getInfoFn = function (&$_products = array()) {

            // get cartInfo
            $cartInfo = array(
                "subTotal" => 0.0,
                "discount" => 0,
                "total" => 0.0,
                "productCount" => 0
            );

            if (empty($_products))
                return $cartInfo;

            foreach ($_products as &$_item) {
                $_item["_total"] = $_item['Price'] * $_item['_quantity'];
                $cartInfo["subTotal"] += $_item['_total'];
                $cartInfo["productCount"] += $_item['_quantity'];
            }
            $cartInfo["total"] = (($cartInfo['discount'] / 100) ?: 1) *  $cartInfo['subTotal'];

            // update money formats
            $cartInfo["discount"] = money_format('%.2n%%', $cartInfo["discount"]);
            $cartInfo["subTotal"] = money_format('%.2n', $cartInfo["subTotal"]);
            $cartInfo["total"] = money_format('%.2n', $cartInfo["total"]);

            return $cartInfo;
        };

        // create/add product
        if ($do == 'ADD' && $productQuantity) {
            if (empty($productData['products'][$productID]['_quantity']))
                $productData['products'][$productID]['_quantity'] = 0;

            $productData['products'][$productID]['_quantity'] += $productQuantity;

            // we keep product until REMOVE action is invoked
            if ($productData['products'][$productID]['_quantity'] <= 0)
                $productData['products'][$productID]['_quantity'] = 1;

            $_SESSION['shopCartProducts'] = $productData['products'];
        }

        // get shopping cart info
        if ($do == 'SAVE') {

            $cartUser = libraryRequest::getPostValue('user');

            // $dataObj->setData('products', $productData['products']);
            // $dataObj->setData('info', $_getInfoFn($productData));
            // return $dataObj;
        }

        $dataObj->setData('products', $productData['products']);
        $dataObj->setData('info', $_getInfoFn($productData['products']));

        return $dataObj;
    }

    // product additional data
    // @products - array with product(s)
    private function _custom_util_getProductAttributes ($products) {
        if (empty($products))
            return null;
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
        $attributes = $this->getDataBase()->getData($configProductsAttr);
        $prices = $this->getDataBase()->getData($configProductsPrice);

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

    private function _custom_util_manageStoredProducts ($sessionKey) {
        $dataObj = new libraryDataObject();
        $productID = libraryRequest::getValue('productID');
        $do = libraryRequest::getValue('action');
        $actions = array('ADD', 'REMOVE', 'CLEAR', 'INFO');

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
                $productEntry = $this->_custom_api_getProductItem($productID);
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
    }

    // product list base

    // accounts
    // private function _custom_accountSignin () {}
    // private function _custom_accountProfile () {}
    // private function _custom_accountSubscriptions () {}
    // private function _custom_accountSettings () {}
    // private function _custom_accountOrdersActive () {}
    // private function _custom_accountOrdersHistory () {}
    // private function _custom_accountSignout () {}

}

?>
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
                $data = $this->_custom_api_getProductList_Latest(array(
                    "limit" => libraryRequest::getValue('offset', false)
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
            case "shop_cart_save" : {
                $data = $this->_custom_api_shoppingCartSave($param);
                break;
            }
            case "shop_cart_clear" : {
                $data = $this->_custom_api_shoppingCartClear();
                break;
            }
            case "shop_cart_manage" : {
                $data = $this->_custom_api_shoppingCartManage($productID, $param);
                break;
            }
            case "shop_cart_content" : {
                $data = $this->_custom_api_shoppingCartContent();
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
    private function _custom_api_getProductList_Latest ($params) {

        $configProducts = configurationShopDataSource::jsapiProductListLatest();

        $products = $this->getDataBase()->getData($configProducts);

        $productsMap = $this->_custom_api_getProductAttributes($products);

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
        $productsMap = $this->_custom_api_getProductAttributes($dataProducts);
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

        // grap all available filter options of running category
        // ---
        // $dataCategoryBrands = new mpwsData(false, $this->objectConfiguration_data_jsapiShopCategoryBrands['data']);
        // $dataCategoryBrands->setValuesDbProcedure($categoryId);
        // $dataCategoryBrand = configurationShopDataSource::jsapiProductListCategory();

        // $dataCategoryPriceEdges = new mpwsData(false, $this->objectConfiguration_data_jsapiShopCategoryPriceEdges['data']);
        // $dataCategoryPriceEdges->setValuesDbProcedure($categoryId);
        // $dataCategoryPriceEdges->process($params);

        // $dataCategorySubCategories = new mpwsData(false, $this->objectConfiguration_data_jsapiShopCategorySubCategories['data']);
        // $dataCategorySubCategories->setValuesDbProcedure($categoryId);
        // $dataCategorySubCategories->process($params);

        // get max and min prices
/*        $filterOptionsAvailable['filter_commonPriceMax'] = intval($dataCategoryPriceEdges->getData('PriceMax') ?: 0);
        $filterOptionsAvailable['filter_commonPriceMin'] = intval($dataCategoryPriceEdges->getData('PriceMin') ?: 0);
        $filterOptionsAvailable['filter_commonAvailability'] = array("AVAILABLE", "OUTOFSTOCK", "COMINGSOON");
        $filterOptionsAvailable['filter_commonOnSaleTypes'] = array('SHOP_CLEARANCE','SHOP_NEW','SHOP_HOTOFFER','SHOP_BESTSELLER','SHOP_LIMITED');

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
        $filterOptionsApplied['filter_commonAvailability'] = getValue($params['filter_commonAvailability'], array());
        $filterOptionsApplied['filter_commonOnSaleTypes'] = getValue($params['filter_commonOnSaleTypes'], array());

        $filterOptionsApplied['filter_viewSortBy'] = getValue($params['filter_viewSortBy'], null);
        $filterOptionsApplied['filter_viewItemsOnPage'] = intval(getNonEmptyValue($params['filter_viewItemsOnPage'], $dataConfigProducts['limit']));

        $filterOptionsApplied['filter_categoryBrands'] = getValue($params['filter_categoryBrands'], array());

        // adjust filters
        if (!empty($filterOptionsApplied['filter_viewItemsOnPage']))
            $dataConfigProducts['limit'] = $filterOptionsApplied['filter_viewItemsOnPage'];
        else
            $filterOptionsApplied['filter_viewItemsOnPage'] = $dataConfigProducts['limit'];

        $_filterSorting = explode('_', strtolower($filterOptionsApplied['filter_viewSortBy']));
        if (count($_filterSorting) === 2 && !empty($_filterSorting[0]) && ($_filterSorting[1] === 'asc' || $_filterSorting[1] === 'desc'))
            $dataConfigProducts['order'] = array('field' => $dataConfigProducts['source'] . '.' . ucfirst($_filterSorting[0]), 'ordering' => strtoupper($_filterSorting[1]));
        else
            $filterOptionsApplied['filter_viewSortBy'] = null;

        if ($filterOptionsApplied['filter_commonPriceMax'] > 0 && $filterOptionsApplied['filter_commonPriceMax'] < $filterOptionsAvailable['filter_commonPriceMax']) {
            $dataConfigProducts['condition']['filter'] .= " + Price (<) ?";
            $pendingDbConditions[] = 'filter_commonPriceMax';
        } else
            $filterOptionsApplied['filter_commonPriceMax'] = $filterOptionsAvailable['filter_commonPriceMax'];

        if ($filterOptionsApplied['filter_commonPriceMin'] > 0) {
            $dataConfigProducts['condition']['filter'] .= " + Price (>) ?";
            $pendingDbConditions[] = 'filter_commonPriceMin';
        } else
            $filterOptionsApplied['filter_commonPriceMin'] = 0;

        // var_dump($filterOptionsApplied['filter_categoryBrands']);

        if (count($filterOptionsApplied['filter_categoryBrands']) > 0) {
            $dataConfigProducts['condition']['filter'] .= " + OriginID (IN) ?";
            $pendingDbConditions[] = 'filter_categoryBrands';
        }

        // update data config
        // ---
        $dataObj->setConfig($dataConfigProducts);

        // update conditions
        $dataObj->setValuesDbCondition($categoryId, MERGE_MODE_APPEND);

        foreach ($pendingDbConditions as $filterKey)
            $dataObj->setValuesDbCondition($filterOptionsApplied[$filterKey], MERGE_MODE_APPEND);

        // var_dump($dataObj->getConfig($dataConfigProducts));

        // get data with filter
        // ---
        $products = $this->getDataBase()->getData($configLocation);
        
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
            $attributesMap[$value['productID']] = $value['ProductAttributes'];

        // update main data object
        // ---
        $dataObj->setData($_result);*/

        // var_dump($dataConfigProducts);
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

            $productsMap = $this->_custom_api_getProductAttributes(array($product));

            $dataObj->setData('products', $productsMap);

            // save product into recently viewed
            $recentProducts = $_SESSION['shop:recentProducts'] ?: array();
            $recentProducts[$productID] = $productsMap[$productID];
            $_SESSION['shop:recentProducts'] = $recentProducts;
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
    private function _custom_api_shoppingCartManage ($productID, $param) {

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
                    unset($cart[$productID]);
                else {
                    // check existatce
                    if (isset($cart[$productID])) {
                        $cart[$productID]["products"][$productID]["CartAmount"] += $amount;
                        // remove item when amount is -1 and current amount is 1
                        if ($cart[$productID]["products"][$productID]["CartAmount"] === 0)
                            unset($cart[$productID]);
                    } else {
                        // just get new product entry annd add it into cart
                        $productEntry = $this->_custom_api_getProductItem($productID, 'short');
                        if ($productEntry->hasData()) {
                            $cart[$productID] = $productEntry->getData();
                            $cart[$productID]["products"][$productID]["CartAmount"] = 1;
                        } else
                            $error = "Wrong product ID";
                    }
                    // update product total
                    if (!$error)
                        $cart[$productID]["products"][$productID]["CartTotal"] = $cart[$productID]["products"][$productID]["CartAmount"] * $cart[$productID]["products"][$productID]["Price"];
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
        foreach ($cart as $productID => $productEntry) {
            // // update each product
            // $cart[$pID] = $productEntry;
            // $cart[$pID]["Total"] = $cart[$pID]["Amount"] * $cart[$pID]["Price"];

            // update cart checkout info
            $cart_info["productAmount"] += $productEntry["products"][$productID]["CartAmount"];
            $cart_info["total"] += $productEntry["products"][$productID]["CartTotal"];
        }

        // $_SESSION['shop:cart_info'] = $cart_info;

        return $cart_info;
    }

    // catalog filtering


    // catalog

    // origins
    // private function _custom_api_getOrigin () {
    //     $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiOriginList['data']);
    //     return $dataObj->process($params);
    // }

    // // categories
    // private function _custom_api_getCategory () {
    //     $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiCategoryList['data']);
    //     return $dataObj->process($params);
    // }

    // category origins
    // private function _custom_api_getCategoryOrigins () {
    //     $dataObj = new mpwsData(false, $this->objectConfiguration_data_jsapiCategoryList['data']);
    //     return $dataObj->process($params);
    // }

    // product list


    // product item

    // product additional data
    // @productIDs - array of product ids
    private function _custom_api_getProductAttributes ($products) {
        if (empty($products))
            return null;
        // list of product ids to fetch related attributes
        $productIDs = array();

        // mapped data (key is record's ID)
        $productsMap = array();

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

        // var_dump($attributes);
        // var_dump($prices);
        if (!empty($attributes))
            foreach ($attributes as $value) {
                // var_dump($value);
                $productsMap[$value['ProductID']]['Attributes'] = $value['ProductAttributes'];
            }
        if (!empty($prices))
            foreach ($prices as $value) {
                // var_dump($value);
                $productsMap[$value['ProductID']]['Prices'] = $value['PriceArchive'];
            }

        // var_dump($productsMap);
        return $productsMap;
    }

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
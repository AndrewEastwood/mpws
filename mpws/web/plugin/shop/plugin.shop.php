<?php

class pluginShop extends objectPlugin {

    private $_listKey_Wish = 'shop:wishList';
    private $_listKey_Recent = 'shop:listRecent';
    private $_listKey_Compare = 'shop:listCompare';
    private $_listKey_Cart = 'shop:cart';
    private $_listKey_Promo = 'shop:promo';

    // product standalone item (short or full)
    // -----------------------------------------------
    private function _getProductByID ($productID, $saveIntoRecent = false, $skipRelations = false) {
        if (empty($productID) || !is_numeric($productID))
            return null;

        $config = configurationShopDataSource::jsapiShopProductItemGet($productID);
        $product = $this->getCustomer()->fetch($config);

        if (empty($product))
            return null;

        $configProductsAttr = configurationShopDataSource::jsapiShopProductAttributesGet($productID);
        $configProductsPrice = configurationShopDataSource::jsapiShopProductPriceStatsGet($productID);
        $configProductsFeatures = configurationShopDataSource::jsapiShopGetProductFeatures($productID);
        $configProductsRelations = configurationShopDataSource::jsapiShopProductRelations($productID);

        $product['Attributes'] = $this->getCustomer()->fetch($configProductsAttr);
        $product['Prices'] = $this->getCustomer()->fetch($configProductsPrice);
        $product['Features'] = $this->getCustomer()->fetch($configProductsFeatures);

        // adjusting
        $product['ID'] = intval($product['ID']);
        $product['CategoryID'] = intval($product['CategoryID']);
        $product['OriginID'] = intval($product['OriginID']);
        $product['Attributes'] = $product['Attributes']['ProductAttributes'];
        $product['IsPromo'] = intval($product['IsPromo']) === 1;
        $product['Price'] = floatval($product['Price']);
        $product['Prices'] = array_map(function($price) { return floatval($price); }, $product['Prices']['PriceArchive'] ?: array());

        if (!is_array($product['Features']))
            $product['Features'] = array();

        $relations = array();
        if (!$skipRelations) {
            $relatedItemsIDs = $this->getCustomer()->fetch($configProductsRelations);
            if (isset($relatedItemsIDs)) {
                foreach ($relatedItemsIDs as $relationItem) {
                    $relatedProductID = intval($relationItem['ProductB_ID']);
                    if ($relatedProductID === $productID)
                        continue;
                    $relatedProduct = $this->_getProductByID($relatedProductID, $saveIntoRecent, true);
                    if (isset($relatedProduct))
                        $relations[] = $relatedProduct;
                }
            }
        }
        $product['Relations'] = $relations;

        // Utils
        $product['_viewExtras'] = array();
        $product['_viewExtras']['InWish'] = $this->__productIsInWishList($productID);
        $product['_viewExtras']['InCompare'] = $this->__productIsInCompareList($productID);
        $product['_viewExtras']['InCartCount'] = $this->__productCountInCart($productID);

        // promo
        $promo = isset($_SESSION[$this->_listKey_Promo]) ? $_SESSION[$this->_listKey_Promo] : array();
        $product['_promoIsApplied'] = false;
        if ($product['IsPromo'] && isset($promo) && !empty($promo['Discount'])&& $promo['Discount'] > 0) {
            $product['_promoIsApplied'] = true;
            $product['Price'] = (100 - intval($promo['Discount'])) / 100 * $product['Price'];
            $product['promo'] = $promo;
        }

        // is available
        $product['_available'] = in_array($product['Status'], array("ACTIVE", "DISCOUNT", "PREORDER", "DEFECT"));

        // save product into recently viewed list
        if ($saveIntoRecent && !glIsToolbox()) {
            $recentProducts = isset($_SESSION[$this->_listKey_Recent]) ? $_SESSION[$this->_listKey_Recent] : array();
            $recentProducts[$productID] = $product;
            $_SESSION[$this->_listKey_Recent] = $recentProducts;
        }
        return $product;
    }

    // products list sorted by date added
    // -----------------------------------------------

    private function _getProducts_TopNonPopular () {
        // get non-popuplar 50 products
        $config = configurationShopDataSource::jsapiShopStat_NonPopularProducts();
        $productIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($productIDs))
            foreach ($productIDs as $val)
                $data[] = $this->_getProductByID($val['ID']);
        return $data;
    }

    private function _getProducts_TopPopular () {
        // get top 50 products
        $config = configurationShopDataSource::jsapiShopStat_PopularProducts();
        $productIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($productIDs))
            foreach ($productIDs as $val)
                $data[] = $this->_getProductByID($val['ProductID']);
        return $data;
    }

    private function _getProducts_Latest () {
        // get expired orders
        $config = configurationShopDataSource::jsapiShopProductListLatest();
        $productIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($productIDs))
            foreach ($productIDs as $val)
                $data[] = $this->_getProductByID($val['ID']);
        return $data;
    }

    private function _getProducts_ByStatus ($status) {
        $config = configurationShopDataSource::jsapiShopProductListByStatus($status);
        $productIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($productIDs))
            foreach ($productIDs as $val)
                $data[] = $this->_getProductByID($val['ID']);
        return $data;
    }

    private function _getProducts_Sale () {
        $config = configurationShopDataSource::jsapiShopProductListByStatus(array('DISCOUNT', 'DEFECT'), 'IN');
        $productIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($productIDs))
            foreach ($productIDs as $val)
                $data[] = $this->_getProductByID($val['ID']);
        return $data;
    }

    private function _getProducts_Uncompleted () {
        $config = configurationShopDataSource::jsapiShopProductListUncompleted();
        $productIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($productIDs))
            foreach ($productIDs as $val)
                $data[] = $this->_getProductByID($val['ID']);
        return $data;
    }

    private function _getOrderByID ($orderID) {
        $config = configurationShopDataSource::jsapiGetShopOrderByID($orderID);
        $order = $this->getCustomer()->fetch($config);
        $order['ID'] = intval($order['ID']);
        $this->___attachOrderExtras($order);
        return $order;
    }

    private function _getOrderByHash ($orderHash) {
        $config = configurationShopDataSource::jsapiGetShopOrderByHash($orderHash);
        $order = $this->getCustomer()->fetch($config);
        $order['ID'] = intval($order['ID']);
        $this->___attachOrderExtras($order);
        return $order;
    }

    private function _getOrderTemp ($options = array()) {
        // $order['ID'] = null;
        // $options = isset($options) ? $options : array();
        $order = isset($_SESSION[$this->_listKey_Cart]) ? $_SESSION[$this->_listKey_Cart] : array();
        $order['temp'] = true;

        // set promo
        // if (isset($options['promo']))
        //     $order['promo'] = $options['promo'];
        // re-validate promo
        // elseif (isset($order['promo']) && isset($order['promo']['Code']))
        //     $order['promo'] = $this->_getPromoByHash($order['promo']['Code'], true) ?: array();
        
        // $order['items'] = isset($order['items']) ? $order['items'] : array();
        // else
        //     $order['promo'] = array();
        // re-calc totals
        // if (!isset($options['useBackup']) || !$options['useBackup'])
        $this->___attachOrderExtras($order);
        // save order in session
        $_SESSION[$this->_listKey_Cart] = $order;
        // $_SESSION[$this->_listKey_Promo] = $order['promo'];
        return $order;
    }

    private function _resetOrderTemp () {
        $_SESSION[$this->_listKey_Cart] = null;
        $_SESSION[$this->_listKey_Promo] = null;
    }

    private function _createOrder ($reqData) {

        $result = array();
        $errors = array();
        $success = false;

        // var_dump($order);
        // var_dump($reqData);

        $accountToken =  "";
        $formAccountToken = "";
        $formAddressID = "";

        if (!empty($reqData['account']))
            $accountToken = $reqData['account']['ValidationString'];

        if (!empty($reqData['form']['shopCartAccountValidationString']))
            $formAccountToken = $reqData['form']['shopCartAccountValidationString'];

        if (!empty($reqData['form']['shopCartAccountValidationString']))
            $formAddressID = $reqData['form']['shopCartAccountAddressID'];

        $pluginAccount = $this->getPlugin('account');

        try {
            $this->getCustomerDataBase()->beginTransaction();
            $this->getCustomerDataBase()->disableTransactions();

            // check if matches
            if ($accountToken !== $formAccountToken)
                throw new Exception("WrongTokensOccured", 1);

            // create new profile
            if (empty($accountToken) && empty($formAccountToken)) {

                // reset address id becuase empty account is occured
                $formAddressID = null;

                // create new account
                $new_password = librarySecure::generateStrongPassword();

                $account = $pluginAccount->createAccount(array(
                    "FirstName" => $reqData['form']['shopCartUserName'],
                    "EMail" => $reqData['form']['shopCartUserEmail'],
                    "Phone" => $reqData['form']['shopCartUserPhone'],
                    "Password" => $new_password,
                    "ConfirmPassword" => $new_password
                ));

                if (count($account['errors']))
                    $errors['Account'] = $account['errors'];

                if ($account['success'] === false)
                    throw new Exception("AccountCreateError", 1);

            } else {

                // get account by token string (ValidationString)
                $account = $pluginAccount->getAccountByValidationString($formAccountToken);

                if (empty($account))
                    throw new Exception("WrongAccount", 1);

                if ($account['Status'] !== "ACTIVE")
                    throw new Exception("AccountIsBlocked", 1);

                // need to validate account
                // if account exits
                // if account is active
                if ($account["FirstName"] !== $reqData["form"]["shopCartAccountFirstName"] ||
                    $account["LastName"] !== $reqData["form"]["shopCartAccountLastName"] ||
                    $account["EMail"] !== $reqData["form"]["shopCartAccountEMail"] ||
                    $account["Phone"] !== $reqData["form"]["shopCartAccountPhone"])
                    throw new Exception("AccountDataMismatch", 1);

                // at this point we have a valid account
            }

            $accountID = $account['ID'];

            // create new address for account
            if (empty($formAddressID) || empty($formAccountToken)) {

                // create account address
                $accountAddress = $pluginAccount->createAddress($accountID, array(
                    "Address" => $reqData['form']['shopCartUserAddress'],
                    "POBox" => $reqData['form']['shopCartUserPOBox'],
                    "Country" => $reqData['form']['shopCartUserCountry'],
                    "City" => $reqData['form']['shopCartUserCity']
                ), true); // <= this allows creating unliked addresses or add new address to account when it's possible

                if (count($accountAddress['errors']))
                    $errors['Account'] = $accountAddress['errors'];

                if ($accountAddress['success'] === false)
                    throw new Exception("AccountAddressCreateError", 1);

            } else {
                // validate provided address id for account
                // we must check it if the authenticated account has this address id
                if (!isset($account['Addresses'][$formAddressID]))
                    throw new Exception("WrongAccountAddressID", 1);
                else
                    $accountAddress = $account['Addresses'][$formAddressID];
            }

            $addressID = $accountAddress['ID'];

            $order = $this->_getOrderTemp(array("useBackup" => true));

            if (empty($order['items']))
                 throw new Exception("NoProudctsToPurchase", 1);

            $orderPromoID = empty($order['promo']) ? null : $order['promo']['ID'];

            // start creating order
            $dataOrder = array();
            $dataOrder["AccountID"] = $accountID;
            $dataOrder["AccountAddressesID"] = $addressID;
            $dataOrder["CustomerID"] = $this->getCustomer()->getCustomerID();
            $dataOrder["Shipping"] = $reqData['form']['shopCartLogistic'];
            $dataOrder["Warehouse"] = $reqData['form']['shopCartWarehouse'];
            $dataOrder["Comment"] = $reqData['form']['shopCartComment'];
            $dataOrder["PromoID"] = $orderPromoID;

            $configOrder = configurationShopDataSource::jsapiShopOrderCreate($dataOrder);
            $orderID = $this->getCustomer()->fetch($configOrder);

            if (empty($orderID))
                throw new Exception("OrderCreateError", 1);

            // save products
            // -----------------------
            // ProductID
            // OrderID
            // ProductPrice
            // _orderQuantity
            foreach ($order['items'] as $productItem) {
                $dataBought = array();
                $dataBought["CustomerID"] = $this->getCustomer()->getCustomerID();
                $dataBought["ProductID"] = $productItem["ID"];
                $dataBought["OrderID"] = $orderID;
                $dataBought["ProductPrice"] = $productItem["Price"];
                $dataBought["Quantity"] = $productItem["_orderQuantity"];
                $dataBought["IsPromo"] = $productItem["IsPromo"];
                $configBought = configurationShopDataSource::jsapiShopOrderBoughtCreate($dataBought);
                $boughtID = $this->getCustomer()->fetch($configBought);

                // check for created bought
                if (empty($boughtID))
                    throw new Exception("BoughtCreateError", 1);
            }

            // if (empty($accountID) || empty($addressID))
            //     throw new Exception("UnableToLinkAccountOrAddress", 1);

            $this->getCustomerDataBase()->enableTransactions();
            $this->getCustomerDataBase()->commit();

            $success = true;

            // reset temp order
            $this->_resetOrderTemp();

            // get created order
            $result = $this->_getOrderByID($orderID);

        } catch (Exception $e) {
            $this->getCustomerDataBase()->enableTransactions();
            $this->getCustomerDataBase()->rollBack();
            $errors['Order'][] = $e->getMessage();
        }

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    private function ___attachOrderExtras (&$order) {
        // echo "___attachOrderExtras";
        if (empty($order))
            return;

        $orderID = isset($order['ID']) ? $order['ID']: null;
        $order['promo'] = null;
        $order['account'] = null;
        $order['address'] = null;
        $productItems = array();
        // var_dump($order);
        // if orderID is set then the order is saved
        if (isset($orderID) && !isset($order['temp'])) {
            // attach account and address
            if ($this->getCustomer()->hasPlugin('account')) {
                if (isset($order['AccountAddressesID']))
                    $order['address'] = $this->getCustomer()->getPlugin('account')->getAddressByID($order['AccountAddressesID']);
                if (isset($order['AccountID']))
                    $order['account'] = $this->getCustomer()->getPlugin('account')->getAccountByID($order['AccountID']);
                unset($order['AccountID']);
                unset($order['AccountAddressesID']);
            }
            // get promo
            if (!empty($order['PromoID']))
                $order['promo'] = $this->_getPromoByID($order['PromoID']);
            // $order['items'] = array();
            $configBoughts = configurationShopDataSource::jsapiShopBoughtsGet($orderID);
            $boughts = $this->getCustomer()->fetch($configBoughts) ?: array();
            if (!empty($boughts))
                foreach ($boughts as $bkey => $soldItem) {
                    $product = $this->_getProductByID($soldItem['ProductID']);
                    // save current product info
                    $product["CurrentIsPromo"] = $product['IsPromo'];
                    $product["CurrentPrice"] = $product['Price'];
                    // restore product info at purchase moment
                    $product["Price"] = $soldItem['ProductPrice'];
                    $product["IsPromo"] = $soldItem['IsPromo'];
                    // get purchased product quantity
                    $product["_orderQuantity"] = $soldItem['Quantity'];
                    // add into list
                    $productItems[$product['ID']] = $product;
                }
        } else {
            $productItems = !empty($order['items']) ? $order['items'] : array();
            // re-validate promo
            $sessionPromo = $_SESSION[$this->_listKey_Promo];
            if (!empty($sessionPromo) && isset($sessionPromo['Code']))
                $sessionPromo = $this->_getPromoByHash($sessionPromo['Code'], true);
            $order['promo'] = $sessionPromo;
        }
        // append info
        $info = array(
            "subTotal" => 0.0,
            "total" => 0.0,
            "productCount" => 0,
            "productUniqueCount" => count($productItems),
            "hasPromo" => isset($promo['Discount']) && $promo['Discount'] > 0,
            "allProductsWithPromo" => true
        );
        // calc order totals
        foreach ($productItems as &$product) {
            $product["_orderSubTotal"] = $product['Price'] * $product['_orderQuantity'];
            $product['IsPromo'] = intval($product['IsPromo']) === 1;
            if ($product['IsPromo'] && isset($promo) && !empty($promo['Discount']))
                $product['Price'] = (100 - intval($promo['Discount'])) / 100 * $product['Price'];
            $product["Total"] = $product['Price'] * $product['_orderQuantity'];
            // update order totals
            $info["total"] += floatval($product['Total']);
            $info["subTotal"] += floatval($product['_orderSubTotal']);
            $info["productCount"] += intval($product['_orderQuantity']);
            $info["allProductsWithPromo"] = $info["allProductsWithPromo"] && $product['IsPromo'];
        }
        $order['items'] = $productItems;
        $order['info'] = $info;
    }

    private function _getOrders_Expired () {
        // get expired orders
        $config = configurationShopDataSource::jsapiShopOrdersForSiteGet();
        $config['condition']['Status'] = configurationShopDataSource::jsapiCreateDataSourceCondition("SHOP_CLOSED", "!=");
        $config['condition']['DateCreated'] = configurationShopDataSource::jsapiCreateDataSourceCondition(date('Y-m-d', strtotime("-1 week")), "<");
        $orderIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($orderIDs))
            foreach ($orderIDs as $val)
                $data[] = $this->_getOrderByID($val['ID']);
        return $data;
    }

    private function _getOrders_Todays () {
        // get todays orders
        $config = configurationShopDataSource::jsapiShopOrdersForSiteGet();
        $config['condition']['Status'] = configurationShopDataSource::jsapiCreateDataSourceCondition("NEW");
        $config['condition']['DateCreated'] = configurationShopDataSource::jsapiCreateDataSourceCondition(date('Y-m-d'));
        $orderIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($orderIDs))
            foreach ($orderIDs as $val)
                $data[] = $this->_getOrderByID($val['ID']);
        return $data;
    }

    private function _getOrders_ByStatus ($status) {
        // get expired orders
        $config = configurationShopDataSource::jsapiShopOrdersForSiteGet();
        $config['condition']['Status'] = configurationShopDataSource::jsapiCreateDataSourceCondition($status);
        $orderIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($orderIDs))
            foreach ($orderIDs as $val)
                $data[] = $this->_getOrderByID($val['ID']);
        return $data;
    }

    private function _getOrders_Browse ($req) {
        // get all orders
        $config = configurationShopDataSource::jsapiShopOrdersGet();

        // pagination
        $page = isset($req->get['page']) ? $req->get['page'] : false;
        $per_page = isset($req->get['per_page']) ? $req->get['per_page'] : false;

        if (!empty($per_page)) {
            $config['limit'] = $per_page;
            if (!empty($page)) {
                $config['offset'] = ($page - 1) * $per_page;
            }
        }

        // sorting
        $sort = isset($req->get['sort']) ? $req->get['sort'] : false;
        $order = isset($req->get['order']) ? $req->get['order'] : false;
        if (!empty($sort) && !empty($order)) {
            $config["order"] = array(
                "field" =>  'shop_orders' . DOT . $sort,
                "ordering" => strtoupper($order)
            );
        }

        $orderIDs = $this->getCustomer()->fetch($config);
        // var_dump($orderIDs);
        $data = array();
        if (!empty($orderIDs))
            foreach ($orderIDs as $val)
                $data[$val['ID']] = $this->_getOrderByID($val['ID']);
        return $data;
    }

    private function _getStats_OrdersOverview () {
        $config = configurationShopDataSource::jsapiShopStat_OrdersOverview();
        $data = $this->getCustomer()->fetch($config);
        return $data;
    }

    private function _getStats_ProductsOverview () {
        // get shop products overview:
        $config = configurationShopDataSource::jsapiShopStat_ProductsOverview();
        $data = $this->getCustomer()->fetch($config);
        return $data;
    }

    // breadcrumb
    // -----------------------------------------------
    private function _getCatalogLocation ($productID = null, $categoryID = null) {
        $location = null;

        if (empty($productID) && empty($categoryID))
            return $location;

        if ($productID) {
            // get product entry
            $configProduct = configurationShopDataSource::jsapiShopProductSingleInfoGet($productID);
            $productDataEntry = $this->getCustomer()->fetch($configProduct);
            if (isset($productDataEntry['CategoryID'])) {
                $configLocation = configurationShopDataSource::jsapiShopCategoryLocationGet($productDataEntry['CategoryID']);
                $location['items'] = $this->getCustomer()->fetch($configLocation);
                $location['product'] = $productDataEntry;
            }
        } else {
            $configLocation = configurationShopDataSource::jsapiShopCategoryLocationGet($categoryID);
            $location['items'] = $this->getCustomer()->fetch($configLocation);
        }
        return $location;
    }

    // shop catalog tree
    // -----------------------------------------------
    private function _getCatalogTree () {

        function getTree (array &$elements, $parentId = null) {
            $branch = array();
            // echo "#######Looking for element where parentid ==", $parentId, PHP_EOL;
            foreach ($elements as $key => $element) {
                // echo "~~~Current element ID = ", $element['ParentID'], PHP_EOL;
                if ($element['ParentID'] == $parentId) {
                    // echo "Element is found".PHP_EOL;
                    // echo "Looking for element child nodes wherer ParentID = ", $key,PHP_EOL;
                    $element['childNodes'] = getTree($elements, $key);
                    $branch[$key] = $element;
                    // unset($elements[$key]);
                }
            }
            // echo PHP_EOL . "-=-=-=-=-=-=--=--Results for element where parentid ==", $parentId. PHP_EOL;
            // var_dump($branch);
            return $branch;
        }

        $config = configurationShopDataSource::jsapiShopCatalogTree();
        $categories = $this->getCustomer()->fetch($config);
        $map = array();
        foreach ($categories as $key => $value)
            $map[$value['ID']] = $value;

        $tree = getTree($map);

        return $tree;
    }

    private function _getCatalogBrowse () {

        $data = array();
        $categoryID = libraryRequest::fromGET('id', null);

        if (!is_numeric($categoryID)) {
            $data['error'] = '"id" parameter is missed';
            return $data;
        }

        $categoryID = intval($categoryID);

        $filterOptions = array(
            /* common options */
            "id" => $categoryID,
            "filter_viewSortBy" => null,
            "filter_viewItemsOnPage" => 16,
            "filter_viewPageNum" => 1,
            "filter_commonPriceMax" => null,
            "filter_commonPriceMin" => 0,
            "filter_commonStatus" => array(),
            "filter_commonFeatures" => array(),

            /* category based */
            "filter_categoryBrands" => array(),
            "filter_categorySubCategories" => array(),
        );

        // filtering
        $filterOptionsApplied = new ArrayObject($filterOptions);
        $filterOptionsAvailable = new ArrayObject($filterOptions);

        // get all product available statuses
        $filterOptionsAvailable['filter_commonStatus'] = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopProducts);

        // init filter
        foreach ($filterOptionsApplied as $key => $value) {
            $filterOptionsApplied[$key] = libraryRequest::fromGET($key, $filterOptions[$key]);
            if ($key == "filter_viewItemsOnPage" || $key == "filter_viewPageNum")
                $filterOptionsApplied[$key] = intval($filterOptionsApplied[$key]);
            if ($key === "filter_commonPriceMax" || $key == "filter_commonPriceMin")
                $filterOptionsApplied[$key] = floatval($filterOptionsApplied[$key]);
            if (is_string($filterOptionsApplied[$key])) {
                if ($key == "filter_commonStatus" || $key == "filter_categoryBrands")
                    $filterOptionsApplied[$key] = explode(',', $filterOptionsApplied[$key]);
                if ($key == "filter_categorySubCategories" || $key == "filter_commonFeatures")
                    $filterOptionsApplied[$key] = explode(',', $filterOptionsApplied[$key]);
            }
            // var_dump($filterOptionsApplied[$key]);
        }

        $dataConfigCategoryPriceEdges = configurationShopDataSource::jsapiShopCategoryPriceEdgesGet($categoryID);
        $dataConfigCategoryAllSubCategories = configurationShopDataSource::jsapiShopCategoryAllSubCategoriesGet($categoryID);

        // get category sub-categories and origins
        $dataCategoryPriceEdges = $this->getCustomer()->fetch($dataConfigCategoryPriceEdges);
        $dataCategoryAllSubCategories = $this->getCustomer()->fetch($dataConfigCategoryAllSubCategories);

        $cetagorySubIDs = array($categoryID);
        if (!empty($dataCategoryAllSubCategories))
            foreach ($dataCategoryAllSubCategories as $value)
                $cetagorySubIDs[] = $value['ID'];

        //filter: get category price edges
        $filterOptionsAvailable['filter_commonPriceMax'] = floatval($dataCategoryPriceEdges['PriceMax'] ?: 0);
        $filterOptionsAvailable['filter_commonPriceMin'] = floatval($dataCategoryPriceEdges['PriceMin'] ?: 0);

        // get all brands for both current category and sub-categories
        $dataConfigCategoryAllBrands = configurationShopDataSource::jsapiShopCategoryAndSubCategoriesAllBrandsGet(implode(',', $cetagorySubIDs));
        $dataCategoryAllBrands = $this->getCustomer()->fetch($dataConfigCategoryAllBrands);

        // set categories and brands
        $filterOptionsAvailable['filter_categoryBrands'] = $dataCategoryAllBrands ?: array();
        $filterOptionsAvailable['filter_categorySubCategories'] = $dataCategoryAllSubCategories ?: array();

        // set data source
        // ---
        $dataConfigCategoryInfo = configurationShopDataSource::jsapiGetShopCategoryProductInfo($cetagorySubIDs);
        $dataConfigProducts = configurationShopDataSource::jsapiGetShopCategoryProductList($cetagorySubIDs);

        // filter: display intems count
        if (!empty($filterOptionsApplied['filter_viewItemsOnPage']))
            $dataConfigProducts['limit'] = $filterOptionsApplied['filter_viewItemsOnPage'];
        else
            $filterOptionsApplied['filter_viewItemsOnPage'] = $dataConfigProducts['limit'];

        if (!empty($filterOptionsApplied['filter_viewPageNum']))
            $dataConfigProducts['offset'] = ($filterOptionsApplied['filter_viewPageNum'] - 1) * $dataConfigProducts['limit'];
        else
            $filterOptionsApplied['filter_viewPageNum'] = $filterOptionsAvailable['filter_viewPageNum'];

        // filter: items sorting
        $_filterSorting = explode('_', strtolower($filterOptionsApplied['filter_viewSortBy']));
        if (count($_filterSorting) === 2 && !empty($_filterSorting[0]) && ($_filterSorting[1] === 'asc' || $_filterSorting[1] === 'desc'))
            $dataConfigProducts['order'] = array('field' => $dataConfigProducts['source'] . '.' . ucfirst($_filterSorting[0]), 'ordering' => strtoupper($_filterSorting[1]));
        else
            $filterOptionsApplied['filter_viewSortBy'] = null;

        // filter: price 
        if ($filterOptionsApplied['filter_commonPriceMax'] > $filterOptionsApplied['filter_commonPriceMin'] && $filterOptionsApplied['filter_commonPriceMax'] <= $filterOptionsAvailable['filter_commonPriceMax'])
            $dataConfigProducts['condition']['Price'][] = configurationShopDataSource::jsapiCreateDataSourceCondition($filterOptionsApplied['filter_commonPriceMax'], '<=');
        else
            $filterOptionsApplied['filter_commonPriceMax'] = $filterOptionsAvailable['filter_commonPriceMax'];

        if ($filterOptionsApplied['filter_commonPriceMax'] > $filterOptionsApplied['filter_commonPriceMin'] && $filterOptionsApplied['filter_commonPriceMin'] >= $filterOptionsAvailable['filter_commonPriceMin'])
            $dataConfigProducts['condition']['Price'][] = configurationShopDataSource::jsapiCreateDataSourceCondition($filterOptionsApplied['filter_commonPriceMin'], '>=');
        else
            $filterOptionsApplied['filter_commonPriceMin'] = $filterOptionsAvailable['filter_commonPriceMin'];

        // var_dump($filterOptionsApplied);
        if (count($filterOptionsApplied['filter_commonFeatures']))
            $dataConfigProducts['condition']["FeatureID"] = configurationShopDataSource::jsapiCreateDataSourceCondition($filterOptionsApplied['filter_commonFeatures'], 'in');

        if (count($filterOptionsApplied['filter_commonStatus']))
            $dataConfigProducts['condition']["shop_products.Status"] = configurationShopDataSource::jsapiCreateDataSourceCondition($filterOptionsApplied['filter_commonStatus'], 'in');

        // filter: brands
        if (count($filterOptionsApplied['filter_categoryBrands']))
            $dataConfigProducts['condition']['OriginID'] = configurationShopDataSource::jsapiCreateDataSourceCondition($filterOptionsApplied['filter_categoryBrands'], 'in');

        // var_dump($dataConfigProducts);
        // get products
        $dataProducts = $this->getCustomer()->fetch($dataConfigProducts);
        // get category info according to product filter
        if (isset($dataConfigProducts['condition']['Price']))
            $dataConfigCategoryInfo['condition']['Price'] = $dataConfigProducts['condition']['Price'];
        // $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
        $dataCategoryInfo = $this->getCustomer()->fetch($dataConfigCategoryInfo);

        $products = array();
        if (!empty($dataProducts))
            foreach ($dataProducts as $val)
                $products[] = $this->_getProductByID($val['ID']);

        $productsInfo = array();
        if (!empty($dataCategoryInfo))
            foreach ($dataCategoryInfo as $val)
                $productsInfo[] = $this->_getProductByID($val['ID']);

        // adjust brands, categories and features
        $brands = array();
        $categories = array();
        $statuses = array();//$this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopProducts);
        $features = array();
        foreach ($filterOptionsAvailable['filter_categoryBrands'] as $brand) {
            $brands[$brand['ID']] = $brand;
            $brands[$brand['ID']]['ProductCount'] = 0;
        }
        foreach ($filterOptionsAvailable['filter_categorySubCategories'] as $category) {
            $categories[$category['ID']] = $category;
            $categories[$category['ID']]['ProductCount'] = 0;
        }
        foreach ($filterOptionsAvailable['filter_commonStatus'] as $status) {
            $statuses[$status]['ID'] = $status;
            $statuses[$status]['ProductCount'] = 0;
        }

        if ($productsInfo)
            foreach ($productsInfo as $obj) {
                $OriginID = $obj['OriginID'];
                $CategoryID = $obj['CategoryID'];
                $status = $obj['Status'];
                if (isset($statuses[$status]))
                    $statuses[$status]['ProductCount']++;
                if (isset($brands[$OriginID]))
                    $brands[$OriginID]['ProductCount']++;
                if (isset($categories[$CategoryID]))
                    $categories[$CategoryID]['ProductCount']++;
                foreach ($obj['Features'] as $featureKey => $feature) {
                    if (isset($features[$featureKey]))
                        $features[$featureKey]['ProductCount']++;
                    else {
                        $features[$featureKey]['Name'] = $feature;
                        $features[$featureKey]['ProductCount'] = 1;
                    }
                }
            }

        $filterOptionsAvailable['filter_categoryBrands'] = $brands;
        $filterOptionsAvailable['filter_categorySubCategories'] = $categories;
        $filterOptionsAvailable['filter_commonStatus'] = $statuses;
        $filterOptionsAvailable['filter_commonFeatures'] = $features;

        // store data
        $data['items'] = $products;
        $data['filter'] = array(
            'filterOptionsAvailable' => $filterOptionsAvailable,
            'filterOptionsApplied' => $filterOptionsApplied,
            'info' => array(
                "count" => count($dataCategoryInfo)
            )
        );
        // return data object
        return $data;
    }

    private function _getPromoByID ($id) {
        $config = configurationShopDataSource::jsapiShopGetPromoByID($id);
        return $this->getCustomer()->fetch($config);
    }

    private function _getPromoByHash ($hash, $activeOnly = false) {
        $config = configurationShopDataSource::jsapiShopGetPromoByHash($hash, $activeOnly);
        return $this->getCustomer()->fetch($config);
    }

    // ----------------------------------------
    // requests
    // ----------------------------------------

    public function get_shop_product (&$resp, $req) {
        $resp = $this->_getProductByID($req->get['id']);
    }

    public function get_shop_overview (&$resp) {
        $resp['products'] = $this->_getStats_ProductsOverview();
        $resp['orders'] = $this->_getStats_OrdersOverview();
        $resp['popular'] = $this->_getProducts_TopPopular();
        $resp['non_popular'] = $this->_getProducts_TopNonPopular();
    }

    public function get_shop_location (&$resp, $req) {
        if (!isset($req->get['productID']) && !isset($req->get['categoryID'])) {
            $resp['error'] = 'The request must contain at least one of parameters: "productID" or "categoryID"';
            return;
        }
        $resp['location'] = $this->_getCatalogLocation(libraryRequest::fromGET('productID'), libraryRequest::fromGET('categoryID'));
    }

    public function get_shop_products (&$resp, $req) {
        if (!empty($req->get['status'])) {
            $resp = $this->_getProducts_ByStatus($req['status']);
            return;
        }
        if (!empty($req->get['type'])) {
            switch ($req->get['type']) {
                case "latest":
                    $resp["items"] = $this->_getProducts_Latest();
                    break;
                case "popular":
                    $resp["items"] = $this->_getProducts_TopPopular();
                    break;
                case "non_popular":
                    $resp["items"] = $this->_getProducts_TopNonPopular();
                    break;
                case "sale":
                    $resp["items"] = $this->_getProducts_Sale();
                    break;
                case "uncompleted":
                    $resp["items"] = $this->_getProducts_Uncompleted();
                    break;
            }
            return;
        }

        $resp['error'] = '"type" or "status" is missed in the request';
    }

    public function get_shop_catalog (&$resp, $req) {
        if (!empty($req->get['type'])) {
            switch ($req->get['type']) {
                case "tree":
                    $resp['tree'] = $this->_getCatalogTree();
                    break;
                case "browse":
                    $resp['browse'] = $this->_getCatalogBrowse();
                    break;
            }
            return;
        }

        $resp['error'] = "MissedParameter_type";
    }

    public function get_shop_wish (&$resp) {
        $resp['items'] = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
    }

    public function post_shop_wish (&$resp, $req) { 
        $resp['items'] = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        if (isset($req->data['productID'])) {
            $productID = $req->data['productID'];
            if (!isset($resp['items'][$productID])) {
                $product = $this->_getProductByID($productID);
                $resp['items'][$productID] = $product;
                $_SESSION[$this->_listKey_Wish] = $resp['items'];
            }
        }
        // $resp['req'] = $req;
    }

    public function delete_shop_wish (&$resp, $req) {
        $resp['items'] = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        if (isset($req->get['productID'])) {
            $productID = $req->get['productID'];
            if ($productID === "*") {
                $resp['items'] = array();
            } elseif (isset($resp['items'][$productID])) {
                unset($resp['items'][$productID]);
            }
            $_SESSION[$this->_listKey_Wish] = $resp['items'];
        }
    }

    private function __productIsInWishList ($id) {
        $list = array();
        $this->get_shop_wish($list);
        return isset($list['items'][$id]);
    }

    public function get_shop_compare (&$resp) {
        $resp['items'] = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        $resp['limit'] = 10;
    }

    public function post_shop_compare (&$resp, $req) {
        $resp['items'] = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        if (isset($req->data['productID'])) {
            $productID = $req->data['productID'];
            if (!isset($resp['items'][$productID])) {
                $product = $this->_getProductByID($productID);
                $resp['items'][$productID] = $product;
                $_SESSION[$this->_listKey_Compare] = $resp['items'];
            }
        }
    }

    public function delete_shop_compare (&$resp, $req) {
        $resp['items'] = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        if (isset($req->get['productID'])) {
            $productID = $req->get['productID'];
            if ($productID === "*") {
                $resp['items'] = array();
            } elseif (isset($resp['items'][$productID])) {
                unset($resp['items'][$productID]);
            }
            $_SESSION[$this->_listKey_Compare] = $resp['items'];
        }
    }

    private function __productIsInCompareList ($id) {
        $list = array();
        $this->get_shop_compare($list);
        return isset($list['items'][$id]);
    }













    public function get_shop_orders (&$resp, $req) {
        if (!empty($req->get['status'])) {
            $resp = $this->_getOrders_ByStatus($req->get['status']);
            return;
        }
        if (!empty($req->get['type'])) {
            switch ($req->get['type']) {
                case "expired":
                    $resp = $this->_getOrders_Expired();
                    break;
                case "todays":
                    $resp = $this->_getOrders_Todays();
                    break;
                case "browse":
                    $resp = $this->_getOrders_Browse($req);
                    break;
            }
            return;
        }

        $resp['error'] = '"type" or "status" is missed in the request';
    }

    public function get_shop_order (&$resp, $req) {
        if (isset($req->get['id']) && $req->get['id'] !== "temp") {
            $resp = $this->_getOrderByID($req->get['id']);
            return;
        } else if (isset($req->get['hash'])) {
            $resp = $this->_getOrderByHash($req->get['hash']);
            return;
        } else {
            $resp = $this->_getOrderTemp(array("useBackup" => true));
        }
        // $resp['error'] = '"id" or "hash" is missed in the request';
    }

    // create new product in the shopping cart list
    public function post_shop_order (&$resp, $req) {
        // if (isset($req['productID'])) {
        //     $items = isset($_SESSION[$this->_listKey_Cart]) ? $_SESSION[$this->_listKey_Cart] : array();
        //     $productID = $req['productID'];
        //     if (!isset($items[$productID])) {
        //         $product = $this->_getProductByID($productID);
        //         $product['_orderQuantity'] = 1;
        //         $product["Total"] = $product['Price'];
        //         $items[$productID] = $product;
        //     }
        //     $_SESSION[$this->_listKey_Cart] = $items;
        // }
        // $this->get_shop_cart($resp, $req);
        $resp = $this->_createOrder($req->data);
        // var_dump($req->data);
    }

    // modify existed product quantity in the shopping cart list
    public function patch_shop_order (&$resp, $req) {
        // var_dump($req);
        // var_dump($_SERVER['REQUEST_METHOD']);
        // var_dump($_POST);
        // var_dump(file_get_contents('php://input'));
        $options = array();
        if (isset($req->data['productID'])) {
            $order = isset($_SESSION[$this->_listKey_Cart]) ? $_SESSION[$this->_listKey_Cart] : array();
            $items = empty($order['items']) ? array() : $order['items'];
            $productID = $req->data['productID'];
            $newQuantity = floatval($req->data['_orderQuantity']);
            if (isset($items[$productID])) {
                $items[$productID]['_orderQuantity'] = $newQuantity;
                if ($items[$productID]['_orderQuantity'] <= 0)
                    unset($items[$productID]);
            } elseif ($newQuantity > 0) {
                $product = $this->_getProductByID($productID);
                $product['_orderQuantity'] = $newQuantity;
                $items[$productID] = $product;
            } elseif ($req->data['productID'] === "*") {
                $items = array();
            }
            $order['items'] = $items;
            $_SESSION[$this->_listKey_Cart] = $order;
        } elseif (!empty($req->data['promo'])) {
            $_SESSION[$this->_listKey_Promo] = $this->_getPromoByHash($req->data['promo'], true);
            // if ($req->data['promo'] === false)
            //     $options['promo'] = array();
            // else
            //     $options['promo'] = $this->_getPromoByHash($req->data['promo'], true);
        }
        // else {
        //     $options['useBackup'] = true;
        // }
        $resp = $this->_getOrderTemp($options);
    }

    // removes particular product or clears whole shopping cart
    // public function delete_shop_cart (&$resp, $req) {
    //     if (isset($req['productID'])) {
    //         $items = isset($_SESSION[$this->_listKey_Cart]) ? $_SESSION[$this->_listKey_Cart] : array();
    //         $productID = $req['productID'];
    //         if ($productID === "*") {
    //             $items = array();
    //         } elseif (isset($items[$productID])) {
    //             unset($items[$productID]);
    //         }
    //         $_SESSION[$this->_listKey_Cart] = $items;
    //     }
    //     $this->_getOrderTemp($resp, $req);
    // }

    private function __productCountInCart ($id) {
        $order = $this->_getOrderTemp();
        return isset($order['items'][$id]) ? $order['items'][$id]['_orderQuantity'] : 0;
    }























































































    public function getResponse () {
        $data = new libraryDataObject();

        switch(libraryRequest::fromGET('fn')) {
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



    // shopping products compare
    // -----------------------------------------------
    // private function _api_getCompareList () {
    //     $do = libraryRequest::fromGET('action');
    //     $productID = libraryRequest::fromGET('productID');
    //     $dataObj = $this->_custom_util_manageStoredProducts('shopProductsCompare');

    //     $products = $dataObj->getData();

    //     if ($do == 'ADD' && count($products['products']) > 10) {
    //         unset($products['products'][$productID]);
    //         $dataObj->setError("MaxProductsAdded");
    //         $dataObj->setData('products', $products);
    //     }

    //     return $dataObj;
    // }

    // shopping cart
    // -----------------------------------------------
    private function www_api_getShoppingCart () {

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
            if (empty($productData['products'][$productID]['_orderQuantity']))
                $productData['products'][$productID]['_orderQuantity'] = 0;

            $productData['products'][$productID]['_orderQuantity'] += $productQuantity;

            // we keep product until REMOVE action is invoked
            if ($productData['products'][$productID]['_orderQuantity'] <= 0)
                $productData['products'][$productID]['_orderQuantity'] = 1;

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
                // _orderQuantity
                foreach ($productData['products'] as $_item) {
                    $configProduct = configurationShopDataSource::jsapiShopOrderProductsCreate();
                    $dataProduct = array();
                    $dataProduct["ProductID"] = $_item["ID"];
                    $dataProduct["OrderID"] = $orderID;
                    $dataProduct["ProductPrice"] = $_item["ProductPrice"];
                    $dataProduct["_orderQuantity"] = $_item["_orderQuantity"];
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

        $dataObj->setData('info', $this->___calcOrderBoughts($productData['products']));
        $dataObj->setData('products', $productData['products']);
        $_SESSION[$sessionKey] = $productData['products'];

        return $dataObj;
    }

    // orders
    // -----------------------------------------------
    private function www_api_addOrder () {

    }

    // profile orders
    // -----------------------------------------------
    private function www_api_getOrderList_ForProfile ($profileID) {

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
    private function www_api_getOrderStatusList () {
        return array("NEW", "ACTIVE", "LOGISTIC_DELIVERING", "LOGISTIC_DELIVERED", "SHOP_CLOSED");
    }


    private function www_api_getOrderList () {
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

    private function www_api_updateOrder ($orderID) {
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

    private function www_api_getOriginStates () {
        $dataObj = new libraryDataObject();
        $dataObj->setData('statuses', $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopOrigins));
        return $dataObj;
    }

    private function www_api_getOrigin ($originID) {
        $dataObj = new libraryDataObject();
        if (!empty($originID)) {
            $configOrigin = configurationShopDataSource::jsapiShopOriginGet($originID);
            $dataOrigin = $this->getCustomer()->fetch($configOrigin);
            $dataObj->setData('origin', $dataOrigin);
        }
        return $dataObj;
    }

    private function www_api_getOriginList () {
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

    private function www_api_createOrigin () {
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

    private function www_api_updateOrigin ($originID) {
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

    private function www_custom_util_manageStoredProducts ($sessionKey, $userActions = array(), $action = null) {
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
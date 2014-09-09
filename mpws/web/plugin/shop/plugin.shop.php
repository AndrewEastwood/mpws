<?php

class pluginShop extends objectPlugin {

    private $_listKey_Wish = 'shop:wishList';
    private $_listKey_Recent = 'shop:listRecent';
    private $_listKey_Compare = 'shop:listCompare';
    private $_listKey_Cart = 'shop:cart';
    private $_listKey_Promo = 'shop:promo';

    public function beforeRun () {
        $this->_getTableStatuses();
    }

    private function _getTableStatuses ($tableName = null, $force = false) {
        $statusDump = array ();
        $mkTimeDiff = 0;
        if (isset($_SESSION['shop:statuses:last_update'])) {
            $mkTimeDiff = mktime() - intval($_SESSION['shop:statuses:last_update']);
        }
        // daily update
        if ($force || $mkTimeDiff > 24 * 60 * 60 || empty($_SESSION['shop:statuses:last_update'])) {
            $statusDump[configurationShopDataSource::$Table_ShopOrders] = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopOrders);
            $statusDump[configurationShopDataSource::$Table_ShopProducts] = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopProducts);
            $statusDump[configurationShopDataSource::$Table_ShopOrigins] = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopOrigins);
            $statusDump[configurationShopDataSource::$Table_ShopCategories] = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopCategories);
            $statusDump[configurationShopDataSource::$Table_ShopProductAttr] = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopProductAttr);
            $statusDump[configurationShopDataSource::$Table_ShopDeliveryAgency] = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopDeliveryAgency);
            // $statusDump[configurationShopDataSource::$Table_ShopSettings] = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopSettings);
            $_SESSION['shop:statuses:list'] = $statusDump;
            $_SESSION['shop:statuses:last_update'] = mktime();
        } else {
            $statusDump = $_SESSION['shop:statuses:list'];
        }
        if (!empty($tableName)) {
            if (isset($statusDump[$tableName])) {
                return $statusDump[$tableName];
            } else {
                throw new Exception("Unknown table name for status list dump", 1);
            }
        }
        // var_dump($statusDump);
        // var_dump($mkTimeDiff);
        // var_dump($_SESSION['shop:statuses:last_update']);
    }

    // product standalone item (short or full)
    // -----------------------------------------------
    public function getProductByID ($productID, $saveIntoRecent = false, $skipRelations = false) {
        if (empty($productID) || !is_numeric($productID))
            return null;

        $config = configurationShopDataSource::jsapiShopGetProductItem($productID);
        $product = $this->getCustomer()->fetch($config);

        if (empty($product))
            return null;

        $configProductsAttr = configurationShopDataSource::jsapiShopGetProductAttributes($productID);
        $configProductsPrice = configurationShopDataSource::jsapiShopGetProductPriceStats($productID);
        $configProductsFeatures = configurationShopDataSource::jsapiShopGetProductFeatures($productID);
        $configProductsRelations = configurationShopDataSource::jsapiShopGetProductRelations($productID);

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
                    $relatedProduct = $this->getProductByID($relatedProductID, $saveIntoRecent, true);
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
        $promo = $this->_getSessionPromo();
        $product['_promoIsApplied'] = false;
        if ($product['IsPromo'] && !empty($promo) && !empty($promo['Discount'])&& $promo['Discount'] > 0) {
            $product['_promoIsApplied'] = true;
            $product['DiscountPrice'] = (100 - intval($promo['Discount'])) / 100 * $product['Price'];
            $product['promo'] = $promo;
        }

        $product['SellingPrice'] = isset($product['DiscountPrice']) ? $product['DiscountPrice'] : $product['Price'];
        $product['SellingPrice'] = floatval($product['SellingPrice']);

        // is available
        $product['_available'] = in_array($product['Status'], array("ACTIVE", "DISCOUNT", "PREORDER", "DEFECT"));

        $product['_statuses'] = $this->_getTableStatuses(configurationShopDataSource::$Table_ShopProducts);
        // save product into recently viewed list
        if ($saveIntoRecent && !glIsToolbox()) {
            $recentProducts = isset($_SESSION[$this->_listKey_Recent]) ? $_SESSION[$this->_listKey_Recent] : array();
            $recentProducts[$productID] = $product;
            $_SESSION[$this->_listKey_Recent] = $recentProducts;
        }
        return $product;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // PRODUCTS LIST SORTED BY DATE ADDED
    // -----------------------------------------------
    // -----------------------------------------------
    public function getProducts_TopNonPopular () {
        // get non-popuplar 15 products
        $config = configurationShopDataSource::jsapiShopStat_NonPopularProducts();
        $productIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($productIDs)) {
            foreach ($productIDs as $val) {
                $data[] = $this->getProductByID($val['ID']);
            }
        }
        return $data;
    }

    public function getProducts_TopPopular () {
        // get top 15 products
        $config = configurationShopDataSource::jsapiShopStat_PopularProducts();
        $productIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($productIDs)) {
            foreach ($productIDs as $val) {
                $product = $this->getProductByID($val['ProductID']);
                $product['SoldTotal'] = floatval($val['SoldTotal']);
                $product['SumTotal'] = floatval($val['SumTotal']);
                $data[] = $product;
            }
        }
        return $data;
    }

    public function getProducts_List ($req) {
        $config = configurationShopDataSource::jsapiShopGetProductList();
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $orderRawItem) {
                    $_items[] = $self->getProductByID($orderRawItem['ID']);
                }
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $req, $callbacks);

        if (isset($req->get['_pStats']))
            $dataList['stats'] = $this->getStats_ProductsOverview();

        return $dataList;
    }

    public function createProduct ($reqData) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            return glWrap("AccessDenied");
        }

    }

    public function updateProduct ($reqData) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            return glWrap("AccessDenied");
        }

    }

    // -----------------------------------------------
    // -----------------------------------------------
    // ORIGINS
    // -----------------------------------------------
    // -----------------------------------------------

    public function getOriginByID ($originID) {
        if (empty($originID) || !is_numeric($originID))
            return null;

        $config = configurationShopDataSource::jsapiShopGetOriginItem($originID);
        $origin = $this->getCustomer()->fetch($config);

        if (empty($origin))
            return null;

        $origin['ID'] = intval($origin['ID']);
        $origin['_statuses'] = $this->_getTableStatuses(configurationShopDataSource::$Table_ShopOrigins);
        return $origin;
    }

    public function getOrigins_List ($req) {
        $config = configurationShopDataSource::jsapiShopGetOriginList();

        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getOriginByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $req, $callbacks);
        return $dataList;
    }

    public function createOrigin ($reqData) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            return glWrap("AccessDenied");
        }

        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 100),
            'Description' => array('int', 'skipIfUnset'),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $this->getCustomerDataBase()->beginTransaction();

                $validatedValues["CustomerID"] = $this->getCustomer()->getCustomerID();

                $configCreateCategory = configurationShopDataSource::jsapiShopCreateOrigin($validatedValues);
                $OriginID = $this->getCustomer()->fetch($configCreateCategory) ?: null;

                if (empty($OriginID))
                    throw new Exception('OriginCreateError');

                $this->getCustomerDataBase()->commit();

                $result = $this->getOriginByID($OriginID);

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateOrigin ($OriginID, $reqData) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            return glWrap("AccessDenied");
        }

        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'Name' => array('string', 'skipIfUnset', 'min' => 1, 'max' => 100),
            'Description' => array('int', 'skipIfUnset'),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $this->getCustomerDataBase()->beginTransaction();

                $configCreateCategory = configurationShopDataSource::jsapiShopUpdateOrigin($OriginID, $validatedValues);
                $this->getCustomer()->fetch($configCreateCategory) ?: null;

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getOriginByID($OriginID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function disableOrigin ($OriginID) {
        // check permissions
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            return glWrap("error", "AccessDenied");
        }

        $errors = array();
        $success = false;

        try {

            $this->getCustomerDataBase()->beginTransaction();

            $config = configurationShopDataSource::jsapiShopDeleteOrigin($OriginID);
            $this->getCustomer()->fetch($config);

            $this->getCustomerDataBase()->commit();

            $success = true;
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
            $errors[] = 'OriginUpdateError';
        }

        $result = $this->getCategoryByID($OriginID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // CATEGORIES
    // -----------------------------------------------
    // -----------------------------------------------
    public function getCategoryByID ($categoryID) {
        if (empty($categoryID) || !is_numeric($categoryID))
            return null;

        $config = configurationShopDataSource::jsapiShopGetCategoryItem($categoryID);
        $category = $this->getCustomer()->fetch($config);

        if (empty($category))
            return null;

        $category['ID'] = intval($category['ID']);
        // $category['RootID'] = is_null($category['RootID']) ? null : intval($category['RootID']);
        $category['ParentID'] = is_null($category['ParentID']) ? null : intval($category['ParentID']);
        // $category['CustomerID'] = intval($category['CustomerID']);
        $category['_statuses'] = $this->_getTableStatuses(configurationShopDataSource::$Table_ShopCategories);

        return $category;
    }

    public function getCategories_Tree ($req) {
        $withRemoved = false;
        if (isset($req->get['removed']))
            $withRemoved = $req->get['removed'];
        $config = configurationShopDataSource::jsapiShopGetCategoryTree($withRemoved);
        $categoryIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($categoryIDs)) {
            foreach ($categoryIDs as $val) {
                $data[] = $this->getCategoryByID($val['ID']);
            }
        }
        return $data;
    }

    public function getCategories_List ($req) {
        $config = configurationShopDataSource::jsapiShopGetCategoryList();

        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getCategoryByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $req, $callbacks);
        return $dataList;
    }

    public function createCategory ($reqData) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            return glWrap("AccessDenied");
        }

        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 100),
            'ParentID' => array('int', 'skipIfUnset'),
            'Description' => array('string', 'skipIfUnset', 'max' => 300)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $this->getCustomerDataBase()->beginTransaction();

                $validatedValues["CustomerID"] = $this->getCustomer()->getCustomerID();

                $configCreateCategory = configurationShopDataSource::jsapiShopCreateCategory($validatedValues);
                $CategoryID = $this->getCustomer()->fetch($configCreateCategory) ?: null;

                if (empty($CategoryID))
                    throw new Exception('CategoryCreateError');

                $this->getCustomerDataBase()->commit();

                $result = $this->getCategoryByID($CategoryID);

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateCategory ($CategoryID, $reqData) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            return glWrap("AccessDenied");
        }

        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'Name' => array('string', 'skipIfUnset', 'min' => 1, 'max' => 100),
            'ParentID' => array('int', 'null', 'skipIfUnset'),
            'Description' => array('string', 'skipIfUnset', 'max' => 300),
            'Status' => array('string', 'skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];


                $this->getCustomerDataBase()->beginTransaction();

                $configCreateCategory = configurationShopDataSource::jsapiShopUpdateCategory($CategoryID, $validatedValues);
                $this->getCustomer()->fetch($configCreateCategory) ?: null;

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getCategoryByID($CategoryID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function disableCategory ($CategoryID) {
        // check permissions
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            return glWrap("error", "AccessDenied");
        }

        $errors = array();
        $success = false;

        try {

            $this->getCustomerDataBase()->beginTransaction();

            $config = configurationShopDataSource::jsapiShopDeleteCategory($CategoryID);
            $this->getCustomer()->fetch($config);

            $this->getCustomerDataBase()->commit();

            $success = true;
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
            $errors[] = 'CategoryUpdateError';
        }

        $result = $this->getCategoryByID($CategoryID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // ORDERS
    // -----------------------------------------------
    // -----------------------------------------------
    public function getOrderByID ($orderID) {
        $config = configurationShopDataSource::jsapiShopGetOrderItem($orderID);
        $order = null;
        // if ($this->getCustomer()->ifYouCan('Admin')) {
            $order = $this->getCustomer()->fetch($config);
        // } else {
        //     $config['condition']['AccountID'] = configurationShopDataSource::jsapiCreateDataSourceCondition($this->getCustomer()->getAuthID());
        //     $order = $this->getCustomer()->fetch($config);
        // }

        if (empty($order)) {
            return glWrap('error', 'OrderDoesNotExist');
        }

        $order['ID'] = intval($order['ID']);
        $this->__attachOrderDetails($order);

        if ($this->getCustomer()->ifYouCan('Admin')) {
            $order['_statuses'] = $this->_getTableStatuses(configurationShopDataSource::$Table_ShopOrders);
        }
        return $order;
    }

    public function getOrderByHash ($orderHash) {
        $config = configurationShopDataSource::jsapiGetShopOrderByHash($orderHash);
        $order = $this->getCustomer()->fetch($config);

        if (empty($order)) {
            return glWrap('error', 'OrderDoesNotExist');
        }

        $order['ID'] = intval($order['ID']);
        $this->__attachOrderDetails($order);
        return $order;
    }

    private function _getOrderTemp () {
        $order['temp'] = true;
        $this->__attachOrderDetails($order);
        return $order;
    }

    private function _resetOrderTemp () {
        $this->_resetSessionPromo();
        $this->_resetSessionOrderProducts();
    }

    public function getOrders_ListExpired ($req) {
        // get expired orders
        $config = configurationShopDataSource::jsapiGetShopOrderList_Expired();
        // check permissions
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $config['condition']['AccountID'] = configurationShopDataSource::jsapiCreateDataSourceCondition($this->getCustomer()->getAuthID());
        }
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $orderRawItem)
                    $_items[] = $self->getOrderByID($orderRawItem['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $req, $callbacks);
        return $dataList;
    }

    public function getOrders_ListTodays ($req) {
        // get todays orders
        $config = configurationShopDataSource::jsapiGetShopOrderList_Todays();
        // set permissions
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $config['condition']['AccountID'] = configurationShopDataSource::jsapiCreateDataSourceCondition($this->getCustomer()->getAuthID());
        }
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $orderRawItem)
                    $_items[] = $self->getOrderByID($orderRawItem['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $req, $callbacks);
        return $dataList;
    }

    public function getOrders_ListPending ($req) {
        // get expired orders
        $config = configurationShopDataSource::jsapiGetShopOrderList_Pending();
        // check permissions
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $config['condition']['AccountID'] = configurationShopDataSource::jsapiCreateDataSourceCondition($this->getCustomer()->getAuthID());
        }
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $orderRawItem)
                    $_items[] = $self->getOrderByID($orderRawItem['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $req, $callbacks);
        return $dataList;
    }

    public function getOrders_List ($req) {
        // get all orders
        $config = configurationShopDataSource::jsapiGetShopOrderList();
        // check permissions
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $config['condition']['AccountID'] = configurationShopDataSource::jsapiCreateDataSourceCondition($this->getCustomer()->getAuthID());
        }
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $orderRawItem) {
                    $_items[] = $self->getOrderByID($orderRawItem['ID']);
                }
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $req, $callbacks);

        if (isset($req->get['_pStats']))
            $dataList['stats'] = $this->getStats_OrdersOverview();

        return $dataList;
    }

    public function createOrder ($reqData) {

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

            $order = $this->_getOrderTemp();
            // $sessionOrderProducts = $this->_getSessionOrderProducts();

            // var_dump($this->_getSessionOrderProducts());
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

            $configOrder = configurationShopDataSource::jsapiShopCreateOrder($dataOrder);
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
                $dataBought["Price"] = $productItem["Price"];
                $dataBought["SellingPrice"] = $productItem["SellingPrice"];
                $dataBought["Quantity"] = $productItem["_orderQuantity"];
                $dataBought["IsPromo"] = $productItem["IsPromo"];
                $configBought = configurationShopDataSource::jsapiShopCreateOrderBought($dataBought);
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
        } catch (Exception $e) {
            $this->getCustomerDataBase()->enableTransactions();
            $this->getCustomerDataBase()->rollBack();
            $errors['Order'][] = $e->getMessage();
            $success = false;
        }

        if ($success) {
            // reset temp order
            $this->_resetOrderTemp();
            // get created order

            $result = $this->getOrderByID($orderID);
        }

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateOrder ($OrderID, $reqData) {
        // check permissions
        if (!$this->getCustomer()->ifYouCan('Edit')) {
            return glWrap("error", "AccessDenied");
        }

        $errors = array();
        $success = false;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'Status' => array('skipIfUnset', 'string', 'notEmpty'),
            'InternalComment' => array('skipIfUnset', 'string')
        ));

        // var_dump($validatedDataObj);

        // return;
        if ($validatedDataObj['count'] > 0) {
            if ($validatedDataObj["totalErrors"] == 0)
                try {

                    $this->getCustomerDataBase()->beginTransaction();

                    $validatedValues = $validatedDataObj['values'];

                    $configUpdateOrder = configurationShopDataSource::jsapiShopUpdateOrder($OrderID, $validatedValues);

                    $this->getCustomer()->fetch($configUpdateOrder, true);

                    $this->getCustomerDataBase()->commit();

                    $success = true;
                } catch (Exception $e) {
                    $this->getCustomerDataBase()->rollBack();
                    $errors[] = 'OrderUpdateError';
                }
            else
                $errors = $validatedDataObj["errors"];
        }

        $result = $this->getOrderByID($OrderID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function disableOrderByID ($OrderID) {
        // check permissions
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            return glWrap("error", "AccessDenied");
        }

        $errors = array();
        $success = false;

        try {

            $this->getCustomerDataBase()->beginTransaction();

            $config = configurationShopDataSource::jsapiShopDisableOrder($OrderID);
            $this->getCustomer()->fetch($config);

            $this->getCustomerDataBase()->commit();

            $success = true;
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
            $errors[] = 'OrderUpdateError';
        }

        $result = $this->getOrderByID($OrderID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // STATISTIC
    // -----------------------------------------------
    // -----------------------------------------------
    public function getStats_OrdersOverview () {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        // get orders count for each states
        $config = configurationShopDataSource::jsapiShopStat_OrdersOverview();
        $data = $this->getCustomer()->fetch($config) ?: array();
        $total = 0;
        $res = array();
        $availableStatuses = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopOrders);
        foreach ($availableStatuses as $key) {
            if (isset($data[$key])) {
                $res[$key] = intval($data[$key]);
                $total += $data[$key];
            } else
                $res[$key] = 0;
        }
        // get total
        $res['TOTAL'] = $total;
        return $res;
    }

    public function getStats_OrdersIntensityLastMonth ($status, $comparator = null) {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        $config = configurationShopDataSource::jsapiShopStat_OrdersIntensityLastMonth($status, $comparator);
        $data = $this->getCustomer()->fetch($config) ?: array();
        // var_dump($config);
        return $data;
    }

    public function getStats_ProductsOverview () {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        // get shop products overview:
        $config = configurationShopDataSource::jsapiShopStat_ProductsOverview();
        $data = $this->getCustomer()->fetch($config) ?: array();
        $total = 0;
        $res = array();
        $availableStatuses = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopProducts);
        foreach ($availableStatuses as $key) {
            if (isset($data[$key])) {
                $res[$key] = intval($data[$key]);
                $total += $data[$key];
            } else
                $res[$key] = 0;
        }
        // get total
        $res['TOTAL'] = $total;
        return $res;
    }

    public function getStats_ProductsIntensityLastMonth ($status) {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        $config = configurationShopDataSource::jsapiShopStat_ProductsIntensityLastMonth($status);
        $data = $this->getCustomer()->fetch($config) ?: array();
        // var_dump($config);
        return $data;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // BREADCRUMB
    // -----------------------------------------------
    // -----------------------------------------------
    public function _getCatalogLocation ($productID = null, $categoryID = null) {
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

    // -----------------------------------------------
    // -----------------------------------------------
    // SHOP CATALOG TREE
    // -----------------------------------------------
    // -----------------------------------------------
    public function _getCatalogTree () {

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

    public function _getCatalogBrowse () {

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
                $products[] = $this->getProductByID($val['ID']);

        $productsInfo = array();
        if (!empty($dataCategoryInfo))
            foreach ($dataCategoryInfo as $val)
                $productsInfo[] = $this->getProductByID($val['ID']);

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

    // -----------------------------------------------
    // -----------------------------------------------
    // PROMO
    // -----------------------------------------------
    // -----------------------------------------------
    public function getPromoByID ($promoID) {
        $config = configurationShopDataSource::jsapiShopGetPromoByID($promoID);
        $data = $this->getCustomer()->fetch($config);
        $data['ID'] = intval($data['ID']);
        $data['Discount'] = floatval($data['Discount']);
        return $data;
    }

    public function getPromoByHash ($hash, $activeOnly = false) {
        $config = configurationShopDataSource::jsapiShopGetPromoByHash($hash, $activeOnly);
        $data = $this->getCustomer()->fetch($config);
        $data['ID'] = intval($data['ID']);
        $data['Discount'] = floatval($data['Discount']);
        return $data;
    }

    public function _createPromo ($reqData) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            return glWrap("AccessDenied");
        }
    }

    public function _updatePromo ($reqData) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            return glWrap("AccessDenied");
        }
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // SESSION DATA
    // -----------------------------------------------
    // -----------------------------------------------
    private function _setSessionPromo ($promo) {
        $_SESSION[$this->_listKey_Promo] = $promo;
    }

    private function _getSessionPromo () {
        if (!isset($_SESSION[$this->_listKey_Promo]))
            $_SESSION[$this->_listKey_Promo] = null;
        return $_SESSION[$this->_listKey_Promo];
    }

    private function _resetSessionPromo () {
        $_SESSION[$this->_listKey_Promo] = null;
    }

    private function _setSessionOrderProducts ($order) {
        $_SESSION[$this->_listKey_Cart] = $order;
    }

    private function _getSessionOrderProducts () {
        if (!isset($_SESSION[$this->_listKey_Cart]))
            $_SESSION[$this->_listKey_Cart] = array();
        return $_SESSION[$this->_listKey_Cart];
    }

    private function _resetSessionOrderProducts () {
        $_SESSION[$this->_listKey_Cart] = array();
    }


    // -----------------------------------------------
    // -----------------------------------------------
    // REQUESTS
    // -----------------------------------------------
    // -----------------------------------------------

    public function get_shop_product (&$resp, $req) {
        if (!empty($req->get['id'])) {
            $ProductID = intval($req->get['id']);
            $resp = $this->getProductByID($ProductID);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    public function get_shop_stats (&$resp, $req) {

        $self = $this;
        $sources = array();
        // $sources['orders_new'] = function ($req) use ($self) {
        //     return $self->getOrders_ListPending($req);
        // };
        $sources['orders_list_pending'] = function ($req) use ($self) {
            return $self->getOrders_ListPending($req);
        };
        $sources['orders_list_todays'] = function ($req) use ($self) {
            return $self->getOrders_ListTodays($req);
        };
        $sources['orders_list_expired'] = function ($req) use ($self) {
            return $self->getOrders_ListExpired($req);
        };
        $sources['orders_intensity_last_month'] = function ($req) use ($self) {
            $res = array();
            $res['OPEN'] = $self->getStats_OrdersIntensityLastMonth('SHOP_CLOSED', '!=');
            $res['CLOSED'] = $self->getStats_OrdersIntensityLastMonth('SHOP_CLOSED');
            return $res;
        };
        $sources['overview_orders'] = function ($req) use ($self) {
            return $self->getStats_OrdersOverview();
        };
        $sources['overview_products'] = function ($req) use ($self) {
            return $self->getStats_ProductsOverview();
        };
        // $sources['products_list_todays'] = function ($req) use ($self) {
        //     $res = array();
        //     $res['items'] = $self->getProducts_ListTodays($req);
        //     return $res;
        // };
        $sources['products_list_popular'] = function ($req) use ($self) {
            $res = array();
            $res['items'] = $self->getProducts_TopPopular($req);
            return $res;
        };
        $sources['products_list_non_popular'] = function ($req) use ($self) {
            $res = array();
            $res['items'] = $self->getProducts_TopNonPopular($req);
            return $res;
        };
        $sources['products_intensity_last_month'] = function ($req) use ($self) {
            $res = array();
            $res['ACTIVE'] = $self->getStats_ProductsIntensityLastMonth('ACTIVE');
            $res['PREORDER'] = $self->getStats_ProductsIntensityLastMonth('PREORDER');
            $res['DISCOUNT'] = $self->getStats_ProductsIntensityLastMonth('DISCOUNT');
            return $res;
        };

        $type = false;
        if (!empty($req->get['type']))
            $type = $req->get['type'];

        if (isset($sources[$type]))
            $resp = $sources[$type]($req);
        else
            $resp['error'] = 'WrongType';
    }

    public function get_shop_location (&$resp, $req) {
        if (!isset($req->get['productID']) && !isset($req->get['categoryID'])) {
            $resp['error'] = 'The request must contain at least one of parameters: "productID" or "categoryID"';
            return;
        }
        $resp['location'] = $this->_getCatalogLocation(libraryRequest::fromGET('productID'), libraryRequest::fromGET('categoryID'));
    }

    public function get_shop_products (&$resp, $req) {
        $resp = $this->getProducts_List($req);
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








    public function get_shop_categories (&$resp, $req) {
        if (!empty($req->get['type'])) {
            switch ($req->get['type']) {
                case "tree":
                    $resp["items"] = $this->getCategories_Tree($req);
                    break;
                case "list":
                    $resp["items"] = $this->getCategories_List();
                    break;
            }
            return;
        }

        $resp['error'] = "MissedParameter_type";
    }

    public function get_shop_category (&$resp, $req) {
        if (!empty($req->get['id'])) {
            $CategoryID = intval($req->get['id']);
            $resp = $this->getCategoryByID($CategoryID);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    public function post_shop_category (&$resp, $req) {
        $resp = $this->createCategory($req->data);
    }

    public function patch_shop_category (&$resp, $req) {
        if (!empty($req->get['id'])) {
            $CategoryID = intval($req->get['id']);
            $resp = $this->updateCategory($CategoryID, $req->data);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    public function delete_shop_category (&$resp, $req) {
        if (!glIsToolbox()) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        if (!empty($req->get['id'])) {
            $CategoryID = intval($req->get['id']);
            $resp = $this->disableCategory($CategoryID);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }





    public function get_shop_origins (&$resp, $req) {
        if (!empty($req->get['type'])) {
            switch ($req->get['type']) {
                case "all":
                    $resp = $this->getOrigins_All($req);
                    break;
                case "list":
                    $resp = $this->getOrigins_List($req);
                    break;
            }
            return;
        }

        $resp['error'] = "MissedParameter_type";
    }

    public function get_shop_origin (&$resp, $req) {
        if (!empty($req->get['id'])) {
            $OriginID = intval($req->get['id']);
            $resp = $this->getOriginByID($OriginID);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    public function post_shop_origin (&$resp, $req) {
        $resp = $this->createOrigin($req->data);
    }

    public function patch_shop_origin (&$resp, $req) {
        if (!empty($req->get['id'])) {
            $CategoryID = intval($req->get['id']);
            $resp = $this->updateOrigin($CategoryID, $req->data);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    public function delete_shop_origin (&$resp, $req) {
        if (!glIsToolbox()) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        if (!empty($req->get['id'])) {
            $CategoryID = intval($req->get['id']);
            $resp = $this->disableOrigin($CategoryID);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }






    public function get_shop_wish (&$resp) {
        $resp['items'] = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
    }

    public function post_shop_wish (&$resp, $req) { 
        $resp['items'] = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        if (isset($req->data['productID'])) {
            $productID = $req->data['productID'];
            if (!isset($resp['items'][$productID])) {
                $product = $this->getProductByID($productID);
                $resp['items'][$productID] = $product;
                $_SESSION[$this->_listKey_Wish] = $resp['items'];
            }
        } else
            $resp['error'] = "MissedParameter_productID";
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
        if (count($resp['items']) >= 10) {
            $resp['error'] = "ProductLimitExceeded";
            return;
        }
        if (isset($req->data['productID'])) {
            $productID = $req->data['productID'];
            if (!isset($resp['items'][$productID])) {
                $product = $this->getProductByID($productID);
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
        $resp = $this->getOrders_List($req);
    }

    public function get_shop_order (&$resp, $req) {
        if (isset($req->get['id']) && $req->get['id'] !== "temp") {
            if ($this->getCustomer()->ifYouCan('Admin'))
                $resp = $this->getOrderByID($req->get['id']);
            else
                $resp['error'] = 'AccessDenied';
            return;
        } else if (isset($req->get['hash'])) {
            $resp = $this->getOrderByHash($req->get['hash']);
            return;
        } else {
            $resp = $this->_getOrderTemp();
        }
    }

    // create new order
    public function post_shop_order (&$resp, $req) {
        $resp = $this->createOrder($req->data);
    }

    // modify existent order status or
    // product quantity in the shopping cart list of temporary order
    public function patch_shop_order (&$resp, $req) {
        // var_dump($req);
        // var_dump($_SERVER['REQUEST_METHOD']);
        // var_dump($_POST);
        // var_dump(file_get_contents('php://input'));
        // $options = array();
        $isTemp = !isset($req->get['id']);

        if (!$isTemp && glIsToolbox()) {
            // if ($this->getCustomer()->ifYouCan('Admin')) {
                // here we're gonna change order's status only
            $resp = $this->updateOrder($req->get['id'], $req->data);
            // } else {
                // $resp['error'] = 'AccessDenied';
            // }
            return;
        }

        // for temp order (site side only)
        if ($isTemp) {
            if (isset($req->data['productID'])) {
                $sessionOrderProducts = $this->_getSessionOrderProducts();
                $productID = $req->data['productID'];
                $newQuantity = floatval($req->data['_orderQuantity']);
                if (isset($sessionOrderProducts[$productID])) {
                    $sessionOrderProducts[$productID]['_orderQuantity'] = $newQuantity;
                    if ($sessionOrderProducts[$productID]['_orderQuantity'] <= 0)
                        unset($sessionOrderProducts[$productID]);
                    $this->_setSessionOrderProducts($sessionOrderProducts);
                } elseif ($newQuantity > 0) {
                    $product['ID'] = $productID;
                    $product['_orderQuantity'] = $newQuantity;
                    $sessionOrderProducts[$productID] = $product;
                    $this->_setSessionOrderProducts($sessionOrderProducts);
                } elseif ($req->data['productID'] === "*") {
                    $this->_resetSessionOrderProducts();
                }
            } elseif (isset($req->data['promo'])) {
                if (empty($req->data['promo']))
                    $this->_resetSessionPromo();
                else
                    $this->_setSessionPromo($this->getPromoByHash($req->data['promo'], true));
            }
            $resp = $this->_getOrderTemp();
        }
    }

    // removes particular product or clears whole shopping cart
    public function delete_shop_cart (&$resp, $req) {
        if (!glIsToolbox()) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        if (!empty($req->get['id'])) {
            $OrderID = intval($req->get['id']);
            $resp = $this->disableOrderByID($OrderID);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    private function __productCountInCart ($id) {
        // $order = $this->_getOrderTemp();
        $sessionOrderProducts = $this->_getSessionOrderProducts();
        return isset($sessionOrderProducts[$id]) ? $sessionOrderProducts[$id]['_orderQuantity'] : 0;
    }

    private function __attachOrderDetails (&$order) {
        // echo "__attachOrderDetails";
        if (empty($order))
            return;

        $orderID = isset($order['ID']) ? $order['ID'] : null;
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
                $order['promo'] = $this->getPromoByID($order['PromoID']);
            // $order['items'] = array();
            $configBoughts = configurationShopDataSource::jsapiShopGetOrderBoughts($orderID);
            $boughts = $this->getCustomer()->fetch($configBoughts) ?: array();
            if (!empty($boughts))
                foreach ($boughts as $soldItem) {
                    $product = $this->getProductByID($soldItem['ProductID']);
                    // save current product info
                    $product["CurrentIsPromo"] = $product['IsPromo'];
                    $product["CurrentPrice"] = $product['Price'];
                    $product["CurrentSellingPrice"] = $product['SellingPrice'];
                    // restore product info at purchase moment
                    $product["Price"] = floatval($soldItem['Price']);
                    $product["SellingPrice"] = floatval($soldItem['SellingPrice']);
                    $product["IsPromo"] = intval($soldItem['IsPromo']) === 1;
                    // get purchased product quantity
                    $product["_orderQuantity"] = floatval($soldItem['Quantity']);
                    // actual price (with discount if promo is active)
                    // $price = isset($product['DiscountPrice']) ? $product['DiscountPrice'] : $product['Price'];
                    // set product gross and net totals
                    $product["_orderProductSubTotal"] = $product['Price'] * $product['_orderQuantity'];
                    $product["_orderProductTotal"] = $product['SellingPrice'] * $product['_orderQuantity'];
                    // add into list
                    $productItems[$product['ID']] = $product;
                }
        } else {
            // $productItems = !empty($order['items']) ? $order['items'] : array();
            $sessionPromo = $this->_getSessionPromo();
            $sessionOrderProducts = $this->_getSessionOrderProducts();
            // re-validate promo
            if (!empty($sessionPromo) && isset($sessionPromo['Code'])) {
                $sessionPromo = $this->getPromoByHash($sessionPromo['Code'], true);
                if (!empty($sessionPromo) && isset($sessionPromo['Code'])) {
                    $this->_setSessionPromo($sessionPromo);
                    $order['promo'] = $sessionPromo;
                } else {
                    $this->_resetSessionPromo();
                    $order['promo'] = null;
                }
            }
            // get prodcut items
            foreach ($sessionOrderProducts as $purchasingProduct) {
                $product = $this->getProductByID($purchasingProduct['ID']);
                // actual price (with discount if promo is active)
                // set product gross and net totals
                // get purchased product quantity
                $product["_orderQuantity"] = $purchasingProduct['_orderQuantity'];
                $product["_orderProductSubTotal"] = $product['Price'] * $purchasingProduct['_orderQuantity'];
                $product["_orderProductTotal"] = $product['SellingPrice'] * $purchasingProduct['_orderQuantity'];
                // add into list
                $productItems[$product['ID']] = $product;
            }
        }
        // append info
        $info = array(
            "subTotal" => 0.0,
            "total" => 0.0,
            "productCount" => 0,
            "productUniqueCount" => count($productItems),
            "hasPromo" => isset($order['promo']['Discount']) && $order['promo']['Discount'] > 0,
            "allProductsWithPromo" => true
        );
        // calc order totals
        foreach ($productItems as $product) {
            // update order totals
            $info["total"] += floatval($product['_orderProductTotal']);
            $info["subTotal"] += floatval($product['_orderProductSubTotal']);
            $info["productCount"] += intval($product['_orderQuantity']);
            $info["allProductsWithPromo"] = $info["allProductsWithPromo"] && $product['IsPromo'];
        }
        $order['items'] = $productItems;
        $order['info'] = $info;
    }
}

?>
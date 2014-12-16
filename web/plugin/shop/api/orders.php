<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use Exception;
use ArrayObject;

class orders extends \engine\objects\api {

    private $_listKey_Cart = 'shop:cart';
    private $_statuses = array(
        'ACTIVE','LOGISTIC_DELIVERING',
        'CUSTOMER_CANCELED','LOGISTIC_DELIVERED',
        'SHOP_CLOSED','SHOP_REFUNDED','NEW');
    public function getOrderStatuses () {
        return $this->_statuses;
    }
    // -----------------------------------------------
    // -----------------------------------------------
    // ORDERS
    // -----------------------------------------------
    // -----------------------------------------------
    public function getOrderByID ($orderID) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetOrderItem($orderID);
        $order = null;
        $order = $this->getCustomer()->fetch($config);
        if (empty($order)) {
            return null;
            // return glWrap('error', 'OrderDoesNotExist');
        }
        $order['ID'] = intval($order['ID']);
        $order['CurrencyRate'] = floatval($order['CurrencyRate']);
        $this->__attachOrderDetails($order);
        if ($this->getCustomer()->ifYouCan('Admin')) {
            $order['_statuses'] = $this->getOrderStatuses();
        }
        return $order;
    }

    public function getOrderByHash ($orderHash) {
        $config = $this->getPluginConfiguration()->data->jsapiGetShopOrderByHash($orderHash);
        $order = $this->getCustomer()->fetch($config);

        if (empty($order)) {
            return null;
            // return glWrap('error', 'OrderDoesNotExist');
        }

        $order['ID'] = intval($order['ID']);
        $order['CurrencyRate'] = floatval($order['CurrencyRate']);
        $this->__attachOrderDetails($order);
        return $order;
    }

    public function getOrders_ListExpired (array $options = array()) {
        // get expired orders
        $config = $this->getPluginConfiguration()->data->jsapiGetShopOrderList_Expired();
        // check permissions to display either all or user's orders only
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $config['condition']['AccountID'] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($this->getCustomer()->getAuthID());
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
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function getOrders_ListTodays (array $options = array()) {
        // get todays orders
        $config = $this->getPluginConfiguration()->data->jsapiGetShopOrderList_Todays();
        // set permissions
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $config['condition']['AccountID'] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($this->getCustomer()->getAuthID());
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
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function getOrders_ListPending (array $options = array()) {
        // get expired orders
        $config = $this->getPluginConfiguration()->data->jsapiGetShopOrderList_Pending();
        // check permissions
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $config['condition']['AccountID'] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($this->getCustomer()->getAuthID());
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
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function getOrders_List (array $options = array()) {
        // get all orders
        $config = $this->getPluginConfiguration()->data->jsapiGetShopOrderList($options);
        // check permissions
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $config['condition']['AccountID'] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($this->getCustomer()->getAuthID());
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
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);

        if (isset($options['_pStats']))
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

        $formSettings = $this->getAPI()->settings->getSettingsMapFormOrder();

        try {

            $orderCurrency = false;
            $orderRate = 1;

            // customer currency
            $selectedCustomerCurrencyName = $reqData['form']['userCurrency'];
            // customer's used default currency
            if (empty($selectedCustomerCurrencyName)) {
                $orderCurrency = $this->getAPI()->exchangerates->getDefaultDBPriceCurrencyType();
            } else {
                // get conversion rate
                $rateTo = $this->getAPI()->exchangerates->getExchangeRateTo_ByCurrencyName($selectedCustomerCurrencyName);
                $orderCurrency = $selectedCustomerCurrencyName;
                $orderRate = $rateTo['Rate'];
            }
            $orderRate = floatval($orderRate);

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
                $new_password = Secure::generateStrongPassword();

                $account = $pluginAccount->createAccount(array(
                    "FirstName" => $formSettings['ShowName']['_isActive'] ? $reqData['form']['shopCartUserName'] : $pluginAccount->getEmptyUserName(),
                    "EMail" => $formSettings['ShowEMail']['_isActive'] ? $reqData['form']['shopCartUserEmail'] : Validate::getEmptyEmail(),
                    "Phone" => $formSettings['ShowPhone']['_isActive'] ? $reqData['form']['shopCartUserPhone'] : Validate::getEmptyPhoneNumber(),
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
                    "Address" => $formSettings['ShowAddress']['_isActive'] ? $reqData['form']['shopCartUserAddress'] : 'n/a',
                    "POBox" => $formSettings['ShowPOBox']['_isActive'] ? $reqData['form']['shopCartUserPOBox'] : 'n/a',
                    "Country" => $formSettings['ShowCountry']['_isActive'] ? $reqData['form']['shopCartUserCountry'] : 'n/a',
                    "City" => $formSettings['ShowCity']['_isActive'] ? $reqData['form']['shopCartUserCity'] : 'n/a'
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
            $dataOrder["DeliveryID"] = $formSettings['ShowDeliveryAganet']['_isActive'] ? $reqData['form']['shopCartLogistic'] : null;
            $dataOrder["Warehouse"] = $formSettings['ShowDeliveryAganet']['_isActive'] ? $reqData['form']['shopCartWarehouse'] : null;
            $dataOrder["Comment"] = $formSettings['ShowComment']['_isActive'] ? $reqData['form']['shopCartComment'] : '';
            $dataOrder["PromoID"] = $orderPromoID;
            $dataOrder["CurrencyName"] = $orderCurrency;
            $dataOrder["CurrencyRate"] = $orderRate;

            $configOrder = $this->getPluginConfiguration()->data->jsapiShopCreateOrder($dataOrder);
            $orderID = $this->getCustomer()->fetch($configOrder);

            if (empty($orderID)) {
                throw new Exception("OrderCreateError", 1);
            }

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
                $dataBought["Price"] = $productItem["_prices"]["price"];
                $dataBought["SellingPrice"] = $productItem["_prices"]["actual"];
                $dataBought["Quantity"] = $productItem["_orderQuantity"];
                $dataBought["IsPromo"] = $productItem["IsPromo"];
                $configBought = $this->getPluginConfiguration()->data->jsapiShopCreateOrderBought($dataBought);
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
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
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

                    $configUpdateOrder = $this->getPluginConfiguration()->data->jsapiShopUpdateOrder($OrderID, $validatedValues);

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
        $errors = array();
        $success = false;

        try {

            $this->getCustomerDataBase()->beginTransaction();

            $config = $this->getPluginConfiguration()->data->jsapiShopDisableOrder($OrderID);
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

    private function __attachOrderDetails (&$order) {
        // echo "__attachOrderDetails";
        if (empty($order))
            return;

        $orderID = isset($order['ID']) ? $order['ID'] : null;
        $order['promo'] = null;
        $order['account'] = null;
        $order['address'] = null;
        $order['delivery'] = null;
        $productItems = array();

        $defaultDBCurrency = $this->getAPI()->exchangerates->getDefaultDBPriceCurrencyType();
        $rates = new ArrayObject($this->getAPI()->exchangerates->getAvailableConversionOptions());
        $ratesCurrent = $rates->getArrayCopy();
        $ratesCustomer = $rates->getArrayCopy();

        // var_dump($ratesCurrent);
        // var_dump($ratesCustomer);
        // var_dump($order['CurrencyRate']);
        // var_dump($order['CurrencyName']);
        // if orderID is set then the order is saved
        if (isset($orderID) && !isset($order['temp'])) {
            if (isset($ratesCustomer[$order['CurrencyName']])) {
                $ratesCustomer[$order['CurrencyName']] = floatval($order['CurrencyRate']);
            }
            // attach account and address
            if ($this->getCustomer()->hasPlugin('account')) {
                if (isset($order['AccountAddressesID']))
                    $order['address'] = $this->getPlugin('account')->getAddressByID($order['AccountAddressesID']);
                if (isset($order['AccountID']))
                    $order['account'] = $this->getPlugin('account')->getAccountByID($order['AccountID']);
                unset($order['AccountID']);
                unset($order['AccountAddressesID']);
            }
            // get promo
            if (!empty($order['PromoID']))
                $order['promo'] = $this->getAPI()->promos->getPromoByID($order['PromoID']);
            if (!empty($order['DeliveryID']))
                $order['delivery'] = $this->getAPI()->delivery->getDeliveryAgencyByID($order['DeliveryID']);
            // $order['items'] = array();
            $configBoughts = $this->getPluginConfiguration()->data->jsapiShopGetOrderBoughts($orderID);
            $boughts = $this->getCustomer()->fetch($configBoughts) ?: array();
            if (!empty($boughts))
                foreach ($boughts as $soldItem) {
                    $product = $this->getAPI()->products->getProductByID($soldItem['ProductID']);
                    
                    $soldItem['Price'] = floatval($soldItem['Price']);
                    $soldItem['SellingPrice'] = floatval($soldItem['SellingPrice']);
                    
                    // save current product info
                    $product["_original"] = array(
                        "IsPromo" => $product['IsPromo'],
                        "_prices" => $product['_prices']
                    );
                    // restore product info at purchase moment
                    $product["IsPromo"] = intval($soldItem['IsPromo']) === 1;
                    $product["_prices"] = array(
                        'price' => $soldItem['Price'],
                        'actual' => $soldItem['SellingPrice'],
                        'others' => $this->getAPI()->exchangerates->convertToDefinedRates($soldItem['SellingPrice'])
                    );
                    // get purchased product quantity
                    $product["_orderQuantity"] = floatval($soldItem['Quantity']);
                    // get product sub and total by raw price
                    $_subTotal = $product['_prices']['price'] * $soldItem['Quantity'];
                    $_total = $product['_prices']['actual'] * $soldItem['Quantity'];
                    // conversions
                    $product['_totalSummary'] = array(
                        "_sub" => $_subTotal,
                        "_total" => $_total,
                        "_sub" => $this->getAPI()->exchangerates->convertToDefinedRates($_subTotal, $defaultDBCurrency, $ratesCurrent),
                        "_totals" => $this->getAPI()->exchangerates->convertToDefinedRates($_total, $defaultDBCurrency, $ratesCurrent),
                        "_customer_sub" => $this->getAPI()->exchangerates->convertToDefinedRates($_subTotal, $defaultDBCurrency, $ratesCustomer),
                        "_customer_totals" => $this->getAPI()->exchangerates->convertToDefinedRates($_total, $defaultDBCurrency, $ratesCustomer)
                    );

                    // add into list
                    $productItems[$product['ID']] = $product;
                }
        } else {
            // $productItems = !empty($order['items']) ? $order['items'] : array();
            $sessionPromo = $this->getAPI()->promos->getSessionPromo();
            $sessionOrderProducts = $this->_getSessionOrderProducts();
            // re-validate promo
            if (!empty($sessionPromo) && isset($sessionPromo['Code'])) {
                $sessionPromo = $this->getAPI()->promos->getPromoByHash($sessionPromo['Code'], true);
                if (!empty($sessionPromo) && isset($sessionPromo['Code'])) {
                    $this->getAPI()->promos->setSessionPromo($sessionPromo);
                    $order['promo'] = $sessionPromo;
                } else {
                    $this->getAPI()->promos->resetSessionPromo();
                    $order['promo'] = null;
                }
            }
            // get product items
            foreach ($sessionOrderProducts as $purchasingProduct) {
                // get product
                $product = $this->getAPI()->products->getProductByID($purchasingProduct['ID']);
                // get purchased product quantity
                $product["_orderQuantity"] = $purchasingProduct['_orderQuantity'];
                // get product sub and total by raw price
                $_subTotal = $product['_prices']['price'] * $purchasingProduct['_orderQuantity'];
                $_total = $product['_prices']['actual'] * $purchasingProduct['_orderQuantity'];
                // conversions
                $product['_totalSummary'] = array(
                    "_sub" => $_subTotal,
                    "_total" => $_total,
                    "_subs" => $this->getAPI()->exchangerates->convertToDefinedRates($_subTotal, $defaultDBCurrency, $ratesCurrent),
                    "_totals" => $this->getAPI()->exchangerates->convertToDefinedRates($_total, $defaultDBCurrency, $ratesCurrent),
                    "_customer_subs" => $this->getAPI()->exchangerates->convertToDefinedRates($_subTotal, $defaultDBCurrency, $ratesCustomer),
                    "_customer_totals" => $this->getAPI()->exchangerates->convertToDefinedRates($_total, $defaultDBCurrency, $ratesCustomer)
                );
                // add into list
                $productItems[$product['ID']] = $product;
            }
        }
        // create info data
        $totals = array(
            "_sub" => 0,
            "_total" => 0,
            "_subs" => array(),
            "_totals" => array(),
            "_customer_subs" => array(),
            "_customer_totals" => array()
        );
        $info = array(
            "productCount" => 0,
            "productUniqueCount" => count($productItems),
            "hasPromo" => isset($order['promo']['Discount']) && $order['promo']['Discount'] > 0,
            "allProductsWithPromo" => true
        );
        // calc order totals
        foreach ($productItems as $product) {
            // update order totals
            $totals["_sub"] += floatval($product['_totalSummary']['_sub']);
            $totals["_total"] += floatval($product['_totalSummary']['_total']);
            $info["productCount"] += intval($product['_orderQuantity']);
            $info["allProductsWithPromo"] = $info["allProductsWithPromo"] && $product['IsPromo'];
        }
        // show available cargo-services
        if (isset($order['temp'])) {
            $info["deliveries"] = $this->getAPI()->delivery->getActiveDeliveryList();
        }
        $totals['_subs'] =  $this->getAPI()->exchangerates->convertToDefinedRates($totals["_sub"], $defaultDBCurrency, $ratesCurrent);
        $totals['_totals'] =  $this->getAPI()->exchangerates->convertToDefinedRates($totals["_total"], $defaultDBCurrency, $ratesCurrent);
        $totals['_customer_subs'] =  $this->getAPI()->exchangerates->convertToDefinedRates($totals["_sub"], $defaultDBCurrency, $ratesCustomer);
        $totals['_customer_totals'] =  $this->getAPI()->exchangerates->convertToDefinedRates($totals["_total"], $defaultDBCurrency, $ratesCustomer);
        // append info
        $order['items'] = $productItems;
        $order['info'] = $info;
        $order['totalSummary'] = $totals;

        // TODO: need to calculate subs and totals according to selected currency and rate by customer
    }

    private function _getOrderTemp () {
        $order['temp'] = true;
        $this->__attachOrderDetails($order);
        return $order;
    }

    private function _resetOrderTemp () {
        $this->getAPI()->promos->resetSessionPromo();
        $this->_resetSessionOrderProducts();
    }

    public function productCountInCart ($id) {
        // $order = $this->_getOrderTemp();
        $sessionOrderProducts = $this->_getSessionOrderProducts();
        return isset($sessionOrderProducts[$id]) ? $sessionOrderProducts[$id]['_orderQuantity'] : 0;
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
    // STATISTIC
    // -----------------------------------------------
    // -----------------------------------------------
    public function getStats_OrdersOverview () {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        // get orders count for each states
        $config = $this->getPluginConfiguration()->data->jsapiShopStat_OrdersOverview();
        $data = $this->getCustomer()->fetch($config) ?: array();
        $total = 0;
        $res = array();
        $availableStatuses = $this->getOrderStatuses();
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

    public function getStats_OrdersIntensityClosedLastMonth () {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        $config = $this->getPluginConfiguration()->data->jsapiShopStat_OrdersIntensityLastMonth('SHOP_CLOSED');
        $data = $this->getCustomer()->fetch($config) ?: array();
        return $data;
    }

    public function getStats_OrdersIntensityAliveLastMonth () {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        $config = $this->getPluginConfiguration()->data->jsapiShopStat_OrdersIntensityLastMonth('SHOP_CLOSED', '!=');
        $data = $this->getCustomer()->fetch($config) ?: array();
        return $data;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // REQUESTS
    // -----------------------------------------------
    // -----------------------------------------------

    public function get (&$resp, $req) {
        if (isset($req->get['id']) && $req->get['id'] !== "temp") {
            if ($this->getCustomer()->ifYouCan('Admin'))
                $resp = $this->getOrderByID($req->get['id']);
            else
                $resp['error'] = 'AccessDenied';
            return;
        } else if (isset($req->get['hash'])) {
            $resp = $this->getOrderByHash($req->get['hash']);
            return;
        } else if ($this->getApp()->isToolbox()) {
            $resp = $this->getOrders_List($req->get);
        } else {
            $resp = $this->_getOrderTemp();
        }
    }

    // create new order
    // public useres do that
    public function post (&$resp, $req) {
        $resp = $this->createOrder($req->data);
    }

    // modify existent order status or
    // product quantity in the shopping cart list of temporary order
    // both admin can update any order and public uses as well
    public function patch (&$resp, $req) {
        // var_dump($req);
        // var_dump($_SERVER['REQUEST_METHOD']);
        // var_dump($_POST);
        // var_dump(file_get_contents('php://input'));
        // $options = array();
        $isTemp = !isset($req->get['id']);

        if (!$isTemp && $this->getApp()->isToolbox()) {
            // if ($this->getCustomer()->ifYouCan('Admin')) {
                // here we're gonna change order's status only
        // check permissions
            if (!$this->getCustomer()->ifYouCan('Edit')) {
                $resp["error"] = "AccessDenied";
            } else {
                $resp = $this->updateOrder($req->get['id'], $req->data);
            }
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
                    $this->getAPI()->promos->resetSessionPromo();
                else
                    $this->getAPI()->promos->setSessionPromo($this->getAPI()->promos->getPromoByHash($req->data['promo'], true));
            }
            $resp = $this->_getOrderTemp();
        }
    }

    // removes particular product or clears whole shopping cart
    public function delete (&$resp, $req) { // ????? we must keep all orders
        if (!$this->getApp()->isToolbox()) {
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

}

?>
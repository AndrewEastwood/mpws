<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class orders {

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
        global $app;
        $config = dbquery::shopGetOrderItem($orderID);
        $order = null;
        $order = $app->getDB()->query($config);
        if (empty($order)) {
            return null;
            // return glWrap('error', 'OrderDoesNotExist');
        }
        $order['ID'] = intval($order['ID']);
        $order['CustomerCurrencyRate'] = floatval($order['CustomerCurrencyRate']);
        $order['ExchangeRateID'] = intval($order['ExchangeRateID']);
        $this->__attachOrderDetails($order);
        if (API::getAPI('system:auth')->ifYouCan('Admin')) {
            $order['_statuses'] = $this->getOrderStatuses();
        }
        return $order;
    }

    public function getOrderByHash ($orderHash) {
        global $app;
        $config = dbquery::getShopOrderByHash($orderHash);
        $order = $app->getDB()->query($config);

        if (empty($order)) {
            return null;
            // return glWrap('error', 'OrderDoesNotExist');
        }

        $order['ID'] = intval($order['ID']);
        $order['CustomerCurrencyRate'] = floatval($order['CustomerCurrencyRate']);
        $order['ExchangeRateID'] = intval($order['ExchangeRateID']);
        $this->__attachOrderDetails($order);
        return $order;
    }

    public function getOrders_ListExpired (array $options = array()) {
        global $app;
        // get expired orders
        $config = dbquery::getShopOrderList_Expired();
        // check permissions to display either all or user's orders only
        if (!API::getAPI('system:auth')->ifYouCan('Admin')) {
            $config['condition']['UserID'] = $app->getDB()->createCondition($this->getCustomer()->getAuthID());
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
        $dataList = $app->getDB()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function getOrders_ListTodays (array $options = array()) {
        global $app;
        // get todays orders
        $config = dbquery::getShopOrderList_Todays();
        // set permissions
        if (!API::getAPI('system:auth')->ifYouCan('Admin')) {
            $config['condition']['UserID'] = $app->getDB()->createCondition($this->getCustomer()->getAuthID());
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
        $dataList = $app->getDB()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function getOrders_ListPending (array $options = array()) {
        global $app;
        // get expired orders
        $config = dbquery::getShopOrderList_Pending();
        // check permissions
        if (!API::getAPI('system:auth')->ifYouCan('Admin')) {
            $config['condition']['UserID'] = $app->getDB()->createCondition($this->getCustomer()->getAuthID());
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
        $dataList = $app->getDB()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function getOrders_List (array $options = array()) {
        global $app;
        // get all orders
        $config = dbquery::getShopOrderList($options);
        // check permissions
        if (!API::getAPI('system:auth')->ifYouCan('Admin')) {
            $config['condition']['UserID'] = $app->getDB()->createCondition($this->getCustomer()->getAuthID());
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
        $dataList = $app->getDB()->getDataList($config, $options, $callbacks);

        if (isset($options['_pStats']))
            $dataList['stats'] = $this->getStats_OrdersOverview();

        return $dataList;
    }

    public function createOrder ($reqData) {
        global $app;

        $result = array();
        $errors = array();
        $success = false;

        // var_dump($order);
        // var_dump($reqData);

        $userToken =  "";
        $formAccountToken = "";
        $formAddressID = "";

        if (!empty($reqData['account']))
            $userToken = $reqData['account']['ValidationString'];

        if (!empty($reqData['form']['shopCartAccountValidationString']))
            $formAccountToken = $reqData['form']['shopCartAccountValidationString'];

        if (!empty($reqData['form']['shopCartAccountValidationString']))
            $formAddressID = $reqData['form']['shopCartAccountAddressID'];

        $apiAccountUser = API::getAPI('system:user');
        $apiAccountAddr = API::getAPI('system:address');
        $formSettings = API::getAPI('shop:settings')->getSettingsFormOrder();

        // var_dump($formSettings);
        try {

            $app->getDB()->beginTransaction();
            $app->getDB()->disableTransactions();

            // check if matches
            if ($userToken !== $formAccountToken)
                throw new Exception("WrongTokensOccured", 1);

            // create new profile
            if (empty($userToken) && empty($formAccountToken)) {

                // reset address id becuase empty account is occured
                $formAddressID = null;

                // create new account
                $new_password = Secure::generateStrongPassword();

                $user = $apiAccountUser->createUser(array(
                    "FirstName" => $formSettings['ShowName']['_isActive'] ? $reqData['form']['shopCartUserName'] : $apiAccountUser->getEmptyUserName(),
                    "EMail" => $formSettings['ShowEMail']['_isActive'] ? $reqData['form']['shopCartUserEmail'] : Validate::getEmptyEmail(),
                    "Phone" => $formSettings['ShowPhone']['_isActive'] ? $reqData['form']['shopCartUserPhone'] : Validate::getEmptyPhoneNumber(),
                    "Password" => $new_password,
                    "ConfirmPassword" => $new_password
                ));

                if (count($user['errors']))
                    $errors['Account'] = $user['errors'];

                if ($user['success'] === false)
                    throw new Exception("AccountCreateError", 1);

            } else {

                // get account by token string (ValidationString)
                $user = $apiAccountUser->getUserByValidationString($formAccountToken);

                if (empty($user))
                    throw new Exception("WrongAccount", 1);

                if ($user['Status'] !== "ACTIVE")
                    throw new Exception("AccountIsBlocked", 1);

                // need to validate account
                // if account exits
                // if account is active
                if ($user["FirstName"] !== $reqData["form"]["shopCartAccountFirstName"] ||
                    $user["LastName"] !== $reqData["form"]["shopCartAccountLastName"] ||
                    $user["EMail"] !== $reqData["form"]["shopCartAccountEMail"] ||
                    $user["Phone"] !== $reqData["form"]["shopCartAccountPhone"])
                    throw new Exception("AccountDataMismatch", 1);

                // at this point we have a valid account
            }

            $userID = $user['ID'];

            // create new address for account
            if (empty($formAddressID) || empty($formAccountToken)) {

                // create account address
                $userAddress = $apiAccountAddr->createAddress($userID, array(
                    "Address" => $formSettings['ShowAddress']['_isActive'] ? $reqData['form']['shopCartUserAddress'] : 'n/a',
                    "POBox" => $formSettings['ShowPOBox']['_isActive'] ? $reqData['form']['shopCartUserPOBox'] : 'n/a',
                    "Country" => $formSettings['ShowCountry']['_isActive'] ? $reqData['form']['shopCartUserCountry'] : 'n/a',
                    "City" => $formSettings['ShowCity']['_isActive'] ? $reqData['form']['shopCartUserCity'] : 'n/a'
                ), true); // <= this allows creating unliked addresses or add new address to account when it's possible

                if (count($userAddress['errors']))
                    $errors['Account'] = $userAddress['errors'];

                if ($userAddress['success'] === false)
                    throw new Exception("AccountAddressCreateError", 1);

            } else {
                // validate provided address id for account
                // we must check it if the authenticated account has this address id
                if (!isset($user['Addresses'][$formAddressID]))
                    throw new Exception("WrongAccountAddressID", 1);
                else
                    $userAddress = $user['Addresses'][$formAddressID];
            }

            $addressID = $userAddress['ID'];

            $order = $this->_getOrderTemp();
            // $sessionOrderProducts = $this->_getSessionOrderProducts();

            // var_dump($this->_getSessionOrderProducts());
            if (empty($order['items']))
                 throw new Exception("NoProudctsToPurchase", 1);

            $orderPromoID = empty($order['promo']) ? null : $order['promo']['ID'];
            $rateDefault = API::getAPI('shop:exchangerates')->getDefaultDBPriceCurrency();
            $customerCurrencyName = $rateDefault['CurrencyA'];
            $customerRate = 1;
            if (empty($reqData['form']['customerCurrencyName']) || $customerCurrencyName !== $reqData['form']['customerCurrencyName']) {
                $customerCurrencyName = $reqData['form']['customerCurrencyName'];
                try {
                    $rate = API::getAPI('shop:exchangerates')->getExchangeRateByBothRateNames($rateDefault['CurrencyA'], $customerCurrencyName);
                    if (!empty($rate)) {
                        $customerRate = $rate['Rate'];
                    }
                } catch (Exception $ex) {
                    $customerCurrencyName = $rateDefault['CurrencyA'];
                }
            }

            // start creating order
            $dataOrder = array();
            $dataOrder["UserID"] = $userID;
            $dataOrder["UserAddressesID"] = $addressID;
            $dataOrder["CustomerID"] = $app->getSite()->getRuntimeCustomerID();
            $dataOrder["DeliveryID"] = $formSettings['ShowDeliveryAganet']['_isActive'] ? $reqData['form']['shopCartLogistic'] : null;
            $dataOrder["Warehouse"] = $formSettings['ShowDeliveryAganet']['_isActive'] ? $reqData['form']['shopCartWarehouse'] : null;
            $dataOrder["Comment"] = $formSettings['ShowComment']['_isActive'] ? $reqData['form']['shopCartComment'] : '';
            $dataOrder["PromoID"] = $orderPromoID;
            $dataOrder["ExchangeRateID"] = $rateDefault['ID'];
            $dataOrder["CustomerCurrencyRate"] = $customerRate; // save rate value because we can change it any time in future
            $dataOrder['CustomerCurrencyName'] = $customerCurrencyName;

            if (empty($dataOrder["DeliveryID"])) {
                $dataOrder["DeliveryID"] = null;
            } else {
                $dataOrder["DeliveryID"] = intval($dataOrder["DeliveryID"]);
            }

            // var_dump($dataOrder);
            // return;

            $configOrder = dbquery::shopCreateOrder($dataOrder);
            $orderID = $app->getDB()->query($configOrder);

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
                $dataBought["CustomerID"] = $app->getSite()->getRuntimeCustomerID();
                $dataBought["ProductID"] = $productItem["ID"];
                $dataBought["OrderID"] = $orderID;
                $dataBought["Price"] = $productItem["_prices"]["price"];
                $dataBought["SellingPrice"] = $productItem["_prices"]["actual"];
                $dataBought["Quantity"] = $productItem["_orderQuantity"];
                $dataBought["IsPromo"] = $productItem["IsPromo"];
                $configBought = dbquery::shopCreateOrderBought($dataBought);
                $boughtID = $app->getDB()->query($configBought);

                // check for created bought
                if (empty($boughtID))
                    throw new Exception("BoughtCreateError", 1);
            }

            // if (empty($userID) || empty($addressID))
            //     throw new Exception("UnableToLinkAccountOrAddress", 1);

            $app->getDB()->enableTransactions();
            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->enableTransactions();
            $app->getDB()->rollBack();
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
        global $app;
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

                    $app->getDB()->beginTransaction();

                    $validatedValues = $validatedDataObj['values'];

                    $configUpdateOrder = dbquery::shopUpdateOrder($OrderID, $validatedValues);

                    $app->getDB()->query($configUpdateOrder, true);

                    $app->getDB()->commit();

                    $success = true;
                } catch (Exception $e) {
                    $app->getDB()->rollBack();
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
        global $app;
        $errors = array();
        $success = false;

        try {

            $app->getDB()->beginTransaction();

            $config = dbquery::shopDisableOrder($OrderID);
            $app->getDB()->query($config);

            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = 'OrderUpdateError';
        }

        $result = $this->getOrderByID($OrderID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    private function __attachOrderDetails (&$order) {
        global $app;
        // echo "__attachOrderDetails";
        if (empty($order))
            return;

        $orderID = isset($order['ID']) ? $order['ID'] : null;
        $order['promo'] = null;
        $order['user'] = null;
        $order['address'] = null;
        $order['delivery'] = null;
        $productItems = array();

        // set order exchange rates
        $orderRate = null;
        $dbDefaultRate = API::getAPI('shop:exchangerates')->getDefaultDBPriceCurrency();
        if (isset($orderID) && !isset($order['temp'])) {
            $orderRate = new ArrayObject(API::getAPI('shop:exchangerates')->getExchangeRateByID($order['ExchangeRateID']));
        } else {
            $orderRate = new ArrayObject($dbDefaultRate);
        }
        $orderBaseCurrencyName = $orderRate['CurrencyA'];
        $customerCurrencyName = $orderRate['CurrencyB'];
        $orderRates = new ArrayObject(API::getAPI('shop:exchangerates')->getAvailableConversionOptions($orderBaseCurrencyName));

        $currentRate = $orderRate->getArrayCopy();
        $customerRate = $orderRate->getArrayCopy();

        $currentRates = $orderRates->getArrayCopy();
        $customerRates = $orderRates->getArrayCopy();

        $dbCurrencyIsChanged = $orderBaseCurrencyName !== $dbDefaultRate['CurrencyA'];

        // if orderID is set then the order is saved
        if (isset($orderID) && !isset($order['temp'])) {

            // $orderBaseCurrencyName = $orderRate['CurrencyA'];
            // $customerCurrencyName = $orderRate['CurrencyB'];
            // $orderRates = new ArrayObject(API::getAPI('shop:exchangerates')->getAvailableConversionOptions($orderBaseCurrencyName));

            // $currentRate = $orderRate->getArrayCopy();
            // $customerRate = $orderRate->getArrayCopy();
            $customerCurrencyName = $order['CustomerCurrencyName'];
            if ($customerCurrencyName === $orderRate['CurrencyB']) {
                $customerRate['Rate'] = floatval($order['CustomerCurrencyRate']);
            }

            // if ($dbCurrencyIsChanged) {
            //     $currentRate['Rate'] = 1;
            //     $customerRate['Rate'] = 1;
            // }

            // $currentRates = $orderRates->getArrayCopy();
            // $customerRates = $orderRates->getArrayCopy();
            $customerRates[$customerCurrencyName] = $customerRate['Rate'];

            $order['rates'] = array(
                'rate' => $customerRate,
                'actual' => $currentRate['Rate'],
                'customer' => $customerRate['Rate'],
                'ourBenefit' => $customerRate['Rate'] - $currentRate['Rate'],
                'dbCurrencyIsChanged' => $dbCurrencyIsChanged,
                'orderBaseCurrencyName' => $orderBaseCurrencyName,
                'defaultDBCurrency' => $dbDefaultRate
            );
            // $order['_currencyName'] = $customerCurrencyName;
            // attach account and address
            if ($app->getSite()->hasPlugin('system')) {
                if (isset($order['UserAddressesID']))
                    $order['address'] = API::getAPI('system:address')->getAddressByID($order['UserAddressesID']);
                if (isset($order['UserID']))
                    $order['user'] = API::getAPI('system:user')->getUserByID($order['UserID']);
                unset($order['UserID']);
                unset($order['UserAddressesID']);
            }
            // get promo
            if (!empty($order['PromoID']))
                $order['promo'] = API::getAPI('shop:promos')->getPromoByID($order['PromoID']);
            if (!empty($order['DeliveryID']))
                $order['delivery'] = API::getAPI('shop:delivery')->getDeliveryAgencyByID($order['DeliveryID']);
            // $order['items'] = array();
            $configBoughts = dbquery::shopGetOrderBoughts($orderID);
            $boughts = $app->getDB()->query($configBoughts) ?: array();
            if (!empty($boughts))
                foreach ($boughts as $soldItem) {
                    $product = API::getAPI('shop:products')->getProductByID($soldItem['ProductID']);
                    
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
                        'others' => API::getAPI('shop:exchangerates')->convertToRates($soldItem['SellingPrice'], $orderBaseCurrencyName, $customerRates)
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
                        "_subs" => API::getAPI('shop:exchangerates')->convertToRates($_subTotal, $orderBaseCurrencyName, $currentRates),
                        "_totals" => API::getAPI('shop:exchangerates')->convertToRates($_total, $orderBaseCurrencyName, $currentRates),
                        "_customer_subs" => API::getAPI('shop:exchangerates')->convertToRates($_subTotal, $orderBaseCurrencyName, $customerRates),
                        "_customer_totals" => API::getAPI('shop:exchangerates')->convertToRates($_total, $orderBaseCurrencyName, $customerRates)
                    );

                    // add into list
                    $productItems[$product['ID']] = $product;
                }
        } else {


            // $productItems = !empty($order['items']) ? $order['items'] : array();
            $sessionPromo = API::getAPI('shop:promos')->getSessionPromo();
            $sessionOrderProducts = $this->_getSessionOrderProducts();
            // re-validate promo
            if (!empty($sessionPromo) && isset($sessionPromo['Code'])) {
                $sessionPromo = API::getAPI('shop:promos')->getPromoByHash($sessionPromo['Code'], true);
                if (!empty($sessionPromo) && isset($sessionPromo['Code'])) {
                    API::getAPI('shop:promos')->setSessionPromo($sessionPromo);
                    $order['promo'] = $sessionPromo;
                } else {
                    API::getAPI('shop:promos')->resetSessionPromo();
                    $order['promo'] = null;
                }
            }
            // get product items
            foreach ($sessionOrderProducts as $purchasingProduct) {
                // get product
                $product = API::getAPI('shop:products')->getProductByID($purchasingProduct['ID']);
                // get purchased product quantity
                $product["_orderQuantity"] = $purchasingProduct['_orderQuantity'];
                // get product sub and total by raw price
                $_subTotal = $product['_prices']['price'] * $purchasingProduct['_orderQuantity'];
                $_total = $product['_prices']['actual'] * $purchasingProduct['_orderQuantity'];
                // conversions
                $product['_totalSummary'] = array(
                    "_sub" => $_subTotal,
                    "_total" => $_total,
                    "_subs" => API::getAPI('shop:exchangerates')->convertToRates($_subTotal, $orderBaseCurrencyName, $currentRates),
                    "_totals" => API::getAPI('shop:exchangerates')->convertToRates($_total, $orderBaseCurrencyName, $currentRates),
                    "_customer_subs" => API::getAPI('shop:exchangerates')->convertToRates($_subTotal, $orderBaseCurrencyName, $customerRates),
                    "_customer_totals" => API::getAPI('shop:exchangerates')->convertToRates($_total, $orderBaseCurrencyName, $customerRates)
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
        // order summary currency names
        $currencyNames = array_keys($currentRates);
        // calc order totals
        $totals['_subs'] = array();
        $totals['_totals'] = array();
        $totals['_customer_subs'] = array();
        $totals['_customer_totals'] = array();
        foreach ($productItems as $product) {
            // update order totals
            $totals["_sub"] += floatval($product['_totalSummary']['_sub']);
            $totals["_total"] += floatval($product['_totalSummary']['_total']);
            $info["productCount"] += intval($product['_orderQuantity']);
            $info["allProductsWithPromo"] = $info["allProductsWithPromo"] && $product['IsPromo'];
            // var_dump($product['_totalSummary']);
            foreach ($currencyNames as $key) {
                if (!isset($totals['_subs'][$key])) {
                    $totals['_subs'][$key] = 0;
                }
                $totals['_subs'][$key] += $product['_totalSummary']['_subs'][$key];
                if (!isset($totals['_totals'][$key])) {
                    $totals['_totals'][$key] = 0;
                }
                $totals['_totals'][$key] += $product['_totalSummary']['_totals'][$key];
                if (!isset($totals['_customer_subs'][$key])) {
                    $totals['_customer_subs'][$key] = 0;
                }
                $totals['_customer_subs'][$key] += $product['_totalSummary']['_customer_subs'][$key];
                if (!isset($totals['_customer_totals'][$key])) {
                    $totals['_customer_totals'][$key] = 0;
                }
                $totals['_customer_totals'][$key] += $product['_totalSummary']['_customer_totals'][$key];
            }
        }
        // show available cargo-services
        if (isset($order['temp'])) {
            $info["deliveries"] = API::getAPI('shop:delivery')->getActiveDeliveryList();
        }
        // $totals['_subs'] =  API::getAPI('shop:exchangerates')->convertToRates($totals["_sub"], $orderBaseCurrencyName, $currentRates);
        // $totals['_totals'] =  API::getAPI('shop:exchangerates')->convertToRates($totals["_total"], $orderBaseCurrencyName, $currentRates);
        // $totals['_customer_subs'] =  API::getAPI('shop:exchangerates')->convertToRates($totals["_sub"], $orderBaseCurrencyName, $customerRates);
        // $totals['_customer_totals'] =  API::getAPI('shop:exchangerates')->convertToRates($totals["_total"], $orderBaseCurrencyName, $customerRates);
        // calc diffs
        $totals['_diff_subs'] = array();
        $totals['_diff_totals'] = array();
        $totals['_diff_promo'] = array();
        foreach ($totals['_totals'] as $key => $value) {
            $totals['_diff_totals'][$key] = $totals['_customer_totals'][$key] - $value;
        }
        foreach ($totals['_subs'] as $key => $value) {
            $totals['_diff_subs'][$key] = $totals['_customer_subs'][$key] - $value;
        }
        foreach ($totals['_customer_subs'] as $key => $value) {
            $totals['_diff_promo'][$key] = $totals['_customer_totals'][$key] - $value;
        }
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
        API::getAPI('shop:promos')->resetSessionPromo();
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
        global $app;
        if (!API::getAPI('system:auth')->ifYouCan('Admin')) {
            return null;
        }
        // get orders count for each states
        $config = dbquery::shopStat_OrdersOverview();
        $data = $app->getDB()->query($config) ?: array();
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
        global $app;
        if (!API::getAPI('system:auth')->ifYouCan('Admin')) {
            return null;
        }
        $config = dbquery::shopStat_OrdersIntensityLastMonth('SHOP_CLOSED');
        $data = $app->getDB()->query($config) ?: array();
        return $data;
    }

    public function getStats_OrdersIntensityAliveLastMonth () {
        global $app;
        if (!API::getAPI('system:auth')->ifYouCan('Admin')) {
            return null;
        }
        $config = dbquery::shopStat_OrdersIntensityLastMonth('SHOP_CLOSED', '!=');
        $data = $app->getDB()->query($config) ?: array();
        return $data;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // REQUESTS
    // -----------------------------------------------
    // -----------------------------------------------

    public function get (&$resp, $req) {
        global $app;
        if (isset($req->get['id']) && $req->get['id'] !== "temp") {
            if (API::getAPI('system:auth')->ifYouCan('Admin'))
                $resp = $this->getOrderByID($req->get['id']);
            else
                $resp['error'] = 'AccessDenied';
            return;
        } else if (isset($req->get['hash'])) {
            $resp = $this->getOrderByHash($req->get['hash']);
            return;
        } else if ($app->isToolbox()) {
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
        global $app;
        // var_dump($req);
        // var_dump($_SERVER['REQUEST_METHOD']);
        // var_dump($_POST);
        // var_dump(file_get_contents('php://input'));
        // $options = array();
        $isTemp = !isset($req->get['id']);

        if (!$isTemp && $app->isToolbox()) {
            // if (API::getAPI('system:auth')->ifYouCan('Admin')) {
                // here we're gonna change order's status only
        // check permissions
            if (!API::getAPI('system:auth')->ifYouCan('Edit')) {
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
                    API::getAPI('shop:promos')->resetSessionPromo();
                else
                    API::getAPI('shop:promos')->setSessionPromo(API::getAPI('shop:promos')->getPromoByHash($req->data['promo'], true));
            }
            $resp = $this->_getOrderTemp();
        }
    }

    // removes particular product or clears whole shopping cart
    public function delete (&$resp, $req) { // ????? we must keep all orders
        global $app;
        if (!$app->isToolbox()) {
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
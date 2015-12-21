<?php
namespace static_\plugins\shop\api;

use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\utils as Utils;
use \engine\lib\api as API;
use \engine\lib\data as BaseData;
use \static_\plugins\shop\api\shoputils as ShopUtils;
use Exception;
use ArrayObject;

class data extends BaseData {

    var $source_products = 'shop_products';
    var $source_origins = 'shop_origins';
    var $source_categories = 'shop_categories';
    var $source_productFeatures = 'shop_productFeatures';
    var $source_productPrices = 'shop_productPrices';
    var $source_relations = 'shop_relations';
    var $source_features = 'shop_features';
    var $source_productAttributes = 'shop_productAttributes';
    var $source_deliveryAgencies = 'shop_deliveryAgencies';
    var $source_settingsAddress = 'shop_settingsAddress';
    var $source_settingsAlerts = 'shop_settingsAlerts';
    var $source_settingsExchangeRatesDisplay = 'shop_settingsExchangeRatesDisplay';
    var $source_settingsFormOrder = 'shop_settingsFormOrder';
    var $source_settingsMisc = 'shop_settingsMisc';
    var $source_settingsProduct = 'shop_settingsProduct';
    var $source_settingsSeo = 'shop_settingsSeo';
    var $source_settingsWebsite = 'shop_settingsWebsite';
    var $source_orders = 'shop_orders';
    var $source_boughts = 'shop_boughts';
    var $source_promo = 'shop_promo';
    var $source_currency = 'shop_currency';

    // functions
    var $source_proc_catalogLocation = 'getShopCatalogLocation';
    var $source_proc_catalogBrands = 'getShopCatalogBrands';
    var $source_proc_catalogPriceEdges = 'getShopCatalogPriceEdges';

    function __construct () {
        global $app;

        parent::__construct();

        // this function is being invoked every time
        // when you do select any task and process
        // raw db value before output
        // $filter = ;

        // create required queries
        // ==== CUSTOMERS
        $this->db->createQuery('shopProducts', $this->source_products);

        $this->db->createQuery('shopProductsWithOrigin', $this->source_products)
            ->setJoin('shop_origins', "shop_products.OriginID=shop_origins.ID",
                    array("ID", "@shop_origins.Name AS OriginName"));

        $this->db->createQuery('shopProductsWithCatalog', $this->source_products)
            ->setJoin('shop_origins', "shop_products.OriginID=shop_origins.ID",
                    array("ID", "@shop_origins.Name AS OriginName"))
            ->setJoin('shop_categories', "shop_products.CategoryID=shop_categories.ID",
                    array("ID", "@shop_categories.Status AS CategoryStatus", "@shop_categories.ExternalKey AS CategoryExternalKey"))
            ->setJoin('shop_productFeatures', "shop_products.CategoryID=shop_productFeatures.ID",
                    array("FeatureID"));

        $this->db->createQuery('shopCatalogLocation', $thsi->source_proc_catalogLocation);
        $this->db->createQuery('shopCatalogBrands', $thsi->source_proc_catalogBrands);
        $this->db->createQuery('shopCatalogPriceEdges', $thsi->source_proc_catalogPriceEdges);

            // "shop_categories" => array(
            //     "constraint" => array("shop_products.CategoryID", "=", "shop_categories.ID"),
            //     "fields" => array("@shop_categories.Status AS CategoryStatus", "@shop_categories.ExternalKey AS CategoryExternalKey")
            // ),
            // "shop_origins" => array(
            //     "constraint" => array("shop_products.OriginID", "=", "shop_origins.ID"),
            //     "fields" => array("@shop_origins.Status AS OriginStatus")
            // ),
            // "shop_productFeatures" => array(
            //     "constraint" => array("shop_products.ID", "=", "shop_productFeatures.ProductID"),
            //     "fields" => array("FeatureID")
            // )
        $this->db->createQuery('shopOrigins', $this->source_origins);
        $this->db->createQuery('shopCategories', $this->source_categories);
        $this->db->createQuery('shopProductFeatures', $this->source_productFeatures);
        $this->db->createQuery('shopProductPrices', $this->source_productPrices);
        $this->db->createQuery('shopRelations', $this->source_relations);
        $this->db->createQuery('shopFeatures', $this->source_features)
            ->setJoin('shop_features', 'shop_productFeatures.FeatureID=shop_features.ID',
                array("ID", "FieldName", "GroupName"));

        // return dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_productFeatures",
        //     "fields" => array("FeatureID"),
        //     'additional' => array(
        //         "shop_features" => array(
        //             "constraint" => array("shop_productFeatures.FeatureID", "=", "shop_features.ID"),
        //             "fields" => array("ID", "FieldName", "GroupName")
        //         )
        //     ),
        //     "condition" => array(
        //         "ProductID" => dbquery::createCondition($id)
        //     ),
        //     "limit" => 0,
        //     "options" => array()
        // ));

        $this->db->createQuery('shopProductAttributes', $this->source_productAttributes);
        $this->db->createQuery('shopDeliveryAgencies', $this->source_deliveryAgencies);
        $this->db->createQuery('shopSettingsAddress', $this->source_settingsAddress);
        $this->db->createQuery('shopSettingsAlerts', $this->source_settingsAlerts);
        $this->db->createQuery('shopSettingsExchangeRatesDisplay', $this->source_settingsExchangeRatesDisplay);
        $this->db->createQuery('shopSettingsFormOrder', $this->source_settingsFormOrder);
        $this->db->createQuery('shopSettingsMisc', $this->source_settingsMisc);
        $this->db->createQuery('shopSettingsProduct', $this->source_settingsProduct);
        $this->db->createQuery('shopSettingsSeo', $this->source_settingsSeo);
        $this->db->createQuery('shopSettingsWebsite', $this->source_settingsWebsite);
        $this->db->createQuery('shopOrders', $this->source_orders);
        $this->db->createQuery('shopBoughts', $this->source_boughts)
            ->setJoin('shop_products', 'shop_boughts.ProductID=shop_products.ID',
                array("ID", "FieldName", "GroupName"));
        $this->db->createQuery('shopBoughtsWithProducts', $this->source_boughts);
        $this->db->createQuery('shopPromo', $this->source_promo);
        $this->db->createQuery('shopCurrency', $this->source_currency);


        $self = $this;

        // this runs before each query to db (select, insert, etc)
        dbQuery::setCommonPreFilter(function ($f) use ($app) {
            // if ($f->isSelect()) {
            $f->addCondition('CustomerID', $app->getSite()->getRuntimeCustomerID());
            // }
            $f->addDataItem('CustomerID', $app->getSite()->getRuntimeCustomerID());
        });

        dbQuery::setQueryFilter(function (&$product) use ($self) {
            global $app;
            // adjusting
            $productID = intval($product['ID']);
            $product['ID'] = $productID;
            $product['OriginID'] = intval($product['OriginID']);
            $product['CategoryID'] = intval($product['CategoryID']);
            $product['_category'] = $this->data->fetchCategoryByID($product['CategoryID']);
            $product['_origin'] = API::getAPI('shop:origins')->getOriginByID($product['OriginID']);
            $product['Attributes'] = self::fetchProductAttributes($productID);
            $product['IsPromo'] = intval($product['IsPromo']) === 1;
            $product['IsFeatured'] = intval($product['IsFeatured']) === 1;
            $product['IsOffer'] = intval($product['IsOffer']) === 1;
            $product['ShowBanner'] = intval($product['ShowBanner']) === 1;

            // create display product title
            $displayName = array();
            if (!empty($product['Name'])) {
                $displayName[] = $product['Name'];
            }
            if (!empty($product['_origin'])) {
                $displayName[] = $product['_origin']['Name'];
            }
            if (!empty($product['Model'])) {
                $displayName[] = $product['Model'];
            }
            $product['_displayNameFull'] = implode(' ', $displayName);
            $product['_displayName'] = implode(' ', array_slice($displayName, 1));

            // misc data
            if (!$skipRelations) {
                $product['Relations'] = self::fetchProductRelationsArray($productID);
            }

            // features
            $product['Features'] = self::fetchProductFeaturesArray($productID);

            // media
            $product['Images'] = self::fetchProductImages($productID);
            $product['Videos'] = self::fetchProductVideos($productID);
            $product['Banners'] = self::fetchProductBanners($productID);

            // Utils
            $product['viewExtrasInWish'] = API::getAPI('shop:wishlists')->productIsInWishList($productID);
            $product['viewExtrasInCompare'] = API::getAPI('shop:comparelists')->productIsInCompareList($productID);
            $product['viewExtrasInCartCount'] = API::getAPI('shop:orders')->productCountInCart($productID);

            // is available
            $product['_available'] = in_array($product['Status'], self::getProductStatusesWhenAvailable());
            $product['_archived'] = in_array($product['Status'], self::getProductStatusesWhenDisabled());

            // promo
            $promo = API::getAPI('shop:promos')->getSessionPromo();
            $product['_promo'] = $promo;

            // prices and actual price
            $price = floatval($product['Price']);
            $prevprice = floatval($product['PrevPrice']);
            $actualPrice = 0;
            $priceHistory = self::fetchProductPriceHistory($productID);
            if ($product['IsPromo'] && !empty($promo) && !empty($promo['Discount']) && $promo['Discount'] > 0) {
                $product['_promoIsApplied'] = true;
                $actualPrice = (100 - intval($promo['Discount'])) / 100 * $price;
            } else {
                $product['_promoIsApplied'] = false;
                $actualPrice = $price;
            }
            $actualPrice = floatval($actualPrice);
            $savingValue = $prevprice - $actualPrice;
            unset($product['Price']);
            unset($product['PrevPrice']);

            // apply currencies
            $convertedPrices = API::getAPI('shop:exchangerates')->convertToRates($actualPrice);
            $convertedPrevPrices = API::getAPI('shop:exchangerates')->convertToRates($prevprice);
            $convertedSavings = API::getAPI('shop:exchangerates')->convertToRates($savingValue);

            // create product prices object
            $product['_prices'] = array(
                'price' => $price,
                'previous' => $prevprice,
                'actual' => $actualPrice,
                'others' => $convertedPrices,
                'history' => $priceHistory,
                'previousothers' => $convertedPrevPrices,
                'savings' => $savingValue,
                'savingsothers' => $convertedSavings
            );

            $product['ShopDiscount'] = $prevprice > 0 ? 100 - intval($price * 100 / $prevprice) : 0;
            $product['IsBigSavings'] = $product['ShopDiscount'] > 5;
            $product['GoodToShowPreviousPrice'] = $savingValue > 10;

            if (!empty($product['Attributes']['PROMO_TEXT'])) {
                $product['Attributes']['PROMO_TEXT'] = str_replace('[DisplayName]', $product['_displayName'], $product['Attributes']['PROMO_TEXT']);
            }

            // // save product into recently viewed list
            // $isDirectRequestToProduct = Request::hasInGet('id') || Request::hasInGet('params');
            // if (Request::isGET() && !$app->isToolbox() && !empty($isDirectRequestToProduct)) {
            //     $recentProducts = isset($_SESSION[self::_listKey_Recent]) ? $_SESSION[self::_listKey_Recent] : array();
            //     $recentProducts[] = $productID;
            //     $_SESSION[self::_listKey_Recent] = array_unique($recentProducts);
            // }

            // var_dump($product);
            return $product;
        }, 'shopProducts');

        dbQuery::setQueryFilter(function (&$category) use ($self) {
            if (empty($category))
                return null;
            $categoryID = intval($category['ID']);
            $category['ID'] = $categoryID;
            $category['ParentID'] = is_null($category['ParentID']) ? null : intval($category['ParentID']);
            $category['_isRemoved'] = $category['Status'] === 'REMOVED';
            $category['_location'] = $this->getCategoryLocationByCategoryID($categoryID);
            if (!empty($category['Image'])) {
                $category['Image'] = array(
                    'name' => $category['Image'],
                    'normal' => '/' . Path::getUploadDirectory() . $this->getCategoryUploadInnerImagePath($category['Image']),
                    'md' => '/' . Path::getUploadDirectory() . $this->getCategoryUploadInnerImagePath($category['Image'], 'md'),
                    'sm' => '/' . Path::getUploadDirectory() . $this->getCategoryUploadInnerImagePath($category['Image'], 'sm'),
                    'xs' => '/' . Path::getUploadDirectory() . $this->getCategoryUploadInnerImagePath($category['Image'], 'xs'),
                    'micro' => '/' . Path::getUploadDirectory() . $this->getCategoryUploadInnerImagePath($category['Image'], 'micro')
                );
            }
            // add sub categoires IDs
            $category['SubIDs'] = array();
            // if (!empty($category['childNodes'])) {
            //     $category['SubIDs'] += array_keys($category['childNodes']);
            // }
            return $category;
        }, 'shopCategories');

        dbQuery::setQueryFilter(function (&$promo) {
            if (empty($promo))
                return null;
            $promo['ID'] = intval($promo['ID']);
            $promo['Discount'] = floatval($promo['Discount']);
            $promo['_isExpired'] = strtotime(dbquery::getDate()) > strtotime($promo['DateExpire']);
            $promo['_isFuture'] = strtotime(dbquery::getDate()) < strtotime($promo['DateStart']);
            $promo['_isActive'] = !$promo['_isExpired'] && !$promo['_isFuture'];
        }, 'shopPromo');

        dbQuery::setQueryFilter(function (&$origin) {
            if (empty($origin))
                return null;
            $origin['ID'] = intval($origin['ID']);
            $origin['_isRemoved'] = $origin['Status'] === 'REMOVED';
        }, 'shopOrigins');

        dbQuery::setQueryFilter(function (&$agency) {
            if (empty($agency))
                return null;
            $agency['ID'] = intval($agency['ID']);
            $agency['_isRemoved'] = $agency['Status'] === 'REMOVED';
            $agency['_isActive'] = $agency['Status'] === 'ACTIVE';
        }, 'shopDeliveryAgencies');


        dbQuery::setQueryFilter(function (&$order) use ($self) {
            global $app;
            // echo "__attachOrderDetails";
            if (empty($order))
                return null;

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
                        $order['user'] = API::getAPI('system:users')->getUserByID($order['UserID']);
                    unset($order['UserID']);
                    unset($order['UserAddressesID']);
                }
                // get promo
                if (!empty($order['PromoID']))
                    // $order['promo'] = API::getAPI('shop:promos')->getPromoByID($order['PromoID']);
                    $order['promo'] = $self->fetchPromoByID($order['PromoID']);
                if (!empty($order['DeliveryID']))
                    // $order['delivery'] = API::getAPI('shop:delivery')->getDeliveryAgencyByID($order['DeliveryID']);
                    $order['delivery'] = $self->fetchDeliveryAgencyByID($order['PromoID']);
                // $order['items'] = array();
                $boughts = $this->data->shopGetOrderBoughts($orderID);
                // $boughts = $app->getDB()->query($configBoughts) ?: array();
                if (!empty($boughts))
                    foreach ($boughts as $soldItem) {
                        // $product = $this->data->fetchSingleProductByID($soldItem['ProductID']);
                        $product = $self->fetchSingleProductByID($soldItem['ProductID']);
                        
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
                    $product = $this->data->fetchSingleProductByID($purchasingProduct['ID']);
                    if (!empty($product)) {
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
                    } else {
                        
                    }
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
                $info["deliveries"] = API::getAPI('shop:delivery')->getActiveDeliveryArray();
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
        }, 'shopOrders');


    }

    public function getProductUploadInnerDir ($productID, $subDir = '') {
        $apiCustomer = API::getAPI('system:customers');
        $customer = $apiCustomer->getRuntimeCustomer();
        $path = '';
        if (empty($subDir))
            $path = Path::createDirPath($customer['HostName'], 'shop', 'products', $productID);
        else
            $path = Path::createDirPath($customer['HostName'], 'shop', 'products', $productID, $subDir);
        return $path;
    }
    public function getProductUploadInnerImagePath ($name, $productID, $subDir = false) {
        $path = $this->getProductUploadInnerDir($productID, $subDir);
        return $path . $name;
    }

    public function getCategoryUploadInnerDir ($subDir = '') {
        $apiCustomer = API::getAPI('system:customers');
        $customer = $apiCustomer->getRuntimeCustomer();
        $path = '';
        if (empty($subDir))
            $path = Path::createDirPath($customer['HostName'], 'shop', 'categories');
        else
            $path = Path::createDirPath($customer['HostName'], 'shop', 'categories', $subDir);
        return $path;
    }
    public function getCategoryUploadInnerImagePath ($name, $subDir = false) {
        $path = $this->getCategoryUploadInnerDir($subDir);
        return $path . $name;
    }

    public function getProductStatuses () {
        return array('ACTIVE','ARCHIVED','DISCOUNT','DEFECT','WAITING','PREORDER');
    }
    public function getProductStatusesWhenAvailable () {
        return array("ACTIVE", "DISCOUNT", "PREORDER", "DEFECT");
    }
    public function getProductStatusesWhenDisabled () {
        return array("ARCHIVED", "REMOVED");
    }
    public function getProductBannerTypes () {
        return array('BANNER_LARGE','BANNER_MEDIUM','BANNER_SMALL','BANNER_MICRO');;
    }

    // private static function __adjustProduct (&$product, $skipRelations = true) {

    // }

    public function fetchProductImages ($productID) {
        $data = $this->fetchProductAttributesArray($productID, 'IMAGE');
        // global $app;
        $images = array();
        // $config = self::fetchProductAttributesArray($productID, 'IMAGE');
        // $data = dbquery::query($config);
        // var_dump($data);
        if (!empty($data)) {
            foreach ($data as $item) {
                if (!empty($item['Value'])) {
                    $images[] = array(
                        'name' => $item['Value'],
                        'normal' => '/' . Path::getUploadDirectory() . $this->getProductUploadInnerImagePath($item['Value'], $productID),
                        'md' => '/' . Path::getUploadDirectory() . $this->getProductUploadInnerImagePath($item['Value'], $productID, 'md'),
                        'sm' => '/' . Path::getUploadDirectory() . $this->getProductUploadInnerImagePath($item['Value'], $productID, 'sm'),
                        'xs' => '/' . Path::getUploadDirectory() . $this->getProductUploadInnerImagePath($item['Value'], $productID, 'xs'),
                        'micro' => '/' . Path::getUploadDirectory() . $this->getProductUploadInnerImagePath($item['Value'], $productID, 'micro')
                    );
                }
            }
        }
        return $images;
    }

    public function fetchProductBanners ($productID) {
        $data = $this->fetchProductAttributesArray($productID, $this->getProductBannerTypes());
        // global $app;
        $banners = array();
        // $config = self::fetchProductAttributesArray($productID, self::getProductBannerTypes());
        // $data = dbquery::query($config);
        if (!empty($data)) {
            foreach ($data as $item) {
                if (!empty($item['Value'])) {
                    $banners[$item['Attribute']] = array(
                        'name' => $item['Value'],
                        'banner' => '/' . Path::getUploadDirectory() . $this->getProductUploadInnerImagePath($item['Value'], $productID)
                    );
                }
            }
        }
        // get banner texts
        // $config = self::fetchProductAttributesArray($productID, array('BANNER_TEXT_LINE1', 'BANNER_TEXT_LINE2'));
        // $config['options']['asDict'] = 'Attribute';
        // $data = dbquery::query($config);
        // // var_dump($data);
        // foreach ($banners as $type => $value) {
        //     if (isset($data['BANNER_TEXT_LINE1'])) {
        //         $banners[$type]['text1'] = $data['BANNER_TEXT_LINE1'];
        //     }
        //     if (isset($data['BANNER_TEXT_LINE2'])) {
        //         $banners[$type]['text2'] = $data['BANNER_TEXT_LINE2'];
        //     }
        // }
        return $banners;
    }

    public function fetchProductVideos ($productID) {
        $data = $this->fetchProductAttributesArray($productID, 'VIDEO');
        $videos = array();
        if (!empty($data)) {
            foreach ($data as $item) {
                $videos[] = $item['Value'];
            }
        }
        return $videos;
        // global $app;
        // $videos = array();
        // $config = self::fetchProductAttributesArray($productID, 'VIDEO');
        // $data = dbquery::query($config);
        // if (!empty($data)) {
        //     foreach ($data as $item) {
        //         $videos[] = $item['Value'];
        //     }
        // }
        // return $videos;
    }

    public function fetchProductAttributes ($productID) {
        $data = $this->fetchProductAttributesArray($productID)
        $attr = array();
        if (!empty($data)) {
            foreach ($data as $item) {
                if ($item['Attribute'] === 'IMAGE' || $item['Attribute'] === 'VIDEO') {
                    continue;
                }
                $attr[$item['Attribute']] = $item['Value'];
            }
        }
        return $featuresGroups;
        // global $app;
        // $attr = array();
        // $config = self::fetchProductAttributesArray($productID);
        // $data = dbquery::query($config);
        // if (!empty($data)) {
        //     foreach ($data as $item) {
        //         if ($item['Attribute'] === 'IMAGE' || $item['Attribute'] === 'VIDEO') {
        //             continue;
        //         }
        //         $attr[$item['Attribute']] = $item['Value'];
        //     }
        // }
        // return $attr;
    }

    public function fetchProductFeaturesArray ($productID) {
        $data = dbquery::shopFeatures()
            ->setAllFields()
            ->setCondition('ProductID', $productID)
            ->selectAsArray();
        $featuresGroups = array();
        if (!empty($data)) {
            foreach ($data as $value) {
                if (!isset($featuresGroups[$value['GroupName']])) {
                    $featuresGroups[$value['GroupName']] = array();
                }
                $featuresGroups[$value['GroupName']][$value['ID']] = $value['FieldName'];
            }
        }
        return $featuresGroups;
        // global $app;
        // $featuresGroups = array();
        // $config = self::dbqProductFeatures($productID);
        // $data = dbquery::query($config);
        // if (!empty($data)) {
        //     foreach ($data as $value) {
        //         if (!isset($featuresGroups[$value['GroupName']])) {
        //             $featuresGroups[$value['GroupName']] = array();
        //         }
        //         $featuresGroups[$value['GroupName']][$value['ID']] = $value['FieldName'];
        //     }
        // }
        // return $featuresGroups;
    }

    public function fetchProductPrice ($productID) {
        $data = dbquery::shopProductPrices()
            ->setAllFields()
            ->setCondition('ProductID', $productID)
            ->selectSingleItem();
        $price = null;
        if (isset($data['Price'])) {
            $price = floatval($data['Price']);
        }
        return $price;
        // global $app;
        // $price = null;
        // $config = self::dbqProductPrice($productID);
        // $data = dbquery::query($config);
        // if (isset($data['Price'])) {
        //     $price = floatval($data['Price']);
        // }
        // return $price;
    }

    public function fetchProductPriceHistory ($productID) {
        $data = dbquery::shopProductPrices()
            ->setFields("ID", "ProductID", "Price", "DateCreated")
            ->ordering('shop_productPrices.DateCreated')
            ->setCondition('ProductID', $productID)
            ->selectAsArray();
        $prices = array();
        foreach ($data as $item) {
            $prices[] = array($item['DateCreated'], floatval($item['Price']));
        }
        return $prices;
        // return dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_productPrices",
        //     "condition" => array(
        //         "ProductID" => dbquery::createCondition($id)
        //     ),
        //     "fields" => array("ID", "ProductID", "Price", "DateCreated"),
        //     "offset" => 0,
        //     "limit" => 50,
        //     "order" => dbquery::createSortOrder('shop_productPrices.DateCreated'),
        //     "options" => array()
        // ));



        // global $app;
        // $prices = array();
        // $config = self::dbqProductPriceStats($productID);
        // $data = dbquery::query($config);
        // if (!empty($data)) {
        //     foreach ($data as $item) {
        //         $prices[] = array($item['DateCreated'], floatval($item['Price']));
        //     }
        // }
        // return $prices;
    }

    public function fetchProductRelationsArray ($productID) {
        $ProductB_ID_Array = dbQuery::shopRelations()
            ->setFields('ProductB_ID')
            ->setCondition('ProductA_ID', $productID)
            ->selectAsArray();
        return dbQuery::shopProducts()
            ->setAllFields()
            ->setCondition('ID', $ProductB_ID_Array)
            ->selectAsArray();
        // global $app;
        // $relations = array();
        // $configProductsRelations = self::dbqProductRelations($productID);
        // $relatedItemsIDs = dbquery::query($configProductsRelations);
        // if (isset($relatedItemsIDs)) {
        //     foreach ($relatedItemsIDs as $relationItem) {
        //         $relatedProductID = intval($relationItem['ProductB_ID']);
        //         if ($relatedProductID === $productID)
        //             continue;
        //         $relatedProduct = self::fetchSingleProductByID($relatedProductID);
        //         if (isset($relatedProduct))
        //             $relations[] = $relatedProduct;
        //     }
        // }
        // return $relations;
    }

    public function fetchSingleProductByID ($productID) {
        return dbQuery::shopProducts()
            ->setAllFields()
            ->setCondition('ID', $productID)
            ->selectSingleItem();
        // global $app;
        // if (empty($productID) || !is_numeric($productID))
        //     return null;
        // // $config = self::dbqProduct($productID);
        // // $product = dbquery::query($config);
        // self::dbqProduct($productID)->query();
        // if (empty($product))
        //     return null;
        // return self::__adjustProduct($product, false);
    }

    public function fetchSingleProductByExternalKey ($eKey) {
        return dbQuery::shopProducts()
            ->setAllFields()
            ->setCondition('ExternalKey', $eKey)
            ->selectSingleItem();
        // global $app;
        // if (empty($eKey) || !is_string($eKey))
        //     return null;
        // $config = self::dbqProductByExternalKey($eKey);
        // $product = dbquery::query($config);
        // if (empty($product))
        //     return null;
        // return self::__adjustProduct($product);
    }

    public function fetchSingleProductByName ($name) {
        return dbQuery::shopProducts()
            ->setAllFields()
            ->setCondition('Name', $name)
            ->selectSingleItem();
        // global $app;
        // if (empty($name) || !is_string($name))
        //     return null;
        // $config = self::dbqProductByName($name);
        // $product = dbquery::query($config);
        // if (empty($product))
        //     return null;
        // return self::__adjustProduct($product);
    }

    public function fetchSingleProductByModel ($modelName) {
        return dbQuery::shopProducts()
            ->setAllFields()
            ->setCondition('Model', $name)
            ->selectSingleItem();
        // global $app;
        // if (empty($modelName) || !is_string($modelName))
        //     return null;
        // $config = self::dbqProductByModel($modelName);
        // $product = dbquery::query($config);
        // if (empty($product))
        //     return null;
        // return self::__adjustProduct($product);
    }

    public function fetchSingleProductByModelAndOrigin ($modelName, $originName) {
        return dbQuery::shopProductsWithOrigin()
            ->setAllFields()
            ->setCondition('Model', $modelName)
            ->addCondition('OriginName', $originName)
            ->selectSingleItem();
            // 'additional' => array(
            //     "shop_origins" => array(
            //         "constraint" => array("shop_products.OriginID", "=", "shop_origins.ID"),
            //         "fields" => array("@shop_origins.Name AS OriginName")
            //     )
            // ),
        // global $app;
        // if (empty($modelName) || !is_string($modelName))
        //     return null;
        // if (empty($originName) || !is_string($originName))
        //     return null;
        // $config = self::dbqProductByModelAndOrigin($modelName, $originName);
        // $product = dbquery::query($config);
        // if (empty($product))
        //     return null;
        // return self::__adjustProduct($product);
    }

    public function fetchSingleProductShortInfo ($productID) {
        return dbQuery::shopProducts()
            ->setFields("Name", "Model")
            ->setCondition('ID', $productID)
            ->selectSingleItem();
        // global $app;
        // if (empty($productID) || !is_numeric($productID))
        //     return null;
        // $config = self::dbqProductShortInfo();
        // return dbquery::query($config);
    }

    public function fetchProductsDataList (array $options = array()) {
        $q = dbQuery::shopProductsWithCatalog()
            ->setAllFields()
            ->setCondition('ID', $id)
            ->groupBy('ID')
            ->orderingExpr('shop_products.DateUpdated DESC, shop_products.Status') // default sorting
            ->addParams($options);

        if (!empty($options['_pSearch'])) {
            $search = $options['_pSearch'];
            if (is_string($search)) {
                $q->addCondition('Name', dbquery::getLike($search));
                // $config['condition']["shop_products.Name"] = dbquery::createCondition('%' . $search . '%');
                // $config['condition']["Model"] = dbquery::createCondition('%' . $options['search'] . '%', 'like');
                // $config['condition']["SKU"] = dbquery::createCondition('%' . $options['search'] . '%', 'like');
            } elseif (is_array($search)) {
                foreach ($search as $value) {
                    $chunks = explode('=', $value);
                    // var_dump($chunks);
                    if (count($chunks) === 2) {
                        $keyToSearch = strtolower($chunks[0]);
                        $valToSearch = $chunks[1];
                        $conditionField = '';
                        // $conditionOp = '=';
                        switch ($keyToSearch) {
                            case 'id':
                                $conditionField = "ID";
                                $valToSearch = intval($valToSearch);
                                break;
                            case 'n':
                                $conditionField = "Name";
                                $valToSearch = dbquery::getLike($valToSearch);
                                // $conditionOp = 'like';
                                break;
                            case 'd':
                                $conditionField = "Description";
                                $valToSearch = dbquery::getLike($valToSearch);
                                // $conditionOp = 'like';
                                break;
                            case 'm':
                                $conditionField = "Model";
                                $valToSearch = dbquery::getLike($valToSearch);
                                // $conditionOp = 'like';
                                break;
                            case 'o':
                                $conditionField = "shop_origins.Name";
                                $valToSearch = dbquery::getLike($valToSearch);
                                // $conditionOp = 'like';
                                break;
                            case 'cat':
                                $conditionField = "shop_categories.Name";
                                $valToSearch = dbquery::getLike($valToSearch);
                                // $conditionOp = 'like';
                                break;
                        }
                        // var_dump($conditionField);
                        // var_dump($valToSearch);
                        // var_dump($conditionOp);
                        if (!empty($conditionField)) {
                            $q->addCondition($conditionField, $valToSearch);
                            // $config['condition'][$conditionField] = dbquery::createCondition($valToSearch);
                        }
                    }
                    // $config['condition']["Name"] = dbquery::createCondition('%' . $value . '%', 'like');
                    // $config['condition']["shop_products.Model"] = dbquery::createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["shop_products.Description"] = dbquery::createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["shop_products.SKU"] = dbquery::createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["Model"] = dbquery::createCondition('%' . $value . '%', 'like');
                    // $config['condition']["SKU"] = dbquery::createCondition('%' . $value . '%', 'like');
                }
            }
        }

        if (!empty($options['_pSearchText'])) {
            if (strlen($options['_pSearchText']) < 5) {
                return null;
            }
            $q->addCondition('SearchText', dbquery::getLike($options['_pSearchText']));
            // $config['condition']["SearchText"] = dbquery::createCondition('%' . strtolower($options['_pSearchText']) . '%');
        }

        if (empty($options['_pStatus'])) {
            $q->addCondition('Status', 'REMOVED', '!=');
            // $config['condition']["Status"] = dbquery::createCondition('REMOVED', '!=');
        } else {
            $q->addCondition('Status', $options['_pStatus']);
            // $config['condition']["Status"] = dbquery::createCondition($options['_pStatus']);
        }

        if (!empty($options['_pCategoryExternalKey'])) {
            $q->addCondition('ExternalKey', $options['_pCategoryExternalKey']);
            // $config['condition']["shop_categories.ExternalKey"] = dbquery::createCondition($options['_pCategoryExternalKey']);
        }

        return $q->selectAsDataList();
        // global $app;
        // $config = self::dbqProducts_MatchedIDs($options);
        // $list = dbquery::queryMatchedIDs($config);

        // // var_dump($list);
        // $productIDs = $list['ids'];

        // $config = self::dbqProducts($productIDs);
        // $products = dbquery::query($config);
        // if (empty($products))
        //     return array();
        // foreach ($products as $key => $product) {
        //     $products[$key] = self::__adjustProduct($product);
        // }
        // $list['items'] = $products;
        // return $list;
    }

    public function fetchNewProducts_List (array $options = array()) {
        return $this->fetchProductsDataList($options + array(
                'sortorder' => dbquery::shopProducts()->genDescSortOrderStr('DateCreated')
            ));
        // global $app;

        // $userListOptions = Utils::getIfIssetOrDefault($options, 'list', array());
        // $callbacks = Utils::getIfIssetOrDefault($options, 'callbacks', array());
        // $listOptions = array();

        // user-available params
        // $options['sort'] = Utils::getIfIssetOrDefault($options, 'sort', '-shop_products.DateUpdated');
        // $listOptions['order'] = Utils::getIfIssetOrDefault($userListOptions, 'order', 'DESC');

        // hardcoded params
        // $options['sort'] = '-shop_products.DateCreated';
        // $listOptions['order'] = 'DESC';
        // $listOptions['_fshop_products.Status'] = join(',', self::getProductStatusesWhenAvailable()) . ':IN';
        // $options['_fIsFeatured'] = true;

        // return self::fetchProductsDataList($options);
        // $config = self::dbqProducts_MatchedIDs($listOptions);
        // if (empty($config))
        //     return null;
        // $dataList = dbquery::queryMatchedIDs($config, $listOptions, $callbacks);

        // return $dataList;
    }

    public function fetchOnSaleProducts_List (array $options = array()) {
        $listParams = array();
        $listParams[dbquery::shopProducts()->genFieldQueryParamStr('Price')] = 'PrevPrice:>';
        $listParams[dbquery::shopProducts()->genFieldQueryParamStr('Status')] = 'DISCOUNT';
        $listParams['sortorder'] = dbquery::shopProducts()->genDescSortOrderStr('DateCreated');
        return $this->fetchProductsDataList($options + $listParams);
        // global $app;

        // $userListOptions = Utils::getIfIssetOrDefault($options, 'list', array());
        // $callbacks = Utils::getIfIssetOrDefault($options, 'callbacks', array());
        // $listOptions = array();

        // // user-available params
        // $listOptions['sort'] = Utils::getIfIssetOrDefault($userListOptions, 'sort', 'shop_products.DateUpdated');
        // $listOptions['order'] = Utils::getIfIssetOrDefault($userListOptions, 'order', 'DESC');

        // $options['sort'] = Utils::getIfIssetOrDefault($options, 'sort', '-shop_products.DateUpdated');
        // // hardcoded params
        // // $listOptions['sort'] = 'shop_products.DateUpdated';
        // // $listOptions['order'] = 'DESC';
        // $options['_fshop_products.Price'] = '';
        // $options['_fshop_products.Status'] = '';

        // return self::fetchProductsDataList($options);
    }

    public function fetchFeaturedProducts_List (array $options = array()) {
        $listParams = array();
        $listParams[dbquery::shopProducts()->genFieldQueryParamStr('IsFeatured')] = true;
        $listParams[dbquery::shopProducts()->genFieldQueryParamStr('Status')] = $this->getProductStatusesWhenAvailable();
        $listParams['sortorder'] = dbquery::shopProducts()->genDescSortOrderStr('DateCreated');
        return $this->fetchProductsDataList($options + $listParams);
        // global $app;

        // $userListOptions = Utils::getIfIssetOrDefault($options, 'list', array());
        // $callbacks = Utils::getIfIssetOrDefault($options, 'callbacks', array());
        // $listOptions = array();

        // // user-available params
        // $listOptions['sort'] = Utils::getIfIssetOrDefault($userListOptions, 'sort', 'shop_products.DateUpdated');
        // $listOptions['order'] = Utils::getIfIssetOrDefault($userListOptions, 'order', 'DESC');

        // $options['sort'] = Utils::getIfIssetOrDefault($options, 'sort', '-shop_products.DateUpdated');
        // hardcoded params
        // $options['sort'] = 'shop_products.DateUpdated';
        // $options['order'] = 'DESC';
        // $options['_fshop_products.Status'] = join(',', self::getProductStatusesWhenAvailable()) . ':IN';
        // $options['_fIsFeatured'] = true;

        // return self::fetchProductsDataList($options);
    }

    public function productExistsByID ($productID) {
        $product = $this->fetchSingleProductShortInfo($productID);
        // global $app;
        // $config = self::dbqCheckProductExistenceByID($productID);
        // $product = dbquery::query($config);
        return intval($product['ID']);
    }
    public function productExistsByExternalKey ($eKey) {
        $product = $this->fetchSingleProductByExternalKey($eKey);
        // global $app;
        // $config = self::dbqCheckProductExistenceByExternalKey($productID);
        // $product = dbquery::query($config);
        return intval($product['ID']);
    }
    public function productExistsByModelAndOrigin ($modelName, $originName) {
        $product = $this->fetchSingleProductByModelAndOrigin($modelName, $originName);
        // global $app;
        // $config = self::dbqCheckProductExistenceByModelAndOrigin($productID);
        // $product = dbquery::query($config);
        return intval($product['ID']);
    }

    public function archiveProduct ($productID) {
        $r = new result();
        try {
            $data = array();
            $data['Status'] = 'ARCHIVED';
            $this->db->beginTransaction();
            dbQuery::shopProducts()
                ->setCondition('ID', $productID)
                ->setData($data)
                ->addStandardDateUpdatedField()
                ->update();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // if (empty($productID) || !is_numeric($productID))
        //     return null;
        // $config = dbquery::createOrGetQuery(array(
        //     "source" => "shop_products",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => dbquery::createCondition($productID),
        //         "Status" => dbquery::createCondition("REMOVED", "!="),
        //     ),
        //     "data" => array(
        //         "Status" => 'ARCHIVED',
        //         "DateUpdated" => dbquery::getDate()
        //     ),
        //     "options" => null
        // ));
        // return dbquery::query($config);
    }

    public function archiveAllProducts () {
        $r = new result();
        try {
            $data = array();
            $data['Status'] = 'ARCHIVED';
            $this->db->beginTransaction();
            dbQuery::shopProducts()
                ->clearConditions()
                ->setData($data)
                ->addStandardDateUpdatedField()
                ->update();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $config = dbquery::createOrGetQuery(array(
        //     "source" => "shop_products",
        //     "action" => "update",
        //     "condition" => array(
        //         "Status" => dbquery::createCondition("REMOVED", "!="),
        //     ),
        //     "data" => array(
        //         "Status" => 'ARCHIVED',
        //         "DateUpdated" => dbquery::getDate()
        //     ),
        //     "options" => null
        // ));
        // return dbquery::query($config);
    }

    public function setProductAsRemovedByModelAndOrigin ($modelName, $originName) {
        $r = new result();
        try {
            $data = array();
            $data['Status'] = 'REMOVED';
            $this->db->beginTransaction();
            dbQuery::shopProductsWithOrigin()
                ->setCondition('Model', $modelName)
                ->setCondition('OriginName', $originName)
                ->setData($data)
                ->addStandardDateUpdatedField()
                ->update();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data = array(
        //     'shop_products.Status' => 'REMOVED',
        //     "shop_products.DateUpdated" => dbquery::getDate()
        // );
        // $config = dbquery::createOrGetQuery(array(
        //     "source" => "shop_products",
        //     "action" => "update",
        //     "condition" => array(
        //         "Model" => dbquery::createCondition($modelName, 'like'),
        //         "shop_origins.Name" => dbquery::createCondition($originName),
        //     ),
        //     'additional' => array(
        //         "shop_origins" => array(
        //             "constraint" => array("shop_products.OriginID", "=", "shop_origins.ID"),
        //             "fields" => array("@shop_origins.Name AS OriginName")
        //         )
        //     ),
        //     "data" => $data,
        //     "options" => null
        // ));
        // return dbquery::query($config);
    }

    public function setProductAsRemovedByID ($productID) {
        return $this->updateProduct($productID, array(
                'Status' => 'REMOVED'
            ));
        // global $app;
        // $data = array(
        //     'shop_products.Status' => 'REMOVED',
        //     "shop_products.DateUpdated" => dbquery::getDate()
        // );
        // $config = dbquery::createOrGetQuery(array(
        //     "source" => "shop_products",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => dbquery::createCondition($productID)
        //     ),
        //     'additional' => array(),
        //     "data" => $data,
        //     "options" => null
        // ));
        // return dbquery::query($config);
    }

    public function updateProductSearchTextByID ($productID, $originID = null) {
        $r = new result();
        try {
            $data = array();
            $data["SearchText"] = "@LOWER(CONCAT_WS(' ', shop_products.Name, shop_origins.Name, shop_products.Model))";
            $this->db->beginTransaction();
            dbQuery::shopProductsWithOrigin()
                ->clearConditions()
                ->addConditionByFlag(!is_null($productID), 'ID', $productID)
                ->addConditionByFlag(is_null($productID) && !is_null($originID), 'OriginID', $originID)
                ->setData($data)
                ->addStandardDateUpdatedField()
                // ->clearJoins()
                // ->addJoinByFlag(!is_null($originID), 'shop_origins'
                //     'shop_products.OriginID=shop_origins.ID', array('ID'))
                ->update();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;

        // global $app;
        // $data["shop_products.DateUpdated"] = dbquery::getDate();
        // $data["SearchText"] = "@LOWER(CONCAT_WS(' ', shop_products.Name, shop_origins.Name, shop_products.Model))";

        // $config = dbquery::createOrGetQuery(array(
        //     "source" => "shop_products",
        //     "action" => "update",
        //     "data" => $data,
        //     "options" => null
        // ));
        // $config['additional'] = array(
        //     "shop_origins" => array(
        //         "constraint" => array("shop_products.OriginID", "=", "shop_origins.ID"),
        //         "fields" => array('ID')
        //     )
        // );
        // if (isset($productID)) {
        //     $config['condition']["shop_products.ID"] = dbquery::createCondition($productID);
        // }
        // if (isset($originID)) {
        //     $config['condition']["shop_products.OriginID"] = dbquery::createCondition($originID);
        // }
        // // var_dump($config);
        // return $config;
        // global $app;
        // $config = self::dbqUpdateProductSearchText($productID, null);
        // return dbquery::query($config);
    }

    public function updateProductSearchTextByOriginID ($originID) {
        return $this->updateProductSearchTextByID(null, $originID);
        // global $app;
        // $config = self::dbqUpdateProductSearchText(null, $originID);
        // return dbquery::query($config);
    }

    // ------- QUERY CONFIGURATIONS ---------


    // products >>>>>
    private static function dbqProduct ($productID) {
        global $app;


        // $dbq = new dbquery('shopProduct');
        // $dbq->setSource('shop_products')
        //     ->addCondition('ID', $productID)
        //     ->addCondition('Status', self::getProductStatuses())
        //     ->setFields("ID", "CategoryID", "OriginID", "ExternalKey", "Name", "Synopsis",
        //         "Description", "Model", "SKU", "Price", "PrevPrice",
        //         "IsPromo", "IsFeatured", "IsOffer", "ShowBanner", "Status", "SearchText",
        //         "DateUpdated", "DateCreated")
        //     ->willBeSingleRow();


        // $isMultiple = is_array($productID);
        // $limit = $isMultiple ? count($productID) : 1;

        // $config = dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_products",
        //     "fields" => array("ID", "CategoryID", "OriginID", "ExternalKey", "Name", "Synopsis",
        //         "Description", "Model", "SKU", "Price", "PrevPrice",
        //         "IsPromo", "IsFeatured", "IsOffer", "ShowBanner", "Status", "SearchText",
        //         "DateUpdated", "DateCreated"),
        //     "offset" => 0,
        //     "limit" => $limit,
        //     "options" => array(
        //         "expandSingleRecord" => !$isMultiple
        //     )
        // ));

        // if (!is_null($productID)) {
        //     $config["condition"] = array(
        //         "shop_products.ID" => dbquery::createCondition($productID)
        //     );
        // }

        // $config['condition']['Status'] = dbquery::createCondition(self::getProductStatuses());




        return $config;
    }
    private static function dbqProducts (array $productIDs) {
        if (empty($productIDs)) {
            throw new Exception("dbqProducts: empty ids", 1);
        }
        return self::dbqProduct($productIDs);
    }
    private static function dbqProductByName ($productName) {
        global $app;
        $config = self::dbqProduct();
        $config['condition']['Name'] = dbquery::createCondition($productName);
        // $config['condition']['Status'] = dbquery::createCondition(self::getProductStatuses(), 'IN');
        return $config;
    }
    private static function dbqProductByModel ($modelName) {
        global $app;
        $config = self::dbqProduct();
        $config['condition']['Model'] = dbquery::createCondition($modelName);
        // $config['condition']['Status'] = dbquery::createCondition(self::getProductStatuses(), 'IN');
        return $config;
    }
    private static function dbqProductByModelAndOrigin ($modelName, $originName) {
        global $app;
        $config = self::dbqProduct();
        $config['condition']['Model'] = dbquery::createCondition($modelName);
        $config['condition']['shop_origins.Name'] = dbquery::createCondition($originName);
        // $config['condition']['shop_products.Status'] = dbquery::createCondition(self::getProductStatuses(), 'IN');
        $config['additional'] = array(
            "shop_origins" => array(
                "constraint" => array("shop_origins.ID", "=", "shop_products.OriginID"),
                "fields" => array("Name")
            )
        );
        return $config;
    }
    private static function dbqProductByExternalKey ($externalKey) {
        global $app;
        $config = self::dbqProduct();
        // $config['fields'] = array("ID");
        $config['condition']["shop_products.ExternalKey"] = dbquery::createCondition($externalKey);
        // $config['condition']['shop_products.Status'] = dbquery::createCondition(self::getProductStatuses(), 'IN');
        // $config['additional'] = array();
        return $config;
    }
    private static function dbqCheckProductExistenceByID ($productID) {
        global $app;
        $config = self::dbqProduct($productID);
        $config['fields'] = array("ID");
        // $config['condition']['ID'] = dbquery::createCondition($productID);
        // $config['condition']['Status'] = dbquery::createCondition(self::getProductStatuses(), 'IN');
        $config['additional'] = array();
        return $config;
    }
    private static function dbqCheckProductExistenceByExternalKey ($eKey) {
        global $app;
        $config = self::dbqProductByExternalKey($eKey);
        $config['fields'] = array("ID");
        // $config['condition']['ID'] = dbquery::createCondition($productID);
        // $config['condition']['Status'] = dbquery::createCondition(self::getProductStatuses(), 'IN');
        $config['additional'] = array();
        return $config;
    }
    private static function dbqCheckProductExistenceByModelAndOrigin ($modelName, $originName) {
        global $app;
        $config = self::dbqProductByModelAndOrigin($modelName, $originName);
        $config['fields'] = array("ID");
        return $config;
    }
    private static function dbqProductShortInfo ($productID) {
        global $app;
        $config = self::dbqProduct($productID);
        $config["fields"] = array("Name", "Model");
        $config['additional'] = array(
            "shop_origins" => array(
                "constraint" => array("shop_products.OriginID", "=", "shop_origins.ID"),
                "fields" => array('@shop_origins.Name AS OriginName')
            )
        );
        return $config;
    }

    // TODO: optimmize list query
    public function dbqProducts_MatchedIDs (array $options = array()) {
        global $app;
        $config = self::dbqProduct();
        $config['condition'] = array();
        // $config["fields"] = array("ID");
        $config['limit'] = Utils::getIfIssetOrDefault($options, 'limit', 32);
        $config['group'] = 'shop_products.ID';
        $config['additional'] = array(
            "shop_categories" => array(
                "constraint" => array("shop_products.CategoryID", "=", "shop_categories.ID"),
                "fields" => array("@shop_categories.Status AS CategoryStatus", "@shop_categories.ExternalKey AS CategoryExternalKey")
            ),
            "shop_origins" => array(
                "constraint" => array("shop_products.OriginID", "=", "shop_origins.ID"),
                "fields" => array("@shop_origins.Status AS OriginStatus")
            ),
            "shop_productFeatures" => array(
                "constraint" => array("shop_products.ID", "=", "shop_productFeatures.ProductID"),
                "fields" => array("FeatureID")
            )
        );
        $config['order'] = array(
            "expr" => "shop_products.DateUpdated DESC, shop_products.Status"
        );
        // $confing['options'] = $confing['options'] :? array();
        $confing['options'] += $options;
        // unset($config['options']);

        // if (!empty($options['useFeatures'])) {
        //     unset($config['additional']['shop_productFeatures']);
        // }

        if (!empty($options['_pSearch'])) {
            if (is_string($options['_pSearch'])) {
                $config['condition']["shop_products.Name"] = dbquery::createCondition('%' . $options['_pSearch'] . '%');
                // $config['condition']["Model"] = dbquery::createCondition('%' . $options['search'] . '%', 'like');
                // $config['condition']["SKU"] = dbquery::createCondition('%' . $options['search'] . '%', 'like');
            } elseif (is_array($options['_pSearch'])) {
                foreach ($options['_pSearch'] as $value) {
                    $chunks = explode('=', $value);
                    // var_dump($chunks);
                    if (count($chunks) === 2) {
                        $keyToSearch = strtolower($chunks[0]);
                        $valToSearch = $chunks[1];
                        $conditionField = '';
                        // $conditionOp = '=';
                        switch ($keyToSearch) {
                            case 'id':
                                $conditionField = "shop_products.ID";
                                $valToSearch = intval($valToSearch);
                                break;
                            case 'n':
                                $conditionField = "shop_products.Name";
                                $valToSearch = '%' . $valToSearch . '%';
                                // $conditionOp = 'like';
                                break;
                            case 'd':
                                $conditionField = "shop_products.Description";
                                $valToSearch = '%' . $valToSearch . '%';
                                // $conditionOp = 'like';
                                break;
                            case 'm':
                                $conditionField = "shop_products.Model";
                                $valToSearch = '%' . $valToSearch . '%';
                                // $conditionOp = 'like';
                                break;
                            case 'o':
                                $conditionField = "shop_origins.Name";
                                $valToSearch = '%' . $valToSearch . '%';
                                // $conditionOp = 'like';
                                break;
                            case 'cat':
                                $conditionField = "shop_categories.Name";
                                $valToSearch = '%' . $valToSearch . '%';
                                // $conditionOp = 'like';
                                break;
                        }
                        // var_dump($conditionField);
                        // var_dump($valToSearch);
                        // var_dump($conditionOp);
                        if (!empty($conditionField)) {
                            $config['condition'][$conditionField] = dbquery::createCondition($valToSearch);
                        }
                    }
                    // $config['condition']["shop_products.Name"] = dbquery::createCondition('%' . $value . '%', 'like');
                    // $config['condition']["shop_products.Model"] = dbquery::createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["shop_products.Description"] = dbquery::createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["shop_products.SKU"] = dbquery::createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["Model"] = dbquery::createCondition('%' . $value . '%', 'like');
                    // $config['condition']["SKU"] = dbquery::createCondition('%' . $value . '%', 'like');
                }
            }
        }

        if (!empty($options['_pSearchText'])) {
            if (strlen($options['_pSearchText']) < 5) {
                return null;
            }
            $config['condition']["shop_products.SearchText"] = dbquery::createCondition('%' . strtolower($options['_pSearchText']) . '%');
        }

        if (empty($options['_pStatus'])) {
            $config['condition']["shop_products.Status"] = dbquery::createCondition('REMOVED', '!=');
        } else {
            $config['condition']["shop_products.Status"] = dbquery::createCondition($options['_pStatus']);
        }

        if (!empty($options['_pCategoryExternalKey'])) {
            $config['condition']["shop_categories.ExternalKey"] = dbquery::createCondition($options['_pCategoryExternalKey']);
        }

        // var_dump($config['condition']);
        return $config;
    }

    // public function dbqUpdateProductSearchText ($productID, $originID) {
    //     global $app;
    //     $data["shop_products.DateUpdated"] = dbquery::getDate();
    //     $data["SearchText"] = "@LOWER(CONCAT_WS(' ', shop_products.Name, shop_origins.Name, shop_products.Model))";

    //     $config = dbquery::createOrGetQuery(array(
    //         "source" => "shop_products",
    //         "action" => "update",
    //         "data" => $data,
    //         "options" => null
    //     ));
    //     $config['additional'] = array(
    //         "shop_origins" => array(
    //             "constraint" => array("shop_products.OriginID", "=", "shop_origins.ID"),
    //             "fields" => array('ID')
    //         )
    //     );
    //     if (isset($productID)) {
    //         $config['condition']["shop_products.ID"] = dbquery::createCondition($productID);
    //     }
    //     if (isset($originID)) {
    //         $config['condition']["shop_products.OriginID"] = dbquery::createCondition($originID);
    //     }
    //     // var_dump($config);
    //     return $config;
    // }

    public function updateProductExternalKeyByID ($productID, $eKey) {
        return $this->updateProduct($productID, array(
                'ExternalKey' => $eKey
            ));
        // global $app;
        // $data = array();
        // $data["DateUpdated"] = dbquery::getDate();
        // $data["ExternalKey"] = $ExternalKey;
        // $config = dbquery::createOrGetQuery(array(
        //     "source" => "shop_products",
        //     "condition" => array(
        //         "ID" => dbquery::createCondition($productID)
        //     ),
        //     "action" => "update",
        //     "data" => $data,
        //     "options" => null
        // ));
        // return $config;
    }

    public function createProduct ($data) {
        $r = new result();
        try {
            $data["Name"] = substr($data["Name"], 0, 300);
            $this->db->beginTransaction();
            $itemID = dbQuery::shopProducts()
                ->setData($data)
                ->addStandardDateFields()
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = dbquery::getDate();
        // $data["DateCreated"] = dbquery::getDate();
        // $data["Name"] = substr($data["Name"], 0, 300);
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_products",
        //     "action" => "insert",
        //     "data" => $data,
        //     "options" => null
        // ));
    }

    public function updateProduct ($productID, $data) {
        $r = new result();
        try {
            if (isset($data['Name'])) {
                $data["Name"] = substr($data["Name"], 0, 300);
            }
            $this->db->beginTransaction();
            dbQuery::shopProducts()
                ->setCondition('ID', $productID)
                ->setData($data)
                ->addStandardDateUpdatedField()
                ->update();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = dbquery::getDate();
        // if (isset($data['Name'])) {
        //     $data["Name"] = substr($data["Name"], 0, 300);
        // }
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_products",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => dbquery::createCondition($productID)
        //     ),
        //     "data" => $data,
        //     "options" => null
        // ));
    }


    // products >>>>>


    // Product category (catalog)
    public function getShopCatalogProductList ($ids) {
        // global $app;
        // $config = self::dbqProducts_MatchedIDs();
        // if (is_array($ids)) {
        //     if (count($ids) > 1)
        //         $config['condition']["shop_products.CategoryID"] = dbquery::createCondition($ids, "IN");
        //     else
        //         $config['condition']["shop_products.CategoryID"] = dbquery::createCondition($ids[0]);
        // } else {
        //     $config['condition']["shop_products.CategoryID"] = dbquery::createCondition($ids);
        // }
        // return $config;
    }

    public function getShopCategoryProductInfo () {
        $q = dbQuery::shopProductsWithCatalog()
            ->setAllFields()
            ->setCondition('ID', $id)
            ->groupBy('ID')
            ->orderingExpr('shop_products.DateUpdated DESC, shop_products.Status') // default sorting
            ->addParams($options);
        // $config = self::dbqProducts_MatchedIDs();
        // $config['fields'] = array("ID");
        // $config['limit'] = 0;
        // $config['group'] = null;
        // $config['options'] = null;
        // return $config;
        $q->selectAsArray();
    }

    // public function dbqProductPrice ($id) {
    //     global $app;
    //     return dbquery::createOrGetQuery(array(
    //         "action" => "select",
    //         "source" => "shop_products",
    //         "condition" => array(
    //             "ID" => dbquery::createCondition($id)
    //         ),
    //         "fields" => array("Price"),
    //         "offset" => 0,
    //         "limit" => 1,
    //         "options" => array(
    //             "expandSingleRecord" => true
    //         )
    //     ));
    // }

    // Product price stats >>>>>
    // public function dbqProductPriceStats ($id) {
    //     global $app;
    //     return dbquery::createOrGetQuery(array(
    //         "action" => "select",
    //         "source" => "shop_productPrices",
    //         "condition" => array(
    //             "ProductID" => dbquery::createCondition($id)
    //         ),
    //         "fields" => array("ID", "ProductID", "Price", "DateCreated"),
    //         "offset" => 0,
    //         "limit" => 50,
    //         "order" => dbquery::createSortOrder('shop_productPrices.DateCreated'),
    //         "options" => array()
    //     ));
    // }
    // <<<< Product price stats















    // Product relations >>>>>
    // public function dbqProductRelations ($id) {
    //     global $app;
    //     return dbquery::createOrGetQuery(array(
    //         "action" => "select",
    //         "source" => "shop_relations",
    //         "condition" => array(
    //             "ProductA_ID" => dbquery::createCondition($id)
    //         ),
    //         "fields" => array("ProductB_ID"),
    //         "offset" => 0,
    //         "limit" => 0
    //     ));
    // }
    public function deleteAllProductRelations ($id) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            dbQuery::shopRelations()
                ->setCondition('ProductA_ID', $id)
                ->delete();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // return dbquery::createOrGetQuery(array(
        //     "action" => "delete",
        //     "source" => "shop_relations",
        //     "condition" => array(
        //         "ProductA_ID" => dbquery::createCondition($id)
        //     )
        // ));
    }
    public function addRelatedProduct ($customerID, $id, $relatedProductID) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::shopRelations()
                ->setData(array(
                    "CustomerID" => $customerID,
                    "ProductA_ID" => $id,
                    "ProductB_ID" => $relatedProductID
                ))
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // return dbquery::createOrGetQuery(array(
        //     "action" => "insert",
        //     "source" => "shop_relations",
        //     "data" => array(
        //         "CustomerID" => $customerID,
        //         "ProductA_ID" => $id,
        //         "ProductB_ID" => $relatedProductID
        //     )
        // ));
    }
    // <<<< Product relations













    // product features & attributes >>>>>
    public function dbqProductFeatures ($id) {
        // global $app;
        // return dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_productFeatures",
        //     "fields" => array("FeatureID"),
        //     'additional' => array(
        //         "shop_features" => array(
        //             "constraint" => array("shop_productFeatures.FeatureID", "=", "shop_features.ID"),
        //             "fields" => array("ID", "FieldName", "GroupName")
        //         )
        //     ),
        //     "condition" => array(
        //         "ProductID" => dbquery::createCondition($id)
        //     ),
        //     "limit" => 0,
        //     "options" => array()
        // ));
    }

    public function fetchProductAttributesArray ($id = null, $type = null) {
        return dbquery::shopProductAttributes()
            ->setFields("ProductID", "Attribute", "Value")
            ->clearConditions()
            ->addConditionByFlag(!empty($id), 'ProductID', $id)
            ->addConditionByFlag(!empty($type), 'Attribute', $type)
            ->selectAsArray(100);

        // if (!empty($id)) {
        //     $q->addCondition('ProductID', $id);
        //     // $config['condition']['ProductID'] = dbquery::createCondition($id);
        // }
        // if (!empty($type)) {
        //     $q->addCondition('Attribute', $type);
        //     // if (is_array($type)) {
        //     //     $config['condition']['Attribute'] = dbquery::createCondition($type, 'IN');
        //     // } else {
        //     //     $config['condition']['Attribute'] = dbquery::createCondition($type);
        //     // }
        // }
        // return $q->selectAsArray(100);
        // global $app;
        // $config = dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_productAttributes",
        //     "condition" => array(),
        //     "fields" => array("ProductID", "Attribute", "Value"),
        //     "offset" => 0,
        //     "limit" => 50,
        //     "options" => array(
        //         "expandSingleRecord" => false
        //     )
        // ));

        // if (!empty($id)) {
        //     $config['condition']['ProductID'] = dbquery::createCondition($id);
        // }
        // if (!empty($type)) {
        //     if (is_array($type)) {
        //         $config['condition']['Attribute'] = dbquery::createCondition($type, 'IN');
        //     } else {
        //         $config['condition']['Attribute'] = dbquery::createCondition($type);
        //     }
        // }

        // return $config;
    }

    public function createFeature ($data) {
        $r = new result();
        try {
            $data["FieldName"] = substr($data["FieldName"], 0, 200);
            $data["GroupName"] = substr($data["GroupName"], 0, 100);
            $this->db->beginTransaction();
            $itemID = dbQuery::shopFeatures()
                ->setData($data)
                ->addStandardDateFields()
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = dbquery::getDate();
        // $data["DateCreated"] = dbquery::getDate();
        // $data["FieldName"] = substr($data["FieldName"], 0, 200);
        // $data["GroupName"] = substr($data["GroupName"], 0, 100);
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_features",
        //     "action" => "insert",
        //     "data" => $data,
        //     "options" => null
        // ));
    }

    public function fetchFeaturesArray () {
        return dbQuery::shopFeatures()
            ->setFields("ID", "FieldName", "GroupName")
            ->selectSingleItem();
        // global $app;
        // return dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_features",
        //     "fields" => array("ID", "FieldName", "GroupName"),
        //     "limit" => 0,
        //     "options" => array()
        // ));
    }

    public function addFeatureToProduct ($data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::shopProductFeatures()
                ->setData($data)
                ->addStandardDateFields()
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = dbquery::getDate();
        // $data["DateCreated"] = dbquery::getDate();
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_productFeatures",
        //     "action" => "insert",
        //     "data" => $data,
        //     "options" => null
        // ));
    }

    public function addAttributeToProduct ($data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::shopProductAttributes()
                ->setData($data)
                ->addStandardDateCreatedField()
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateCreated"] = dbquery::getDate();
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_productAttributes",
        //     "action" => "insert",
        //     "data" => $data,
        //     "options" => null
        // ));
    }

    public function clearProductFeatures ($productID) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            dbQuery::shopProductFeatures()
                ->setCondition('ProductID', $productID)
                ->delete();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_productFeatures",
        //     "action" => "delete",
        //     "condition" => array(
        //         "ProductID" => dbquery::createCondition($productID)
        //     ),
        //     "options" => null
        // ));
    }

    public function clearProductAttributes ($productID, $attributeType = false) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            dbQuery::shopProductFeatures()
                ->setCondition('ProductID', $productID)
                ->addConditionByFlag(!empty($attributeType), 'Attribute', strtoupper($attributeType))
                ->delete();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $config = dbquery::createOrGetQuery(array(
        //     "source" => "shop_productAttributes",
        //     "action" => "delete",
        //     "condition" => array(
        //         "ProductID" => dbquery::createCondition($productID)
        //     ),
        //     "options" => null
        // ));
        // if (!empty($attributeType)) {
        //     $config['condition']['Attribute'] = dbquery::createCondition(strtoupper($attributeType));
        // }
        // return $config;
    }
    // <<<< product features & attributes








    // Catalog >>>>>
    public function fetchShopCatalogBrandsArray ($categoryID) {
        $brands = dbquery::shopCatalogBrands()
            ->setData($categoryID)
            ->callProcAsArray();
        foreach ($brands as $key => $brandItem) {
            $brands[$key]['ID'] = intval($brandItem['ID']);
        }
        return $brands;
        // global $app;
        // return dbquery::createOrGetQuery(array(
        //     "action" => "call",
        //     "procedure" => array(
        //         "name" => "getShopCatalogBrands",
        //         "parameters" => array($categoryID)
        //     )
        // ));
    }

    public function fetchCatalogPriceEdges ($categoryID) {
        $dataCategoryPriceEdges = dbquery::shopCatalogPriceEdges()
            ->setData($categoryID)
            ->callProcAsSingleItem();
        $dataCategoryPriceEdges['PriceMin'] = floatval($dataCategoryPriceEdges['PriceMin']);
        $dataCategoryPriceEdges['PriceMax'] = floatval($dataCategoryPriceEdges['PriceMax']);
        return $dataCategoryPriceEdges;
        // global $app;
        // return dbquery::shopCatalogPriceEdges(array(
        //     "action" => "call",
        //     "procedure" => array(
        //         "name" => "getShopCatalogPriceEdges",
        //         "parameters" => array($categoryID)
        //     ),
        //     "options" => array(
        //         "expandSingleRecord" => true
        //     )
        // ));
    }
    public function fetchCategoryLocation ($id) {
        $q = dbQuery::shopCatalogLocation()
            ->setAllFields()
            ->setCondition('Status', 'ACTIVE');
        if ($selectedCategoryID !== false) {
            $q->addCondition('ID', $selectedCategoryID);
        }
        $locations = $q->callProcAsArray();
        foreach ($locations as &$categoryItem) {
            $categoryItem['ID'] = intval($categoryItem['ID']);
        }
        return $locations;
        // global $app;
        // return dbquery::createOrGetQuery(array(
        //     "action" => "call",
        //     "procedure" => array(
        //         "name" => "getShopCatalogLocation",
        //         "parameters" => array($id)
        //     )
        // ));
    }
    public function fetchCatalogFeatures ($categotyIDs) {
        $productIDs = dbquery::shopProductsWithCatalog()
            ->setFields('ID')
            ->setCondition($this->source_categories . '.ID', $categoryIDs)
            ->selectAsArray();
        $features = array();
        $featureItems = $this->fetchProductFeaturesArray($productIDs);
        foreach ($featureItems as $featureGroup => $featureList) {
            // if (!isset($features[$featureGroup])) {
            //     $features[$featureGroup] = array();
            // }
            foreach ($featureList as $key => $featureName) {
                $features[$featureGroup][$key] = $featureName;
            }
        }
        return $features;
    }
    public function fetchCatalogProductDataListByCatalogFilter ($categoriesIDs, array &$filterUser, array &$filterDefault) {
        $q = dbquery::shopProductsWithCatalog()
            ->setAllFields()
            ->setLimit($filterDefault['filter_viewItemsOnPage'])
            ->ordering($filterDefault['filter_viewSortBy'])
            ->setCondition($this->source_categories . '.ID', $categoryIDs);

        $q->addCondition()

        // filter: display intems count
        if (!empty($filterUser['filter_viewItemsOnPage']))
            $q->setLimit($filterUser['filter_viewItemsOnPage']);
            // $dataConfigProducts['limit'] = $filterUser['filter_viewItemsOnPage'];
        else
            $filterUser['limit'] = $q->getLimit();
            // $filterUser['filter_viewItemsOnPage'] = $dataConfigProducts['limit'];

        if (!empty($filterUser['filter_viewPageNum']))
            // $dataConfigProducts['offset'] = ($filterUser['filter_viewPageNum'] - 1) * $dataConfigProducts['limit'];
        else
            // $filterUser['filter_viewPageNum'] = $filterDefault['filter_viewPageNum'];

        // filter: items sorting
        $_filterSorting = explode('_', strtolower($filterUser['filter_viewSortBy']));
        if (count($_filterSorting) === 2 && !empty($_filterSorting[0]) && ($_filterSorting[1] === 'asc' || $_filterSorting[1] === 'desc'))
            $dataConfigProducts['order'] = array('field' => $dataConfigProducts['source'] . '.' . ucfirst($_filterSorting[0]), 'ordering' => strtoupper($_filterSorting[1]));
        else
            $filterUser['filter_viewSortBy'] = null;

        // filter: price 
        if ($filterUser['filter_commonPriceMax'] > $filterUser['filter_commonPriceMin'] && $filterUser['filter_commonPriceMax'] <= $filterDefault['filter_commonPriceMax'])
            $dataConfigProducts['condition']['Price'][] = $app->getDB()->createCondition($filterUser['filter_commonPriceMax'], '<=');
        else
            $filterUser['filter_commonPriceMax'] = $filterDefault['filter_commonPriceMax'];

        if ($filterUser['filter_commonPriceMax'] > $filterUser['filter_commonPriceMin'] && $filterUser['filter_commonPriceMin'] >= $filterDefault['filter_commonPriceMin'])
            $dataConfigProducts['condition']['Price'][] = $app->getDB()->createCondition($filterUser['filter_commonPriceMin'], '>=');
        else
            $filterUser['filter_commonPriceMin'] = $filterDefault['filter_commonPriceMin'];

        if (count($filterUser['filter_commonFeatures']))
            $dataConfigProducts['condition']["FeatureID"] = $app->getDB()->createCondition($filterUser['filter_commonFeatures'], 'in');

        if (count($filterUser['filter_commonStatus']))
            $dataConfigProducts['condition']["shop_products.Status"] = $app->getDB()->createCondition($filterUser['filter_commonStatus'], 'in');
        else
            $dataConfigProducts['condition']["shop_products.Status"] = $app->getDB()->createCondition('REMOVED', '!=');

        // filter: brands
        if (count($filterUser['filter_categoryBrands']))
            $dataConfigProducts['condition']['OriginID'] = $app->getDB()->createCondition($filterUser['filter_categoryBrands'], 'in');

// adjust brands, categories and features
        $brands = array();
        $categories = array();
        $statuses = array();
        $features = array();

        foreach ($filterOptionsAvailable['filter_categoryBrands'] as $brand) {
            $dataConfigCategoryInfo = $this->data->getShopCategoryProductInfo();
            $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
            $arrValues = array($brand['ID']);
            if (!empty($filterOptionsApplied['filter_categoryBrands'])) {
                $arrValues = array_merge($filterOptionsApplied['filter_categoryBrands'], $arrValues);
            }
            $arrValues = array_unique($arrValues);
            // var_dump($arrValues);
            $dataConfigCategoryInfo['condition']['OriginID'] = $app->getDB()->createCondition($arrValues, 'IN');
            $filterData = $app->getDB()->query($dataConfigCategoryInfo);
            $count = $this->getUniqueProductsCount($filterData);
            $brands[$brand['ID']] = $brand;
            $brands[$brand['ID']]['ProductCount'] = $count;
            $brands[$brand['ID']]['Active'] = false;
            if (!empty($filterOptionsApplied['filter_categoryBrands'])) {
                $brands[$brand['ID']]['ProductCount'] -= $currentProductCount;
                $brands[$brand['ID']]['Active'] = true;
            }

            $dataConfigCategoryInfo['condition']['OriginID'] = $app->getDB()->createCondition($brand['ID']);
            $filterData = $app->getDB()->query($dataConfigCategoryInfo);
            $brands[$brand['ID']]['Total'] = $this->getUniqueProductsCount($filterData);
        }
        foreach ($filterOptionsAvailable['filter_categorySubCategories'] as $categoryID => $categoryItem) {
            $dataConfigCategoryInfo = $this->data->getShopCategoryProductInfo();
            $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
            $arrValues = array($categoryID) + $categoryItem['SubIDs'];
            if (!empty($filterOptionsApplied['filter_categorySubCategories'])) {
                $arrValues = array_merge($filterOptionsApplied['filter_categorySubCategories'], $arrValues);
            }
            $arrValues = array_unique($arrValues);
            // var_dump(">>> values >>>>>>");
            // var_dump($arrValues);
            $dataConfigCategoryInfo['condition']['shop_products.CategoryID'] = $app->getDB()->createCondition($arrValues, 'IN');
            // var_dump($dataConfigCategoryInfo);
            $filterData = $app->getDB()->query($dataConfigCategoryInfo);
            $count = $this->getUniqueProductsCount($filterData);
            // var_dump(">>>>results>>>>>>>");
            // var_dump($filterData);
            // var_dump("-=-=-=-=-=-=-=-=-=-=-=-=-");
            $categories[$categoryItem['ExternalKey']] = $categoryItem;
            $categories[$categoryItem['ExternalKey']]['ProductCount'] = $count;
            $categories[$categoryItem['ExternalKey']]['Active'] = false;
            if (!empty($filterOptionsApplied['filter_categorySubCategories'])) {
                $categories[$categoryItem['ExternalKey']]['ProductCount'] -= $currentProductCount;
                $categories[$categoryItem['ExternalKey']]['Active'] = true;
            }

            $dataConfigCategoryInfo['condition']['CategoryID'] = $app->getDB()->createCondition($categoryID);
            $filterData = $app->getDB()->query($dataConfigCategoryInfo);
            $categories[$categoryItem['ExternalKey']]['Total'] = $this->getUniqueProductsCount($filterData);
        }
        foreach ($filterOptionsAvailable['filter_commonStatus'] as $status) {
            $dataConfigCategoryInfo = $this->data->getShopCategoryProductInfo();
            $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
            $arrValues = array($status);
            if (!empty($filterOptionsApplied['filter_commonStatus'])) {
                $arrValues = array_merge($filterOptionsApplied['filter_commonStatus'], $arrValues);
            }
            $arrValues = array_unique($arrValues);
            // var_dump($arrValues);
            $dataConfigCategoryInfo['condition']['shop_products.Status'] = $app->getDB()->createCondition($arrValues, 'IN');
            $filterData = $app->getDB()->query($dataConfigCategoryInfo);
            $count = $this->getUniqueProductsCount($filterData);
            // var_dump($filterData);
            // var_dump($dataConfigCategoryInfo);
            // var_dump('-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=');
            // var_dump('-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=');
            // var_dump('-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=');
            // var_dump('-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=');
            $statuses[$status]['ID'] = $status;
            $statuses[$status]['ProductCount'] = $count;
            $statuses[$status]['Active'] = false;
            // var_dump($filterData);
            if (!empty($filterOptionsApplied['filter_commonStatus'])) {
                $statuses[$status]['ProductCount'] -= $currentProductCount;
                $statuses[$status]['Active'] = true;
            }

            $dataConfigCategoryInfo['condition']['shop_products.Status'] = $app->getDB()->createCondition($status);
            $filterData = $app->getDB()->query($dataConfigCategoryInfo);
            $statuses[$status]['Total'] = $this->getUniqueProductsCount($filterData);
        }
        foreach ($filterOptionsAvailable['filter_commonFeatures'] as $featureGroup => $featureList) {
            $group = array();
            foreach ($featureList as $key => $featureName) {
                $dataConfigCategoryInfo = $this->data->getShopCategoryProductInfo();
                $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
                $arrValues = array($key);
                if (!empty($filterOptionsApplied['filter_commonFeatures'])) {
                    $arrValues = array_merge($filterOptionsApplied['filter_commonFeatures'], $arrValues);
                }
                $arrValues = array_unique($arrValues);
                // var_dump($arrValues);
                $dataConfigCategoryInfo['condition']['FeatureID'] = $app->getDB()->createCondition($arrValues, 'IN');
                // var_dump($dataConfigCategoryInfo);
                $filterData = $app->getDB()->query($dataConfigCategoryInfo);
                $count = $this->getUniqueProductsCount($filterData);
                $group[$key]['ID'] = $key;
                $group[$key]['ProductCount'] = $count;
                $group[$key]['Active'] = false;
                $group[$key]['Name'] = $featureName;
                // var_dump($filterData);
                if (!empty($filterOptionsApplied['filter_commonFeatures'])) {
                    $group[$key]['ProductCount'] -= $currentProductCount;
                    $group[$key]['Active'] = true;
                }
                $dataConfigCategoryInfo['condition']['FeatureID'] = $app->getDB()->createCondition(array($key), 'IN');
                $filterData = $app->getDB()->query($dataConfigCategoryInfo);
                $group[$key]['Total'] = $this->getUniqueProductsCount($filterData);
            }
            $features[$featureGroup] = $group;
        }

        $filterOptionsAvailable['filter_categoryBrands'] = $brands;
        $filterOptionsAvailable['filter_categorySubCategories'] = $categories;
        $filterOptionsAvailable['filter_commonStatus'] = $statuses;
        $filterOptionsAvailable['filter_commonFeatures'] = $features;

        // get products
        // $dataProducts = $app->getDB()->query($dataConfigProducts);
        return $q->selectAsDataList();
    }
    public function fetchCatalogTreeDict ($selectedCategoryID = false) {
        $q = dbQuery::shopCategories()
            ->setAllFields()
            ->setCondition('Status', 'ACTIVE');
        if ($selectedCategoryID !== false) {
            $q->addCondition('ID', $selectedCategoryID);
        }
        return $q->selectAsDict('ID');
        // global $app;
        // $config = dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_categories",
        //     "condition" => array(
        //         "Status" => dbquery::createCondition("ACTIVE")
        //     ),
        //     "fields" => array("ID", "ParentID", "ExternalKey", "Name", "Image", "Status"),
        // ));
        // if ($selectedCategoryID !== false) {
        //     $config["condition"]["ID"] = dbquery::createCondition($selectedCategoryID);
        // }
        // return $config;
    }
    // <<<< Catalog
















    // shop cetegories >>>>>
    public function fetchCategoryByID ($id) {
        return dbQuery::shopCategories()
            ->setAllFields()
            ->setCondition('ID', $id)
            ->selectSingleItem();
        // global $app;
        // $config = dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_categories",
        //     "condition" => array(),
        //     "fields" => array("ID", "ParentID", "ExternalKey", "Name", "Description", "Image", "Status", "DateCreated", "DateUpdated"),
        //     "options" => array(
        //         "expandSingleRecord" => true
        //     ),
        //     "limit" => 1
        // ));

        // if (!is_null($id)) {
        //     $config["condition"] = array(
        //         "shop_categories.ID" => dbquery::createCondition($id)
        //     );
        // }

        // return $config;
    }
    public function fetchCategoryByExternalKey ($eKey) {
        return dbQuery::shopCategories()
            ->setAllFields()
            ->setCondition('ExternalKey', $eKey)
            ->selectSingleItem();
        // global $app;
        // $config = dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_categories",
        //     "condition" => array(),
        //     "fields" => array("ID", "ParentID", "ExternalKey", "Name", "Description", "Image", "Status", "DateCreated", "DateUpdated"),
        //     "options" => array(
        //         "expandSingleRecord" => true
        //     ),
        //     "limit" => 1
        // ));

        // if (!is_null($id)) {
        //     $config["condition"] = array(
        //         "shop_categories.ID" => dbquery::createCondition($id)
        //     );
        // }

        // return $config;
    }

    // TODO: optimmize list query
    public function fetchCategoryDataList (array $options = array()) {
        $q = dbQuery::shopCategories()
            ->setAllFields()
            ->setCondition('ID', $id)
            ->groupBy('ID')
            ->addParams($options);
        if (empty($options['removed'])) {
            $q->addCondition('Status', 'ACTIVE');
            // $config['condition']['Status'] = dbquery::createCondition('ACTIVE');
        }
        return $q->selectAsDataList();
        // global $app;
        // $config = self::fetchCategoryByID();
        // $config['fields'] = array("ID");
        // $config['limit'] = 64;
        // $config['options']['expandSingleRecord'] = false;
        // if (empty($options['removed'])) {
        //     $config['condition']['Status'] = dbquery::createCondition('ACTIVE');
        // }
        // return $config;
    }

    public function createCategory ($data) {
        $r = new result();
        try {
            $data["Description"] = empty($data["Description"]) ? "" : $data["Description"];
            $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name'], array('transliterate' => true));
            $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
            $data["Name"] = substr($data["Name"], 0, 300);
            $this->db->beginTransaction();
            $itemID = dbQuery::shopCategories()
                ->setData($data)
                ->addStandardDateFields()
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;


        // global $app;
        // $data["DateUpdated"] = dbquery::getDate();
        // $data["DateCreated"] = dbquery::getDate();
        // $data["Description"] = empty($data["Description"]) ? "" : $data["Description"];
        // $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name'], array('transliterate' => true));
        // $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
        // $data["Name"] = substr($data["Name"], 0, 300);
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_categories",
        //     "action" => "insert",
        //     "data" => $data,
        //     "options" => null
        // ));
    }

    public function updateCategory ($categoryID, $data) {
        $r = new result();
        try {
            if (isset($data['Name'])) {
                $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name'], array('transliterate' => true));
                $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
                $data["Name"] = substr($data["Name"], 0, 300);
            }
            $this->db->beginTransaction();
            dbQuery::shopCategories()
                ->setCondition('ID', $categoryID)
                ->setData($data)
                ->addStandardDateUpdatedField()
                ->update();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = dbquery::getDate();
        // if (isset($data['Name'])) {
        //     $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name'], array('transliterate' => true));
        //     $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
        //     $data["Name"] = substr($data["Name"], 0, 300);
        // }
        // // var_dump($data);
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_categories",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => dbquery::createCondition($CategoryID)
        //     ),
        //     "data" => $data,
        //     "options" => null
        // ));
    }

    public function deleteCategory ($categoryID) {
        return $this->updateCategory($categoryID, array(
                'Status' => 'REMOVED'
            ));
        // global $app;
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_categories",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => dbquery::createCondition($categoryID)
        //     ),
        //     "data" => array(
        //         "Status" => 'REMOVED',
        //         "DateUpdated" => dbquery::getDate()
        //     ),
        //     "options" => null
        // ));
    }
    // shop cetegories <<<<<












    // shop origins <<<<<
    public function fetchOriginByID ($id) {
        return dbQuery::shopOrigins()
            ->setAllFields()
            ->setCondition('ID', $id)
            ->selectSingleItem();
        // global $app;
        // $config = dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_origins",
        //     "condition" => array(),
        //     "fields" => array("ID", "ExternalKey", "Name", "Description", "HomePage", "Status", "DateCreated", "DateUpdated"),
        //     "options" => array(
        //         "expandSingleRecord" => true
        //     ),
        //     "limit" => 1
        // ));

        // if (!is_null($id)) {
        //     $config["condition"] = array(
        //         "shop_origins.ID" => dbquery::createCondition($id)
        //     );
        // }

        // return $config;
    }
    public function fetchOriginByExternalKey ($eKey) {
        return dbQuery::shopOrigins()
            ->setAllFields()
            ->setCondition('ExternalKey', $eKey)
            ->selectSingleItem();
        // global $app;
        // $config = dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_origins",
        //     "condition" => array(),
        //     "fields" => array("ID", "ExternalKey", "Name", "Description", "HomePage", "Status", "DateCreated", "DateUpdated"),
        //     "options" => array(
        //         "expandSingleRecord" => true
        //     ),
        //     "limit" => 1
        // ));

        // if (!is_null($id)) {
        //     $config["condition"] = array(
        //         "shop_origins.ID" => dbquery::createCondition($id)
        //     );
        // }

        // return $config;
    }
    public function fetchOriginByName ($name) {
        return dbQuery::shopOrigins()
            ->setAllFields()
            ->setCondition('Name', $name)
            ->selectSingleItem();
        // global $app;
        // $config = dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_origins",
        //     "condition" => array(),
        //     "fields" => array("ID", "ExternalKey", "Name", "Description", "HomePage", "Status", "DateCreated", "DateUpdated"),
        //     "options" => array(
        //         "expandSingleRecord" => true
        //     ),
        //     "limit" => 1
        // ));

        // if (!is_null($id)) {
        //     $config["condition"] = array(
        //         "shop_origins.ID" => dbquery::createCondition($id)
        //     );
        // }

        // return $config;
    }

    // TODO: optimmize list query
    public function fetchOriginDataList (array $options = array()) {
        $q = dbQuery::shopOrigins()
            ->setAllFields()
            ->setCondition('ID', $id)
            ->groupBy('ID')
            ->addParams($options);
        if (empty($options['removed'])) {
            $q->addCondition('Status', 'ACTIVE');
            // $config['condition']['Status'] = dbquery::createCondition('ACTIVE');
        }
        return $q->selectAsDataList();
        // global $app;
        // $config = self::fetchOriginByID();
        // $config['fields'] = array("ID");
        // $config['limit'] = 64;
        // $config['options']['expandSingleRecord'] = false;
        // if (empty($options['removed'])) {
        //     $config['condition']['Status'] = dbquery::createCondition('ACTIVE');
        // }
        // return $config;
    }

    public function createOrigin ($data) {
        $r = new result();
        try {
            $data["Description"] = empty($data["Description"]) ? "" : $data["Description"];
            $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name'], array('transliterate' => true));
            $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
            $data["Name"] = substr($data["Name"], 0, 300);
            $this->db->beginTransaction();
            $itemID = dbQuery::shopOrigins()
                ->setData($data)
                ->addStandardDateFields()
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = dbquery::getDate();
        // $data["DateCreated"] = dbquery::getDate();
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_origins",
        //     "action" => "insert",
        //     "data" => $data,
        //     "options" => null
        // ));
    }

    public function updateOrigin ($originID, $data) {
        $r = new result();
        try {
            if (isset($data['Name'])) {
                $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name'], array('transliterate' => true));
                $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
            }
            if (isset($data["Name"])) {
                $data["Name"] = substr($data["Name"], 0, 300);
            }
            $this->db->beginTransaction();
            dbQuery::systemCustomer()
                ->setCondition('ID', $customerID)
                ->setData($data)
                ->addStandardDateUpdatedField()
                ->update();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = dbquery::getDate();
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_origins",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => dbquery::createCondition($originID)
        //     ),
        //     "data" => $data,
        //     "options" => null
        // ));
    }

    public function deleteOrigin ($originID) {
        return $this->updateOrigin($originID, array(
                'Status' => 'REMOVED'
            ));
    }
    // shop origins <<<<<




















    // shop delivery agencies >>>>>
    public function fetchDeliveryAgencyByID ($id) {
        return dbQuery::shopDeliveryAgencies()
            ->setAllFields()
            ->setCondition('ID', $id)
            ->selectSingleItem();
    }

    // TODO: optimmize list query
    public function fetchDeliveriesDataList (array $options = array()) {
        $q = dbQuery::shopDeliveryAgencies()
            ->setAllFields()
            ->setCondition('ID', $id)
            ->groupBy('ID')
            ->addParams($options);
        return $q->selectAsDataList();
    }

    public function fetchAllActiveDeliveriesArray (array $options = array()) {
        $q = dbQuery::shopDeliveryAgencies()
            ->setAllFields()
            ->setCondition('ID', $id)
            ->setCondition('Status', 'ACTIVE')
            ->groupBy('ID')
            ->addParams($options);
        return $q->selectAsArray(0);
    }

    public function createDeliveryAgent ($data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::shopDeliveryAgencies()
                ->setData($data)
                ->addStandardDateFields()
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
    }

    public function updateDeliveryAgent ($id, $data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            dbQuery::shopDeliveryAgencies()
                ->setCondition('ID', $id)
                ->setData($data)
                ->addStandardDateUpdatedField()
                ->update();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
    }

    public function deleteDeliveryAgent ($id) {
        return $this->updateDeliveryAgent($id, array(
                'Status' => 'REMOVED'
            ));
    }
    // <<<<< shop delivery agencies









    // shop settings >>>>>
    // public function setting
    public static $ALLOW_MULTIPLE_SETTINGS = array('ADDRESS', 'EXCHANAGERATESDISPLAY'/*, 'PHONES', 'OPENHOURS', 'INFO'*/);
    public static $ALLOW_SETTINGS_TO_DELETE = array('ADDRESS', 'EXCHANAGERATESDISPLAY'/*, 'PHONES'*/);
    public static $SETTING_TYPE_TO_DBTABLE_MAP = array(
        'ADDRESS' => 'shop_settingsAddress',
        'ALERTS' => 'shop_settingsAlerts',
        'EXCHANAGERATES' => '',
        'EXCHANAGERATESDISPLAY' => 'shop_settingsExchangeRatesDisplay',
        'FORMORDER' => 'shop_settingsFormOrder',
        'MISC' => 'shop_settingsMisc',
        'PRODUCT' => 'shop_settingsProduct',
        'SEO' => 'shop_settingsSeo',
        'WEBSITE' => 'shop_settingsWebsite'
    );

    public function isOneForCustomer ($type) {
        return !in_array($type, self::$ALLOW_MULTIPLE_SETTINGS);
    }
    public function settingCanBeRemoved ($type) {
        return in_array($type, self::$ALLOW_SETTINGS_TO_DELETE);
    }

    public function getVerifiedSettingsType ($type) {
        return isset(self::$SETTING_TYPE_TO_DBTABLE_MAP[$type]) ? $type : null;
    }

    public function getSettingsDBTableNameByType ($type) {
        if (self::getVerifiedSettingsType($type)) {
            return self::$SETTING_TYPE_TO_DBTABLE_MAP[$type];
        }
        throw new Exception("Unknown shop settings type", 1);
    }

    public function customerSettingsCount ($type) {
        global $app;
        $config = dbquery::createOrGetQuery(array(
            "action" => "select",
            "source" => self::getSettingsDBTableNameByType($type),
            "fields" => array("@COUNT(*) AS ItemsCount"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));
        return $config;
    }

    public function shopGetSettingByID ($type, $id) {
        global $app;
        $config = dbquery::createOrGetQuery(array(
            "action" => "select",
            "source" => self::getSettingsDBTableNameByType($type),
            "condition" => array(),
            "fields" => array("*"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));
        // if (!is_null($id))
        $config["condition"]["ID"] = dbquery::createCondition($id);
        return $config;
    }
    public function shopGetSettingByType ($type) {
        global $app;
        $config = dbquery::createOrGetQuery(array(
            "action" => "select",
            "source" => self::getSettingsDBTableNameByType($type),
            "fields" => array("*"),
            "limit" => 0,
            "options" => array()
        ));
        if (self::isOneForCustomer($type)) {
            $config["limit"] = 1;
            $config["options"]["expandSingleRecord"] = true;
        }
        return $config;
    }
    public function shopGetSettingsAddressActive () {
        global $app;
        $config = self::shopGetSettingByType('ADDRESS');
        $config["condition"]["Status"] =  dbquery::createCondition('ACTIVE');
        return $config;
    }
    public function shopGetSettingsAddressPhones ($addressID) {
        global $app;
        $config = self::shopGetSettingByType('PHONES');
        $config["condition"]["ShopAddressID"] = dbquery::createCondition($addressID);
        return $config;
    }
    public function shopGetSettingsAddressOpenHours ($addressID) {
        global $app;
        $config = self::shopGetSettingByType('OPENHOURS');
        $config["condition"]["ShopAddressID"] = dbquery::createCondition($addressID);
        $config["limit"] = 1;
        $config["options"]["expandSingleRecord"] = true;
        return $config;
    }
    public function shopGetSettingsAddressInfo ($addressID) {
        global $app;
        $config = self::shopGetSettingByType('INFO');
        $config["condition"]["ShopAddressID"] = dbquery::createCondition($addressID);
        $config["limit"] = 1;
        $config["options"]["expandSingleRecord"] = true;
        return $config;
    }
    public function shopCreateSetting ($type, $data) {
        global $app;
        return dbquery::createOrGetQuery(array(
            "source" => self::getSettingsDBTableNameByType($type),
            "action" => "insert",
            "data" => $data,
            "options" => null,
            "saveOptions" => array(
                "useInsertIgnore" => true
            )
        ));
    }

    public function shopUpdateSetting ($type, $id, $data) {
        global $app;
        $config = dbquery::createOrGetQuery(array(
            "source" => self::getSettingsDBTableNameByType($type),
            "action" => "update",
            "condition" => array(
                "ID" => dbquery::createCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
        return $config;
    }

    public function shopRemoveSetting ($type, $id) {
        global $app;
        return dbquery::createOrGetQuery(array(
            "source" => self::getSettingsDBTableNameByType($type),
            "action" => "delete",
            "condition" => array(
                "ID" => dbquery::createCondition($id)
            )
        ));
    }
    // <<<<< shop delivery agencies










    // Shop order >>>>>
    public function fetchOrderItemByID ($orderID) {
        if ($orderID == 'temp') {
            $f = dbQuery::shopOrders()->getFilter('fetch');
            $order['temp'] = true;
            // TODO: add condition chk for fn
            $f($order);
            return $order;
        }
        return dbQuery::shopOrders()
            ->setAllFields()
            ->setCondition('ID', $orderID)
            ->selectSingleItem();


        // global $app;
        // $config = dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_orders",
        //     "condition" => array(),
        //     "fields" => array("ID", "UserID", "UserAddressesID", "DeliveryID", "ExchangeRateID", "CustomerCurrencyRate", "CustomerCurrencyName", "Warehouse", "Comment", "InternalComment", "Status", "Hash", "PromoID", "DateCreated", "DateUpdated"),
        //     "options" => array(
        //         "expandSingleRecord" => true
        //     ),
        //     "limit" => 1
        // ));

        // if (!is_null($orderID))
        //     $config["condition"] = array(
        //         "shop_orders.ID" => dbquery::createCondition($orderID)
        //     );

        // return $config;
    }

    // TODO: optimmize list query
    public function fetchOrderDataList (array $options = array()) {
        $q = dbQuery::shopOrders()
            ->setAllFields()
            ->setCondition('ID', $id)
            ->groupBy('ID')
            ->addParams($options);

        if (!empty($options['_pSearch'])) {
            if (is_string($options['_pSearch'])) {
                $q->addCondition('Hash', dbQuery::getLike($options['_pSearch']));
                // $config['condition']["Hash"] = dbquery::createCondition($options['_pSearch'] . '%', 'like');
            } elseif (is_array($options['_pSearch'])) {
                foreach ($options['_pSearch'] as $value) {
                    $q->addCondition('Hash', dbQuery::getLike($value));
                    // $config['condition']["Hash"] = dbquery::createCondition($value . '%', 'like');
                }
            }
        }
        // select for specific user
        if (!empty($options['_pUser'])) {
            // $config['condition']['UserID'] = dbquery::createCondition($options['_pUser']);
            $q->addCondition('Hash', dbQuery::getLike($options['_pUser']));
        }

        return $q->selectAsDataList();
        // global $app;
        // $config = self::fetchOrderItemByID();
        // $config['fields'] = array("ID");
        // $config['limit'] = 64;
        // $config['options']['expandSingleRecord'] = false;
        // if (!empty($options['_pSearch'])) {
        //     if (is_string($options['_pSearch'])) {
        //         $config['condition']["Hash"] = dbquery::createCondition($options['_pSearch'] . '%', 'like');
        //         // $config['condition']["Model"] = dbquery::createCondition('%' . $options['search'] . '%', 'like');
        //         // $config['condition']["SKU"] = dbquery::createCondition('%' . $options['search'] . '%', 'like');
        //     } elseif (is_array($options['_pSearch'])) {
        //         foreach ($options['_pSearch'] as $value) {
        //             $config['condition']["Hash"] = dbquery::createCondition($value . '%', 'like');
        //             // $config['condition']["Model"] = dbquery::createCondition('%' . $value . '%', 'like');
        //             // $config['condition']["SKU"] = dbquery::createCondition('%' . $value . '%', 'like');
        //         }
        //     }
        // }
        // // select for specific user
        // if (!empty($options['_pUser'])) {
        //     $config['condition']['UserID'] = dbquery::createCondition($options['_pUser']);
        // }
        // return $config;
    }
    // TODO: optimmize list query
    public function fetchOrderDataList_Pending () {
        $listParams = array();
        $listParams[dbquery::shopOrders()->genFieldQueryParamStr('Status')] = 'NEW';
        return $this->fetchOrderDataList($options + $listParams);
        // global $app;
        // $config = self::fetchOrderDataList();
        // $config['condition']['Status'] = dbquery::createCondition('NEW');
        // return $config;
    }
    // TODO: optimmize list query
    public function fetchOrderDataList_Todays () {
        $listParams = array();
        $listParams[dbquery::shopOrders()->genFieldQueryParamDateCreatedStr()] = dbquery::genValueQueryParamDateNowCondition('>');
        return $this->fetchOrderDataList($options + $listParams);
        // global $app;
        // $config = self::fetchOrderDataList();
        // $config['condition']['DateCreated'] = dbquery::createCondition(date('Y-m-d'), ">");
        // return $config;
    }
    // TODO: optimmize list query
    public function fetchOrderDataList_Expired () {
        $listParams = array();
        $listParams[dbquery::shopOrders()->genFieldQueryParamStr('Status')] = dbquery::genValueQueryParamCondition(array("SHOP_CLOSED", "SHOP_REFUNDED", "CUSTOMER_CANCELED"), "NOT IN");
        $listParams[dbquery::shopOrders()->genFieldQueryParamDateCreatedStr()] = dbquery::genValueQueryParamDateCondition(date('Y-m-d', strtotime("-1 week")), "<");
        return $this->fetchOrderDataList($options + $listParams);
        // global $app;
        // $config = self::fetchOrderDataList();
        // $config['condition']['Status'] = dbquery::createCondition(array("SHOP_CLOSED", "SHOP_REFUNDED", "CUSTOMER_CANCELED"), "NOT IN");
        // $config['condition']['DateCreated'] = dbquery::createCondition(date('Y-m-d', strtotime("-1 week")), "<");
        // return $config;
    }
    // TODO: optimmize list query
    public function fetchOrderDataList_ForUser ($userID) {
        $listParams = array();
        $listParams[dbquery::shopOrders()->genFieldQueryParamStr('UserID')] = $userID;
        return $this->fetchOrderDataList($options + $listParams);
        // global $app;
        // $config = self::fetchOrderDataList();
        // $config['condition']['UserID'] = $userID;
        // return $config;
    }
    public function shopCreateOrder ($data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::shopOrders()
                ->setData($data)
                ->addDataItem('Hash', substr(md5(time() . md5(time())), 0, 5))
                ->addDataItemByFlag(is_string($data["DeliveryID"]), 'DeliveryID', null)
                ->addDataItemByFlag(is_string($data["Warehouse"]), 'Warehouse', null)
                ->addStandardDateFields()
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = dbquery::getDate();
        // $data["DateCreated"] = dbquery::getDate();
        // $data["Hash"] = substr(md5(time() . md5(time())), 0, 5);
        // // adjust values
        // if (is_string($data["DeliveryID"])) {
        //     $data["DeliveryID"] = null;
        // }
        // if (is_string($data["Warehouse"])) {
        //     $data["Warehouse"] = null;
        // }
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_orders",
        //     "action" => "insert",
        //     "data" => $data,
        //     "options" => null
        // ));
    }
    public function shopCreateOrderBought ($data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::shopBoughts()
                ->setData($data)
                ->addDataItem('IsPromo', empty($data["IsPromo"]) ? 0 : 1)
                ->addStandardDateCreatedField()
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateCreated"] = dbquery::getDate();
        // $data["IsPromo"] = empty($data["IsPromo"]) ? 0 : 1;
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_boughts",
        //     "action" => "insert",
        //     "data" => $data,
        //     "options" => null
        // ));
    }
    public function shopGetOrderBoughts ($orderID) {
        return dbQuery::shopBoughtsWithProducts()
            ->setAllFields()
            ->setCondition('OrderID', $orderID)
            ->selectAsArray();
        // global $app;
        // return dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_boughts",
        //     "condition" => array(
        //         "OrderID" => dbquery::createCondition($orderID)
        //     ),
        //     "fields" => array("ID", "ProductID", "Price", "SellingPrice", "Quantity", "IsPromo", "DateCreated"),
        //     "offset" => 0,
        //     "limit" => 0
        // ));
    }
    public function fetchOrderByHash ($orderHash) {
        return dbQuery::shopOrders()
            ->setAllFields()
            ->setCondition('Hash', $orderHash)
            // ->addCondition('Status', 'ACTIVE')
            ->selectSingleItem();
        // global $app;
        // $config = self::fetchOrderItemByID();
        // $config['condition'] = array(
        //     "Hash" => dbquery::createCondition($orderHash)
        // );
        // $config['options'] = array(
        //     "expandSingleRecord" => true
        // );
        // $config['limit'] = 1;
        // return $config;
    }
    public function updateOrder ($orderID, $data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            dbQuery::shopOrders()
                ->setData($data)
                ->addStandardDateUpdatedField()
                ->setCondition('ID', $orderID)
                ->update();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = dbquery::getDate();
        // return dbquery::createOrGetQuery(array(
        //     "action" => "update",
        //     "source" => "shop_orders",
        //     "condition" => array(
        //         "ID" => dbquery::createCondition($orderID)
        //     ),
        //     "data" => $data,
        //     "options" => null
        // ));
    }
    public function disableOrder ($OrderID) {
        return $this->updateOrder($categoryID, array(
                'Status' => 'REMOVED'
            ));
        // global $app;
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_orders",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => dbquery::createCondition($OrderID)
        //     ),
        //     "data" => array(
        //         "Status" => 'REMOVED',
        //         "DateUpdated" => dbquery::getDate()
        //     ),
        //     "options" => null
        // ));
    }
    // <<<< Shop order













    // >>>> Shop statistics
    // TODO: optimmize list query
    public function shopStat_PopularProducts (array $options = array()) {
        global $app;
        return dbquery::createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_boughts",
            "fields" => array("ProductID", "@SUM(Quantity) AS SoldTotal", "@SUM(shop_boughts.Price * Quantity) AS SumTotal"),
            "condition" => array(
                "shop_products.Status" => dbquery::createCondition(array("REMOVED", "ARCHIVED"), "NOT IN")
            ),
            "order" => array(
                "field" => "SoldTotal",
                "ordering" => "DESC"
            ),
            'additional' => array(
                "shop_products" => array(
                    "constraint" => array("shop_boughts.ProductID", "=", "shop_products.ID"),
                    "fields" => array("@shop_products.Status AS ProductStatus")
                )
            ),
            "limit" => !empty($options['limit']) ? $options['limit'] : 15,
            "group" => "ProductID",
            "options" => null
        ));
    }

    // TODO: optimmize list query
    public function shopStat_NonPopularProducts (array $options = array()) {
        global $app;
        return dbquery::createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_products",
            "fields" => array("ID"),
            "condition" => array(
                "Status" => dbquery::createCondition("ACTIVE"),
                "ID" => dbquery::createCondition("SELECT ProductID AS ID FROM shop_boughts", "NOT IN")
            ),
            "order" => array(
                "field" => "DateCreated",
                "ordering" => "ASC"
            ),
            "limit" => !empty($options['limit']) ? $options['limit'] : 15,
            "options" => null
        ));
    }

    public function shopStat_ProductsOverview ($filter = null) {
        global $app;
        $config = self::dbqProduct();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "Status");
        $config['group'] = "Status";
        $config['limit'] = 0;
        $config['options'] = array(
            'asDict' => array(
                'keys' => 'Status',
                'values' => 'ItemsCount'
            )
        );
        $config['condition'] = array();
        unset($config['additional']);
        // var_dump($requestGetData);
        if (!empty($filter)) {
            if (isset($filter['_fCategoryID']))
                $config['condition']['CategoryID'] = dbquery::createCondition($filter['_fCategoryID']);
        }
        return $config;
    }

    public function shopStat_OrdersOverview () {
        global $app;
        $config = self::fetchOrderItemByID();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "Status");
        $config['group'] = "Status";
        $config['limit'] = 0;
        $config['options'] = array(
            'asDict' => array(
                'keys' => 'Status',
                'values' => 'ItemsCount'
            )
        );
        $config['condition'] = array();
        unset($config['additional']);
        return $config;
    }

    public function shopStat_OrdersIntensityLastMonth ($status, $comparator = null) {
        global $app;
        if (!is_string($comparator))
            $comparator = dbquery::DEFAULT_COMPARATOR;
        $config = self::fetchOrderItemByID();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "@Date(DateUpdated) AS CloseDate");
        $config['condition'] = array(
            'Status' => dbquery::createCondition($status, $comparator),
            'DateUpdated' => dbquery::createCondition(date('Y-m-d', strtotime("-1 month")), ">")
        );
        $config['options'] = array(
            'asDict' => array(
                'keys' => 'CloseDate',
                'values' => 'ItemsCount'
            )
        );
        $config['group'] = 'Date(DateUpdated)';
        $config['limit'] = 0;
        unset($config['additional']);
        // var_dump($config);
        return $config;
    }

    public function shopStat_ProductsIntensityLastMonth ($status) {
        global $app;
        $config = self::dbqProduct();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "@Date(DateUpdated) AS CloseDate");
        $config['condition'] = array(
            'Status' => dbquery::createCondition($status),
            'DateUpdated' => dbquery::createCondition(date('Y-m-d', strtotime("-1 month")), ">")
        );
        $config['options'] = array(
            'asDict' => array(
                'keys' => 'CloseDate',
                'values' => 'ItemsCount'
            )
        );
        $config['group'] = 'Date(DateCreated)';
        $config['limit'] = 0;
        unset($config['additional']);
        return $config;
    }
    // <<<< Shop statistics



















    // <<<< Promo area
    public function fetchPromoByHash ($hash, $activeOnly) {
        $q = dbQuery::shopPromo()
            ->setAllFields()
            ->setCondition('Code', $hash);
        if ($activeOnly) {
            $q->addCondition('DateStart', dbquery::getDate(), '<=');
            $q->addCondition('DateExpire', dbquery::getDate(), '>=');
        }
        return $q->selectSingleItem();

        // global $app;
        // $config = dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_promo",
        //     "condition" => array(
        //         "Code" => dbquery::createCondition($hash)
        //     ),
        //     "options" => array(
        //         "expandSingleRecord" => true
        //     )
        // ));

        // if ($activeOnly) {
        //     $config['condition']['DateStart'] = dbquery::createCondition(dbquery::getDate(), '<=');
        //     $config['condition']['DateExpire'] = dbquery::createCondition(dbquery::getDate(), '>=');
        // }

        // return $config;
    }

    public function fetchPromoByID ($promoID) {
        return dbQuery::shopPromo()
            ->setAllFields()
            ->setCondition('ID', $promoID)
            ->selectSingleItem();

        // global $app;
        // $config = dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_promo",
        //     "condition" => array(),
        //     "fields" => array("ID", "Code", "DateStart", "DateExpire", "Discount", "DateCreated"),
        //     "options" => array(
        //         "expandSingleRecord" => true
        //     )
        // ));

        // if (!is_null($promoID))
        //     $config["condition"] = array(
        //         "ID" => dbquery::createCondition($promoID)
        //     );
        // return $config;
    }

    // TODO: optimmize list query
    public function fetchPromoDataList (array $options = array()) {
        $q = dbQuery::shopPromo()
            ->setAllFields()
            ->groupBy('ID')
            ->addParams($options);
        if (empty($options['expired'])) {
            $q->addCondition('DateExpire', dbquery::getDate(), '>=');
        }
        return $q->selectAsDataList();

        // global $app;
        // $config = self::fetchPromoByID();
        // $config['fields'] = array("ID");
        // $config['limit'] = 64;
        // $config['options']['expandSingleRecord'] = false;
        // if (empty($options['expired'])) {
        //     $config['condition']['DateExpire'] = dbquery::createCondition(dbquery::getDate(), '>=');
        // }
        // return $config;
    }

    public function createPromo ($data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::shopPromo()
                ->setData($data)
                ->addStandardDateFields()
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateCreated"] = dbquery::getDate();
        // return dbquery::createOrGetQuery(array(
        //     "action" => "insert",
        //     "source" => "shop_promo",
        //     "data" => $data,
        //     "options" => null
        // ));
    }

    public function updatePromo ($promoID, $data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            dbQuery::shopPromo()
                ->setData($data)
                ->addStandardDateUpdatedField()
                ->setCondition('ID', $promoID)
                ->update();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
    }

    public function expirePromo ($promoID) {
        return $this->updatePromo($promoID, array(
                'DateExpire' => dbquery::getDate()
            ));
        // global $app;
        // return dbquery::createOrGetQuery(array(
        //     "action" => "update",
        //     "source" => "shop_promo",
        //     "condition" => array(
        //         "ID" => dbquery::createCondition($promoID)
        //     ),
        //     "data" => array(
        //         "DateExpire" => dbquery::getDate()
        //     ),
        //     "options" => null
        // ));
    }
    // Promo area >>>>>






















    // shop delivery agencies >>>>>
    public function fetchExchangeRateByID ($id) {
        return dbQuery::shopCurrency()
            ->setAllFields()
            ->setCondition('ID', $id)
            ->selectSingleItem();
        // global $app;
        // $config = dbquery::createOrGetQuery(array(
        //     "action" => "select",
        //     "source" => "shop_currency",
        //     "condition" => array(),
        //     "fields" => array("ID", "CurrencyA", "CurrencyB", "Rate"),
        //     "options" => array(
        //         "expandSingleRecord" => true
        //     ),
        //     "limit" => 1
        // ));

        // if (!is_null($id))
        //     $config["condition"]["ID"] = dbquery::createCondition($id);

        // return $config;
    }
    public function fetchExchangeRateTo_ByCurrencyName ($currencyNameTo = null) {
        return dbQuery::shopCurrency()
            ->setAllFields()
            ->setCondition('CurrencyB', $currencyNameTo)
            ->selectSingleItem();
        // global $app;
        // $config = self::fetchExchangeRateByID();
        // $config["condition"] = array(
        //     "CurrencyB" => dbquery::createCondition($currencyNameTo)
        // );
        // return $config;
    }
    public function fetchExchangeRateFrom_ByCurrencyName ($currencyNameFrom = null) {
        return dbQuery::shopCurrency()
            ->setAllFields()
            ->setCondition('CurrencyA', $currencyNameTo)
            ->selectSingleItem();
        // global $app;
        // $config = self::fetchExchangeRateByID();
        // $config["condition"] = array(
        //     "CurrencyA" => dbquery::createCondition($currencyNameFrom)
        // );
        // return $config;
    }
    public function fetchExchangeRateByBothNames ($currencyNameFrom, $currencyNameTo) {
        return dbQuery::shopCurrency()
            ->setAllFields()
            ->setCondition('CurrencyA', $currencyNameFrom)
            ->setCondition('CurrencyB', $currencyNameTo)
            ->selectSingleItem();
        // global $app;
        // $config = self::fetchExchangeRateByID();
        // $config["condition"] = array(
        //     "CurrencyA" => dbquery::createCondition($currencyNameFrom),
        //     "CurrencyB" => dbquery::createCondition($currencyNameTo)
        // );
        // return $config;
    }

    // TODO: optimmize list query
    public function fetchExchangeRatesList (array $options = array()) {
        $q = dbQuery::shopCurrency()
            ->setAllFields()
            ->groupBy('ID')
            ->addParams($options);
        return $q->selectAsDataList();
        // global $app;
        // $config = self::fetchExchangeRateByID();
        // $config['fields'] = array("ID");
        // $config['limit'] = 64; // assume that 64 ex.rates nobody will have
        // $config['options']['expandSingleRecord'] = false;
        // if (isset($options['fields'])) {
        //     $config['fields'] = $options['fields'];
        // }
        // if (isset($options['limit'])) {
        //     $config['limit'] = $options['limit'];
        // }
        // if (empty($options['removed'])) {
        //     $config['condition']['Status'] = dbquery::createCondition('ACTIVE');
        // }
        // return $config;
    }

    // public function fetchUniqueAvailableCurrencyNamesByField ($fieldToGroupBy) {
    //     // global $app;
    //     // $config = self::fetchExchangeRateByID();
    //     // $config['fields'] = array($fieldToGroupBy);
    //     // $config['limit'] = 0;
    //     // $config['group'] = $fieldToGroupBy;
    //     // $config['options']['expandSingleRecord'] = false;
    //     // return $config;
    //     $r = new result();
    //     try {
    //         $this->db->beginTransaction();
    //         $itemID = dbQuery::shopCurrency()
    //             ->setData($data)
    //             ->addStandardDateFields()
    //             ->insert();
    //         $this->db->commit();
    //         $r->success()
    //             ->setResult($itemID);
    //     } catch (Exception $e) {
    //         $this->db->rollBack();
    //         $r->fail()
    //             ->addError($e->getMessage());
    //     }
    //     return $r;
    // }

    public function shopCreateExchangeRate ($data) {
        // global $app;
        // $data["DateUpdated"] = dbquery::getDate();
        // $data["DateCreated"] = dbquery::getDate();
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_currency",
        //     "action" => "insert",
        //     "data" => $data,
        //     "options" => null
        // ));
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::shopCurrency()
                ->setData($data)
                ->addStandardDateFields()
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
    }

    public function updateExchangeRate ($id, $data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::shopCurrency()
                ->setCondition('ID', $id)
                ->setData($data)
                ->addStandardDateUpdatedField()
                ->update();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = dbquery::getDate();
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_currency",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => dbquery::createCondition($id)
        //     ),
        //     "data" => $data,
        //     "options" => null
        // ));
    }

    public function shopDeleteExchangeRate ($id) {
        return $this->updateAddress($id, array(
                'Status' => 'REMOVED'
            ));
        // global $app;
        // return dbquery::createOrGetQuery(array(
        //     "source" => "shop_currency",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => dbquery::createCondition($id)
        //     ),
        //     "data" => array(
        //         "Status" => 'REMOVED',
        //         "DateUpdated" => dbquery::getDate()
        //     ),
        //     "options" => null
        // ));
    }
    // <<<<< shop delivery agencies





}

?>
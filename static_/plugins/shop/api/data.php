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
        $this->db->createQuery('shopOrigins', $this->source_origins);
        $this->db->createQuery('shopCategories', $this->source_categories);
        $this->db->createQuery('shopProductFeatures', $this->source_productFeatures);
        $this->db->createQuery('shopProductPrices', $this->source_productPrices);
        $this->db->createQuery('shopRelations', $this->source_relations);
        $this->db->createQuery('shopFeatures', $this->source_features);
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
        $this->db->createQuery('shopBoughts', $this->source_boughts);
        $this->db->createQuery('shopPromo', $this->source_promo);
        $this->db->createQuery('shopCurrency', $this->source_currency);


    }



    public static function getProductUploadInnerDir ($productID, $subDir = '') {
        $apiCustomer = API::getAPI('system:customers');
        $customer = $apiCustomer->getRuntimeCustomer();
        $path = '';
        if (empty($subDir))
            $path = Path::createDirPath($customer['HostName'], 'shop', 'products', $productID);
        else
            $path = Path::createDirPath($customer['HostName'], 'shop', 'products', $productID, $subDir);
        return $path;
    }
    public static function getProductUploadInnerImagePath ($name, $productID, $subDir = false) {
        $path = self::getProductUploadInnerDir($productID, $subDir);
        return $path . $name;
    }

    public static function getProductStatuses () {
        return array('ACTIVE','ARCHIVED','DISCOUNT','DEFECT','WAITING','PREORDER');
    }
    public static function getProductStatusesWhenAvailable () {
        return array("ACTIVE", "DISCOUNT", "PREORDER", "DEFECT");
    }
    public static function getProductStatusesWhenDisabled () {
        return array("ARCHIVED", "REMOVED");
    }
    public static function getProductBannerTypes () {
        return array('BANNER_LARGE','BANNER_MEDIUM','BANNER_SMALL','BANNER_MICRO');;
    }

    private static function __adjustProduct (&$product, $skipRelations = true) {
        global $app;
        // adjusting
        $productID = intval($product['ID']);
        $product['ID'] = $productID;
        $product['OriginID'] = intval($product['OriginID']);
        $product['CategoryID'] = intval($product['CategoryID']);
        $product['_category'] = API::getAPI('shop:categories')->getCategoryByID($product['CategoryID']);
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
            $product['Relations'] = self::fetchProductRelations($productID);
        }

        // features
        $product['Features'] = self::fetchProductFeatures($productID);

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
    }

    public static function fetchProductImages ($productID) {
        global $app;
        $images = array();
        $config = self::dbqProductAttributes($productID, 'IMAGE');
        $data = $app->getDB()->query($config);
        // var_dump($data);
        if (!empty($data)) {
            foreach ($data as $item) {
                if (!empty($item['Value'])) {
                    $images[] = array(
                        'name' => $item['Value'],
                        'normal' => '/' . Path::getUploadDirectory() . self::getProductUploadInnerImagePath($item['Value'], $productID),
                        'md' => '/' . Path::getUploadDirectory() . self::getProductUploadInnerImagePath($item['Value'], $productID, 'md'),
                        'sm' => '/' . Path::getUploadDirectory() . self::getProductUploadInnerImagePath($item['Value'], $productID, 'sm'),
                        'xs' => '/' . Path::getUploadDirectory() . self::getProductUploadInnerImagePath($item['Value'], $productID, 'xs'),
                        'micro' => '/' . Path::getUploadDirectory() . self::getProductUploadInnerImagePath($item['Value'], $productID, 'micro')
                    );
                }
            }
        }
        return $images;
    }

    public static function fetchProductBanners ($productID) {
        global $app;
        $banners = array();
        $config = self::dbqProductAttributes($productID, self::getProductBannerTypes());
        $data = $app->getDB()->query($config);
        if (!empty($data)) {
            foreach ($data as $item) {
                if (!empty($item['Value'])) {
                    $banners[$item['Attribute']] = array(
                        'name' => $item['Value'],
                        'banner' => '/' . Path::getUploadDirectory() . self::getProductUploadInnerImagePath($item['Value'], $productID)
                    );
                }
            }
        }
        // get banner texts
        // $config = self::dbqProductAttributes($productID, array('BANNER_TEXT_LINE1', 'BANNER_TEXT_LINE2'));
        // $config['options']['asDict'] = 'Attribute';
        // $data = $app->getDB()->query($config);
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

    public static function fetchProductVideos ($productID) {
        global $app;
        $videos = array();
        $config = self::dbqProductAttributes($productID, 'VIDEO');
        $data = $app->getDB()->query($config);
        if (!empty($data)) {
            foreach ($data as $item) {
                $videos[] = $item['Value'];
            }
        }
        return $videos;
    }

    public static function fetchProductAttributes ($productID) {
        global $app;
        $attr = array();
        $config = self::dbqProductAttributes($productID);
        $data = $app->getDB()->query($config);
        if (!empty($data)) {
            foreach ($data as $item) {
                if ($item['Attribute'] === 'IMAGE' || $item['Attribute'] === 'VIDEO') {
                    continue;
                }
                $attr[$item['Attribute']] = $item['Value'];
            }
        }
        return $attr;
    }

    public static function fetchProductFeatures ($productID) {
        global $app;
        $featuresGroups = array();
        $config = self::dbqProductFeatures($productID);
        $data = $app->getDB()->query($config);
        if (!empty($data)) {
            foreach ($data as $value) {
                if (!isset($featuresGroups[$value['GroupName']])) {
                    $featuresGroups[$value['GroupName']] = array();
                }
                $featuresGroups[$value['GroupName']][$value['ID']] = $value['FieldName'];
            }
        }
        return $featuresGroups;
    }

    public static function fetchProductPrice ($productID) {
        global $app;
        $price = null;
        $config = self::dbqProductPrice($productID);
        $data = $app->getDB()->query($config);
        if (isset($data['Price'])) {
            $price = floatval($data['Price']);
        }
        return $price;
    }

    public static function fetchProductPriceHistory ($productID) {
        global $app;
        $prices = array();
        $config = self::dbqProductPriceStats($productID);
        $data = $app->getDB()->query($config);
        if (!empty($data)) {
            foreach ($data as $item) {
                $prices[] = array($item['DateCreated'], floatval($item['Price']));
            }
        }
        return $prices;
    }

    public static function fetchProductRelations ($productID) {
        global $app;
        $relations = array();
        $configProductsRelations = self::dbqProductRelations($productID);
        $relatedItemsIDs = $app->getDB()->query($configProductsRelations);
        if (isset($relatedItemsIDs)) {
            foreach ($relatedItemsIDs as $relationItem) {
                $relatedProductID = intval($relationItem['ProductB_ID']);
                if ($relatedProductID === $productID)
                    continue;
                $relatedProduct = self::fetchSingleProductByID($relatedProductID);
                if (isset($relatedProduct))
                    $relations[] = $relatedProduct;
            }
        }
        return $relations;
    }

    public static function fetchSingleProductByID ($productID) {
        global $app;
        if (empty($productID) || !is_numeric($productID))
            return null;
        // $config = self::dbqProduct($productID);
        // $product = $app->getDB()->query($config);
        self::dbqProduct($productID)->query();
        if (empty($product))
            return null;
        return self::__adjustProduct($product, false);
    }

    public static function fetchSingleProductByExternalKey ($eKey) {
        global $app;
        if (empty($eKey) || !is_string($eKey))
            return null;
        $config = self::dbqProductByExternalKey($eKey);
        $product = $app->getDB()->query($config);
        if (empty($product))
            return null;
        return self::__adjustProduct($product);
    }

    public static function fetchSingleProductByName ($name) {
        global $app;
        if (empty($name) || !is_string($name))
            return null;
        $config = self::dbqProductByName($name);
        $product = $app->getDB()->query($config);
        if (empty($product))
            return null;
        return self::__adjustProduct($product);
    }

    public static function fetchSingleProductByModel ($modelName) {
        global $app;
        if (empty($modelName) || !is_string($modelName))
            return null;
        $config = self::dbqProductByModel($modelName);
        $product = $app->getDB()->query($config);
        if (empty($product))
            return null;
        return self::__adjustProduct($product);
    }

    public static function fetchSingleProductByModelAndOrigin ($modelName, $originName) {
        global $app;
        if (empty($modelName) || !is_string($modelName))
            return null;
        if (empty($originName) || !is_string($originName))
            return null;
        $config = self::dbqProductByModelAndOrigin($modelName, $originName);
        $product = $app->getDB()->query($config);
        if (empty($product))
            return null;
        return self::__adjustProduct($product);
    }

    public static function fetchSingleProductShortInfo ($productID) {
        global $app;
        if (empty($productID) || !is_numeric($productID))
            return null;
        $config = self::dbqProductShortInfo();
        return $app->getDB()->query($config);
    }

    public static function fetchProducts_List (array $options = array()) {
        global $app;
        $config = self::dbqProducts_MatchedIDs($options);
        $list = $app->getDB()->queryMatchedIDs($config);

        // var_dump($list);
        $productIDs = $list['ids'];

        $config = self::dbqProducts($productIDs);
        $products = $app->getDB()->query($config);
        if (empty($products))
            return array();
        foreach ($products as $key => $product) {
            $products[$key] = self::__adjustProduct($product);
        }
        $list['items'] = $products;
        return $list;
    }

    public static function fetchNewProducts_List (array $options = array()) {
        // global $app;

        // $userListOptions = Utils::getIfIssetOrDefault($options, 'list', array());
        // $callbacks = Utils::getIfIssetOrDefault($options, 'callbacks', array());
        // $listOptions = array();

        // user-available params
        // $options['sort'] = Utils::getIfIssetOrDefault($options, 'sort', '-shop_products.DateUpdated');
        // $listOptions['order'] = Utils::getIfIssetOrDefault($userListOptions, 'order', 'DESC');

        // hardcoded params
        $options['sort'] = '-shop_products.DateCreated';
        // $listOptions['order'] = 'DESC';
        // $listOptions['_fshop_products.Status'] = join(',', self::getProductStatusesWhenAvailable()) . ':IN';
        // $options['_fIsFeatured'] = true;

        return self::fetchProducts_List($options);
        // $config = self::dbqProducts_MatchedIDs($listOptions);
        // if (empty($config))
        //     return null;
        // $dataList = $app->getDB()->queryMatchedIDs($config, $listOptions, $callbacks);

        // return $dataList;
    }

    public static function fetchOnSaleProducts_List (array $options = array()) {
        // global $app;

        // $userListOptions = Utils::getIfIssetOrDefault($options, 'list', array());
        // $callbacks = Utils::getIfIssetOrDefault($options, 'callbacks', array());
        // $listOptions = array();

        // // user-available params
        // $listOptions['sort'] = Utils::getIfIssetOrDefault($userListOptions, 'sort', 'shop_products.DateUpdated');
        // $listOptions['order'] = Utils::getIfIssetOrDefault($userListOptions, 'order', 'DESC');

        $options['sort'] = Utils::getIfIssetOrDefault($options, 'sort', '-shop_products.DateUpdated');
        // hardcoded params
        // $listOptions['sort'] = 'shop_products.DateUpdated';
        // $listOptions['order'] = 'DESC';
        $options['_fshop_products.Price'] = 'PrevPrice:>';
        $options['_fshop_products.Status'] = 'DISCOUNT';

        return self::fetchProducts_List($options);
    }

    public static function fetchFeaturedProducts_List (array $options = array()) {
        // global $app;

        // $userListOptions = Utils::getIfIssetOrDefault($options, 'list', array());
        // $callbacks = Utils::getIfIssetOrDefault($options, 'callbacks', array());
        // $listOptions = array();

        // // user-available params
        // $listOptions['sort'] = Utils::getIfIssetOrDefault($userListOptions, 'sort', 'shop_products.DateUpdated');
        // $listOptions['order'] = Utils::getIfIssetOrDefault($userListOptions, 'order', 'DESC');

        $options['sort'] = Utils::getIfIssetOrDefault($options, 'sort', '-shop_products.DateUpdated');
        // hardcoded params
        // $options['sort'] = 'shop_products.DateUpdated';
        // $options['order'] = 'DESC';
        // $options['_fshop_products.Status'] = join(',', self::getProductStatusesWhenAvailable()) . ':IN';
        // $options['_fIsFeatured'] = true;

        return self::fetchProducts_List($options);
    }

    public static function productExistsByID ($productID) {
        global $app;
        $config = self::dbqCheckProductExistenceByID($productID);
        $product = $app->getDB()->query($config);
        return intval($product['ID']);
    }
    public static function productExistsByExternalKey ($eKey) {
        global $app;
        $config = self::dbqCheckProductExistenceByExternalKey($productID);
        $product = $app->getDB()->query($config);
        return intval($product['ID']);
    }
    public static function productExistsByModelAndOrigin ($modelName, $originName) {
        global $app;
        $config = self::dbqCheckProductExistenceByModelAndOrigin($productID);
        $product = $app->getDB()->query($config);
        return intval($product['ID']);
    }

    public static function archiveProduct ($productID) {
        global $app;
        if (empty($productID) || !is_numeric($productID))
            return null;
        $config = $app->getDB()->createOrGetQuery(array(
            "source" => "shop_products",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($productID),
                "Status" => $app->getDB()->createCondition("REMOVED", "!="),
            ),
            "data" => array(
                "Status" => 'ARCHIVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
        return $app->getDB()->query($config);
    }

    public static function archiveAllProducts () {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
            "source" => "shop_products",
            "action" => "update",
            "condition" => array(
                "Status" => $app->getDB()->createCondition("REMOVED", "!="),
            ),
            "data" => array(
                "Status" => 'ARCHIVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
        return $app->getDB()->query($config);
    }

    public static function setProductAsRemovedByModelAndOrigin ($modelName, $originName) {
        global $app;
        $data = array(
            'shop_products.Status' => 'REMOVED',
            "shop_products.DateUpdated" => $app->getDB()->getDate()
        );
        $config = $app->getDB()->createOrGetQuery(array(
            "source" => "shop_products",
            "action" => "update",
            "condition" => array(
                "Model" => $app->getDB()->createCondition($modelName, 'like'),
                "shop_origins.Name" => $app->getDB()->createCondition($originName),
            ),
            'additional' => array(
                "shop_origins" => array(
                    "constraint" => array("shop_products.OriginID", "=", "shop_origins.ID"),
                    "fields" => array("@shop_origins.Name AS OriginName")
                )
            ),
            "data" => $data,
            "options" => null
        ));
        return $app->getDB()->query($config);
    }

    public static function setProductAsRemovedByID ($productID) {
        global $app;
        $data = array(
            'shop_products.Status' => 'REMOVED',
            "shop_products.DateUpdated" => $app->getDB()->getDate()
        );
        $config = $app->getDB()->createOrGetQuery(array(
            "source" => "shop_products",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($productID)
            ),
            'additional' => array(),
            "data" => $data,
            "options" => null
        ));
        return $app->getDB()->query($config);
    }

    public static function updateProductSearchTextByID ($ProductID) {
        global $app;
        $config = self::dbqUpdateProductSearchText($ProductID, null);
        return $app->getDB()->query($config);
    }

    public static function updateProductSearchTextByOriginID ($OriginID) {
        global $app;
        $config = self::dbqUpdateProductSearchText(null, $OriginID);
        return $app->getDB()->query($config);
    }

    // ------- QUERY CONFIGURATIONS ---------


    // products >>>>>
    private static function dbqProduct ($productID) {
        global $app;


        $dbq = new dbquery('shopProduct');
        $dbq->setSource('shop_products')
            ->addCondition('ID', $productID)
            ->addCondition('Status', self::getProductStatuses())
            ->setFields("ID", "CategoryID", "OriginID", "ExternalKey", "Name", "Synopsis",
                "Description", "Model", "SKU", "Price", "PrevPrice",
                "IsPromo", "IsFeatured", "IsOffer", "ShowBanner", "Status", "SearchText",
                "DateUpdated", "DateCreated")
            ->willBeSingleRow();


        // $isMultiple = is_array($productID);
        // $limit = $isMultiple ? count($productID) : 1;

        // $config = $app->getDB()->createOrGetQuery(array(
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
        //         "shop_products.ID" => $app->getDB()->createCondition($productID)
        //     );
        // }

        // $config['condition']['Status'] = $app->getDB()->createCondition(self::getProductStatuses());




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
        $config['condition']['Name'] = $app->getDB()->createCondition($productName);
        // $config['condition']['Status'] = $app->getDB()->createCondition(self::getProductStatuses(), 'IN');
        return $config;
    }
    private static function dbqProductByModel ($modelName) {
        global $app;
        $config = self::dbqProduct();
        $config['condition']['Model'] = $app->getDB()->createCondition($modelName);
        // $config['condition']['Status'] = $app->getDB()->createCondition(self::getProductStatuses(), 'IN');
        return $config;
    }
    private static function dbqProductByModelAndOrigin ($modelName, $originName) {
        global $app;
        $config = self::dbqProduct();
        $config['condition']['Model'] = $app->getDB()->createCondition($modelName);
        $config['condition']['shop_origins.Name'] = $app->getDB()->createCondition($originName);
        // $config['condition']['shop_products.Status'] = $app->getDB()->createCondition(self::getProductStatuses(), 'IN');
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
        $config['condition']["shop_products.ExternalKey"] = $app->getDB()->createCondition($externalKey);
        // $config['condition']['shop_products.Status'] = $app->getDB()->createCondition(self::getProductStatuses(), 'IN');
        // $config['additional'] = array();
        return $config;
    }
    private static function dbqCheckProductExistenceByID ($productID) {
        global $app;
        $config = self::dbqProduct($productID);
        $config['fields'] = array("ID");
        // $config['condition']['ID'] = $app->getDB()->createCondition($productID);
        // $config['condition']['Status'] = $app->getDB()->createCondition(self::getProductStatuses(), 'IN');
        $config['additional'] = array();
        return $config;
    }
    private static function dbqCheckProductExistenceByExternalKey ($eKey) {
        global $app;
        $config = self::dbqProductByExternalKey($eKey);
        $config['fields'] = array("ID");
        // $config['condition']['ID'] = $app->getDB()->createCondition($productID);
        // $config['condition']['Status'] = $app->getDB()->createCondition(self::getProductStatuses(), 'IN');
        $config['additional'] = array();
        return $config;
    }
    private static function dbqCheckProductExistenceByModelAndOrigin ($modelName, $originName) {
        global $app;
        $config = self::dbqProductByModelAndOrigin($modelName, $originName);
        $config['fields'] = array("ID");
        return $config;
    }
    private static function dbqProductShortInfo ($ProductID) {
        global $app;
        $config = self::dbqProduct($ProductID);
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
    public static function dbqProducts_MatchedIDs (array $options = array()) {
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
                $config['condition']["shop_products.Name"] = $app->getDB()->createCondition('%' . $options['_pSearch'] . '%');
                // $config['condition']["Model"] = $app->getDB()->createCondition('%' . $options['search'] . '%', 'like');
                // $config['condition']["SKU"] = $app->getDB()->createCondition('%' . $options['search'] . '%', 'like');
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
                            $config['condition'][$conditionField] = $app->getDB()->createCondition($valToSearch);
                        }
                    }
                    // $config['condition']["shop_products.Name"] = $app->getDB()->createCondition('%' . $value . '%', 'like');
                    // $config['condition']["shop_products.Model"] = $app->getDB()->createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["shop_products.Description"] = $app->getDB()->createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["shop_products.SKU"] = $app->getDB()->createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["Model"] = $app->getDB()->createCondition('%' . $value . '%', 'like');
                    // $config['condition']["SKU"] = $app->getDB()->createCondition('%' . $value . '%', 'like');
                }
            }
        }

        if (!empty($options['_pSearchText'])) {
            if (strlen($options['_pSearchText']) < 5) {
                return null;
            }
            $config['condition']["shop_products.SearchText"] = $app->getDB()->createCondition('%' . strtolower($options['_pSearchText']) . '%');
        }

        if (empty($options['_pStatus'])) {
            $config['condition']["shop_products.Status"] = $app->getDB()->createCondition('REMOVED', '!=');
        } else {
            $config['condition']["shop_products.Status"] = $app->getDB()->createCondition($options['_pStatus']);
        }

        if (!empty($options['_pCategoryExternalKey'])) {
            $config['condition']["shop_categories.ExternalKey"] = $app->getDB()->createCondition($options['_pCategoryExternalKey']);
        }

        // var_dump($config['condition']);
        return $config;
    }

    public static function dbqUpdateProductSearchText ($ProductID, $OriginID) {
        global $app;
        $data["shop_products.DateUpdated"] = $app->getDB()->getDate();
        $data["SearchText"] = "@LOWER(CONCAT_WS(' ', shop_products.Name, shop_origins.Name, shop_products.Model))";

        $config = $app->getDB()->createOrGetQuery(array(
            "source" => "shop_products",
            "action" => "update",
            "data" => $data,
            "options" => null
        ));
        $config['additional'] = array(
            "shop_origins" => array(
                "constraint" => array("shop_products.OriginID", "=", "shop_origins.ID"),
                "fields" => array('ID')
            )
        );
        if (isset($ProductID)) {
            $config['condition']["shop_products.ID"] = $app->getDB()->createCondition($ProductID);
        }
        if (isset($OriginID)) {
            $config['condition']["shop_products.OriginID"] = $app->getDB()->createCondition($OriginID);
        }
        // var_dump($config);
        return $config;
    }

    public static function updateProductExternalKeyByID ($ProductID, $ExternalKey) {
        global $app;
        $data = array();
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["ExternalKey"] = $ExternalKey;
        $config = $app->getDB()->createOrGetQuery(array(
            "source" => "shop_products",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($ProductID)
            ),
            "action" => "update",
            "data" => $data,
            "options" => null
        ));
        return $config;
    }

    public static function shopCreateProduct ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        $data["Name"] = substr($data["Name"], 0, 300);
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_products",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopUpdateProduct ($productID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        if (isset($data['Name'])) {
            $data["Name"] = substr($data["Name"], 0, 300);
        }
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_products",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($productID)
            ),
            "data" => $data,
            "options" => null
        ));
    }


    // products >>>>>


    // Product category (catalog)
    public static function getShopCatalogProductList ($ids) {
        global $app;
        $config = self::dbqProducts_MatchedIDs();
        if (is_array($ids)) {
            if (count($ids) > 1)
                $config['condition']["shop_products.CategoryID"] = $app->getDB()->createCondition($ids, "IN");
            else
                $config['condition']["shop_products.CategoryID"] = $app->getDB()->createCondition($ids[0]);
        } else {
            $config['condition']["shop_products.CategoryID"] = $app->getDB()->createCondition($ids);
        }
        return $config;
    }

    public static function getShopCategoryProductInfo () {
        $config = self::dbqProducts_MatchedIDs();
        $config['fields'] = array("ID");
        $config['limit'] = 0;
        $config['group'] = null;
        $config['options'] = null;
        return $config;
    }

    public static function dbqProductPrice ($id) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_products",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            ),
            "fields" => array("Price"),
            "offset" => 0,
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    // Product price stats >>>>>
    public static function dbqProductPriceStats ($id) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_productPrices",
            "condition" => array(
                "ProductID" => $app->getDB()->createCondition($id)
            ),
            "fields" => array("ID", "ProductID", "Price", "DateCreated"),
            "offset" => 0,
            "limit" => 50,
            "order" => $app->getDB()->createSortOrder('shop_productPrices.DateCreated'),
            "options" => array()
        ));
    }
    // <<<< Product price stats















    // Product relations >>>>>
    public static function dbqProductRelations ($id) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_relations",
            "condition" => array(
                "ProductA_ID" => $app->getDB()->createCondition($id)
            ),
            "fields" => array("ProductB_ID"),
            "offset" => 0,
            "limit" => 0
        ));
    }
    public static function shopClearProductRelations ($id) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "delete",
            "source" => "shop_relations",
            "condition" => array(
                "ProductA_ID" => $app->getDB()->createCondition($id)
            )
        ));
    }
    public static function shopSetRelatedProduct ($CustomerID, $id, $relatedProductID) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "insert",
            "source" => "shop_relations",
            "data" => array(
                "CustomerID" => $CustomerID,
                "ProductA_ID" => $id,
                "ProductB_ID" => $relatedProductID
            )
        ));
    }
    // <<<< Product relations













    // product features & attributes >>>>>
    public static function dbqProductFeatures ($id) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_productFeatures",
            "fields" => array("FeatureID"),
            'additional' => array(
                "shop_features" => array(
                    "constraint" => array("shop_productFeatures.FeatureID", "=", "shop_features.ID"),
                    "fields" => array("ID", "FieldName", "GroupName")
                )
            ),
            "condition" => array(
                "ProductID" => $app->getDB()->createCondition($id)
            ),
            "limit" => 0,
            "options" => array()
        ));
    }

    public static function dbqProductAttributes ($id = null, $type = null) {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_productAttributes",
            "condition" => array(),
            "fields" => array("ProductID", "Attribute", "Value"),
            "offset" => 0,
            "limit" => 50,
            "options" => array(
                "expandSingleRecord" => false
            )
        ));

        if (!empty($id)) {
            $config['condition']['ProductID'] = $app->getDB()->createCondition($id);
        }
        if (!empty($type)) {
            if (is_array($type)) {
                $config['condition']['Attribute'] = $app->getDB()->createCondition($type, 'IN');
            } else {
                $config['condition']['Attribute'] = $app->getDB()->createCondition($type);
            }
        }

        return $config;
    }

    public static function shopCreateFeature ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        $data["FieldName"] = substr($data["FieldName"], 0, 200);
        $data["GroupName"] = substr($data["GroupName"], 0, 100);
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_features",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopGetFeatures () {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_features",
            "fields" => array("ID", "FieldName", "GroupName"),
            "limit" => 0,
            "options" => array()
        ));
    }

    public static function shopAddFeatureToProduct ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_productFeatures",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopAddAttributeToProduct ($data) {
        global $app;
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_productAttributes",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopClearProductFeatures ($ProductID) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_productFeatures",
            "action" => "delete",
            "condition" => array(
                "ProductID" => $app->getDB()->createCondition($ProductID)
            ),
            "options" => null
        ));
    }

    public static function shopClearProductAttributes ($ProductID, $attributeType = false) {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
            "source" => "shop_productAttributes",
            "action" => "delete",
            "condition" => array(
                "ProductID" => $app->getDB()->createCondition($ProductID)
            ),
            "options" => null
        ));
        if (!empty($attributeType)) {
            $config['condition']['Attribute'] = $app->getDB()->createCondition(strtoupper($attributeType));
        }
        return $config;
    }
    // <<<< product features & attributes








    // Product category (catalog) >>>>>
    public static function shopCatalogBrands ($categoryID) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCatalogBrands",
                "parameters" => array($categoryID)
            )
        ));
    }

    public static function getShopCatalogPriceEdges ($categoryID) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCatalogPriceEdges",
                "parameters" => array($categoryID)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }
    // <<<< Product category (catalog)






    // Additional: category location >>>>>
    public static function shopCategoryLocationGet ($id) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCatalogLocation",
                "parameters" => array($id)
            )
        ));
    }
    // <<<< Additional: category location







    // Shop catalog tree >>>>>
    public static function shopCatalogTree ($selectedCategoryID = false) {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_categories",
            "condition" => array(
                "Status" => $app->getDB()->createCondition("ACTIVE")
            ),
            "fields" => array("ID", "ParentID", "ExternalKey", "Name", "Image", "Status"),
        ));
        if ($selectedCategoryID !== false) {
            $config["condition"]["ID"] = $app->getDB()->createCondition($selectedCategoryID);
        }
        return $config;
    }
    // <<<< Shop catalog tree
















    // shop cetegories >>>>>
    public static function shopGetCategoryItem ($id = null) {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_categories",
            "condition" => array(),
            "fields" => array("ID", "ParentID", "ExternalKey", "Name", "Description", "Image", "Status", "DateCreated", "DateUpdated"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));

        if (!is_null($id)) {
            $config["condition"] = array(
                "shop_categories.ID" => $app->getDB()->createCondition($id)
            );
        }

        return $config;
    }

    // TODO: optimmize list query
    public static function shopGetCategoryList (array $options = array()) {
        global $app;
        $config = self::shopGetCategoryItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['removed'])) {
            $config['condition']['Status'] = $app->getDB()->createCondition('ACTIVE');
        }
        return $config;
    }

    public static function shopCreateCategory ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        $data["Description"] = empty($data["Description"]) ? "" : $data["Description"];
        $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name'], array('transliterate' => true));
        $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
        $data["Name"] = substr($data["Name"], 0, 300);
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_categories",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopUpdateCategory ($CategoryID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        if (isset($data['Name'])) {
            $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name'], array('transliterate' => true));
            $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
            $data["Name"] = substr($data["Name"], 0, 300);
        }
        // var_dump($data);
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_categories",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($CategoryID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopDeleteCategory ($CategoryID) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_categories",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($CategoryID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }
    // shop cetegories <<<<<












    // shop origins <<<<<
    public static function shopGetOriginItem ($id = null) {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_origins",
            "condition" => array(),
            "fields" => array("ID", "ExternalKey", "Name", "Description", "HomePage", "Status", "DateCreated", "DateUpdated"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));

        if (!is_null($id)) {
            $config["condition"] = array(
                "shop_origins.ID" => $app->getDB()->createCondition($id)
            );
        }

        return $config;
    }

    // TODO: optimmize list query
    public static function shopGetOriginList (array $options = array()) {
        global $app;
        $config = self::shopGetOriginItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['removed'])) {
            $config['condition']['Status'] = $app->getDB()->createCondition('ACTIVE');
        }
        return $config;
    }

    public static function shopCreateOrigin ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        $data["Description"] = empty($data["Description"]) ? "" : $data["Description"];
        $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name'], array('transliterate' => true));
        $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
        $data["Name"] = substr($data["Name"], 0, 300);
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_origins",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopUpdateOrigin ($OriginID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        if (isset($data['Name'])) {
            $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name'], array('transliterate' => true));
            $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
        }
        if (isset($data["Name"])) {
            $data["Name"] = substr($data["Name"], 0, 300);
        }
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_origins",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($OriginID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopDeleteOrigin ($OriginID) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_origins",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($OriginID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }
    // shop origins <<<<<




















    // shop delivery agencies >>>>>
    public static function shopGetDeliveryAgencyByID ($id = null) {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_deliveryAgencies",
            "condition" => array(),
            "fields" => array("ID", "Name", "HomePage", "Status", "DateCreated", "DateUpdated"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));

        if (!is_null($id))
            $config["condition"]["ID"] = $app->getDB()->createCondition($id);

        return $config;
    }

    // TODO: optimmize list query
    public static function shopGetDeliveriesList (array $options = array()) {
        global $app;
        $config = self::shopGetDeliveryAgencyByID();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        return $config;
    }

    public static function shopCreateDeliveryAgent ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_deliveryAgencies",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopUpdateDeliveryAgent ($id, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_deliveryAgencies",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopDeleteDeliveryAgent ($id) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_deliveryAgencies",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }
    // <<<<< shop delivery agencies









    // shop settings >>>>>
    // public static function setting
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

    public static function isOneForCustomer ($type) {
        return !in_array($type, self::$ALLOW_MULTIPLE_SETTINGS);
    }
    public static function settingCanBeRemoved ($type) {
        return in_array($type, self::$ALLOW_SETTINGS_TO_DELETE);
    }

    public static function getVerifiedSettingsType ($type) {
        return isset(self::$SETTING_TYPE_TO_DBTABLE_MAP[$type]) ? $type : null;
    }

    public static function getSettingsDBTableNameByType ($type) {
        if (self::getVerifiedSettingsType($type)) {
            return self::$SETTING_TYPE_TO_DBTABLE_MAP[$type];
        }
        throw new Exception("Unknown shop settings type", 1);
    }

    public static function customerSettingsCount ($type) {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
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

    public static function shopGetSettingByID ($type, $id) {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
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
        $config["condition"]["ID"] = $app->getDB()->createCondition($id);
        return $config;
    }
    public static function shopGetSettingByType ($type) {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
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
    public static function shopGetSettingsAddressActive () {
        global $app;
        $config = self::shopGetSettingByType('ADDRESS');
        $config["condition"]["Status"] =  $app->getDB()->createCondition('ACTIVE');
        return $config;
    }
    public static function shopGetSettingsAddressPhones ($addressID) {
        global $app;
        $config = self::shopGetSettingByType('PHONES');
        $config["condition"]["ShopAddressID"] = $app->getDB()->createCondition($addressID);
        return $config;
    }
    public static function shopGetSettingsAddressOpenHours ($addressID) {
        global $app;
        $config = self::shopGetSettingByType('OPENHOURS');
        $config["condition"]["ShopAddressID"] = $app->getDB()->createCondition($addressID);
        $config["limit"] = 1;
        $config["options"]["expandSingleRecord"] = true;
        return $config;
    }
    public static function shopGetSettingsAddressInfo ($addressID) {
        global $app;
        $config = self::shopGetSettingByType('INFO');
        $config["condition"]["ShopAddressID"] = $app->getDB()->createCondition($addressID);
        $config["limit"] = 1;
        $config["options"]["expandSingleRecord"] = true;
        return $config;
    }
    public static function shopCreateSetting ($type, $data) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "source" => self::getSettingsDBTableNameByType($type),
            "action" => "insert",
            "data" => $data,
            "options" => null,
            "saveOptions" => array(
                "useInsertIgnore" => true
            )
        ));
    }

    public static function shopUpdateSetting ($type, $id, $data) {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
            "source" => self::getSettingsDBTableNameByType($type),
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
        return $config;
    }

    public static function shopRemoveSetting ($type, $id) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "source" => self::getSettingsDBTableNameByType($type),
            "action" => "delete",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            )
        ));
    }
    // <<<<< shop delivery agencies










    // Shop order >>>>>
    public static function shopGetOrderItem ($orderID = null) {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_orders",
            "condition" => array(),
            "fields" => array("ID", "UserID", "UserAddressesID", "DeliveryID", "ExchangeRateID", "CustomerCurrencyRate", "CustomerCurrencyName", "Warehouse", "Comment", "InternalComment", "Status", "Hash", "PromoID", "DateCreated", "DateUpdated"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));

        if (!is_null($orderID))
            $config["condition"] = array(
                "shop_orders.ID" => $app->getDB()->createCondition($orderID)
            );

        return $config;
    }

    // TODO: optimmize list query
    public static function getShopOrderList (array $options = array()) {
        global $app;
        $config = self::shopGetOrderItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (!empty($options['_pSearch'])) {
            if (is_string($options['_pSearch'])) {
                $config['condition']["Hash"] = $app->getDB()->createCondition($options['_pSearch'] . '%', 'like');
                // $config['condition']["Model"] = $app->getDB()->createCondition('%' . $options['search'] . '%', 'like');
                // $config['condition']["SKU"] = $app->getDB()->createCondition('%' . $options['search'] . '%', 'like');
            } elseif (is_array($options['_pSearch'])) {
                foreach ($options['_pSearch'] as $value) {
                    $config['condition']["Hash"] = $app->getDB()->createCondition($value . '%', 'like');
                    // $config['condition']["Model"] = $app->getDB()->createCondition('%' . $value . '%', 'like');
                    // $config['condition']["SKU"] = $app->getDB()->createCondition('%' . $value . '%', 'like');
                }
            }
        }
        // select for specific user
        if (!empty($options['_pUser'])) {
            $config['condition']['UserID'] = $app->getDB()->createCondition($options['_pUser']);
        }
        return $config;
    }
    // TODO: optimmize list query
    public static function getShopOrderList_Pending () {
        global $app;
        $config = self::getShopOrderList();
        $config['condition']['Status'] = $app->getDB()->createCondition('NEW');
        return $config;
    }
    // TODO: optimmize list query
    public static function getShopOrderList_Todays () {
        global $app;
        $config = self::getShopOrderList();
        $config['condition']['DateCreated'] = $app->getDB()->createCondition(date('Y-m-d'), ">");
        return $config;
    }
    // TODO: optimmize list query
    public static function getShopOrderList_Expired () {
        global $app;
        $config = self::getShopOrderList();
        $config['condition']['Status'] = $app->getDB()->createCondition(array("SHOP_CLOSED", "SHOP_REFUNDED", "CUSTOMER_CANCELED"), "NOT IN");
        $config['condition']['DateCreated'] = $app->getDB()->createCondition(date('Y-m-d', strtotime("-1 week")), "<");
        return $config;
    }
    // TODO: optimmize list query
    public static function getShopOrderList_ForUser ($userID) {
        global $app;
        $config = self::getShopOrderList();
        $config['condition']['UserID'] = $userID;
        return $config;
    }
    public static function shopCreateOrder ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        $data["Hash"] = substr(md5(time() . md5(time())), 0, 5);
        // adjust values
        if (is_string($data["DeliveryID"])) {
            $data["DeliveryID"] = null;
        }
        if (is_string($data["Warehouse"])) {
            $data["Warehouse"] = null;
        }
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_orders",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }
    public static function shopCreateOrderBought ($data) {
        global $app;
        $data["DateCreated"] = $app->getDB()->getDate();
        $data["IsPromo"] = empty($data["IsPromo"]) ? 0 : 1;
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_boughts",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }
    public static function shopGetOrderBoughts ($orderID) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_boughts",
            "condition" => array(
                "OrderID" => $app->getDB()->createCondition($orderID)
            ),
            "fields" => array("ID", "ProductID", "Price", "SellingPrice", "Quantity", "IsPromo", "DateCreated"),
            "offset" => 0,
            "limit" => 0
        ));
    }
    public static function getShopOrderByHash ($orderHash) {
        global $app;
        $config = self::shopGetOrderItem();
        $config['condition'] = array(
            "Hash" => $app->getDB()->createCondition($orderHash)
        );
        $config['options'] = array(
            "expandSingleRecord" => true
        );
        $config['limit'] = 1;
        return $config;
    }
    public static function shopUpdateOrder ($orderID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        return $app->getDB()->createOrGetQuery(array(
            "action" => "update",
            "source" => "shop_orders",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($orderID)
            ),
            "data" => $data,
            "options" => null
        ));
    }
    public static function shopDisableOrder ($OrderID) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_orders",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($OrderID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }
    // <<<< Shop order













    // >>>> Shop statistics
    // TODO: optimmize list query
    public static function shopStat_PopularProducts (array $options = array()) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_boughts",
            "fields" => array("ProductID", "@SUM(Quantity) AS SoldTotal", "@SUM(shop_boughts.Price * Quantity) AS SumTotal"),
            "condition" => array(
                "shop_products.Status" => $app->getDB()->createCondition(array("REMOVED", "ARCHIVED"), "NOT IN")
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
    public static function shopStat_NonPopularProducts (array $options = array()) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_products",
            "fields" => array("ID"),
            "condition" => array(
                "Status" => $app->getDB()->createCondition("ACTIVE"),
                "ID" => $app->getDB()->createCondition("SELECT ProductID AS ID FROM shop_boughts", "NOT IN")
            ),
            "order" => array(
                "field" => "DateCreated",
                "ordering" => "ASC"
            ),
            "limit" => !empty($options['limit']) ? $options['limit'] : 15,
            "options" => null
        ));
    }

    public static function shopStat_ProductsOverview ($filter = null) {
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
                $config['condition']['CategoryID'] = $app->getDB()->createCondition($filter['_fCategoryID']);
        }
        return $config;
    }

    public static function shopStat_OrdersOverview () {
        global $app;
        $config = self::shopGetOrderItem();
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

    public static function shopStat_OrdersIntensityLastMonth ($status, $comparator = null) {
        global $app;
        if (!is_string($comparator))
            $comparator = $app->getDB()->DEFAULT_COMPARATOR;
        $config = self::shopGetOrderItem();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "@Date(DateUpdated) AS CloseDate");
        $config['condition'] = array(
            'Status' => $app->getDB()->createCondition($status, $comparator),
            'DateUpdated' => $app->getDB()->createCondition(date('Y-m-d', strtotime("-1 month")), ">")
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

    public static function shopStat_ProductsIntensityLastMonth ($status) {
        global $app;
        $config = self::dbqProduct();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "@Date(DateUpdated) AS CloseDate");
        $config['condition'] = array(
            'Status' => $app->getDB()->createCondition($status),
            'DateUpdated' => $app->getDB()->createCondition(date('Y-m-d', strtotime("-1 month")), ">")
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
    public static function shopGetPromoByHash ($hash, $activeOnly) {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_promo",
            "condition" => array(
                "Code" => $app->getDB()->createCondition($hash)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));

        if ($activeOnly) {
            $config['condition']['DateStart'] = $app->getDB()->createCondition($app->getDB()->getDate(), '<=');
            $config['condition']['DateExpire'] = $app->getDB()->createCondition($app->getDB()->getDate(), '>=');
        }

        return $config;
    }

    public static function shopGetPromoByID ($promoID = null) {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_promo",
            "condition" => array(),
            "fields" => array("ID", "Code", "DateStart", "DateExpire", "Discount", "DateCreated"),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));

        if (!is_null($promoID))
            $config["condition"] = array(
                "ID" => $app->getDB()->createCondition($promoID)
            );
        return $config;
    }

    // TODO: optimmize list query
    public static function shopGetPromoList (array $options = array()) {
        global $app;
        $config = self::shopGetPromoByID();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['expired'])) {
            $config['condition']['DateExpire'] = $app->getDB()->createCondition($app->getDB()->getDate(), '>=');
        }
        return $config;
    }

    public static function shopCreatePromo ($data) {
        global $app;
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createOrGetQuery(array(
            "action" => "insert",
            "source" => "shop_promo",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopUpdatePromo ($promoID, $data) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "update",
            "source" => "shop_promo",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($promoID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopExpirePromo ($promoID) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "action" => "update",
            "source" => "shop_promo",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($promoID)
            ),
            "data" => array(
                "DateExpire" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }
    // Promo area >>>>>






















    // shop delivery agencies >>>>>
    public static function shopGetExchangeRateByID ($id = null) {
        global $app;
        $config = $app->getDB()->createOrGetQuery(array(
            "action" => "select",
            "source" => "shop_currency",
            "condition" => array(),
            "fields" => array("ID", "CurrencyA", "CurrencyB", "Rate"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));

        if (!is_null($id))
            $config["condition"]["ID"] = $app->getDB()->createCondition($id);

        return $config;
    }
    public static function shopGetExchangeRateTo_ByCurrencyName ($currencyNameTo = null) {
        global $app;
        $config = self::shopGetExchangeRateByID();
        $config["condition"] = array(
            "CurrencyB" => $app->getDB()->createCondition($currencyNameTo)
        );
        return $config;
    }
    public static function shopGetExchangeRateFrom_ByCurrencyName ($currencyNameFrom = null) {
        global $app;
        $config = self::shopGetExchangeRateByID();
        $config["condition"] = array(
            "CurrencyA" => $app->getDB()->createCondition($currencyNameFrom)
        );
        return $config;
    }
    public static function shopGetExchangeRateByBothNames ($currencyNameFrom, $currencyNameTo) {
        global $app;
        $config = self::shopGetExchangeRateByID();
        $config["condition"] = array(
            "CurrencyA" => $app->getDB()->createCondition($currencyNameFrom),
            "CurrencyB" => $app->getDB()->createCondition($currencyNameTo)
        );
        return $config;
    }

    // TODO: optimmize list query
    public static function shopGetExchangeRatesList (array $options = array()) {
        global $app;
        $config = self::shopGetExchangeRateByID();
        $config['fields'] = array("ID");
        $config['limit'] = 64; // assume that 64 ex.rates nobody will have
        $config['options']['expandSingleRecord'] = false;
        if (isset($options['fields'])) {
            $config['fields'] = $options['fields'];
        }
        if (isset($options['limit'])) {
            $config['limit'] = $options['limit'];
        }
        if (empty($options['removed'])) {
            $config['condition']['Status'] = $app->getDB()->createCondition('ACTIVE');
        }
        return $config;
    }

    public static function shopGetUniqueAvailableCurrencyNamesByField ($fieldToGroupBy) {
        global $app;
        $config = self::shopGetExchangeRateByID();
        $config['fields'] = array($fieldToGroupBy);
        $config['limit'] = 0;
        $config['group'] = $fieldToGroupBy;
        $config['options']['expandSingleRecord'] = false;
        return $config;
    }

    public static function shopCreateExchangeRate ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_currency",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopUpdateExchangeRate ($id, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_currency",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopDeleteExchangeRate ($id) {
        global $app;
        return $app->getDB()->createOrGetQuery(array(
            "source" => "shop_currency",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }
    // <<<<< shop delivery agencies





}

?>
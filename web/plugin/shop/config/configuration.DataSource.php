<?php

class configurationShopDataSource extends objectConfiguration {

    static $Table_ShopOrders = "shop_orders";
    static $Table_ShopProducts = "shop_products";
    static $Table_ShopOrigins = "shop_origins";
    static $Table_ShopCategories = "shop_categories";
    static $Table_ShopProductAttr = "shop_productAttributes";
    static $Table_ShopDeliveryAgency = "shop_deliveryAgencies";
    static $Table_ShopSettings = "shop_settings";

    // Product base configuration >>>>>
    static function jsapiShopSettings ($id) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_settings",
            "fields" => array("ID", "Property", "Value"),
            "limit" => 0,
            "options" => array(
                "asDict" => array(
                    "keys" => "Property",
                    "values" => "Value"
                )
            )
        ));
    }
    static function jsapiShopGetProductItem ($productID = null) {
        $config = self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_products",
            "fields" => array("ID", "CategoryID", "OriginID", "ExternalKey", "Name", "Description", "Model", "SKU", "Price", "IsPromo", "Status", "DateUpdated", "DateCreated"),
            "offset" => 0,
            "limit" => 1,
            "additional" => array(
                "shop_categories" => array(
                    "constraint" => array("shop_categories.ID", "=", "shop_products.CategoryID"),
                    "fields" => array(
                        "CategoryName" => "Name",
                        "CategoryDescription" => "Description",
                        "CategoryStatus" => "Status"
                    )
                ),
                "shop_origins" => array(
                    "constraint" => array("shop_origins.ID", "=", "shop_products.OriginID"),
                    "fields" => array(
                        "OriginName" => "Name",
                        "OriginDescription" => "Description",
                        "OriginStatus" => "Status"
                    )
                )
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));

        if (!is_null($productID))
            $config["condition"] = array(
                "shop_products.ID" => self::jsapiCreateDataSourceCondition($productID)
            );

        return $config;
    }
    // <<<< Product base configuration

    // Product base list configuration >>>>>
    static function jsapiShopGetProductList ($useFeatures = true) {
        $config = self::jsapiShopGetProductItem();
        $config['condition'] = array();
        $config["fields"] = array("ID");
        $config['limit'] = 64;
        $config['group'] = 'shop_products.ID';
        $config['additional'] = array(
            "shop_categories" => array(
                "constraint" => array("shop_products.CategoryID", "=", "shop_categories.ID"),
                "fields" => array("Status")
            ),
            "shop_origins" => array(
                "constraint" => array("shop_products.OriginID", "=", "shop_origins.ID"),
                "fields" => array("Status")
            ),
            "shop_productFeatures" => array(
                "constraint" => array("shop_products.ID", "=", "shop_productFeatures.ProductID"),
                "fields" => array("FeatureID")
            )
        );
        unset($config['options']);

        if (!$useFeatures)
            unset($config['additional']['shop_productFeatures']);

        return $config;
    }
    // <<<< Product base list configuration

    // Product category (catalog)
    static function jsapiGetShopCategoryProductList ($ids) {
        $config = self::jsapiShopGetProductList();
        $config['condition']["shop_products.CategoryID"] = self::jsapiCreateDataSourceCondition($ids, "IN");
        return $config;
    }

    static function jsapiGetShopCategoryProductInfo ($ids) {
        $config = self::jsapiGetShopCategoryProductList($ids);
        $config["fields"] = array("ID", "CategoryID", "OriginID");
        $config['limit'] = 0;
        return $config;
    }

    // Product additional information >>>>>
    static function jsapiShopGetProductAttributes ($id) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_productAttributes",
            "condition" => array(
                "Status" => self::jsapiCreateDataSourceCondition('ACTIVE'),
                "ProductID" => self::jsapiCreateDataSourceCondition($id)
            ),
            "fields" => array(
                "ProductID",
                "@GROUP_CONCAT(Attribute SEPARATOR \"#EXPLODE#\") AS `Attributes`",
                "@GROUP_CONCAT(Value SEPARATOR \"#EXPLODE#\") AS `Values`"
            ),
            "offset" => 0,
            "limit" => 10,
            "group" => "ProductID",
            "options" => array(
                "expandSingleRecord" => true,
                "combineDataByKeys" => array(
                    "mapKeysToCombine" => array(
                        "ProductAttributes" => array(
                            "keys" => "Attributes",
                            "values" => "Values"
                        )
                    )
                )
            )
        ));
    }
    // <<<< Product additional information

    // Product price stats >>>>>
    static function jsapiShopGetProductPriceStats ($id) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_productPrices",
            "condition" => array(
                "ProductID" => self::jsapiCreateDataSourceCondition($id)
            ),
            "fields" => array(
                "ID",
                "ProductID",
                "@GROUP_CONCAT(DISTINCT Price ORDER BY DateCreated ASC SEPARATOR \"#EXPLODE#\") AS PriceArchive"
            ),
            "offset" => 0,
            "limit" => 10,
            "group" => "ProductID",
            "order" => array(
                "field" => "shop_productPrices.DateCreated",
                "ordering" => "ASC"
            ),
            "options" => array(
                "expandSingleRecord" => true,
                "transformToArray" => array("PriceArchive")
            )
        ));
    }
    // <<<< Product price stats

    // Single prouct info >>>>>
    static function jsapiShopGetProductSingleInfo ($id) {
        $config = self::jsapiShopGetProductItem($id);
        $config['fields'] = array("CategoryID", "Name");
        unset($config['additional']);
        return $config;
    }
    // <<<< Single prouct info

    static function jsapiShopGetProductFeatures ($id) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_productFeatures",
            "fields" => array("FeatureID"),
            'additional' => array(
                "shop_features" => array(
                    "constraint" => array("shop_productFeatures.FeatureID", "=", "shop_features.ID"),
                    "fields" => array("FieldName")
                )
            ),
            "condition" => array(
                "ProductID" => self::jsapiCreateDataSourceCondition($id)
            ),
            "limit" => 0,
            "options" => array(
                "asDict" => array(
                    "keys" => "FeatureID",
                    "values" => "FieldName"
                )
            )
        ));
    }

    // Product relations >>>>>
    static function jsapiShopGetProductRelations ($id) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_relations",
            "condition" => array(
                "ProductA_ID" => self::jsapiCreateDataSourceCondition($id)
            ),
            "fields" => array("ProductB_ID"),
            "offset" => 0,
            "limit" => 0
        ));
    }
    // <<<< Product relations












    // Product category (catalog) >>>>>
    static function jsapiShopCategoryAndSubCategoriesAllBrandsGet ($categoryID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getAllShopCategoryBrandsWithSubCategories",
                "parameters" => array($categoryID)
            )
        ));
    }

    static function jsapiShopCategoryAllSubCategoriesGet ($categoryID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getAllShopCategorySubCategories",
                "parameters" => array($categoryID)
            )
        ));
    }

    static function jsapiShopCategoryPriceEdgesGet ($categoryID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCategoryPriceEdges",
                "parameters" => array($categoryID)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }
    // <<<< Product category (catalog)

    // Additional: category location >>>>>
    static function jsapiShopCategoryLocationGet ($id) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCategoryLocation",
                "parameters" => array($id)
            )
        ));
    }
    // <<<< Additional: category location

    // Shop catalog tree >>>>>
    static function jsapiShopCatalogTree () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_categories",
            "condition" => array(
                "Status" => self::jsapiCreateDataSourceCondition("ACTIVE")
            ),
            "fields" => array("ID", "ParentID", "ExternalKey", "Name", "Status"),
        ));
    }
    // <<<< Shop catalog tree
















    static function jsapiShopGetCategoryItem ($id = null) {
        $config = self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_categories",
            "condition" => array(),
            "fields" => array("ID", "ParentID", "ExternalKey", "Name", "Description", "Status", "DateCreated", "DateUpdated"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));

        if (!is_null($id)) {
            $config["condition"] = array(
                "shop_categories.ID" => self::jsapiCreateDataSourceCondition($id)
            );
        }

        return $config;
    }

    static function jsapiShopGetCategoryList () {
        $config = self::jsapiShopGetCategoryItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        return $config;
    }

    static function jsapiShopGetCategoryAll ($includeRemoved = false) {
        $config = self::jsapiShopGetCategoryList();
        $config['limit'] = 0;
        if (!$includeRemoved) {
            $config['condition']['Status'] = self::jsapiCreateDataSourceCondition('ACTIVE');
        }
        return $config;
    }

    static function jsapiShopCreateCategory ($data) {
        $data["DateUpdated"] = self::getDate();
        $data["DateCreated"] = self::getDate();
        $data["ExternalKey"] = libraryUtils::url_slug($data['Name']);
        $data["Description"] = empty($data["Description"]) ? "" : $data["Description"];
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_categories",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopUpdateCategory ($CategoryID, $data) {
        $data["DateUpdated"] = self::getDate();
        if (isset($data['Name']))
            $data["ExternalKey"] = libraryUtils::url_slug($data['Name']);
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_categories",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($CategoryID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopDeleteCategory ($CategoryID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_categories",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($CategoryID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => self::getDate()
            ),
            "options" => null
        ));
    }












    static function jsapiShopGetOriginItem ($id = null) {
        $config = self::jsapiGetDataSourceConfig(array(
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
                "shop_origins.ID" => self::jsapiCreateDataSourceCondition($id)
            );
        }

        return $config;
    }

    static function jsapiShopGetOriginList () {
        $config = self::jsapiShopGetOriginItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        return $config;
    }

    static function jsapiShopGetOriginAll ($includeRemoved = false) {
        $config = self::jsapiShopGetOriginList();
        $config['limit'] = 0;
        if (!$includeRemoved) {
            $config['condition']['Status'] = self::jsapiCreateDataSourceCondition('ACTIVE');
        }
        return $config;
    }

    static function jsapiShopCreateOrigin ($data) {
        $data["DateUpdated"] = self::getDate();
        $data["DateCreated"] = self::getDate();
        $data["ExternalKey"] = libraryUtils::url_slug($data['Name']);
        $data["Description"] = empty($data["Description"]) ? "" : $data["Description"];
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_origins",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopUpdateOrigin ($OriginID, $data) {
        $data["DateUpdated"] = self::getDate();
        if (isset($data['Name']))
            $data["ExternalKey"] = libraryUtils::url_slug($data['Name']);
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_origins",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($OriginID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopDeleteOrigin ($OriginID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_origins",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($OriginID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => self::getDate()
            ),
            "options" => null
        ));
    }













    // Shop order >>>>>
    static function jsapiShopGetOrderItem ($orderID = null) {
        $config = self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_orders",
            "condition" => array(),
            "fields" => array("ID", "AccountID", "AccountAddressesID", "Shipping", "Warehouse", "Comment", "InternalComment", "Status", "Hash", "PromoID", "DateCreated", "DateUpdated"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));

        if (!is_null($orderID))
            $config["condition"] = array(
                "shop_orders.ID" => self::jsapiCreateDataSourceCondition($orderID)
            );

        return $config;
    }
    static function jsapiGetShopOrderList () {
        $config = self::jsapiShopGetOrderItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        return $config;
    }
    static function jsapiGetShopOrderList_Pending () {
        $config = self::jsapiGetShopOrderList();
        $config['condition']['Status'] = self::jsapiCreateDataSourceCondition('NEW');
        return $config;
    }
    static function jsapiGetShopOrderList_Todays () {
        $config = self::jsapiGetShopOrderList();
        $config['condition']['DateCreated'] = self::jsapiCreateDataSourceCondition(date('Y-m-d'), ">");
        return $config;
    }
    static function jsapiGetShopOrderList_Expired () {
        $config = self::jsapiGetShopOrderList();
        $config['condition']['Status'] = self::jsapiCreateDataSourceCondition("SHOP_CLOSED", "!=");
        $config['condition']['DateCreated'] = self::jsapiCreateDataSourceCondition(date('Y-m-d', strtotime("-1 week")), "<");
        return $config;
    }
    static function jsapiShopCreateOrder ($data) {
        $data["DateUpdated"] = self::getDate();
        $data["DateCreated"] = self::getDate();
        $data["Hash"] = md5(time() . md5(time()));
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_orders",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }
    static function jsapiShopCreateOrderBought ($data) {
        $data["DateCreated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_boughts",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }
    static function jsapiShopGetOrderBoughts ($orderID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_boughts",
            "condition" => array(
                "OrderID" => self::jsapiCreateDataSourceCondition($orderID)
            ),
            "fields" => array("ID", "ProductID", "Price", "SellingPrice", "Quantity", "IsPromo", "DateCreated"),
            "offset" => 0,
            "limit" => 0
        ));
    }
    static function jsapiGetShopOrderByHash ($orderHash) {
        $config = self::jsapiShopGetOrderItem();
        $config['condition'] = array(
            "Hash" => self::jsapiCreateDataSourceCondition($orderHash)
        );
        $config['options'] = array(
            "expandSingleRecord" => true
        );
        $config['limit'] = 1;
        return $config;
    }
    static function jsapiShopUpdateOrder ($orderID, $data) {
        $data["DateUpdated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "action" => "update",
            "source" => "shop_orders",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($orderID)
            ),
            "data" => $data,
            "options" => null
        ));
    }
    static function jsapiShopDisableOrder ($OrderID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_orders",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($OrderID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => self::getDate()
            ),
            "options" => null
        ));
    }
    // <<<< Shop order


    // >>>> Shop statistics
    static function jsapiShopStat_PopularProducts () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_boughts",
            "fields" => array("ProductID", "@SUM(Quantity) AS SoldTotal", "@SUM(Price * Quantity) AS SumTotal"),
            "order" => array(
                "field" => "SoldTotal",
                "ordering" => "DESC"
            ),
            "limit" => 15,
            "group" => "ProductID",
            "options" => null
        ));
    }

    static function jsapiShopStat_NonPopularProducts () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_products",
            "fields" => array("ID"),
            "condition" => array(
                "Status" => self::jsapiCreateDataSourceCondition("ACTIVE"),
                "ID" => self::jsapiCreateDataSourceCondition("SELECT ProductID AS ID FROM shop_boughts", "NOT IN")
            ),
            "order" => array(
                "field" => "DateCreated",
                "ordering" => "ASC"
            ),
            "limit" => 15,
            "options" => null
        ));
    }

    static function jsapiShopStat_ProductsOverview ($filter = null) {
        $config = self::jsapiShopGetProductItem();
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
                $config['condition']['CategoryID'] = self::jsapiCreateDataSourceCondition($filter['_fCategoryID']);
        }
        return $config;
    }

    static function jsapiShopStat_OrdersOverview () {
        $config = self::jsapiShopGetOrderItem();
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

    static function jsapiShopStat_OrdersIntensityLastMonth ($status, $comparator = null) {
        if (!is_string($comparator))
            $comparator = self::$DEFAULT_COMPARATOR;
        $config = self::jsapiShopGetOrderItem();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "@Date(DateUpdated) AS CloseDate");
        $config['condition'] = array(
            'Status' => self::jsapiCreateDataSourceCondition($status, $comparator),
            'DateUpdated' => self::jsapiCreateDataSourceCondition(date('Y-m-d', strtotime("-1 month")), "<=")
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
        return $config;
    }

    static function jsapiShopStat_ProductsIntensityLastMonth ($status) {
        $config = self::jsapiShopGetProductItem();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "@Date(DateUpdated) AS CloseDate");
        $config['condition'] = array(
            'Status' => self::jsapiCreateDataSourceCondition($status),
            'DateUpdated' => self::jsapiCreateDataSourceCondition(date('Y-m-d', strtotime("-1 month")), "<=")
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
    static function jsapiShopGetPromoByHash ($hash, $activeOnly) {
        $config = self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_promo",
            "condition" => array(
                "Code" => self::jsapiCreateDataSourceCondition($hash)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));

        if ($activeOnly) {
            $config['condition']['DateExpire'] = self::jsapiCreateDataSourceCondition(self::getDate(), '>=');
            $config['condition']['DateStart'] = self::jsapiCreateDataSourceCondition(self::getDate(), '<=');
        }

        return $config;
    }

    static function jsapiShopGetPromoByID ($promoID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_promo",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($promoID)
            ),
            "fields" => array("ID", "Code", "DateStart", "DateExpire", "Discount", "DateCreated"),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    static function jsapiShopCreatePromo ($data) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "insert",
            "source" => "shop_promo",
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopUpdatePromo ($promoID, $data) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "update",
            "source" => "shop_promo",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($promoID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopClosePromo ($promoID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "update",
            "source" => "shop_promo",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($promoID)
            ),
            "data" => array(
                "DateExpire" => self::getDate()
            ),
            "options" => null
        ));
    }
    // Promo area >>>>>












    // >>>> Origins
    static function jsapiShop_GetOrigin ($originID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_origins",
            "fields" => array("ID", "Name", "Description", "HomePage", "Status"),
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($originID)
            ),
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }
    static function jsapiShop_GetOriginList () {
        $config = self::jsapiShopOriginGet(null);
        $config['condition'] = array(
            "filter" => "",
            "values" => array()
        );
        unset($config['options']);
        $config['limit'] = 0;
        return $config;
    }
    static function jsapiShop_CreateOrigin ($data) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "insert",
            "source" => "shop_origins",
            "data" => $data,
            "options" => null
        ));
    }
    static function jsapiShop_UpdateOrigin ($originID, $data) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "update",
            "source" => "shop_origins",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($originID)
            ),
            "data" => $data,
            "options" => null
        ));
    }
    // <<<< Origins
}

?>
<?php

class configurationShopDataSource extends objectConfiguration {

    static $Table_ShopOrders = "shop_orders";
    static $Table_ShopProducts = "shop_products";
    static $Table_ShopOrigins = "shop_origins";

    // Product base configuration >>>>>
    static function jsapiShopProductItemGet ($id) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_products",
            "condition" => array(
                "shop_products.ID" => self::jsapiCreateDataSourceCondition($id),
                "shop_products.Status" => self::jsapiCreateDataSourceCondition('ACTIVE')
            ),
            "fields" => array("ID", "CategoryID", "OriginID", "ExternalKey", "Name", "Description", "Specifications", "Model", "SKU", "Price", "Status", "SellMode", "DateUpdated", "DateCreated"),
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
    }
    // <<<< Product base configuration

    // Product base list configuration >>>>>
    static function jsapiShopProductListGet () {
        $config = self::jsapiShopProductItemGet();
        unset($config['options']);
        $config['condition'] = array(
            "shop_products.Status" => self::jsapiCreateDataSourceCondition('ACTIVE'),
            "shop_categories.Status" => self::jsapiCreateDataSourceCondition('ACTIVE'),
            "shop_origins.Status" => self::jsapiCreateDataSourceCondition('ACTIVE')
        );
        $config['limit'] = 64;
        return $config;
    }
    // <<<< Product base list configuration

    // Product list of recently added products >>>>>
    static function jsapiShopProductListGetLatestGet () {
        $config = self::jsapiShopProductListGet();
        $config['order'] = array(
            "field" => "shop_products.DateCreated",
            "ordering" => "DESC"
        );
        return $config;
    }
    // <<<< Product list of recently added products

    // Product category (catalog)
    static function jsapiShopProductListGetCategoryGet ($id) {
        $config = self::jsapiShopProductListGet();
        $config['condition']["shop_products.CategoryID"] = self::jsapiCreateDataSourceCondition($id, "IN");
        // var_dump($config);
        return $config;
    }

    static function jsapiShopProductListGetCategoryGetInfoGet () {
        $config = self::jsapiShopProductListGetCategoryGet();
        $config["fields"] = array("ID", "CategoryID", "OriginID");
        $config['limit'] = 0;
        return $config;
    }

    static function jsapiShopCategoryAllBrandsGet () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getAllShopCategoryBrands",
                "parameters" => array()
            )
        ));
    }

    static function jsapiShopCategoryAllSubCategoriesGet () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getAllShopCategorySubCategories",
                "parameters" => array()
            )
        ));
    }

    static function jsapiShopCategoryPriceEdgesGet () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCategoryPriceEdges",
                "parameters" => array()
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }
    // <<<< Product category (catalog)

    // Product additional information >>>>>
    static function jsapiShopProductAttributesGet ($id) {
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
    static function jsapiShopProductPriceStatsGet ($id) {
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
                "transformToArray" => array("PriceArchive")
            )
        ));
    }
    // <<<< Product price stats

    // Single prouct info >>>>>
    static function jsapiShopProductSingleInfoGet ($id) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_products",
            "condition" => array(
                "shop_products.ID" => self::jsapiCreateDataSourceCondition($id),
                "shop_products.Status" => self::jsapiCreateDataSourceCondition("ACTIVE")
            ),
            "fields" => array("CategoryID", "Name"),
            "offset" => 0,
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }
    // <<<< Single prouct info

    // Additional: category location >>>>>
    static function jsapiShopCategoryLocationGet () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCategoryLocation",
                "parameters" => array()
            )
        ));
    }
    // <<<< Additional: category location

    // Shop catalog >>>>>
    static function jsapiShopCatalogStructureGet () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_categories",
            "condition" => array(
                "Status" => self::jsapiCreateDataSourceCondition("ACTIVE")
            ),
            "fields" => array("ID", "RootID", "ParentID", "ExternalKey", "Name", "Status"),
        ));
    }
    // <<<< Shop catalog

    // Shop order >>>>>
    static function jsapiShopOrderCreate () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "insert",
            "source" => "shop_orders",
            "data" => array(),
            "options" => null
        ));
    }
    static function jsapiShopOrderProductsCreate () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "insert",
            "source" => "shop_boughts",
            "data" => array(),
            "options" => null
        ));
    }
    static function jsapiShopOrdersGetStatus ($orderHash) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_orders",
            "condition" => array(
                "Hash" => self::jsapiCreateDataSourceCondition($orderHash)
            ),
            "fields" => array("ID", "Shipping", "Warehouse", "Comment", "Status", "Hash", "DateCreated", "DateUpdated"),
            "offset" => 0,
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }
    static function jsapiShopBoughtsGet ($orderID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_boughts",
            "condition" => array(
                "OrderID" => self::jsapiCreateDataSourceCondition($orderID)
            ),
            "fields" => array("ID", "ProductID", "ProductPrice", "Quantity"),
            "offset" => 0,
            "limit" => 0
        ));
    }
    static function jsapiShopOrderGet ($orderID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_orders",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($orderID)
            ),
            "fields" => array("ID", "AccountID", "AccountAddressesID", "Shipping", "Warehouse", "Comment", "Status", "Hash", "DateCreated", "DateUpdated"),
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    static function jsapiShopOrdersGet () {
        $config = self::jsapiShopOrderGet(null);
        $config['condition'] = array();
        $config["order"] = array(
            "field" => "shop_orders.DateCreated",
            "ordering" => "DESC"
        );
        $config['options'] = null;
        $config['limit'] = 0;
        return $config;
    }

    static function jsapiShopOrdersForProfileGet ($profileID) {
        $config = self::jsapiShopOrdersGet();
        $config['condition'] = array(
            "AccountID" => self::jsapiCreateDataSourceCondition($profileID)
        );
        $config["order"] = array(
            "field" => "shop_orders.ID",
            "ordering" => "DESC"
        );
        return $config;
    }

    static function jsapiShopOrdersForSiteGet () {
        $config = self::jsapiShopOrdersGet();
        return $config;
    }

    static function jsapiShopOrderUpdate ($orderID, $data) {
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
    // <<<< Shop order

    // >>>> Statistic: best and worst selling products
    static function jsapiShopStat_BestSellingProducts () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_boughts",
            "fields" => array("ProductID", "@SUM(Quantity) AS SoldTotal", "@SUM(ProductPrice * Quantity) AS SumTotal"),
            "order" => array(
                "field" => "SoldTotal",
                "ordering" => "DESC"
            ),
            "limit" => 50,
            "group" => "ProductID",
            "options" => null
        ));
    }
    static function jsapiShopStat_WorstSellingProducts () {
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
            "limit" => 50,
            "options" => null
        ));
    }
    // <<<< Statistic: best and worst selling products

    // >>>> Origins
    static function jsapiShopOriginGet ($originID) {
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
    static function jsapiShopOriginListGet () {
        $config = self::jsapiShopOriginGet(null);
        $config['condition'] = array(
            "filter" => "",
            "values" => array()
        );
        unset($config['options']);
        $config['limit'] = 0;
        return $config;
    }
    static function jsapiShopOriginCreate ($data) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "insert",
            "source" => "shop_origins",
            "data" => $data,
            "options" => null
        ));
    }
    static function jsapiShopOriginUpdate ($originID, $data) {
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
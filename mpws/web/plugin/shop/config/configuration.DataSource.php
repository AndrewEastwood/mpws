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
                "shop_products.ID" => self::jsapiCreateDataSourceCondition($id)
                // "shop_products.Status" => self::jsapiCreateDataSourceCondition('ACTIVE'),
                // "shop_categories.Status" => self::jsapiCreateDataSourceCondition('ACTIVE'),
                // "shop_origins.Status" => self::jsapiCreateDataSourceCondition('ACTIVE')
            ),
            "fields" => array("ID", "CategoryID", "OriginID", "ExternalKey", "Name", "Description", "Specifications", "Model", "SKU", "Price", "Status", "DateUpdated", "DateCreated"),
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
    static function jsapiShopProductList () {
        $config = self::jsapiShopProductItemGet(null);
        $config['condition'] = array();
        $config["fields"] = array("ID");
        $config['limit'] = 64;
        $config['additional'] = array(
            "shop_categories" => array(
                "constraint" => array("shop_categories.ID", "=", "shop_products.CategoryID"),
                "fields" => array("Status")
            ),
            "shop_origins" => array(
                "constraint" => array("shop_origins.ID", "=", "shop_products.OriginID"),
                "fields" => array("Status")
            )
        );
        unset($config['options']);
        return $config;
    }
    // <<<< Product base list configuration

    // Product list of recently added products >>>>>
    static function jsapiShopProductListLatest () {
        $config = self::jsapiShopProductList();
        $config['condition'] = array(
            "shop_products.Status" => self::jsapiCreateDataSourceCondition('ACTIVE'),
            "shop_categories.Status" => self::jsapiCreateDataSourceCondition('ACTIVE'),
            "shop_origins.Status" => self::jsapiCreateDataSourceCondition('ACTIVE')
        );
        $config['order'] = array(
            "field" => "shop_products.DateCreated",
            "ordering" => "DESC"
        );
        $config['limit'] = 5;
        return $config;
    }
    // <<<< Product list of recently added products

    // Product list of products by status >>>>>
    static function jsapiShopProductListByStatus ($status, $comparator = '=') {
        $config = self::jsapiShopProductList();
        $config['condition']['shop_products.Status'] = self::jsapiCreateDataSourceCondition($status, $comparator);
        return $config;
    }
    // <<<< Product list of products by status

    // Product list of uncompleted products >>>>>
    static function jsapiShopProductListUncompleted () {
        $config = self::jsapiShopProductList();
        $config['condition']['shop_products.Name'] = self::jsapiCreateDataSourceCondition("");
        $config['condition']['shop_products.Description'] = self::jsapiCreateDataSourceCondition("");
        $config['condition']['shop_products.Model'] = self::jsapiCreateDataSourceCondition("");
        $config['condition']['shop_products.Price'] = self::jsapiCreateDataSourceCondition(0.00);
        return $config;
    }
    // <<<< Product list of uncompleted products

    // Product category (catalog)
    static function jsapiGetShopCategoryProductList ($ids) {
        $config = self::jsapiShopProductList();
        $config['condition']["shop_products.CategoryID"] = self::jsapiCreateDataSourceCondition($ids, "IN");
        // var_dump($config);
        return $config;
    }

    static function jsapiGetShopCategoryProductInfo ($ids) {
        $config = self::jsapiGetShopCategoryProductList($ids);
        $config["fields"] = array("ID", "CategoryID", "OriginID");
        $config['limit'] = 0;
        return $config;
    }

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
        $config = self::jsapiShopProductItemGet($id);
        $config['fields'] = array("CategoryID", "Name");
        unset($config['additional']);
        return $config;
    }
    // <<<< Single prouct info

    // >>>> Statistic: best and worst selling products
    static function jsapiShopStat_PopularProducts () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_boughts",
            "fields" => array("ProductID", "@SUM(Quantity) AS SoldTotal", "@SUM(ProductPrice * Quantity) AS SumTotal"),
            "order" => array(
                "field" => "SoldTotal",
                "ordering" => "DESC"
            ),
            "limit" => 25,
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
            "limit" => 25,
            "options" => null
        ));
    }
    static function jsapiShopStat_ProductsOverview () {
        $config = self::jsapiShopProductItemGet(null);
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "Status");
        $config['group'] = "Status";
        $config['limit'] = 0;
        unset($config['condition']);
        unset($config['additional']);
        unset($config['options']);
        return $config;
    }













    static function jsapiShopCategoryAllBrandsGet ($categoryID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getAllShopCategoryBrands",
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

    // Shop catalog >>>>>
    static function jsapiShopCatalogTree () {
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
    static function jsapiGetShopOrderByID ($orderID) {
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
    static function jsapiGetShopOrderByHash ($orderHash) {
        $config = self::jsapiGetShopOrderByID(null);
        $config['condition'] = array(
            "Hash" => self::jsapiCreateDataSourceCondition($orderHash)
        );
        return $config;
    }

    static function jsapiShopOrdersGet () {
        $config = self::jsapiShopOrderGet(null);
        $config['condition'] = array();
        $config["order"] = array(
            "field" => "shop_orders.DateCreated",
            "ordering" => "DESC"
        );
        $config['fields'] = array("ID");
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

    static function jsapiShopStat_OrdersOverview () {
        $config = self::jsapiShopOrderGet(null);
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "Status");
        $config['group'] = "Status";
        $config['limit'] = 0;
        unset($config['condition']);
        unset($config['additional']);
        unset($config['options']);
        return $config;
    }
    // <<<< Statistic: order overview














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
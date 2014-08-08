<?php

class configurationShopDataSource extends objectConfiguration {

    static $Table_ShopOrders = "shop_orders";
    static $Table_ShopProducts = "shop_products";
    static $Table_ShopOrigins = "shop_origins";
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
    }
    // <<<< Product base configuration

    // Product base list configuration >>>>>
    static function jsapiShopProductList () {
        $config = self::jsapiShopProductItemGet(null);
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
        if (isset($config['additional']['shop_productFeatures']))
            unset($config['additional']['shop_productFeatures']);
        $config['order'] = array(
            "field" => "shop_products.DateCreated",
            "ordering" => "DESC"
        );
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

        // if (!empty($filters['features'])) {
        //     $config["additional"]["shop_productFeatures"] = array(
        //         "constraint" => array("shop_origins.ID", "=", "shop_products.OriginID"),
        //         "fields" => array(
        //             "SpecFieldID" => "SpecFieldID"
        //         )
        //     );
        // }
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
                "expandSingleRecord" => true,
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
    static function jsapiShopProductRelations ($id) {
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
            "fields" => array("ID", "RootID", "ParentID", "ExternalKey", "Name", "Status"),
        ));
    }
    // <<<< Shop catalog tree










    // Shop order >>>>>
    static function jsapiShopOrderCreate ($data) {
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
    static function jsapiShopOrderBoughtCreate ($data) {
        $data["DateCreated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_boughts",
            "action" => "insert",
            "data" => $data,
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
            "fields" => array("ID", "ProductID", "Price", "SellingPrice", "Quantity", "IsPromo", "DateCreated"),
            "offset" => 0,
            "limit" => 0
        ));
    }
    static function jsapiGetShopOrders () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_orders",
            "condition" => array(),
            "fields" => array("ID", "AccountID", "AccountAddressesID", "Shipping", "Warehouse", "Comment", "Status", "Hash", "PromoID", "DateCreated", "DateUpdated"),
            "options" => null
        ));
    }
    static function jsapiGetShopOrderIDs () {
        $config = self::jsapiGetShopOrders();
        $config['fields'] = array("ID");
        return $config;
    }
    static function jsapiGetShopOrderByID ($orderID) {
        $config = self::jsapiGetShopOrders();
        $config['condition'] = array(
            "ID" => self::jsapiCreateDataSourceCondition($orderID)
        );
        $config['options'] = array(
            "expandSingleRecord" => true
        );
        $config['limit'] = 1;
        return $config;
    }
    static function jsapiGetShopOrderByHash ($orderHash) {
        $config = self::jsapiGetShopOrders();
        $config['condition'] = array(
            "Hash" => self::jsapiCreateDataSourceCondition($orderHash)
        );
        $config['options'] = array(
            "expandSingleRecord" => true
        );
        $config['limit'] = 1;
        return $config;
    }
    static function jsapiShopOrdersGet () {
        $config = self::jsapiGetShopOrders();
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
    // static function jsapiGetShopOrdersForProfile ($profileID) {
    //     $config = self::jsapiGetShopOrders();
    //     $config['condition'] = array(
    //         "AccountID" => self::jsapiCreateDataSourceCondition($profileID)
    //     );
    //     $config["order"] = array(
    //         "field" => "shop_orders.ID",
    //         "ordering" => "DESC"
    //     );
    //     return $config;
    // }

    static function jsapiShopOrderUpdate ($orderID, $data) {
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

    static function jsapiDisableOrder ($OrderID) {
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
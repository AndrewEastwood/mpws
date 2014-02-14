<?php

class configurationShopDataSource extends configurationDefaultDataSource {

    // Product base configuration >>>>>
    static function jsapiProductItem () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_products",
            "condition" => array(
                "filter" => "shop_products.Status (=) ? + shop_products.Enabled (=) ? + shop_products.ID (=) ?",
                "values" => array("AVAILABLE", 1)
            ),
            "fields" => array("ID", "CategoryID", "OriginID", "ExternalKey", "Name", "Description", "Specifications", "Model", "SKU", "Price", "Status", "DateUpdated"),
            "offset" => 0,
            "limit" => 1,
            "additional" => array(
                "shop_categories" => array(
                    "constraint" => array("shop_categories.ID", "=", "shop_products.CategoryID"),
                    "fields" => array(
                        "CategoryName" => "Name",
                        "CategoryDescription" => "Description",
                        "CategoryEnable" => "Enabled"
                    )
                ),
                "shop_origins" => array(
                    "constraint" => array("shop_origins.ID", "=", "shop_products.OriginID"),
                    "fields" => array(
                        "OriginName" => "Name",
                        "OriginDescription" => "Description",
                        "OriginEnable" => "Enabled"
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
    static function jsapiProductList () {
        $config = self::jsapiProductItem();
        unset($config['options']);
        $config['condition'] = array(
            "filter" => "shop_products.Status (=) ? + shop_products.Enabled (=) ? + shop_categories.Enabled (=) ? + shop_origins.Enabled (=) ?",
            "values" => array("AVAILABLE", 1, 1, 1)
        );
        $config['limit'] = 64;
        return $config;
    }
    // <<<< Product base list configuration

    // Product list of recently added products >>>>>
    static function jsapiProductListLatest () {
        $config = self::jsapiProductList();
        $config['order'] = array(
            "field" => "shop_products.DateCreated",
            "ordering" => "DESC"
        );
        return $config;
    }
    // <<<< Product list of recently added products

    // Product category (catalog)
    static function jsapiProductListCategory () {
        $config = self::jsapiProductList();
        $config['condition']["filter"] = "shop_products.Status (=) ? + shop_products.Enabled (=) ? + shop_categories.Enabled (=) ? + shop_origins.Enabled (=) ? + shop_products.CategoryID (IN) ?";
        // var_dump($config);
        return $config;
    }

    static function jsapiProductListCategoryInfo () {
        $config = self::jsapiProductListCategory();
        // $config["useFieldPrefix"] = false;
        $config["fields"] = array("ID", "CategoryID", "OriginID");
        $config['limit'] = 0;
        return $config;
    }

    static function jsapiShopCategoryAllBrands () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getAllShopCategoryBrands",
                "parameters" => array()
            )
        ));
    }

    static function jsapiShopCategoryAllSubCategories () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getAllShopCategorySubCategories",
                "parameters" => array()
            )
        ));
    }

    static function jsapiShopCategoryPriceEdges () {
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
    static function jsapiProductAttributes () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_productAttributes",
            "condition" => array(
                "filter" => "ProductID (IN) (?)",
                "values" => array()
            ),
            "fields" => array(
                "ProductID",
                "@GROUP_CONCAT(Attribute SEPARATOR \"#EXPLODE#\") AS `Attributes`",
                "@GROUP_CONCAT(Value SEPARATOR \"#EXPLODE#\") AS `Values`"
            ),
            "offset" => "0",
            "limit" => "10",
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
    static function jsapiProductPriceStats () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_productPrices",
            "condition" => array(
                "filter" => "ProductID (IN) (?)",
                "values" => array()
            ),
            "fields" => array(
                "ID",
                "ProductID",
                "@GROUP_CONCAT(DISTINCT Price ORDER BY Price DESC SEPARATOR \"#EXPLODE#\") AS PriceArchive"
            ),
            "offset" => "0",
            "limit" => "10",
            "group" => "ProductID",
            "order" => array(
                "field" => "shop_productPrices.Price",
                "ordering" => "ASC"
            ),
            "options" => array(
                "transformToArray" => array("PriceArchive")
            )
        ));
    }
    // <<<< Product price stats

    // Single prouct info >>>>>
    static function jsapiProductSingleInfo () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_products",
            "condition" => array(
                "filter" => "shop_products.Status (=) ? + shop_products.Enabled (=) ? + shop_products.ID (=) ?",
                "values" => array("AVAILABLE", 1)
            ),
            "fields" => array("CategoryID", "Name"),
            "offset" => "0",
            "limit" => "1",
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }
    // <<<< Single prouct info

    // Additional: category location >>>>>
    static function jsapiShopCategoryLocation () {
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
    static function jsapiCatalogStructure () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_categories",
            "condition" => array(
                "filter" => "Enabled (=) ?",
                "values" => array(1)
            ),
            "fields" => array("ID", "RootID", "ParentID", "ExternalKey", "Name", "Enabled"),
        ));
    }
    // <<<< Shop catalog

}

?>
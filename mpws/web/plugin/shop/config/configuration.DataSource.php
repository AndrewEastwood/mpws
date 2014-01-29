<?php

class configurationShopDataSource extends configurationDefaultDataSource {

    static function jsapiProductListLatest () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_products",
            "condition" => array(
                "filter" => "shop_products.Status (=) ? + shop_products.Enabled (=) ? + shop_categories.Enabled (=) ? + shop_origins.Enabled (=) ?",
                "values" => array("AVAILABLE", 1, 1, 1)
            ),
            "fields" => array("ID", "CategoryID", "OriginID", "ExternalKey", "Name", "Model", "SKU", "Description", "Price", "DateCreated"),
            "offset" => "0",
            "limit" => "64",
            "additional" => array(
                "shop_categories" => array(
                    "constraint" => array("shop_categories.ID", "=", "shop_products.CategoryID"),
                    "fields" => array(
                        "CategoryName" => "Name",
                        "CategoryEnable" => "Enabled"
                    )
                ),
                "shop_origins" => array(
                    "constraint" => array("shop_origins.ID", "=", "shop_products.OriginID"),
                    "fields" => array(
                        "OriginName" => "Name",
                        "OriginEnable" => "Enabled"
                    )
                )
            ),
            "order" => array(
                "field" => "shop_products.DateCreated",
                "ordering" => "DESC"
            )
        ));
    }

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

    static function jsapiProductsPriceStats () {
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
            )
            "options" => array(
                "transformToArray" => array("PriceArchive")
            )
        ));
    }

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
            "output" => "DEFAULT",
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    static function jsapiShopCategoryLocation () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCategoryLocation",
                "parameters" => array()
            )
        ));
    }

} 

?>
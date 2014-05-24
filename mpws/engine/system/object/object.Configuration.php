<?php

class objectConfiguration implements iConfiguration {

    static $DATE_FORMAT = 'Y-m-d H:i:s';

    static function getDate () {
        return date(self::$DATE_FORMAT);
    }

    static function jsapiCreateDataSourceCondition() {
        
    }

    static function jsapiGetDataSourceConfig($configExtend = null) {
        $configDefault = array(
            "source" => "",
            "action" => "select",
            "procedure" => array(
                "name" => "",
                "parameters" => array()
            ),
            "condition" => array(
                "filter" => "", //"shop_products.Status = ? AND shop_products.Enabled = ?",
                "values" => array(/*"ACTIVE", 1*/)
            ),
            "data" => array(
                "fields" => array(/*"DateLastAccess", "IsOnline"*/),
                "values" => array()
            ),
            "useFieldPrefix" => true,
            "fields" => array(/*"ID", "CategoryID", "OriginID", "Name", "Model", "SKU", "Description", "DateCreated"*/),
            "offset" => 0,
            "limit" => 10,
            // "output" => "DEFAULT", // see function "to" for available values
            "group" => "", // "ProductID"
            "transformToArray" => array(), // keys which values will be served as array
            "additional" => array(
                // example of join configuration
                // "shop_categories" => array(
                //     "constraint" => array("shop_categories.ID", "=", "shop_products.CategoryID"),
                //     "fields" => array(
                //         "CategoryName" => "Name",
                //         "CategoryEnable" => "Enabled"
                //     )
                // ),
                // "shop_origins" => array(
                //     "constraint" => array("shop_origins.ID", "=", "shop_products.OriginID"],
                //     "fields" => array(
                //         "CategoryName" => "Name",
                //         "CategoryEnable" => "Enabled"
                //     )
                // )
            ),
            "order" => array(
                // "field" => "shop_productPrices.DateCreated",
                // "ordering" => "DESC"
            ),
            "options" => array(
                // This goes by default.
                // You can baypass any filed names to force make value as array of each
                "transformToArray" => array(),
                // required fields:
                // "combineDataByKeys" => array(
                //    "mapKeysToCombine" => array(
                //        'ProductAttributes' => array(
                //            'keys' => 'Attributes',
                //            'values' => 'Values',
                //            'keepOriginal' => true|false (optional) if true then Attributes and Values fields will be removed from this example
                //        )
                //    ),
                // optional:
                //    "doOptimization" => true,
                //    "keysToForceTransformToArray" = array("FieldName")
                // )
                "expandSingleRecord" => false
            )
        );

        return self::extendConfigs($configDefault, $configExtend, true);
    }

    static function extendConfigs ($configA, $configB = null, $useRecursiveMerge = false) {
        return libraryUtils::array_merge_recursive_distinct($configA, $configB);
    }

    static function jsapiUtil_GetTableRecordsCount ($table) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => $table,
            "condition" => array(
                "filter" => "",
                "values" => array()
            ),
            "fields" => array("@COUNT(*) AS ItemsCount"),
            "offset" => 0,
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    static function jsapiUtil_GetTableStatusFieldOptions ($table) {
        return self::jsapiUtil_GetFieldOptions($table, 'Status');
    }

    static function jsapiUtil_GetFieldOptions ($table, $field) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getFieldOptions",
                "parameters" => array($table, $field)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

}

?>
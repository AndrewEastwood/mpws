<?php

class configurationDefaultDataSource extends objectConfiguration {

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

    static function jsapiGetCustomer () {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_customer",
            "fields" => array("*"),
            "condition" => array(
                "filter" => "ExternalKey (=) ?", //"shop_products.Status = ? AND shop_products.Enabled = ?",
                "values" => array(MPWS_CUSTOMER)
            ),
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    static function jsapiGetAccount ($login, $password) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "fields" => array("*"),
            "limit" => 1,
            "condition" => array(
                "filter" => "EMail (=) ? + Password (=) ?", //"shop_products.Status = ? AND shop_products.Enabled = ?",
                "values" => array($login, md5($password))
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    static function jsapiAddAccount () {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "action" => "insert",
            "options" => null
        ));
    }

    static function jsapiUpdateAccount () {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "fields" => array("*"),
            "limit" => 1,
            "condition" => array(
                "filter" => "ID (=) ?",
                "values" => array()
            ),
            "data" => array(
                "fields" => array('Status'),
                "values" => array('REMOVED')
            ),
            "options" => null
        ));
    }

    static function jsapiRemoveAccount () {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "fields" => array("*"),
            "limit" => 1,
            "condition" => array(
                "filter" => "ID (=) ?",
                "values" => array()
            ),
            "data" => array(
                "fields" => array('Status'),
                "values" => array('REMOVED')
            ),
            "options" => null
        ));
    }

}


?>
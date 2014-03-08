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

    static function jsapiGetCustomer ($ExternalKey = MPWS_CUSTOMER) {
        // if (empty($ExternalKey))
        //     $ExternalKey = ;

        $filter = "ExternalKey (=) ? + Status (=) ?";
        $values = array($ExternalKey, "ACTIVE");

        // if (glIsToolbox() && unmanaged) {
        //     $filter = "";
        //     $values = "";
        // }

        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_customer",
            "fields" => array("*"),
            "condition" => array(
                "filter" => $filter, //"shop_products.Status = ? AND shop_products.Enabled = ?",
                "values" => $values
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
                "filter" => "EMail (=) ? + Password (=) ? + IsTemporary (=) ? + Status (=) ?", //"shop_products.Status = ? AND shop_products.Enabled = ?",
                "values" => array($login, $password, 0, "ACTIVE")
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

    static function jsapiUpdateAccount ($AccountID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "condition" => array(
                "filter" => "ID (=) ? + Status (=) ?",
                "values" => array($AccountID, "ACTIVE")
            ),
            "options" => null
        ));
    }

    static function jsapiRemoveAccount ($AccountID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "condition" => array(
                "filter" => "ID (=) ? + Status (=) ?",
                "values" => array($AccountID, "ACTIVE")
            ),
            "data" => array(
                "fields" => array('Status', 'DateUpdated'),
                "values" => array('REMOVED', date('Y:m:d H:i:s'))
            ),
            "options" => null
        ));
    }

    static function jsapiActivateAccount ($ValidationString) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "condition" => array(
                "filter" => "ValidationString (=) ?",
                "values" => array($ValidationString)
            ),
            "data" => array(
                "fields" => array('IsTemporary'),
                "values" => array(0)
            ),
            "options" => null
        ));
    }

    static function jsapiGetAccountAddresses ($AccountID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "fields" => array("*"),
            "condition" => array(
                "filter" => "AccountID (=) ? + Status (=) ?",
                "values" => array($AccountID, "ACTIVE")
            ),
            "options" => null
        ));
    }

    static function jsapiGetAccountAddress ($AccountID, $AddressID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "fields" => array("*"),
            "condition" => array(
                "filter" => "ID (=) ? + AccountID (=) ? + Status (=) ?",
                "values" => array($AddressID, $AccountID, "ACTIVE")
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    static function jsapiAddAccountAddress () {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "action" => "insert",
            "options" => null
        ));
    }

    static function jsapiUpdateAccountAddress ($AccountID, $AddressID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "action" => "update",
            "condition" => array(
                "filter" => "ID (=) ? + AccountID (=) ? + Status (=) ?",
                "values" => array($AddressID, $AccountID, "ACTIVE")
            ),
            "options" => null
        ));
    }

    static function jsapiRemoveAccountAddress ($AccountID, $AccountID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "action" => "update",
            "condition" => array(
                "filter" => "ID (=) ? + AccountID (=) ? + Status (=) ?",
                "values" => array($AccountID, $AccountID, "ACTIVE")
            ),
            "data" => array(
                "fields" => array('Status', 'DateUpdated'),
                "values" => array('REMOVED', date('Y:m:d H:i:s'))
            ),
            "options" => null
        ));
    }
}


?>
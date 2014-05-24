<?php

class configurationAccountDataSource extends objectConfiguration {

    static function jsapiGetAccount ($login, $password) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "fields" => array("*"),
            "limit" => 1,
            "condition" => array(
                "EMail" => array(
                    "comparator" => "=",
                    "value" => $login,
                    "concatenate" => "+"
                ),
                "Password" => array(
                    "comparator" => "=",
                    "value" => $password,
                    "concatenate" => "+"
                ),
                "IsTemporary" => array(
                    "comparator" => "=",
                    "value" => 0,
                    "concatenate" => "+"
                ),,
                "Status" => array(
                    "comparator" => "=",
                    "value" => "ACTIVE",
                    "concatenate" => "+"
                )
            ),
            "additional" => array(
                "mpws_permissions" => array(
                    "constraint" => array("mpws_accounts.PermissionID", "=", "mpws_permissions.ID"),
                    "fields" => array("*")
                )
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }
    static function jsapiGetAccountByID ($id) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "fields" => array("*"),
            "limit" => 1,
            "condition" => array(
                "filter" => "ID (=) ?", //"shop_products.Status = ? AND shop_products.Enabled = ?",
                "values" => array($id)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    static function jsapiGetAccountPermissions () {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_permissions",
            "fields" => array("*"),
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    static function jsapiAddAccountPermissions ($data) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_permissions",
            "fields" => array("*"),
            "data" => array(
                "fields" => array_keys($data),
                "values" => array_values($data)
            ),
            "options" => null
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

    static function jsapiGetAddress ($AddressID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "fields" => array("*"),
            "condition" => array(
                "filter" => "ID (=) ?",
                "values" => array($AddressID)
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

    static function jsapiRemoveAccountAddress ($AccountID, $AddressID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "action" => "update",
            "condition" => array(
                "filter" => "ID (=) ? + AccountID (=) ? + Status (=) ?",
                "values" => array($AddressID, $AccountID, "ACTIVE")
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
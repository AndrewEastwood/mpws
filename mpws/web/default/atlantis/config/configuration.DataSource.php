<?php

class configurationDefaultDataSource extends objectConfiguration {

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
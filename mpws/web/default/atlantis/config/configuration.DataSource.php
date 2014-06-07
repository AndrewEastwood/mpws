<?php

class configurationDefaultDataSource extends objectConfiguration {

    static $Table_SystemAccounts = "mpws_accounts";

    static function jsapiGetCustomer ($ExternalKey = MPWS_CUSTOMER) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_customer",
            "fields" => array("*"),
            "condition" => array(
                "ExternalKey" => self::jsapiCreateDataSourceCondition($ExternalKey),
                "Status" => self::jsapiCreateDataSourceCondition("ACTIVE")
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
                "EMail" => self::jsapiCreateDataSourceCondition($login),
                "Password" => self::jsapiCreateDataSourceCondition($password),
                "IsTemporary" => self::jsapiCreateDataSourceCondition(0),
                "Status" => self::jsapiCreateDataSourceCondition("ACTIVE")
            ),
            "additional" => array(
                "mpws_permissions" => array(
                    "constraint" => array("mpws_accounts.PermissionID", "=", "mpws_permissions.ID"),
                    "fields" => array(
                        "Permission_isAdmin" => "isAdmin",
                        "Permission_CanCreate" => "CanCreate",
                        "Permission_CanEdit" => "CanEdit",
                        "Permission_CanView" => "CanView",
                        "Permission_CanAddUsers" => "CanAddUsers"
                    )
                )
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }
    static function jsapiGetAccountByID ($id) {
        $config = self::jsapiGetAccount(null, null);
        $config["condition"] = array(
            "ID" => self::jsapiCreateDataSourceCondition($id)
        );
        return $config;
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
            "data" => $data,
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
                "AccountID" => self::jsapiCreateDataSourceCondition($AccountID),
                "Status" => self::jsapiCreateDataSourceCondition("ACTIVE")
            ),
            "options" => null
        ));
    }

    static function jsapiRemoveAccount ($AccountID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "condition" => array(
                "AccountID" => self::jsapiCreateDataSourceCondition($AccountID),
                "Status" => self::jsapiCreateDataSourceCondition("ACTIVE")
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => date('Y:m:d H:i:s')
            ),
            "options" => null
        ));
    }

    static function jsapiActivateAccount ($ValidationString) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "condition" => array(
                "ValidationString" => self::jsapiCreateDataSourceCondition($ValidationString)
            ),
            "data" => array(
                "IsTemporary" => 0
            ),
            "options" => null
        ));
    }

    static function jsapiGetAccountAddresses ($AccountID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "fields" => array("*"),
            "condition" => array(
                "AccountID" => self::jsapiCreateDataSourceCondition($AccountID),
                "Status" => self::jsapiCreateDataSourceCondition("ACTIVE")
            ),
            "options" => null
        ));
    }

    static function jsapiGetAccountAddress ($AccountID, $AddressID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "fields" => array("*"),
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($AddressID),
                "AccountID" => self::jsapiCreateDataSourceCondition($AccountID),
                "Status" => self::jsapiCreateDataSourceCondition("ACTIVE")
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
                "ID" => self::jsapiCreateDataSourceCondition($AddressID),
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
                "ID" => self::jsapiCreateDataSourceCondition($AddressID),
                "AccountID" => self::jsapiCreateDataSourceCondition($AccountID),
                "Status" => self::jsapiCreateDataSourceCondition("ACTIVE")
            ),
            "options" => null
        ));
    }

    static function jsapiRemoveAccountAddress ($AccountID, $AddressID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($AddressID),
                "AccountID" => self::jsapiCreateDataSourceCondition($AccountID),
                "Status" => self::jsapiCreateDataSourceCondition("ACTIVE")
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => date('Y:m:d H:i:s')
            ),
            "options" => null
        ));
    }

}


?>
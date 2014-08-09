<?php

class configurationDefaultDataSource extends objectConfiguration {

    static $Table_SystemAccounts = "mpws_accounts";

    static function jsapiGetNewPermission () {
        $perms = array(
            "CanAdmin" => 0,
            "CanCreate" => 0,
            "CanEdit" => 0,
            "CanViewReports" => 0,
            "CanAddUsers" => 0
        );
        return $perms;
    }

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

    static function jsapiGetAccount () {
        $config = self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "fields" => array("*"),
            "limit" => 1,
            "condition" => array(),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
        return $config;
    }

    static function jsapiGetAccountByCredentials ($login, $password) {
        $config = self::jsapiGetAccount();
        $config["condition"]["EMail"] = self::jsapiCreateDataSourceCondition($login);
        $config["condition"]["Password"] = self::jsapiCreateDataSourceCondition($password);
        return $config;
    }

    static function jsapiGetAccountByID ($id) {
        $config = self::jsapiGetAccount();
        $config["condition"] = array(
            "ID" => self::jsapiCreateDataSourceCondition($id)
        );
        return $config;
    }

    static function jsapiGetAccountByEMail ($email) {
        $config = self::jsapiGetAccount();
        $config["condition"] = array(
            "EMail" => self::jsapiCreateDataSourceCondition($email)
        );
        return $config;
    }


    static function jsapiGetAccountByValidationString ($ValidationString) {
        $config = self::jsapiGetAccount();
        $config["condition"] = array(
            "ValidationString" => self::jsapiCreateDataSourceCondition($ValidationString)
        );
        return $config;
    }

    static function jsapiAddAccount ($data) {
        $data["DateUpdated"] = self::getDate();
        $data["DateCreated"] = self::getDate();
        $data["DateLastAccess"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiUpdateAccount ($AccountID, $data) {
        $data["DateUpdated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($AccountID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiDisableAccount ($AccountID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($AccountID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => self::getDate()
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
                "Status" => "ACTIVE",
                "DateUpdated" => self::getDate()
            ),
            "options" => null
        ));
    }

    static function jsapiSetOnlineAccount ($AccountID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($AccountID)
            ),
            "data" => array(
                "IsOnline" => true,
                "DateUpdated" => self::getDate()
            ),
            "options" => null
        ));
    }

    static function jsapiSetOfflineAccount ($AccountID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($AccountID)
            ),
            "data" => array(
                "IsOnline" => true,
                "DateUpdated" => self::getDate()
            ),
            "options" => null
        ));
    }

    static function jsapiGetPermissions ($AccountID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_permissions",
            "fields" => array("*"),
            "condition" => array(
                "AccountID" => self::jsapiCreateDataSourceCondition($AccountID)
            ),
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    static function jsapiAddPermissions ($data) {
        $data["DateUpdated"] = self::getDate();
        $data["DateCreated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_permissions",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiUpdatePermissions ($AccountID, $data) {
        $data["DateUpdated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_permissions",
            "action" => "update",
            "condition" => array(
                "AccountID" => self::jsapiCreateDataSourceCondition($AccountID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiGetAccountAddresses ($AccountID) {
        $config = self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "fields" => array("ID", "AccountID", "Address", "POBox", "Country", "City", "Status", "DateCreated", "DateUpdated"),
            "condition" => array(
                "AccountID" => self::jsapiCreateDataSourceCondition($AccountID)
            ),
            "options" => array(
                "asDict" => "ID"
            )
        ));
        if (!glIsToolbox())
            $config['condition']["Status"] = self::jsapiCreateDataSourceCondition("ACTIVE");
        return $config;
    }

    // static function jsapiGetAccountAddress ($AccountID, $AddressID) {
    //     return self::jsapiGetDataSourceConfig(array(
    //         "source" => "mpws_accountAddresses",
    //         "fields" => array("*"),
    //         "condition" => array(
    //             "ID" => self::jsapiCreateDataSourceCondition($AddressID),
    //             "AccountID" => self::jsapiCreateDataSourceCondition($AccountID),
    //             "Status" => self::jsapiCreateDataSourceCondition("ACTIVE")
    //         ),
    //         "options" => array(
    //             "expandSingleRecord" => true
    //         )
    //     ));
    // }

    static function jsapiGetAddress ($AddressID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "fields" => array("ID", "AccountID", "Address", "POBox", "Country", "City", "Status", "DateCreated", "DateUpdated"),
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($AddressID),
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    static function jsapiAddAddress ($data) {
        $data["DateUpdated"] = self::getDate();
        $data["DateCreated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiUpdateAddress ($AddressID, $data) {
        $data["DateUpdated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($AddressID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiDisableAddress ($AddressID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_accountAddresses",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($AddressID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => self::getDate()
            ),
            "options" => null
        ));
    }

}


?>
<?php
namespace web\plugin\account\api;

class shared {

    public $Table_SystemAccounts = "mpws_accounts";

    public static function jsapiGetNewPermission () {
        global $app;
        $perms = array(
            "CanAdmin" => 0,
            "CanCreate" => 0,
            "CanEdit" => 0,
            "CanViewReports" => 0,
            "CanAddUsers" => 0
        );
        return $perms;
    }

    public static function jsapiGetCustomer ($ExternalKey) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_customer",
            "fields" => array("*"),
            "condition" => array(
                "ExternalKey" => $app->getDB()->createCondition($ExternalKey),
                "Status" => $app->getDB()->createCondition("ACTIVE")
            ),
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    public static function jsapiGetAccount () {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
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

    public static function jsapiGetAccountByCredentials ($login, $password) {
        global $app;
        $config = self::jsapiGetAccount();
        $config["condition"]["EMail"] = $app->getDB()->createCondition($login);
        $config["condition"]["Password"] = $app->getDB()->createCondition($password);
        return $config;
    }

    public static function jsapiGetAccountByID ($id) {
        global $app;
        $config = self::jsapiGetAccount();
        $config["condition"] = array(
            "ID" => $app->getDB()->createCondition($id)
        );
        return $config;
    }

    public static function jsapiGetAccountByEMail ($email) {
        global $app;
        $config = self::jsapiGetAccount();
        $config["condition"] = array(
            "EMail" => $app->getDB()->createCondition($email)
        );
        return $config;
    }


    public static function jsapiGetAccountByValidationString ($ValidationString) {
        global $app;
        $config = self::jsapiGetAccount();
        $config["condition"] = array(
            "ValidationString" => $app->getDB()->createCondition($ValidationString)
        );
        return $config;
    }

    public static function jsapiAddAccount ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        $data["DateLastAccess"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_accounts",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function jsapiUpdateAccount ($AccountID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($AccountID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function jsapiDisableAccount ($AccountID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($AccountID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }

    public static function jsapiActivateAccount ($ValidationString) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "condition" => array(
                "ValidationString" => $app->getDB()->createCondition($ValidationString)
            ),
            "data" => array(
                "Status" => "ACTIVE",
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }

    public static function jsapiSetOnlineAccount ($AccountID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($AccountID)
            ),
            "data" => array(
                "IsOnline" => true,
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }

    public static function jsapiSetOfflineAccount ($AccountID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_accounts",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($AccountID)
            ),
            "data" => array(
                "IsOnline" => true,
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }

    public static function jsapiGetPermissions ($AccountID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_permissions",
            "fields" => array("*"),
            "condition" => array(
                "AccountID" => $app->getDB()->createCondition($AccountID)
            ),
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    public static function jsapiAddPermissions ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_permissions",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function jsapiUpdatePermissions ($AccountID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_permissions",
            "action" => "update",
            "condition" => array(
                "AccountID" => $app->getDB()->createCondition($AccountID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function jsapiGetAccountAddresses ($AccountID, $withRemoved = false) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "source" => "mpws_accountAddresses",
            "fields" => array("ID", "AccountID", "Address", "POBox", "Country", "City", "Status", "DateCreated", "DateUpdated"),
            "condition" => array(
                "AccountID" => $app->getDB()->createCondition($AccountID)
            ),
            "options" => array(
                "asDict" => "ID"
            )
        ));
        if (!$withRemoved)
            $config['condition']["Status"] = $app->getDB()->createCondition("ACTIVE");
        return $config;
    }

    // public static function jsapiGetAccountAddress ($AccountID, $AddressID) {
    global $app;
    //     return $app->getDB()->createDBQuery(array(
    //         "source" => "mpws_accountAddresses",
    //         "fields" => array("*"),
    //         "condition" => array(
    //             "ID" => $app->getDB()->createCondition($AddressID),
    //             "AccountID" => $app->getDB()->createCondition($AccountID),
    //             "Status" => $app->getDB()->createCondition("ACTIVE")
    //         ),
    //         "options" => array(
    //             "expandSingleRecord" => true
    //         )
    //     ));
    // }

    public static function jsapiGetAddress ($AddressID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_accountAddresses",
            "fields" => array("ID", "AccountID", "Address", "POBox", "Country", "City", "Status", "DateCreated", "DateUpdated"),
            "condition" => array(
                "ID" => $app->getDB()->createCondition($AddressID),
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    public static function jsapiAddAddress ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_accountAddresses",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function jsapiUpdateAddress ($AddressID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_accountAddresses",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($AddressID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function jsapiDisableAddress ($AddressID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_accountAddresses",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($AddressID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }

    // >>>> Account statistics
    public static function jsapiStat_AccountsOverview () {
        global $app;
        $config = self::jsapiGetAccount();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "Status");
        $config['group'] = "Status";
        $config['limit'] = 0;
        $config['options'] = array(
            'asDict' => array(
                'keys' => 'Status',
                'values' => 'ItemsCount'
            )
        );
        unset($config['condition']);
        unset($config['additional']);
        return $config;
    }

    public static function jsapiStat_AccountsIntensityLastMonth ($status) {
        global $app;
        $config = self::jsapiGetAccount();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "@Date(DateCreated) AS IncomeDate");
        $config['condition'] = array(
            'Status' => $app->getDB()->createCondition($status),
            'DateCreated' => $app->getDB()->createCondition(date('Y-m-d', strtotime("-10 month")), ">")
        );
        $config['options'] = array(
            'asDict' => array(
                'keys' => 'IncomeDate',
                'values' => 'ItemsCount'
            )
        );
        $config['group'] = 'Date(DateCreated)';
        $config['limit'] = 0;
        unset($config['additional']);
        return $config;
    }
    // <<<< Account statistics
<?php
namespace web\plugin\account\api;

class dbquery {

    public $Table_SystemUsers = "mpws_users";


    public static function getUser () {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "source" => "mpws_users",
            "fields" => array("*"),
            "limit" => 1,
            "condition" => array(),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
        return $config;
    }

    public static function getUserByCredentials ($login, $password) {
        global $app;
        $config = self::getUser();
        $config["condition"]["EMail"] = $app->getDB()->createCondition($login);
        $config["condition"]["Password"] = $app->getDB()->createCondition($password);
        return $config;
    }

    public static function getUserByID ($id) {
        global $app;
        $config = self::getUser();
        $config["condition"] = array(
            "ID" => $app->getDB()->createCondition($id)
        );
        return $config;
    }

    public static function getUserByEMail ($email) {
        global $app;
        $config = self::getUser();
        $config["condition"] = array(
            "EMail" => $app->getDB()->createCondition($email)
        );
        return $config;
    }


    public static function getUserByValidationString ($ValidationString) {
        global $app;
        $config = self::getUser();
        $config["condition"] = array(
            "ValidationString" => $app->getDB()->createCondition($ValidationString)
        );
        return $config;
    }

    public static function addUser ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        $data["DateLastAccess"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_users",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function updateUser ($UserID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_users",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($UserID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function disableUser ($UserID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_users",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($UserID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }

    public static function activateUser ($ValidationString) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_users",
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

    public static function online ($UserID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_users",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($UserID)
            ),
            "data" => array(
                "IsOnline" => true,
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }

    public static function offline ($UserID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_users",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($UserID)
            ),
            "data" => array(
                "IsOnline" => true,
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }

    public static function getPermissions ($UserID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_permissions",
            "fields" => array("*"),
            "condition" => array(
                "UserID" => $app->getDB()->createCondition($UserID)
            ),
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    public static function addPermissions ($data) {
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

    public static function updatePermissions ($UserID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_permissions",
            "action" => "update",
            "condition" => array(
                "UserID" => $app->getDB()->createCondition($UserID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function getUserAddresses ($UserID, $withRemoved = false) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "source" => "mpws_userAddresses",
            "fields" => array("ID", "UserID", "Address", "POBox", "Country", "City", "Status", "DateCreated", "DateUpdated"),
            "condition" => array(
                "UserID" => $app->getDB()->createCondition($UserID)
            ),
            "options" => array(
                "asDict" => "ID"
            )
        ));
        if (!$withRemoved)
            $config['condition']["Status"] = $app->getDB()->createCondition("ACTIVE");
        return $config;
    }

    // public static function getUserAddress ($UserID, $AddressID) {
    //     return $app->getDB()->createDBQuery(array(
    //         "source" => "mpws_userAddresses",
    //         "fields" => array("*"),
    //         "condition" => array(
    //             "ID" => $app->getDB()->createCondition($AddressID),
    //             "UserID" => $app->getDB()->createCondition($UserID),
    //             "Status" => $app->getDB()->createCondition("ACTIVE")
    //         ),
    //         "options" => array(
    //             "expandSingleRecord" => true
    //         )
    //     ));
    // }

    public static function getAddress ($AddressID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_userAddresses",
            "fields" => array("ID", "UserID", "Address", "POBox", "Country", "City", "Status", "DateCreated", "DateUpdated"),
            "condition" => array(
                "ID" => $app->getDB()->createCondition($AddressID),
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    public static function addAddress ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_userAddresses",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function updateAddress ($AddressID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_userAddresses",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($AddressID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function disableAddress ($AddressID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_userAddresses",
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

    // >>>> User statistics
    public static function stat_UsersOverview () {
        global $app;
        $config = self::getUser();
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

    public static function stat_UsersIntensityLastMonth ($status) {
        global $app;
        $config = self::getUser();
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
    // <<<< User statistics

}

?>
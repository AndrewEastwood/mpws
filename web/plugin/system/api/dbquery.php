<?php

namespace web\plugin\system\api;

class dbquery {

    public static $statusCustomer = array('ACTIVE','REMOVED');
    public static $statusCustomerSettings = array('ACTIVE','DISABLED');

    // TASKS
    public static function addTask ($data) {
        global $app;
        $data["DateCreated"] = $app->getDB()->getDate();
        $params = isset($data['Params']) ? $data['Params'] : '';
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_tasks",
            "action" => "insert",
            "data" => array(
                'CustomerID' => $data['CustomerID'],
                'Group' => $data['Group'],
                'Name' => $data['Name'],
                'Hash' => md5($data['Group'] . $data['Name'] . $params),
                'PrcPath' => isset($data['PrcPath']) ? $data['PrcPath'] : '',
                'PID' => isset($data['PID']) ? $data['PID'] : '',
                'Params' => $params,
                'DateCreated' => $data["DateCreated"]
            ),
            "options" => null
        ));
    }

    public static function scheduleTask ($hash) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_tasks",
            "action" => "update",
            'condition' => array(
                'Hash' => $app->getDB()->createCondition($hash)
            ),
            "data" => array(
                'Scheduled' => 1,
                'IsRunning' => 0,
                'Complete' => 0,
                'ManualCancel' => 0
            ),
            "options" => null
        ));
    }

    public static function startTask ($hash) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_tasks",
            "action" => "update",
            'condition' => array(
                'Hash' => $app->getDB()->createCondition($hash)
            ),
            "data" => array(
                'Scheduled' => 0,
                'IsRunning' => 1,
                'Complete' => 0,
                'ManualCancel' => 0
            ),
            "options" => null
        ));
    }

    public static function getGroupTasks ($groupName, $active = false, $completed = false, $canceled = false) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "source" => "mpws_tasks",
            "action" => "select",
            'condition' => array(
                'Group' => $app->getDB()->createCondition($groupName)
            ),
            "options" => null
        ));
        if ($active) {
            $config['condition']['IsRunning'] = $app->getDB()->createCondition(1);
        }
        if ($completed) {
            $config['condition']['Complete'] = $app->getDB()->createCondition(1);
        }
        if ($canceled) {
            $config['condition']['ManualCancel'] = $app->getDB()->createCondition(1);
        }
        return $config;
    }

    public static function stopTask ($id) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_tasks",
            "action" => "update",
            'condition' => array(
                'ID' => $app->getDB()->createCondition($id)
            ),
            "data" => array(
                'Scheduled' => 0,
                'IsRunning' => 0,
                'Complete' => 0,
                'ManualCancel' => 1
            ),
            "options" => null
        ));
    }

    public static function setTaskResult ($id, $result) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_tasks",
            "action" => "update",
            'condition' => array(
                'ID' => $app->getDB()->createCondition($id)
            ),
            "data" => array(
                'Scheduled' => 0,
                'IsRunning' => 0,
                'Complete' => 1,
                'ManualCancel' => 0,
                'Result' => $result
            ),
            "options" => null
        ));
    }

    public static function getTaskByHash ($hash) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "source" => "mpws_tasks",
            "action" => "select",
            'condition' => array(
                'Hash' => $app->getDB()->createCondition($hash)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
        return $config;
    }

    public static function deleteTaskByHash ($hash) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_tasks",
            "action" => "delete",
            'condition' => array(
                'Hash' => $app->getDB()->createCondition($hash)
            ),
            "options" => null
        ));
    }

    public static function getNextTaskToProcess ($group, $name) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_tasks",
            "action" => "select",
            'condition' => array(
                'Group' => $app->getDB()->createCondition($group),
                'Name' => $app->getDB()->createCondition($name)
            ),
            "order" => array(
                "field" => "DateCreated",
                "ordering" => "ASC"
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // CUSTOMERS
    // -----------------------------------------------
    // -----------------------------------------------

    public static function getCustomer ($id = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "source" => "mpws_customer",
            "fields" => array("*"),
            // "condition" => array(
            //     "Name" => $app->getDB()->createCondition($Name),
            //     "Status" => $app->getDB()->createCondition("ACTIVE")
            // ),
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
        if ($id !== null) {
            $config['condition']['ID'] = $app->getDB()->createCondition($id);
        }
        return $config;
    }

    public static function getCustomerList (array $options = array()) {
        global $app;
        $config = self::getCustomer();
        $config['condition'] = array();
        $config["fields"] = array("ID");
        $config['limit'] = 64;
        $config['group'] = 'mpws_customer.ID';
        unset($config['options']);

        if (!empty($options['_pSearch'])) {
            if (is_string($options['_pSearch'])) {
                $config['condition']["mpws_customer.Name"] = $app->getDB()->createCondition('%' . $options['_pSearch'] . '%', 'like');
                // $config['condition']["Model"] = $app->getDB()->createCondition('%' . $options['search'] . '%', 'like');
                // $config['condition']["SKU"] = $app->getDB()->createCondition('%' . $options['search'] . '%', 'like');
            } elseif (is_array($options['_pSearch'])) {
                foreach ($options['_pSearch'] as $value) {
                    $chunks = explode('=', $value);
                    // var_dump($chunks);
                    if (count($chunks) === 2) {
                        $keyToSearch = strtolower($chunks[0]);
                        $valToSearch = $chunks[1];
                        $conditionField = '';
                        $conditionOp = '=';
                        switch ($keyToSearch) {
                            case 'id':
                                $conditionField = "mpws_customer.ID";
                                $valToSearch = intval($valToSearch);
                                break;
                            case 'n':
                                $conditionField = "mpws_customer.Name";
                                $valToSearch = '%' . $valToSearch . '%';
                                $conditionOp = 'like';
                                break;
                            case 'd':
                                $conditionField = "mpws_customer.Description";
                                $valToSearch = '%' . $valToSearch . '%';
                                $conditionOp = 'like';
                                break;
                            case 'm':
                                $conditionField = "mpws_customer.Model";
                                $valToSearch = '%' . $valToSearch . '%';
                                $conditionOp = 'like';
                                break;
                        }
                        // var_dump($conditionField);
                        // var_dump($valToSearch);
                        // var_dump($conditionOp);
                        if (!empty($conditionField)) {
                            $config['condition'][$conditionField] = $app->getDB()->createCondition($valToSearch, $conditionOp);
                        }
                    }
                    // $config['condition']["mpws_customer.Name"] = $app->getDB()->createCondition('%' . $value . '%', 'like');
                    // $config['condition']["mpws_customer.Model"] = $app->getDB()->createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["mpws_customer.Description"] = $app->getDB()->createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["mpws_customer.SKU"] = $app->getDB()->createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["Model"] = $app->getDB()->createCondition('%' . $value . '%', 'like');
                    // $config['condition']["SKU"] = $app->getDB()->createCondition('%' . $value . '%', 'like');
                }
            }
        }

        // var_dump($config['condition']);
        return $config;
    }

    public static function createCustomer ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_customer",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function updateCustomer ($CustomerID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_customer",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($CustomerID)
            ),
            "action" => "update",
            "data" => $data,
            "options" => null
        ));
    }

    public static function deleteCustomer ($CustomerID) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["Status"] = "REMOVED";
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_customer",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($CustomerID)
            ),
            "action" => "update",
            "data" => $data,
            "options" => null
        ));
    }


    // -----------------------------------------------
    // -----------------------------------------------
    // CUSTOMERS SETTINGS
    // -----------------------------------------------
    // -----------------------------------------------

    public static function getCustomerSettingByID ($customerID, $id = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "source" => "mpws_customerSettings",
            "action" => "select",
            "fields" => array("ID", "CustomerID", "Property", "Value", "Status"),
            "condition" => array(
                "CustomerID" => $app->getDB()->createCondition($customerID)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
        if ($id !== null) {
            $config['condition']['ID'] = $app->getDB()->createCondition($id);
        }
        return $config;
    }

    public static function getCustomerSettings ($customerID) {
        $config = self::getCustomerSetting($customerID);
        $config['limit'] = 64;
        unset($config['options']);
        return $config;
    }

    public static function createCustomerSettings ($data) {
        global $app;
        $data["DateCreated"] = $app->getDB()->getDate();//???? data is array with settings
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_customerSettings",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function updateCustomerSettings ($data) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_customerSettings",
            "action" => "update",
            "data" => $data,
            "options" => null
        ));
    }




    // -----------------------------------------------
    // -----------------------------------------------
    // USERS
    // -----------------------------------------------
    // -----------------------------------------------


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

    public static function setUserOnline ($UserID) {
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

    public static function setUserOffline ($UserID) {
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


    // -----------------------------------------------
    // -----------------------------------------------
    // USER PERMISSIONS
    // -----------------------------------------------
    // -----------------------------------------------
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

    public static function createPermissions ($data) {
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


    // -----------------------------------------------
    // -----------------------------------------------
    // USER ADDRESSES
    // -----------------------------------------------
    // -----------------------------------------------
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

    public static function createAddress ($data) {
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

    // -----------------------------------------------
    // -----------------------------------------------
    // USER STATS
    // -----------------------------------------------
    // -----------------------------------------------
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
}

?>
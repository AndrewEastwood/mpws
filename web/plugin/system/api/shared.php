<?php

namespace web\plugin\system\api;

class shared {

    public static $statusCustomer = array('ACTIVE','REMOVED');
    public static $statusCustomerSettings = array('ACTIVE','DISABLED');

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

    public static function jsapiGetCustomer ($id = null) {
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

    public static function jsapiGetCustomerList (array $options = array()) {
        global $app;
        $config = self::jsapiGetCustomer();
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

    public static function getCustomerSetting ($customerID, $id = null) {
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


    public static function addCustomerParameter ($data) {
        global $app;
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "mpws_customerSettings",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

}

?>
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

    public static function getCustomerSetting ($customerID, $id = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "source" => "mpws_customerSettings",
            "fields" => array("ID", "CustomerID", "Property", "Value", "Status"),
            "condition" = array(
                "CustomerID" => $app->getDB()->createCondition($customerID)
            ),
            "action" => "select",
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
        $config = self::getCustomerSetting();
        unset($config['options']);
        return $config;
    }


    public static function addCustomerParame ($data) {
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
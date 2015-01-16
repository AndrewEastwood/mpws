<?php

namespace web\plugin\system\api;

class shared {

    public function addTask ($data) {
        global $app;
        $data["DateCreated"] = $this->getDate();
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

    public function scheduleTask ($hash) {
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

    public function startTask ($hash) {
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

    public function getGroupTasks ($groupName, $active = false, $completed = false, $canceled = false) {
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

    public function stopTask ($id) {
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

    public function setTaskResult ($id, $result) {
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

    public function getTaskByHash ($hash) {
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

    public function deleteTaskByHash ($hash) {
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

    public function getNextTaskToProcess ($group, $name) {
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
}

?>
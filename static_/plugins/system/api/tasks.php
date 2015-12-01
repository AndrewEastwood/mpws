<?php
namespace static_\plugins\system\api;

use \engine\lib\api as API;

class tasks extends API {

    public function addTask ($group, $name, $params) {
        global $app;
        $result = array();
        $success = false;
        $errors = array();
        // echo 1111;
        $config = data::addTask(array(
            'CustomerID' => $app->getSite()->getRuntimeCustomerID(),
            'Group' => $group,
            'Name' => $name,
            'Params' => $params
        ));
        // var_dump($config);
        try {
            $app->getDB()->beginTransaction();
            $app->getDB()->query($config);
            $app->getDB()->commit();
            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function startTask ($group, $name, $params) {
        global $app;
        $result = array();
        $success = false;
        $errors = array();
        $config = data::startTask(md5($group.$name.$params));
        try {
            $app->getDB()->beginTransaction();
            $app->getDB()->query($config);
            $app->getDB()->commit();
            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function scheduleTask ($group, $name, $params) {
        global $app;
        $result = array();
        $success = false;
        $errors = array();
        $config = data::scheduleTask(md5($group.$name.$params));
        try {
            $app->getDB()->beginTransaction();
            $app->getDB()->query($config);
            $app->getDB()->commit();
            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function cancelTask ($id) {
        global $app;
        $result = array();
        $success = false;
        $errors = array();
        $config = data::stopTask($id);
        try {
            $app->getDB()->beginTransaction();
            $app->getDB()->query($config);
            $app->getDB()->commit();
            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function setTaskResult ($id, $taskResult) {
        global $app;
        $result = array();
        $success = false;
        $errors = array();
        $config = data::setTaskResult($id, $taskResult);
        try {
            $app->getDB()->beginTransaction();
            $app->getDB()->query($config);
            $app->getDB()->commit();
            $success = true;
        } catch (Exception $e) {
            echo '# ..error setting up task result: ' . $e . PHP_EOL;
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function isTaskAdded ($group, $name, $params) {
        global $app;
        $result = array();
        $config = data::getTaskByHash(md5($group . $name . $params));
        $result = $app->getDB()->query($config);
        $this->__adjustTask($result);
        return $result;
    }

    public function deleteTaskByParams ($group, $name, $params) {
        return $this->deleteTaskByHash(md5($group . $name . $params));
    }

    public function deleteTaskByHash ($hash) {
        global $app;
        $result = array();
        $success = false;
        $errors = array();
        $config = data::deleteTaskByHash($hash);
        try {
            $app->getDB()->beginTransaction();
            $result = $app->getDB()->query($config);
            $app->getDB()->commit();
            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function getActiveTasksByGroupName ($groupName) {
        global $app;
        $result = array();
        $config = data::getGroupTasks($groupName, true, false, false);
        $result = $app->getDB()->query($config);
        if ($result) {
            foreach ($result as &$value) {
                $this->__adjustTask($value);
            }
        }
        return $result;
    }

    public function getCompletedTasksByGroupName ($groupName) {
        global $app;
        $result = array();
        $config = data::getGroupTasks($groupName, false, true, false);
        $result = $app->getDB()->query($config);
        if ($result) {
            foreach ($result as &$value) {
                $this->__adjustTask($value);
            }
        }
        return $result;
    }

    public function getNewTasksByGroupName ($groupName) {
        global $app;
        $result = array();
        $config = data::getGroupTasks($groupName, false, false, false);
        $result = $app->getDB()->query($config);
        if ($result) {
            foreach ($result as &$value) {
                $this->__adjustTask($value);
            }
        }
        return $result;
    }

    public function getCanceledTasksByGroupName ($groupName) {
        global $app;
        $result = array();
        $config = data::getGroupTasks($groupName, false, false, true);
        $result = $app->getDB()->query($config);
        if ($result) {
            foreach ($result as &$value) {
                $this->__adjustTask($value);
            }
        }
        return $result;
    }

    public function getNextNewTaskToProcess ($group, $name) {
        global $app;
        $result = array();
        $config = data::getNextTaskToProcess($group, $name);
        $result = $app->getDB()->query($config);
        if ($result) {
            foreach ($result as &$value) {
                $this->__adjustTask($value);
            }
        }
        return $result;
    }

    private function __adjustTask (&$task) {
        if (empty($task))
            return null;
        $task['ID'] = intval($task['ID']);
        $task['CustomerID'] = intval($task['CustomerID']);
        $task['IsRunning'] = intval($task['IsRunning']) === 1;
        $task['Complete'] = intval($task['Complete']) === 1;
        $task['ManualCancel'] = intval($task['ManualCancel']) === 1;
        $task['Scheduled'] = intval($task['Scheduled']) === 1;
    }

}


?>
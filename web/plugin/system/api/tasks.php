<?php
namespace web\plugin\system\api;

class tasks {

    var $shared = null;

    function __construct() {
        $this->shared = new shared();
    }

    public function addTask ($group, $name, $params) {
        global $app;
        $result = array();
        $success = false;
        $errors = array();
        // echo 1111;
        $config = $this->shared->addTask(array(
            'CustomerID' => $this->getCustomerID(),
            'Group' => $group,
            'Name' => $name,
            'Params' => $params
        ));
        // var_dump($config);
        try {
            $app->getDB()->beginTransaction();
            $this->fetch($config);
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
        $config = $this->shared->startTask(md5($group.$name.$params));
        try {
            $app->getDB()->beginTransaction();
            $this->fetch($config);
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
        $config = $this->shared->scheduleTask(md5($group.$name.$params));
        try {
            $app->getDB()->beginTransaction();
            $this->fetch($config);
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
        $config = $this->shared->stopTask($id);
        try {
            $app->getDB()->beginTransaction();
            $this->fetch($config);
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
        $config = $this->shared->setTaskResult($id, $taskResult);
        try {
            $app->getDB()->beginTransaction();
            $this->fetch($config);
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
        $config = $this->shared->getTaskByHash(md5($group . $name . $params));
        $result = $this->fetch($config);
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
        $config = $this->shared->deleteTaskByHash($hash);
        try {
            $app->getDB()->beginTransaction();
            $result = $this->fetch($config);
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
        $config = $this->shared->getGroupTasks($groupName, true, false, false);
        $result = $this->fetch($config);
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
        $config = $this->shared->getGroupTasks($groupName, false, true, false);
        $result = $this->fetch($config);
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
        $config = $this->shared->getGroupTasks($groupName, false, false, false);
        $result = $this->fetch($config);
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
        $config = $this->shared->getGroupTasks($groupName, false, false, true);
        $result = $this->fetch($config);
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
        $config = $this->shared->getNextTaskToProcess($group, $name);
        $result = $this->fetch($config);
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
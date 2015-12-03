<?php
namespace static_\plugins\system\api;

use \engine\lib\api as API;

class tasks extends API {

    public function addTask ($group, $name, $params) {
        return $this->data->addTask(array(
            'Group' => $group,
            'Name' => $name,
            'Params' => $params
        ));
    }

    public function startTask ($group, $name, $params) {
        // global $app;
        return $this->data->startTask(md5($group.$name.$params));
        // global $app;
        // $result = array();
        // $success = false;
        // $errors = array();
        // $config = data::startTask(md5($group.$name.$params));
        // try {
        //     $app->getDB()->beginTransaction();
        //     $app->getDB()->query($config);
        //     $app->getDB()->commit();
        //     $success = true;
        // } catch (Exception $e) {
        //     $app->getDB()->rollBack();
        //     $errors[] = $e->getMessage();
        // }
        // $result['errors'] = $errors;
        // $result['success'] = $success;

        // return $result;
    }

    public function scheduleTask ($group, $name, $params) {
        return $this->data->scheduleTask(md5($group.$name.$params));
        // global $app;
        // $result = array();
        // $success = false;
        // $errors = array();
        // $config = data::scheduleTask(md5($group.$name.$params));
        // try {
        //     $app->getDB()->beginTransaction();
        //     $app->getDB()->query($config);
        //     $app->getDB()->commit();
        //     $success = true;
        // } catch (Exception $e) {
        //     $app->getDB()->rollBack();
        //     $errors[] = $e->getMessage();
        // }
        // $result['errors'] = $errors;
        // $result['success'] = $success;

        // return $result;
    }

    public function cancelTask ($id) {
        return $this->data->stopTask(md5($group.$name.$params));
        // global $app;
        // $result = array();
        // $success = false;
        // $errors = array();
        // $config = data::stopTask($id);
        // try {
        //     $app->getDB()->beginTransaction();
        //     $app->getDB()->query($config);
        //     $app->getDB()->commit();
        //     $success = true;
        // } catch (Exception $e) {
        //     $app->getDB()->rollBack();
        //     $errors[] = $e->getMessage();
        // }
        // $result['errors'] = $errors;
        // $result['success'] = $success;
        // return $result;
    }

    public function setTaskResult ($id, $taskResult) {
        return $this->data->completeTask($id, $taskResult);
        // global $app;
        // $result = array();
        // $success = false;
        // $errors = array();
        // $config = data::setTaskResult($id, $taskResult);
        // try {
        //     $app->getDB()->beginTransaction();
        //     $app->getDB()->query($config);
        //     $app->getDB()->commit();
        //     $success = true;
        // } catch (Exception $e) {
        //     echo '# ..error setting up task result: ' . $e . PHP_EOL;
        //     $app->getDB()->rollBack();
        //     $errors[] = $e->getMessage();
        // }
        // $result['errors'] = $errors;
        // $result['success'] = $success;
        // return $result;
    }

    public function isTaskAdded ($group, $name, $params) {
        return $this->data->getTaskByHash(md5($group . $name . $params));
        // $result = $app->getDB()->query($config);
        // $this->__adjustTask($result);
        // return $result;
    }

    public function deleteTaskByParams ($group, $name, $params) {
        return $this->deleteTaskByHash(md5($group . $name . $params));
    }

    public function deleteTaskByHash ($hash) {
        return $this->data->deleteTaskByHash($hash);
        // global $app;
        // $result = array();
        // $success = false;
        // $errors = array();
        // $config = data::deleteTaskByHash($hash);
        // try {
        //     $app->getDB()->beginTransaction();
        //     $result = $app->getDB()->query($config);
        //     $app->getDB()->commit();
        //     $success = true;
        // } catch (Exception $e) {
        //     $app->getDB()->rollBack();
        //     $errors[] = $e->getMessage();
        // }
        // $result['errors'] = $errors;
        // $result['success'] = $success;
        // return $result;
    }

    public function getActiveTasksByGroupName ($groupName) {
        return $this->data->getGroupTasksArray($groupName, true, false, false);
    }

    public function getCompletedTasksByGroupName ($groupName) {
        return $this->data->getGroupTasksArray($groupName, false, true, false);
    }

    public function getNewTasksByGroupName ($groupName) {
        return $this->data->getGroupTasksArray($groupName, false, false, false);
    }

    public function getCanceledTasksByGroupName ($groupName) {
        return $this->data->getGroupTasksArray($groupName, false, false, true);
    }

    // public function getNextNewTaskToProcess ($group, $name) {
    //     global $app;
    //     $result = array();
    //     $config = data::getNextTaskToProcess($group, $name);
    //     $result = $app->getDB()->query($config);
    //     if ($result) {
    //         foreach ($result as &$value) {
    //             $this->__adjustTask($value);
    //         }
    //     }
    //     return $result;
    // }


}


?>
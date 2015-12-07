<?php

namespace static_\plugins\system\api;

use \engine\lib\validate as Validate;
use \engine\lib\path as Path;
use \engine\lib\dbquery as dbQuery;
use \engine\lib\data as BaseData;
use \engine\lib\result as Result;
use \engine\lib\api as API;

class data extends BaseData {

    // public $statusCustomer = array('ACTIVE','REMOVED');
    // public $statusCustomerSettings = array('ACTIVE','DISABLED');

    var $source_customer = 'mpws_customer';
    var $source_tasks = 'mpws_tasks';
    var $source_users = 'mpws_users';
    var $source_permissions = 'mpws_permissions';
    var $source_address = 'mpws_userAddresses';
    var $source_emails = 'mpws_emails';

    function __construct () {
        global $app;

        parent::__construct();
        
        // this function is being invoked every time
        // when you do select any task and process
        // raw db value before output
        // $filter = ;

        // create required queries
        // ==== CUSTOMERS
        $this->db->createQuery('systemCustomer', $this->source_customer);
        // $this->db->createQuery('systemCustomer_Add', $this->source_customer)
            // ->setConditionFn('CustomerID', array($app->getSite(), 'getRuntimeCustomerID'));

        // ==== TASKS
        $this->db->createQuery('systemTask', $this->source_tasks);
        // $this->db->createQuery('systemTask_Delete', $this->source_tasks);
        // $this->db->createQuery('systemTask_Add', $this->source_tasks)
            // ->setConditionFn('CustomerID', array($app->getSite(), 'getRuntimeCustomerID'));
        $this->db->createQuery('systemTask_Schedule', $this->source_tasks)
            ->setData(array(
                'Scheduled' => 1,
                'IsRunning' => 0,
                'Complete' => 0,
                'ManualCancel' => 0
            ));
        $this->db->createQuery('systemTask_Start', $this->source_tasks)
            ->setData(array(
                'Scheduled' => 0,
                'IsRunning' => 1,
                'Complete' => 0,
                'ManualCancel' => 0
            ));
        $this->db->createQuery('systemTask_Stop', $this->source_tasks)
            ->setData(array(
                'Scheduled' => 0,
                'IsRunning' => 0,
                'Complete' => 0,
                'ManualCancel' => 1
            ));
        $this->db->createQuery('systemTask_Complete', $this->source_tasks)
            ->setData(array(
                'Scheduled' => 0,
                'IsRunning' => 0,
                'Complete' => 1,
                'ManualCancel' => 0
            ));
        $this->db->createQuery('systemTask_getRunning', $this->source_tasks)
            ->setCondition('IsRunning', 1);
        $this->db->createQuery('systemTask_getComplete', $this->source_tasks)
            ->setCondition('Complete', 1);
        $this->db->createQuery('systemTask_getCanceled', $this->source_tasks)
            ->setCondition('ManualCancel', 1);
        $this->db->createQuery('systemTask_getNew', $this->source_tasks)
            ->setCondition('Scheduled', 0)
            ->setCondition('IsRunning', 0)
            ->setCondition('Complete', 0)
            ->setCondition('ManualCancel', 0);

        // ==== USERS
        $this->db->createQuery('systemUsers', $this->source_users);
        // $this->db->createQuery('systemUsers_GetByCreds', $this->source_users);
        // $this->db->createQuery('systemUsers_GetByHash', $this->source_users);
        // $this->db->createQuery('systemUsers_GetByEmil', $this->source_users);
        // $this->db->createQuery('systemUsers_Add', $this->source_users);
        // $this->db->createQuery('systemUsers_Update', $this->source_users);

        // ==== PERMISSIONS
        $this->db->createQuery('systemUserPerms', $this->source_permissions);
        // $this->db->createQuery('systemUserPerms_Add', $this->source_permissions);
        // $this->db->createQuery('systemUserPerms_Update', $this->source_permissions);

        // ==== ADDRESS
        $this->db->createQuery('systemAddress', $this->source_address)
            ->setFields("ID", "UserID", "Address", "POBox",
                "Country", "City", "Status", "DateCreated", "DateUpdated");
            // ->cloneQuery('systemAddress_GetForUser');
        // $this->db->createQuery('systemAddress_GetForUser', $this->source_address);
        // $this->db->createQuery('systemAddress_Update', $this->source_address)
        // $this->db->createQuery('systemAddress_Add', $this->source_address)
        // $this->db->createQuery('systemAddress_Archive', $this->source_address)

        dbQuery::setQueryFilter(function (&$task) {
            if (empty($task))
                return null;
            $task['ID'] = intval($task['ID']);
            $task['CustomerID'] = intval($task['CustomerID']);
            $task['IsRunning'] = intval($task['IsRunning']) === 1;
            $task['Complete'] = intval($task['Complete']) === 1;
            $task['ManualCancel'] = intval($task['ManualCancel']) === 1;
            $task['Scheduled'] = intval($task['Scheduled']) === 1;
        }, 'systemTask');

        dbQuery::setQueryFilter(function (&$customer) {
            // adjusting
            $ID = intval($customer['ID']);
            $customer['ID'] = $ID;
            // $customer['Settings'] = API::getAPI('system:settings')->getSettingsByCustomerID($ID);
            $customer['isBlocked'] = $customer['Status'] != 'ACTIVE';
            $customer['Plugins'] = explode(",", $customer['Plugins']);
            // var_dump($customer);
            if (!empty($customer['Logo'])) {
                $customer['Logo'] = array(
                    'name' => $customer['Logo'],
                    'normal' => '/' . Path::getUploadDirectory() . $this->getCustomerUploadInnerImagePath($customer['HostName'], $customer['Logo']),
                    'sm' => '/' . Path::getUploadDirectory() . $this->getCustomerUploadInnerImagePath($customer['HostName'], $customer['Logo'], 'sm'),
                    'xs' => '/' . Path::getUploadDirectory() . $this->getCustomerUploadInnerImagePath($customer['HostName'], $customer['Logo'], 'xs')
                );
            }
        }, 'systemCustomer');

        dbQuery::setQueryFilter(function (&$address) {
            if (empty($address))
                return null;
            $address['ID'] = intval($address['ID']);
            $address['UserID'] = intval($address['UserID']);
            $address['isRemoved'] = $address['Status'] === 'REMOVED';
        }, 'systemAddress');

        dbQuery::setQueryFilter(function (&$user) {
            // adjusting
            $ID = intval($user['ID']);
            $user['ID'] = $ID;
            $user['IsOnline'] = intval($user['IsOnline']) === 1;
            $user['IsTemp'] = $user['Status'] === "TEMP";
            $user['isBlocked'] = $user['Status'] === "REMOVED";
            unset($user['Password']);

            // attach addresses
            $user['Addresses'] = $this->fetchUserAddresses($ID);

            // append user's permissions
            $permissions = $this->fetchUserPermissionsByUserID($ID);
            unset($permissions['ID']);
            unset($permissions['UserID']);
            unset($permissions['DateUpdated']);
            unset($permissions['DateCreated']);
            foreach ($permissions as $key => $value) {
                $user['p_' . $key] = $value;
            }
            // attach plugin's permissions
            $plugins = API::getAPI('system:plugins');
            $user['_availableOtherPerms'] = $plugins->getPluginsPermissons();

            // customizations
            $user['FullName'] = $user['FirstName'] . ' ' . $user['LastName'];
            $user['ActiveAddressesCount'] = count($user['Addresses']);//
            // $user['ActiveAddressesCount'] = count(array_filter($user['Addresses'], function ($v) {
            //     return !$v['isRemoved'];
            // }));
        }, 'systemUsers');

        dbQuery::setQueryFilter(function (&$perms) {
            // $adjustedPerms = array();
            // adjust permission values
            // var_dump($perms);
            if (!empty($perms)) {
                foreach ($perms as $field => $value) {
                    if (preg_match("/^Can/", $field) === 1) {
                        $perms[$field] = intval($value) === 1;
                    }
                    // if ($field === "Custom") {
                    //     $customPerms = explode(';', $value);
                    //     foreach ($customPerms as $cFiled => $cValue) {
                    //         if (preg_match("/^Can/", $cFiled) === 1) {
                    //             // in custom permission exsists then it's enabled by default
                    //             $adjustedPerms[$cFiled] = true;
                    //         }
                    //     }
                    // }
                }
            }
            $perms['Others'] = array_filter(explode(';', $perms['Others'] ?: ''));
            // $this->permissions = $listOfDOs;
            // $perms = $adjustedPerms;
        }, 'systemUserPerms');

        dbQuery::setBeforeSaveFilter(function ($dataToInsert) {
            $dataToInsert['Others'] = isset($dataToInsert['Others']) ? $dataToInsert['Others'] : array();
            $dataToInsert['Others'] = implode(';', array_filter(
                $dataToInsert['Others'],
                function ($v) {
                    return trim($v);
                }
            ));
        }, 'systemUserPerms');

        // $r = $this->db->getQuery('systemTask_getComplete')
            // ->addConditionFn('CustomerID', array($app->getSite(), 'getRuntimeCustomerID'))
            // ->selectSingleItem();

        // var_dump($r);
        // var_dump(dbQuery::systemTask_getComplete());
        // var_dump(dbQuery::$queryNameToInstanceMap);
        die();
    }

    // public function getCustomerStatuses () {
    //     return $this->_statuses;
    // }

    public function getCustomerUploadInnerDir ($host, $subDir = '') {
        global $app;
        $path = '';
        if (empty($subDir))
            $path = Path::createDirPath($host, 'customers');
        else
            $path = Path::createDirPath($host, 'customers', $subDir);
        return $path;
    }
    public function getCustomerUploadInnerImagePath ($host, $name, $subDir = false) {
        $path = $this->getCustomerUploadInnerDir($host, $subDir);
        return $path . $name;
    }

    // TASKS
    public function addTask ($data) {
        // global $app;
        // $itemID = null;
        $r = new Result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::systemTask_Stop()
                ->insert($data);
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
            // $result = $this->getSuccessResultObject($taskId);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
            // $result = $this->getFailedResultObject($e->getMessage());
        }
        return $r;
        // return $itemID;
        // $result['errors'] = $errors;
        // $result['success'] = $success;
        // $data["DateCreated"] = $this->db->getDate();
        // $params = isset($data['Params']) ? $data['Params'] : '';
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_tasks",
        //     "action" => "insert",
        //     "data" => array(
        //         'CustomerID' => $data['CustomerID'],
        //         'Group' => $data['Group'],
        //         'Name' => $data['Name'],
        //         'Hash' => md5($data['Group'] . $data['Name'] . $params),
        //         'PrcPath' => isset($data['PrcPath']) ? $data['PrcPath'] : '',
        //         'PID' => isset($data['PID']) ? $data['PID'] : '',
        //         'Params' => $params,
        //         'DateCreated' => $data["DateCreated"]
        //     ),
        //     "options" => null
        // ));
    }

    public function scheduleTask ($hash) {
        // global $app;
        $r = new Result();
        // $result = array();
        try {
            $this->db->beginTransaction();
            dbQuery::systemTask_Schedule()
                ->setCondition('Hash', $hash)
                ->update();
            $this->db->commit();
            $r->success();
            // $result = $this->getSuccessResultObject();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
            // $result = $this->getFailedResultObject($e->getMessage());
        }
        return $r;
        // global $app;
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_tasks",
        //     "action" => "update",
        //     'condition' => array(
        //         'Hash' => $this->db->createCondition($hash)
        //     ),
        //     "data" => array(
        //         'Scheduled' => 1,
        //         'IsRunning' => 0,
        //         'Complete' => 0,
        //         'ManualCancel' => 0
        //     ),
        //     "options" => null
        // ));
    }

    public function startTask ($hash) {
        // global $app;
        $r = new Result();
        // $result = array();
        try {
            $this->db->beginTransaction();
            dbQuery::systemTask_Start()
                ->setCondition('Hash', $hash)
                ->update();
            $this->db->commit();
            $r->success();
            // $result = $this->getSuccessResultObject();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
            // $result = $this->getFailedResultObject($e->getMessage());
        }
        return $r;
        // global $app;
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_tasks",
        //     "action" => "update",
        //     'condition' => array(
        //         'Hash' => $this->db->createCondition($hash)
        //     ),
        //     "data" => array(
        //         'Scheduled' => 0,
        //         'IsRunning' => 1,
        //         'Complete' => 0,
        //         'ManualCancel' => 0
        //     ),
        //     "options" => null
        // ));
    }

    public function getGroupTasksArray ($groupName, $active = false, $completed = false, $canceled = false) {
        // global $app;
        $result = array();
        if ($active) {
            $result = dbQuery::systemTask_getRunning()
                ->setAllFields()
                ->setCondition('Group', $groupName)
                ->selectAsArray();
        } else if ($completed) {
            $result = dbQuery::systemTask_getComplete()
                ->setAllFields()
                ->setCondition('Group', $groupName)
                ->selectAsArray();
        } else if ($canceled) {
            $result = dbQuery::systemTask_getCanceled()
                ->setAllFields()
                ->setCondition('Group', $groupName)
                ->selectAsArray();
        } else {
            $result = dbQuery::systemTask_getNew()
                ->setAllFields()
                ->setCondition('Group', $groupName)
                ->selectAsArray();
        }
        return $result;

        // global $app;
        // $config = $this->db->createOrGetQuery(array(
        //     "source" => "mpws_tasks",
        //     "action" => "select",
        //     'condition' => array(
        //         'Group' => $this->db->createCondition($groupName)
        //     ),
        //     "options" => null
        // ));
        // if ($active) {
        //     $config['condition']['IsRunning'] = $this->db->createCondition(1);
        // }
        // if ($completed) {
        //     $config['condition']['Complete'] = $this->db->createCondition(1);
        // }
        // if ($canceled) {
        //     $config['condition']['ManualCancel'] = $this->db->createCondition(1);
        // }
        // return $config;
    }

    public function stopTask ($id) {
        // global $app;
        $r = new Result();
        // $result = array();
        try {
            $this->db->beginTransaction();
            dbQuery::systemTask_Stop()
                ->setCondition('ID', $id)
                ->update();
            $this->db->commit();
            $r->success();
            // $result = $this->getSuccessResultObject();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
            // $result = $this->getFailedResultObject($e->getMessage());
        }
        return $r;
        // global $app;
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_tasks",
        //     "action" => "update",
        //     'condition' => array(
        //         'ID' => $this->db->createCondition($id)
        //     ),
        //     "data" => array(
        //         'Scheduled' => 0,
        //         'IsRunning' => 0,
        //         'Complete' => 0,
        //         'ManualCancel' => 1
        //     ),
        //     "options" => null
        // ));
    }

    public function completeTask ($id, $result) {
        // global $app;
        // $result = array();
        $r = new Result();
        try {
            $this->db->beginTransaction();
            dbQuery::systemTask_Complete()
                ->setCondition('ID', $id)
                ->addDataItem('Result', $result)
                ->update();
            $this->db->commit();
            $r->success();
            // $result = $this->getSuccessResultObject();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
            // $result = $this->getFailedResultObject($e->getMessage());
        }
        return $r;
        // global $app;
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_tasks",
        //     "action" => "update",
        //     'condition' => array(
        //         'ID' => $this->db->createCondition($id)
        //     ),
        //     "data" => array(
        //         'Scheduled' => 0,
        //         'IsRunning' => 0,
        //         'Complete' => 1,
        //         'ManualCancel' => 0,
        //         'Result' => $result
        //     ),
        //     "options" => null
        // ));
    }

    public function fetchTaskByHash ($hash) {
        // global $app;
        // $result = array();
        // $r = new Result();
        // try {
        //     $this->db->beginTransaction();
            return dbQuery::systemTask()
                ->setAllFields()
                ->setCondition('Hash', $hash)
                ->selectSingleItem();
        //     $this->db->commit();
        //     // $result = $this->getSuccessResultObject();
        //     $r->success();
        // } catch (Exception $e) {
        //     $this->db->rollBack();
        //     $r->fail()
        //         ->addError($e->getMessage());
        //     // $result = $this->getFailedResultObject($e->getMessage());
        // }
        // return $r;

        // global $app;
        // $config = $this->db->createOrGetQuery(array(
        //     "source" => "mpws_tasks",
        //     "action" => "select",
        //     'condition' => array(
        //         'Hash' => $this->db->createCondition($hash)
        //     ),
        //     "options" => array(
        //         "expandSingleRecord" => true
        //     )
        // ));
        // return $config;
    }

    public function deleteTaskByHash ($hash) {
        // global $app;
        // $result = array();
        $r = new Result();
        try {
            $this->db->beginTransaction();
            dbQuery::systemTask_Delete()
                ->setCondition('Hash', $hash)
                ->delete();
            $this->db->commit();
            $r->success();
            // $result = $this->getSuccessResultObject();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
            // $result = $this->getFailedResultObject($e->getMessage());
        }
        return $r;
        // global $app;
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_tasks",
        //     "action" => "delete",
        //     'condition' => array(
        //         'Hash' => $this->db->createCondition($hash)
        //     ),
        //     "options" => null
        // ));
    }

    // public function getNextTaskToProcess ($group, $name) {
    //     global $app;
    //     return $this->db->createOrGetQuery(array(
    //         "source" => "mpws_tasks",
    //         "action" => "select",
    //         'condition' => array(
    //             'Group' => $this->db->createCondition($group),
    //             'Name' => $this->db->createCondition($name)
    //         ),
    //         "order" => array(
    //             "field" => "DateCreated",
    //             "ordering" => "ASC"
    //         ),
    //         "options" => array(
    //             "expandSingleRecord" => true
    //         )
    //     ));
    // }

    // -----------------------------------------------
    // -----------------------------------------------
    // CUSTOMERS
    // -----------------------------------------------
    // -----------------------------------------------

    public function fetchCustomerByID ($id) {
        return dbQuery::systemCustomer()
            ->setAllFields()
            ->setCondition('ID', $id)
            ->selectSingleItem();
    }

    public function fetchCustomerByName ($name) {
        return dbQuery::systemCustomer()
            ->setAllFields()
            ->setCondition('HostName', $name)
            ->selectSingleItem();
    }

    public function fetchCustomerDataList (array $options = array()) {
        $q = dbQuery::systemCustomer()
            ->setAllFields()
            ->setCondition('ID', $id)
            ->groupBy('ID')
            ->addParams($options);

        if (!empty($options['_pSearch'])) {
            $searchData = $options['_pSearch'];

            if (is_string($searchData)) {
                $q->addCondition('HostName', '%' . $searchData . '%');
            } elseif (is_array($searchData)) {
                foreach ($searchData as $value) {
                    $chunks = explode('=', $value);
                    if (count($chunks) === 2) {
                        $keyToSearch = strtolower($chunks[0]);
                        $valToSearch = $chunks[1];
                        $conditionField = '';
                        // $conditionOp = '=';
                        switch ($keyToSearch) {
                            case 'id':
                                $conditionField = "mpws_customer.ID";
                                $valToSearch = intval($valToSearch);
                                break;
                            case 'n':
                                $conditionField = "mpws_customer.HostName";
                                $valToSearch = '%' . $valToSearch . '%';
                                // $conditionOp = 'like';
                                break;
                        }
                        if (!empty($conditionField)) {
                            $q->addCondition($conditionField, $valToSearch);
                            // $config['condition'][$conditionField] = $this->db->createCondition($valToSearch, $conditionOp);
                        }
                    }
                }
            }
        }
        return $q->selectAsDataList();
    }

    public function createCustomer ($data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::systemCustomer()
                ->setData($data)
                ->addStandardDateFields()
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
    }

    public function updateCustomer ($customerID, $data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            dbQuery::systemCustomer()
                ->setCondition('ID', $customerID)
                ->setData($data)
                ->addStandardDateUpdatedField()
                ->update();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
    }

    public function archiveCustomer ($customerID) {
        return $this->updateCustomer($customerID, array('Status' => 'REMOVED'));
    }


    // -----------------------------------------------
    // -----------------------------------------------
    // USERS
    // -----------------------------------------------
    // -----------------------------------------------


    public function fetchUserByID ($userID) {
        return dbQuery::systemUsers()
            ->setAllFields()
            ->setCondition('ID', $userID)
            ->selectSingleItem();
    }

    public function fetchUserDataList (array $options = array()) {
        global $app;

        $q = dbQuery::systemUsers()
            ->setAllFields()
            ->groupBy('ID')
            ->addParams($options);

        $q->addCondition(
            // !API::getAPI('system:auth')->ifYouCan('Maintain')
            'CustomerID', $app->getSite()->getRuntimeCustomerID());

        if (!empty($options['_pSearch'])) {
            $searchData = $options['_pSearch'];
            if (is_string($searchData)) {
                $config['condition']["mpws_users.FirstName"] = $this->db->createCondition('%' . $searchData . '%', 'like');
                // $config['condition']["Model"] = $this->db->createCondition('%' . $options['search'] . '%', 'like');
                // $config['condition']["SKU"] = $this->db->createCondition('%' . $options['search'] . '%', 'like');
            } elseif (is_array($searchData)) {
                foreach ($options['_pSearch'] as $value) {
                    $chunks = explode('=', $value);
                    // var_dump($chunks);
                    if (count($chunks) === 2) {
                        $keyToSearch = strtolower($chunks[0]);
                        $valToSearch = $chunks[1];
                        $conditionField = '';
                        // $conditionOp = '=';
                        switch ($keyToSearch) {
                            case 'id':
                                $conditionField = "mpws_users.ID";
                                $valToSearch = intval($valToSearch);
                                break;
                            case 'n':
                                $conditionField = "mpws_users.FirstName";
                                $valToSearch = '%' . $valToSearch . '%';
                                // $conditionOp = 'like';
                                break;
                            case 'ln':
                                $conditionField = "mpws_users.LastName";
                                $valToSearch = '%' . $valToSearch . '%';
                                // $conditionOp = 'like';
                                break;
                            case 'email':
                                $conditionField = "mpws_users.EMail";
                                $valToSearch = '%' . $valToSearch . '%';
                                // $conditionOp = 'like';
                                break;
                            case 'p':
                                $conditionField = "mpws_users.Phone";
                                $valToSearch = '%' . $valToSearch . '%';
                                // $conditionOp = 'like';
                                break;
                            // case 'd':
                            //     $conditionField = "mpws_users.Description";
                            //     $valToSearch = '%' . $valToSearch . '%';
                            //     $conditionOp = 'like';
                            //     break;
                        }
                        // var_dump($conditionField);
                        // var_dump($valToSearch);
                        // var_dump($conditionOp);
                        if (!empty($conditionField)) {
                            $q->addCondition($conditionField, $valToSearch);
                            // $config['condition'][$conditionField] = $this->db->createCondition($valToSearch);
                        }
                    }
                    // $config['condition']["mpws_users.Name"] = $this->db->createCondition('%' . $value . '%', 'like');
                    // $config['condition']["mpws_users.Model"] = $this->db->createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["mpws_users.Description"] = $this->db->createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["mpws_users.SKU"] = $this->db->createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["Model"] = $this->db->createCondition('%' . $value . '%', 'like');
                    // $config['condition']["SKU"] = $this->db->createCondition('%' . $value . '%', 'like');
                }
            }
        }

        return $q->selectAsDataList();
    }

    public function fetchUserByCredentials ($login, $password) {
        global $app;
        return dbQuery::systemUsers()
            ->setAllFields()
            ->setCondition('EMAil', $login)
            ->addCondition('Password', $password)
            ->addConditionByFlag(!$app->isToolbox(), 'CustomerID', $app->getSite()->getRuntimeCustomerID())
            ->selectSingleItem();
    }

    public function fetchUserByEMail ($email) {
        return dbQuery::systemUsers()
            ->setAllFields()
            ->setCondition('Password', $email)
            ->selectSingleItem();
    }

    public function fetchUserByValidationString ($ValidationString) {
        return dbQuery::systemUsers()
            ->setAllFields()
            ->setCondition('ValidationString', $ValidationString)
            ->selectSingleItem();
    }

    public function createUser ($data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::systemUsers_Add()
                ->setData($data)
                ->addStandardDateFields()
                ->addStandardDateNowFiled('DateLastactivateUserAccess')
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
    }

    public function updateUser ($userID, $data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            dbQuery::systemUsers_Update()
                ->setData($data)
                ->addStandardDateUpdatedField()
                ->setCondition('ID', $userID)
                ->update();
            $this->db->commit();
            $r->success();
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = $this->db->getDate();
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_users",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => $this->db->createCondition($UserID)
        //     ),
        //     "data" => $data,
        //     "options" => null
        // ));
    }

    public function disableUser ($userID) {
        return $this->updateUser($userID, array(
                'Status' => 'REMOVED'
            ));
        // global $app;
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_users",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => $this->db->createCondition($UserID)
        //     ),
        //     "data" => array(
        //         "Status" => 'REMOVED',
        //         "DateUpdated" => $this->db->getDate()
        //     ),
        //     "options" => null
        // ));
    }

    public function activateUser ($validationString) {
        return $this->updateUser($userID, array(
                'ValidationString' => $validationString,
                'Status' => 'ACTIVE'
            ));

        // global $app;
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_users",
        //     "action" => "update",
        //     "condition" => array(
        //         "ValidationString" => $this->db->createCondition($ValidationString)
        //     ),
        //     "data" => array(
        //         "Status" => "ACTIVE",
        //         "DateUpdated" => $this->db->getDate()
        //     ),
        //     "options" => null
        // ));
    }

    public function setUserOnline ($userID) {
        return $this->updateUser($userID, array(
            'IsOnline' => true
            ));
        // global $app;
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_users",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => $this->db->createCondition($UserID)
        //     ),
        //     "data" => array(
        //         "IsOnline" => true,
        //         "DateUpdated" => $this->db->getDate()
        //     ),
        //     "options" => null
        // ));
    }

    public function setUserOffline ($userID) {
        return $this->updateUser($userID, array(
            'IsOnline' => false
            ));
        // global $app;
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_users",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => $this->db->createCondition($UserID)
        //     ),
        //     "data" => array(
        //         "IsOnline" => true,
        //         "DateUpdated" => $this->db->getDate()
        //     ),
        //     "options" => null
        // ));
    }


    // -----------------------------------------------
    // -----------------------------------------------
    // USER PERMISSIONS
    // -----------------------------------------------
    // -----------------------------------------------
    public function fetchUserPermissionsByUserID ($userID) {
        return dbQuery::systemUserPerms()
            ->setAllFields()
            ->setCondition('UserID', $userID)
            ->selectSingleItem();
    //     global $app;
    //     return $this->db->createOrGetQuery(array(
    //         "source" => "mpws_permissions",
    //         "fields" => array("*"),
    //         "condition" => array(
    //             "UserID" => $this->db->createCondition($UserID)
    //         ),
    //         "limit" => 1,
    //         "options" => array(
    //             "expandSingleRecord" => true
    //         )
    //     ));
    }

    public function createUserPermissions ($userID, $data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::systemUserPerms()
                ->setCondition('UserID', $userID)
                ->setData($data)
                ->addStandardDateFields()
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = $this->db->getDate();
        // $data["DateCreated"] = $this->db->getDate();
        // $data['UserID'] = $UserID;
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_permissions",
        //     "action" => "insert",
        //     "data" => $data,
        //     "options" => null
        // ));
    }

    public function updateUserPermissions ($userID, $data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::systemUserPerms()
                ->setCondition('UserID', $userID)
                ->setData($data)
                ->addStandardDateUpdatedField()
                ->update();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = $this->db->getDate();
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_permissions",
        //     "action" => "update",
        //     "condition" => array(
        //         "UserID" => $this->db->createCondition($UserID)
        //     ),
        //     "data" => $data,
        //     "options" => null
        // ));
    }


    // -----------------------------------------------
    // -----------------------------------------------
    // USER ADDRESSES
    // -----------------------------------------------
    // -----------------------------------------------
    public function fetchAddress ($addressID) {
        return dbQuery::systemAddress()
            ->setAllFields()
            ->setCondition('ID', $addressID)
            ->selectSingleItem();
        // global $app;
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_userAddresses",
        //     "fields" => array("ID", "UserID", "Address", "POBox", "Country", "City", "Status", "DateCreated", "DateUpdated"),
        //     "condition" => array(
        //         "ID" => $this->db->createCondition($AddressID),
        //     ),
        //     "options" => array(
        //         "expandSingleRecord" => true
        //     )
        // ));
    }

    public function fetchUserAddresses ($userID) {
        return dbQuery::systemAddress()
            ->setAllFields()
            ->setCondition('UserID', $userID)
            ->addCondition('Status', "ACTIVE")
            ->selectAsDict("ID");
        // global $app;
        // $config = $this->db->createOrGetQuery(array(
        //     "source" => "mpws_userAddresses",
        //     "fields" => array("ID", "UserID", "Address", "POBox", "Country", "City", "Status", "DateCreated", "DateUpdated"),
        //     "condition" => array(
        //         "UserID" => $this->db->createCondition($UserID)
        //     ),
        //     "options" => array(
        //         "asDict" => "ID"
        //     )
        // ));
        // if (!$withRemoved)
        //     $config['condition']["Status"] = $this->db->createCondition("ACTIVE");
        // return $config;
    }

    public function fetchUserAddressesCount ($userID) {
        return dbQuery::systemAddress()
            ->setCondition('UserID', $userID)
            ->addCondition('Status', "ACTIVE")
            ->selectCount("ID");
    }

    public function createAddress ($data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::systemAddress()
                ->setData($data)
                ->addStandardDateFields()
                ->insert();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = $this->db->getDate();
        // $data["DateCreated"] = $this->db->getDate();
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_userAddresses",
        //     "action" => "insert",
        //     "data" => $data,
        //     "options" => null
        // ));
    }

    public function updateAddress ($addressID, $data) {
        $r = new result();
        try {
            $this->db->beginTransaction();
            $itemID = dbQuery::systemAddress()
                ->setCondition('ID', $addressID)
                ->setData($data)
                ->addStandardDateUpdatedField()
                ->update();
            $this->db->commit();
            $r->success()
                ->setResult($itemID);
        } catch (Exception $e) {
            $this->db->rollBack();
            $r->fail()
                ->addError($e->getMessage());
        }
        return $r;
        // global $app;
        // $data["DateUpdated"] = $this->db->getDate();
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_userAddresses",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => $this->db->createCondition($AddressID)
        //     ),
        //     "data" => $data,
        //     "options" => null
        // ));
    }

    public function disableAddress ($addressID) {
        return $this->updateAddress($addressID, array(
                'Status' => 'REMOVED'
            ));
        // global $app;
        // return $this->db->createOrGetQuery(array(
        //     "source" => "mpws_userAddresses",
        //     "action" => "update",
        //     "condition" => array(
        //         "ID" => $this->db->createCondition($AddressID)
        //     ),
        //     "data" => array(
        //         "Status" => 'REMOVED',
        //         "DateUpdated" => $this->db->getDate()
        //     ),
        //     "options" => null
        // ));
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // USER STATS
    // -----------------------------------------------
    // -----------------------------------------------
    public function stat_UsersOverview () {
        return dbQuery::systemUserPerms()
            ->setFileds("@COUNT(*) AS ItemsCount", "Status")
            ->groupBy('Status')
            ->selectAsDict('Status', 'ItemsCount');

        // global $app;
        // $config = self::getUser();
        // $config['fields'] = array("@COUNT(*) AS ItemsCount", "Status");
        // $config['group'] = "Status";
        // $config['limit'] = 0;
        // $config['options'] = array(
        //     'asDict' => array(
        //         'keys' => 'Status',
        //         'values' => 'ItemsCount'
        //     )
        // );
        // unset($config['condition']);
        // unset($config['additional']);
        // return $config;
    }

    public function stat_UsersIntensityLastMonth ($status) {
        return dbQuery::systemUserPerms()
            ->setFileds("@COUNT(*) AS ItemsCount", "@Date(DateCreated) AS IncomeDate")
            ->groupBy('Date(DateCreated)')
            ->setCondition('Status', $status)
            ->addCondition('DateCreated', date('Y-m-d', strtotime("-10 month")))
            ->selectAsDict('IncomeDate', 'ItemsCount');
        // global $app;
        // $config = self::getUser();
        // $config['fields'] = array("@COUNT(*) AS ItemsCount", "@Date(DateCreated) AS IncomeDate");
        // $config['condition'] = array(
        //     'Status' => $this->db->createCondition($status),
        //     'DateCreated' => $this->db->createCondition(date('Y-m-d', strtotime("-10 month")), ">")
        // );
        // $config['options'] = array(
        //     'asDict' => array(
        //         'keys' => 'IncomeDate',
        //         'values' => 'ItemsCount'
        //     )
        // );
        // $config['group'] = 'Date(DateCreated)';
        // $config['limit'] = 0;
        // unset($config['additional']);
        // return $config;
    }


    // -----------------------------------------------
    // -----------------------------------------------
    // EMAILS
    // -----------------------------------------------
    // -----------------------------------------------


    // public function getEmailByID ($EmailID = null) {
    //     global $app;
    //     $config = $this->db->createOrGetQuery(array(
    //         "source" => "mpws_emails",
    //         "fields" => array("*"),
    //         "limit" => 1,
    //         "condition" => array(),
    //         "options" => array(
    //             "expandSingleRecord" => true
    //         )
    //     ));
    //     if (isset($EmailID) && $EmailID != null) {
    //         $config['condition']['ID'] = $this->db->createCondition($EmailID);
    //     }
    //     return $config;
    // }

    // public function getEmailList (array $options = array()) {
    //     global $app;
    //     $config = self::getEmailByID();
    //     $config['fields'] = array("ID");
    //     $config['limit'] = 64;
    //     $config['options']['expandSingleRecord'] = false;
    //     if (empty($options['removed'])) {
    //         $config['condition']['Status'] = $this->db->createCondition('ACTIVE');
    //     }
    //     return $config;
    // }

    // public function getEmailListSimple (array $options = array()) {
    //     global $app;
    //     $config = self::getEmailList($options);
    //     $config['fields'] = array("ID", "Name");
    //     return $config;
    // }

    // public function createEmail ($data) {
    //     global $app;
    //     $data["DateUpdated"] = $this->db->getDate();
    //     $data["DateCreated"] = $this->db->getDate();
    //     $data["Name"] = substr($data["Name"], 0, 300);
    //     return $this->db->createOrGetQuery(array(
    //         "source" => "mpws_emails",
    //         "action" => "insert",
    //         "data" => $data,
    //         "options" => null
    //     ));
    // }

    // public function updateEmail ($EmailID, $data) {
    //     global $app;
    //     $data["DateUpdated"] = $this->db->getDate();
    //     if (isset($data['Name'])) {
    //         $data["Name"] = substr($data["Name"], 0, 300);
    //     }
    //     return $this->db->createOrGetQuery(array(
    //         "source" => "mpws_emails",
    //         "action" => "update",
    //         "condition" => array(
    //             "ID" => $this->db->createCondition($EmailID)
    //         ),
    //         "data" => $data,
    //         "options" => null
    //     ));
    // }

    // public function archiveEmail ($EmailID) {
    //     global $app;
    //     $config = $this->db->createOrGetQuery(array(
    //         "source" => "mpws_emails",
    //         "action" => "update",
    //         "condition" => array(
    //             "Status" => $this->db->createCondition("REMOVED", "!="),
    //         ),
    //         "data" => array(
    //             "Status" => 'ARCHIVED',
    //             "DateUpdated" => $this->db->getDate()
    //         ),
    //         "options" => null
    //     ));
    //     if (isset($EmailID) && $EmailID != null) {
    //         $config['condition']['ID'] = $this->db->createCondition($EmailID);
    //     }
    //     return $config;
    // }

    // public function getSubscriberByID ($SubscriberID = null) {
    //     global $app;
    //     $config = $this->db->createOrGetQuery(array(
    //         "source" => "mpws_subscribers",
    //         "fields" => array("*"),
    //         "limit" => 1,
    //         "condition" => array(),
    //         "options" => array(
    //             "expandSingleRecord" => true
    //         )
    //     ));
    //     if (isset($SubscriberID) && $SubscriberID != null) {
    //         $config['condition']['ID'] = $this->db->createCondition($SubscriberID);
    //     }
    //     return $config;
    // }

    // public function getSubscribersList (array $options = array()) {
    //     global $app;
    //     $config = self::getSubscriberByID();
    //     $config['fields'] = array("ID");
    //     $config['limit'] = 64;
    //     $config['options']['expandSingleRecord'] = false;
    //     if (empty($options['removed'])) {
    //         $config['condition']['Status'] = $this->db->createCondition('ACTIVE');
    //     }
    //     return $config;
    // }


}

?>
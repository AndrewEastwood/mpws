<?php

namespace static_\plugins\system\api;

use \engine\lib\dbquery as dbQuery;
use \engine\lib\data as BaseData;

class data extends BaseData {

    public static $statusCustomer = array('ACTIVE','REMOVED');
    public static $statusCustomerSettings = array('ACTIVE','DISABLED');


    var $source_tasks = 'mpws_tasks';

    function __construct () {
        global $app;

        parent::__construct();
        
        // this function is being invoked every time
        // when you do select any task and process
        // raw db value before output
        // $filter = ;
        
        // create required queries
        // ==== TASKS
        $this->db->createQuery('systemTask_Get', $this->source_tasks);

        $this->db->createQuery('systemTask_Delete', $this->source_tasks);

        $this->db->createQuery('systemTask_Add', $this->source_tasks)
            ->setConditionFn('CustomerID', array($app->getSite(), 'getRuntimeCustomerID'));

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

        dbQuery::setQueryFilter(function (&$task) {
            if (empty($task))
                return null;
            $task['ID'] = intval($task['ID']);
            $task['CustomerID'] = intval($task['CustomerID']);
            $task['IsRunning'] = intval($task['IsRunning']) === 1;
            $task['Complete'] = intval($task['Complete']) === 1;
            $task['ManualCancel'] = intval($task['ManualCancel']) === 1;
            $task['Scheduled'] = intval($task['Scheduled']) === 1;
        }, 'systemTask_.*');

        // $r = $this->db->getQuery('systemTask_getComplete')
            // ->addConditionFn('CustomerID', array($app->getSite(), 'getRuntimeCustomerID'))
            // ->selectSingleItem();

        // var_dump($r);
        // var_dump(dbQuery::systemTask_getComplete());
        // var_dump(dbQuery::$queryNameToInstanceMap);
        die();
    }

    // TASKS
    public function addTask ($data) {
        global $app;
        $result = array();
        try {
            $this->db->beginTransaction();
            dbQuery::systemTask_Stop()
                ->insert($data);
            $this->db->commit();
            $result = $this->getSuccessResultObject();
        } catch (Exception $e) {
            $this->db->rollBack();
            $result = $this->getFailedResultObject($e->getMessage());
        }
        return $result;
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

    public static function scheduleTask ($hash) {
        global $app;
        $result = array();
        try {
            $this->db->beginTransaction();
            dbQuery::systemTask_Schedule()
                ->setCondition('Hash', $hash)
                ->update();
            $this->db->commit();
            $result = $this->getSuccessResultObject();
        } catch (Exception $e) {
            $this->db->rollBack();
            $result = $this->getFailedResultObject($e->getMessage());
        }
        return $result;
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

    public static function startTask ($hash) {
        global $app;
        $result = array();
        try {
            $this->db->beginTransaction();
            dbQuery::systemTask_Start()
                ->setCondition('Hash', $hash)
                ->update();
            $this->db->commit();
            $result = $this->getSuccessResultObject();
        } catch (Exception $e) {
            $this->db->rollBack();
            $result = $this->getFailedResultObject($e->getMessage());
        }
        return $result;
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

    public static function getGroupTasks ($groupName, $active = false, $completed = false, $canceled = false) {
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

    public static function stopTask ($id) {
        global $app;
        $result = array();
        try {
            $this->db->beginTransaction();
            dbQuery::systemTask_Stop()
                ->setCondition('ID', $id)
                ->update();
            $this->db->commit();
            $result = $this->getSuccessResultObject();
        } catch (Exception $e) {
            $this->db->rollBack();
            $result = $this->getFailedResultObject($e->getMessage());
        }
        return $result;
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

    public static function completeTask ($id, $result) {
        global $app;
        $result = array();
        try {
            $this->db->beginTransaction();
            dbQuery::systemTask_Complete()
                ->setCondition('ID', $id)
                ->addDataItem('Result', $result)
                ->update();
            $this->db->commit();
            $result = $this->getSuccessResultObject();
        } catch (Exception $e) {
            $this->db->rollBack();
            $result = $this->getFailedResultObject($e->getMessage());
        }
        return $result;
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

    public static function getTaskByHash ($hash) {
        global $app;
        $result = array();
        try {
            $this->db->beginTransaction();
            dbQuery::systemTask_Get()
                ->setCondition('Hash', $hash)
                ->select();
            $this->db->commit();
            $result = $this->getSuccessResultObject();
        } catch (Exception $e) {
            $this->db->rollBack();
            $result = $this->getFailedResultObject($e->getMessage());
        }
        return $result;

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

    public static function deleteTaskByHash ($hash) {
        global $app;
        $result = array();
        try {
            $this->db->beginTransaction();
            dbQuery::systemTask_Delete()
                ->addCondition('Hash', $hash)
                ->delete();
            $this->db->commit();
            $result = $this->getSuccessResultObject();
        } catch (Exception $e) {
            $this->db->rollBack();
            $result = $this->getFailedResultObject($e->getMessage());
        }
        return $result;
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

    public static function getNextTaskToProcess ($group, $name) {
        global $app;
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_tasks",
            "action" => "select",
            'condition' => array(
                'Group' => $this->db->createCondition($group),
                'Name' => $this->db->createCondition($name)
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
        $config = $this->db->createOrGetQuery(array(
            "source" => "mpws_customer",
            "fields" => array("*"),
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
        if ($id !== null) {
            $config['condition']['ID'] = $this->db->createCondition($id);
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
                $config['condition']["mpws_customer.HostName"] = $this->db->createCondition('%' . $options['_pSearch'] . '%', 'like');
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
                                $conditionField = "mpws_customer.HostName";
                                $valToSearch = '%' . $valToSearch . '%';
                                $conditionOp = 'like';
                                break;
                        }
                        if (!empty($conditionField)) {
                            $config['condition'][$conditionField] = $this->db->createCondition($valToSearch, $conditionOp);
                        }
                    }
                }
            }
        }

        return $config;
    }

    public static function createCustomer ($data) {
        global $app;
        $data["DateUpdated"] = $this->db->getDate();
        $data["DateCreated"] = $this->db->getDate();
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_customer",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function updateCustomer ($CustomerID, $data) {
        global $app;
        $data["DateUpdated"] = $this->db->getDate();
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_customer",
            "condition" => array(
                "ID" => $this->db->createCondition($CustomerID)
            ),
            "action" => "update",
            "data" => $data,
            "options" => null
        ));
    }

    public static function archiveCustomer ($CustomerID) {
        global $app;
        $data["DateUpdated"] = $this->db->getDate();
        $data["Status"] = "REMOVED";
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_customer",
            "condition" => array(
                "ID" => $this->db->createCondition($CustomerID)
            ),
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
        $config = $this->db->createOrGetQuery(array(
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

    public static function getUserList (array $options = array()) {
        global $app;
        $config = self::getUser();
        $config['condition'] = array();
        $config["fields"] = array("ID");
        $config['limit'] = 64;
        $config['group'] = 'mpws_users.ID';
        unset($config['options']);

        if (!empty($options['_pSearch'])) {
            if (is_string($options['_pSearch'])) {
                $config['condition']["mpws_users.FirstName"] = $this->db->createCondition('%' . $options['_pSearch'] . '%', 'like');
                // $config['condition']["Model"] = $this->db->createCondition('%' . $options['search'] . '%', 'like');
                // $config['condition']["SKU"] = $this->db->createCondition('%' . $options['search'] . '%', 'like');
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
                                $conditionField = "mpws_users.ID";
                                $valToSearch = intval($valToSearch);
                                break;
                            case 'n':
                                $conditionField = "mpws_users.FirstName";
                                $valToSearch = '%' . $valToSearch . '%';
                                $conditionOp = 'like';
                                break;
                            case 'ln':
                                $conditionField = "mpws_users.LastName";
                                $valToSearch = '%' . $valToSearch . '%';
                                $conditionOp = 'like';
                                break;
                            case 'email':
                                $conditionField = "mpws_users.EMail";
                                $valToSearch = '%' . $valToSearch . '%';
                                $conditionOp = 'like';
                                break;
                            case 'p':
                                $conditionField = "mpws_users.Phone";
                                $valToSearch = '%' . $valToSearch . '%';
                                $conditionOp = 'like';
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
                            $config['condition'][$conditionField] = $this->db->createCondition($valToSearch, $conditionOp);
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

        // var_dump($config['condition']);
        return $config;
    }

    public static function getUserByCredentials ($login, $password) {
        global $app;
        $config = self::getUser();
        $config["condition"]["EMail"] = $this->db->createCondition($login);
        $config["condition"]["Password"] = $this->db->createCondition($password);
        return $config;
    }

    public static function getUserByID ($id) {
        global $app;
        $config = self::getUser();
        $config["condition"] = array(
            "ID" => $this->db->createCondition($id)
        );
        return $config;
    }

    public static function getUserByEMail ($email) {
        global $app;
        $config = self::getUser();
        $config["condition"] = array(
            "EMail" => $this->db->createCondition($email)
        );
        return $config;
    }

    public static function getUserByValidationString ($ValidationString) {
        global $app;
        $config = self::getUser();
        $config["condition"] = array(
            "ValidationString" => $this->db->createCondition($ValidationString)
        );
        return $config;
    }

    public static function addUser ($data) {
        global $app;
        $data["DateUpdated"] = $this->db->getDate();
        $data["DateCreated"] = $this->db->getDate();
        $data["DateLastAccess"] = $this->db->getDate();
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_users",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function updateUser ($UserID, $data) {
        global $app;
        $data["DateUpdated"] = $this->db->getDate();
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_users",
            "action" => "update",
            "condition" => array(
                "ID" => $this->db->createCondition($UserID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function disableUser ($UserID) {
        global $app;
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_users",
            "action" => "update",
            "condition" => array(
                "ID" => $this->db->createCondition($UserID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $this->db->getDate()
            ),
            "options" => null
        ));
    }

    public static function activateUser ($ValidationString) {
        global $app;
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_users",
            "action" => "update",
            "condition" => array(
                "ValidationString" => $this->db->createCondition($ValidationString)
            ),
            "data" => array(
                "Status" => "ACTIVE",
                "DateUpdated" => $this->db->getDate()
            ),
            "options" => null
        ));
    }

    public static function setUserOnline ($UserID) {
        global $app;
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_users",
            "action" => "update",
            "condition" => array(
                "ID" => $this->db->createCondition($UserID)
            ),
            "data" => array(
                "IsOnline" => true,
                "DateUpdated" => $this->db->getDate()
            ),
            "options" => null
        ));
    }

    public static function setUserOffline ($UserID) {
        global $app;
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_users",
            "action" => "update",
            "condition" => array(
                "ID" => $this->db->createCondition($UserID)
            ),
            "data" => array(
                "IsOnline" => true,
                "DateUpdated" => $this->db->getDate()
            ),
            "options" => null
        ));
    }


    // -----------------------------------------------
    // -----------------------------------------------
    // USER PERMISSIONS
    // -----------------------------------------------
    // -----------------------------------------------
    public static function getUserPermissionsByUserID ($UserID) {
        global $app;
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_permissions",
            "fields" => array("*"),
            "condition" => array(
                "UserID" => $this->db->createCondition($UserID)
            ),
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    public static function createUserPermissions ($UserID, $data) {
        global $app;
        $data["DateUpdated"] = $this->db->getDate();
        $data["DateCreated"] = $this->db->getDate();
        $data['UserID'] = $UserID;
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_permissions",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function updateUserPermissions ($UserID, $data) {
        global $app;
        $data["DateUpdated"] = $this->db->getDate();
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_permissions",
            "action" => "update",
            "condition" => array(
                "UserID" => $this->db->createCondition($UserID)
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
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_userAddresses",
            "fields" => array("ID", "UserID", "Address", "POBox", "Country", "City", "Status", "DateCreated", "DateUpdated"),
            "condition" => array(
                "ID" => $this->db->createCondition($AddressID),
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    public static function getUserAddresses ($UserID, $withRemoved = false) {
        global $app;
        $config = $this->db->createOrGetQuery(array(
            "source" => "mpws_userAddresses",
            "fields" => array("ID", "UserID", "Address", "POBox", "Country", "City", "Status", "DateCreated", "DateUpdated"),
            "condition" => array(
                "UserID" => $this->db->createCondition($UserID)
            ),
            "options" => array(
                "asDict" => "ID"
            )
        ));
        if (!$withRemoved)
            $config['condition']["Status"] = $this->db->createCondition("ACTIVE");
        return $config;
    }

    public static function createAddress ($data) {
        global $app;
        $data["DateUpdated"] = $this->db->getDate();
        $data["DateCreated"] = $this->db->getDate();
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_userAddresses",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function updateAddress ($AddressID, $data) {
        global $app;
        $data["DateUpdated"] = $this->db->getDate();
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_userAddresses",
            "action" => "update",
            "condition" => array(
                "ID" => $this->db->createCondition($AddressID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function disableAddress ($AddressID) {
        global $app;
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_userAddresses",
            "action" => "update",
            "condition" => array(
                "ID" => $this->db->createCondition($AddressID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $this->db->getDate()
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
            'Status' => $this->db->createCondition($status),
            'DateCreated' => $this->db->createCondition(date('Y-m-d', strtotime("-10 month")), ">")
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


    // -----------------------------------------------
    // -----------------------------------------------
    // EMAILS
    // -----------------------------------------------
    // -----------------------------------------------


    public static function getEmailByID ($EmailID = null) {
        global $app;
        $config = $this->db->createOrGetQuery(array(
            "source" => "mpws_emails",
            "fields" => array("*"),
            "limit" => 1,
            "condition" => array(),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
        if (isset($EmailID) && $EmailID != null) {
            $config['condition']['ID'] = $this->db->createCondition($EmailID);
        }
        return $config;
    }

    public static function getEmailList (array $options = array()) {
        global $app;
        $config = self::getEmailByID();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['removed'])) {
            $config['condition']['Status'] = $this->db->createCondition('ACTIVE');
        }
        return $config;
    }

    public static function getEmailListSimple (array $options = array()) {
        global $app;
        $config = self::getEmailList($options);
        $config['fields'] = array("ID", "Name");
        return $config;
    }

    public static function createEmail ($data) {
        global $app;
        $data["DateUpdated"] = $this->db->getDate();
        $data["DateCreated"] = $this->db->getDate();
        $data["Name"] = substr($data["Name"], 0, 300);
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_emails",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function updateEmail ($EmailID, $data) {
        global $app;
        $data["DateUpdated"] = $this->db->getDate();
        if (isset($data['Name'])) {
            $data["Name"] = substr($data["Name"], 0, 300);
        }
        return $this->db->createOrGetQuery(array(
            "source" => "mpws_emails",
            "action" => "update",
            "condition" => array(
                "ID" => $this->db->createCondition($EmailID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function archiveEmail ($EmailID) {
        global $app;
        $config = $this->db->createOrGetQuery(array(
            "source" => "mpws_emails",
            "action" => "update",
            "condition" => array(
                "Status" => $this->db->createCondition("REMOVED", "!="),
            ),
            "data" => array(
                "Status" => 'ARCHIVED',
                "DateUpdated" => $this->db->getDate()
            ),
            "options" => null
        ));
        if (isset($EmailID) && $EmailID != null) {
            $config['condition']['ID'] = $this->db->createCondition($EmailID);
        }
        return $config;
    }

    public static function getSubscriberByID ($SubscriberID = null) {
        global $app;
        $config = $this->db->createOrGetQuery(array(
            "source" => "mpws_subscribers",
            "fields" => array("*"),
            "limit" => 1,
            "condition" => array(),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
        if (isset($SubscriberID) && $SubscriberID != null) {
            $config['condition']['ID'] = $this->db->createCondition($SubscriberID);
        }
        return $config;
    }

    public static function getSubscribersList (array $options = array()) {
        global $app;
        $config = self::getSubscriberByID();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['removed'])) {
            $config['condition']['Status'] = $this->db->createCondition('ACTIVE');
        }
        return $config;
    }


}

?>
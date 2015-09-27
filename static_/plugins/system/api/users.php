<?php
namespace static_\plugins\system\api;

use \engine\lib\api as API;
use \engine\lib\secure as Secure;
use \engine\lib\validate as Validate;
use \engine\lib\utils as Utils;
use Exception;

class users {

    public function getEmptyUserName () {
        return 'No Name';
    }

    private function __attachUserDetails ($user, $withCustomerID = false) {
        $UserID = intval($user['ID']);
        // get user info
        // get user addresses
        // $configPermissions = dbquery::getPermissions($UserID);
        // $user['Permissions'] = $app->getDB()->query($configPermissions, true) ?: array();
        $user['Addresses'] = API::getAPI('system:address')->getAddresses($UserID);

        // adjust values
        $user['ID'] = $UserID;
        // $user['CustomerID'] = intval($user['CustomerID']);
        // $user['PermissionID'] = intval($user['PermissionID']);
        $user['IsOnline'] = intval($user['IsOnline']) === 1;
        $user['IsTemp'] = $user['Status'] === "TEMP";
        $user['isBlocked'] = $user['Status'] === "REMOVED";
        $user['isCurrent'] = API::getAPI('system:auth')->getAuthenticatedUserID() === $UserID;
        if (!$withCustomerID) {
            unset($user['CustomerID']);
        }
        unset($user['Password']);


        $permissions = $this->getUserPermissionsByUserID($UserID);
        unset($permissions['ID']);
        unset($permissions['UserID']);
        unset($permissions['DateUpdated']);
        unset($permissions['DateCreated']);

        foreach ($permissions as $key => $value) {
            $user['p_' . $key] = $value;
        }

        // attach plugin's permissions
        $plugins = API::getAPI('system:plugins');
        $user['_availableOtherPerms'] = $plugins->getPlugnisPermissons();

        // customizations
        $user['FullName'] = $user['FirstName'] . ' ' . $user['LastName'];
        $user['ActiveAddressesCount'] = count(array_filter($user['Addresses'], function ($v) {
            return !$v['isRemoved'];
        }));
        return $user;
    }

    public function getUserByValidationString ($ValidationString) {
        global $app;
        $config = dbquery::getUserByValidationString($ValidationString);
        $user = $app->getDB()->query($config);
        // var_dump('getUserByValidationString', $config);
        if (is_null($user)) {
            return null;
        }
        $user = $this->__attachUserDetails($user);
        return $user;
    }

    public function getUserByID ($UserID) {
        global $app;
        $user = $app->getDB()->query(dbquery::getUserByID($UserID), !$app->isToolbox());
        // var_dump('getUserByID', $UserID);
        if (!is_null($user))
            $user = $this->__attachUserDetails($user);
        return $user;
    }

    public function getUsers_List (array $options = array()) {
        global $app;
        $config = dbquery::getUserList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $item) {
                    $_items[] = $self->getUserByID($item['ID']);
                }
                return $_items;
            }
        );
        if (!API::getAPI('system:auth')->ifYouCan('Maintain')) {
            $options['useCustomerID'] = true;
        }
        $dataList = $app->getDB()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function getActiveUserByCredentials ($login, $password, $withCustomerID = false) {
        global $app;
        $query = dbquery::getUserByCredentials($login, $password);
        // avoid removed account
        // $query["fields"] = array("ID");
        $query["condition"]["Status"] = $app->getDB()->createCondition('REMOVED', '!=');
        $user = $app->getDB()->query($query, !$app->isToolbox());
        // var_dump($user);
        if (!is_null($user)) {
            $user = $this->__attachUserDetails($user, $withCustomerID);
        }
        return $user;
    }

    public function isEmailAllowedToRegister ($email) {
        global $app;
        $userWithEmail = $app->getDB()->query(dbquery::getUserByEMail($email));
        return empty($userWithEmail);
    }

    public function createUser ($reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        $autoPwd = Secure::generateStrongPassword();
        $validatedDataObj = Validate::getValidData($reqData, array(
            'FirstName' => array('string', 'notEmpty', 'min' => 2, 'max' => 40),
            'LastName' => array('skipIfUnset', 'string', "defaultValueIfUnset" => ""),
            'EMail' => array('isEmail', 'min' => 5, 'max' => 100),
            'Phone' => array('isPhone', 'skipIfUnset', 'defaultValueIfUnset' => Validate::getEmptyPhoneNumber()),
            'Password' => array('isPassword', 'notEmpty', 'min' => 8, 'max' => 30, 'defaultValueIfUnset' => $autoPwd),
            'ConfirmPassword' => array('equalTo' => 'Password', 'notEmpty', 'defaultValueIfUnset' => $autoPwd),
            // permissions
            'p_CanAdmin' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
            'p_CanCreate' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
            'p_CanEdit' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
            'p_CanUpload' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
            'p_CanViewReports' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
            'p_CanAddUsers' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
            'p_CanMaintain' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
            'p_Others' => array('array', 'skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                // separate data
                $dataUser = array();
                $dataPermission = array();

                foreach ($validatedValues as $field => $value) {
                    if (preg_match("/^p_/", $field) === 1)
                        $dataPermission[substr($field, strlen("p_"))] = $value;
                    else
                        $dataUser[$field] = $value;
                }
                // filter permissions
                $dataPermission = $this->_filterPermissions($dataPermission);
                $dataUser["CustomerID"] = $app->getSite()->getRuntimeCustomerID();
                $dataUser["Password"] = Secure::EncodeUserPassword($validatedValues['Password']);
                $dataUser["ValidationString"] = Secure::EncodeUserPassword(time());
                if (!$this->isEmailAllowedToRegister($validatedValues['EMail']))
                    throw new Exception("EmailAlreadyInUse", 1);
                
                unset($dataUser['ConfirmPassword']);

                $app->getDB()->beginTransaction();

                $configCreateUser = dbquery::addUser($dataUser);
                $UserID = $app->getDB()->query($configCreateUser) ?: null;

                if (empty($UserID)) {
                    throw new Exception('UserCreateError');
                }

                // create permission (admins only)
                if (API::getAPI('system:auth')->ifYouCan('Admin') && (
                    API::getAPI('system:auth')->ifYouCan('AddUsers') ||
                    API::getAPI('system:auth')->ifYouCan('Maintain')
                )) {
                    $Permission = $this->createUserPermissions($UserID, $dataPermission);
                    if (!$Permission['success']) {
                        throw new Exception(implode(';', $Permission['errors']));
                    }
                } else {
                    $Permission = $this->createUserPermissions($UserID, array());
                }

                $app->getDB()->commit();

                $result = $this->getUserByID($UserID);

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors = Utils::formatExceptionMsgForResponse($e->getMessage());
            }
        else
            $errors = $validatedDataObj["errors"];

        if ($success && !empty($UserID))
            $result = $this->getUserByID($UserID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function updateUser ($UserID, $reqData, $isUpdate = false) {
        global $app;

        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'FirstName' => array('skipIfUnset', 'string', 'notEmpty', 'min' => 2, 'max' => 40),
            'LastName' => array('skipIfUnset', 'string'),
            'Status' => array('skipIfUnset', 'string'),
            'Phone' => array('skipIfUnset', 'isPhone'),
            'Password' => array('skipIfUnset', 'isPassword', 'min' => 8, 'max' => 30, 'inPairWith' => 'ConfirmPassword'),
            'ConfirmPassword' => array('skipIfUnset', 'equalTo' => 'Password', 'notEmpty'),
            // permissions
            'p_CanAdmin' => array('bool', 'skipIfUnset', 'transformToTinyInt'),
            'p_CanCreate' => array('bool', 'skipIfUnset', 'transformToTinyInt'),
            'p_CanEdit' => array('bool', 'skipIfUnset', 'transformToTinyInt'),
            'p_CanUpload' => array('bool', 'skipIfUnset', 'transformToTinyInt'),
            'p_CanViewReports' => array('bool', 'skipIfUnset', 'transformToTinyInt'),
            'p_CanAddUsers' => array('bool', 'skipIfUnset', 'transformToTinyInt'),
            'p_CanMaintain' => array('bool', 'skipIfUnset', 'transformToTinyInt'),
            'p_Others' => array('array', 'skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $app->getDB()->beginTransaction();

                // separate data
                $dataUser = array();
                $dataPermission = array();

                foreach ($validatedValues as $field => $value) {
                    if (preg_match("/^p_/", $field) === 1)
                        $dataPermission[substr($field, strlen("p_"))] = $value;
                    else
                        $dataUser[$field] = $value;
                }

                // filter permissions
                $dataPermission = $this->_filterPermissions($dataPermission);

                if (!empty($dataUser)) {
                    if (isset($dataUser['Password'])) {
                        $dataUser['Password'] = Secure::EncodeUserPassword($validatedValues['Password']);
                        unset($dataUser['ConfirmPassword']);
                    }
                    $configUpdateUser = dbquery::updateUser($UserID, $dataUser);
                    $app->getDB()->query($configUpdateUser);
                }

                if (!empty($dataPermission)) {
                    $Permission = $this->updateUserPermissions($UserID, $dataPermission);
                    if (!$Permission['success']) {
                        throw new Exception(implode(';', $Permission['errors']));
                    }
                }

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                // echo $app->getDB()->getLastErrorCode();
                // echo $e;
                $errors[] = 'UserUpdateError';
                $errors['Others'] = Utils::formatExceptionMsg($e->getMessage());
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getUserByID($UserID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function activateUserByValidationStyring ($ValidationString) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        try {
            $app->getDB()->query(dbquery::activateUser($ValidationString));
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }
        $result = $this->getUserByValidationString($ValidationString);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function disableUserByID ($UserID) {
        global $app;
        $errors = array();
        $success = false;

        try {
            $user = $this->getUserByID($UserID);
            $app->getDB()->beginTransaction();

            $app->getDB()->disableTransactions();
            // disable all related addresses
            if ($user['Addresses']) {
                foreach ($user['Addresses'] as $addr) {
                    API::getAPI('system:address')->disableAddressByID($addr['ID']);
                }
            }
            $app->getDB()->enableTransactions();

            $config = dbquery::disableUser($UserID);
            $app->getDB()->query($config, false);

            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->enableTransactions();
            $app->getDB()->rollBack();
            $errors[] = 'UserDisableError';
        }

        $result = $this->getUserByID($UserID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function setOffline ($UserID) {
        global $app;
        $app->getDB()->query(dbquery::setUserOffline($UserID));
    }

    public function setOnline ($UserID) {
        global $app;
        $app->getDB()->query(dbquery::setUserOnline($UserID));
    }

    // permissions
    // -----------------------------------------------
    private function getUserPermissionsByUserID ($UserID) {
        global $app;
        $query = dbquery::getUserPermissionsByUserID($UserID);
        $userPermissions = $app->getDB()->query($query, false);
        return $this->_adjustPermissions($userPermissions);
    }

    private function createUserPermissions ($UserID, $data = array()) {
        global $app;
        // $perms = self::getNewPermissions($permissions);
        $errors = array();
        $success = false;
        $PermissionID = null;
        try {
            $query = dbquery::createUserPermissions($UserID, $data);
            $PermissionID = $app->getDB()->query($query, false) ?: null;
            $success = true;
        } catch (Exception $e) {
            $errors[] = 'PermissionsCreateError';
        }
        if ($success && !empty($PermissionID))
            $result = $this->getUserPermissionsByUserID($UserID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    private function updateUserPermissions ($UserID, $data = array()) {
        global $app;
        $errors = array();
        $success = false;
        try {
            $query = dbquery::updateUserPermissions($UserID, $data);
            $app->getDB()->query($query, false) ?: null;
            $success = true;
        } catch (Exception $e) {
            $errors[] = 'PermissionsUpdateError';
            $errors[] = $e->getMessage();
        }
        $result = $this->getUserPermissionsByUserID($UserID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    private function _adjustPermissions ($perms) {
        $adjustedPerms = array();
        // adjust permission values
        // var_dump($perms);
        if (!empty($perms)) {
            foreach ($perms as $field => $value) {
                if (preg_match("/^Can/", $field) === 1) {
                    $adjustedPerms[$field] = intval($value) === 1;
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
        $adjustedPerms['Others'] = array_filter(explode(';', $perms['Others'] ?: ''));
        // $this->permissions = $listOfDOs;
        return $adjustedPerms;
    }

    private function _filterPermissions ($dataPermission) {
        $dataPermission['Others'] = isset($dataPermission['Others']) ? $dataPermission['Others'] : array();
        $dataPermission['Others'] = implode(';', array_filter(
            $dataPermission['Others'],
            function ($v) {
                return trim($v);
            }
        ));
        return $dataPermission;
    }

    // stats
    // -----------------------------------------------
    public function getStats_UsersOverview () {
        global $app;
        if (!API::getAPI('system:auth')->ifYouCan('Admin')) {
            return null;
        }
        $config = dbquery::stat_UsersOverview();
        $data = $app->getDB()->query($config) ?: array();
        return $data;
    }

    public function getStats_UsersIntensityLastMonth ($status) {
        global $app;
        if (!API::getAPI('system:auth')->ifYouCan('Admin')) {
            return null;
        }
        $config = dbquery::stat_UsersIntensityLastMonth($status);
        $data = $app->getDB()->query($config) ?: array();
        return $data;
    }

    public function get (&$resp, $req) {
        $allAccess = API::getAPI('system:auth')->ifYouCan('Admin') ||
            API::getAPI('system:auth')->ifYouCan('AddUsers') ||
            API::getAPI('system:auth')->ifYouCan('Maintain');
        // var_dump($req);
        if (empty($req->get['params']) && $allAccess) {
            $resp = $this->getUsers_List($req->get);
            return;
        } else {
            if (is_numeric($req->get['params']) && $allAccess) {
                $UserID = intval($req->get['params']);
                $resp = $this->getUserByID($UserID);
                return;
            } elseif (is_string($req->get['params'])) {
                $resp = $this->getUserByValidationString($req->get['params']);
                return;
            } elseif (!empty($req->get['activate'])) {
                $ValidationString = $req->get['activate'];
                $resp = $this->activateUserByValidationStyring($ValidationString);
                return;
            } else {
                $resp['error'] = "AccessDenied";
                return;
            }
        }
    }

    public function post (&$resp, $req) {
        // if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('AddUsers')) {
        //     $resp['error'] = "AccessDenied";
        //     return;
        // }
        $resp = $this->createUser($req->data);
    }

    public function put (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('AddUsers')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (!empty($req->get['params'])) {
            if (is_numeric($req->get['params'])) {
                $UserID = intval($req->get['params']);
                $resp = $this->updateUser($UserID, $req->data);
                return;
            } else {
                $resp['error'] = "MissedParameter_id";
                return;
            }
        }
        $resp['error'] = 'UnknownAction';
    }

    public function patch (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('AddUsers')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (!empty($req->get['params'])) {
            if (is_numeric($req->get['params'])) {
                $UserID = intval($req->get['params']);
                $resp = $this->updateUser($UserID, $req->data, true);
                return;
            } else {
                $resp['error'] = "MissedParameter_id";
                return;
            }
        }
        $resp['error'] = 'UnknownAction';
    }

    public function delete (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('AddUsers')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (!empty($req->get['id'])) {
            $UserID = intval($req->get['id']);
            $resp = $this->disableUserByID($UserID);
            return;
        }
        $resp['error'] = 'UnknownAction';
    }
}

?>
<?php
namespace web\plugins\system\api;

use \engine\lib\api as API;
use \engine\lib\secure as Secure;
use \engine\lib\validate as Validate;

class user {

    public function getEmptyUserName () {
        return 'No Name';
    }

    private function __attachUserDetails ($user) {
        $UserID = intval($user['ID']);
        // get user info
        // get user addresses
        // $configPermissions = dbquery::getPermissions($UserID);
        // $user['Permissions'] = $app->getDB()->query($configPermissions, true) ?: array();
        $user['Addresses'] = API::getAPI('system:address')->getAddresses($UserID);
        $user['Permissions'] = $this->getUserPermissionsByUserID($UserID);

        // adjust values
        $user['ID'] = $UserID;
        // $user['CustomerID'] = intval($user['CustomerID']);
        // $user['PermissionID'] = intval($user['PermissionID']);
        $user['IsOnline'] = intval($user['IsOnline']) === 1;
        $user['IsTemp'] = $user['Status'] === "TEMP";
        unset($user['CustomerID']);
        unset($user['Permissions']['ID']);
        unset($user['Permissions']['UserID']);
        unset($user['Permissions']['DateUpdated']);
        unset($user['Permissions']['DateCreated']);
        unset($user['Password']);

        // customizations
        $user['FullName'] = $user['FirstName'] . ' ' . $user['LastName'];

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
        $user = $app->getDB()->query(dbquery::getUserByID($UserID));
        // var_dump('getUserByID', $UserID);
        if (!is_null($user))
            $user = $this->__attachUserDetails($user);
        return $user;
    }

    public function getActiveUserByCredentials ($login, $password) {
        global $app;
        $query = dbquery::getUserByCredentials($login, $password);
        // avoid removed account
        // $query["fields"] = array("ID");
        $query["condition"]["Status"] = $app->getDB()->createCondition('REMOVED', '!=');
        $user = $app->getDB()->query($query);
        // var_dump($user);
        if (!is_null($user))
            $user = $this->__attachUserDetails($user);
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

        $validatedDataObj = Validate::getValidData($reqData, array(
            'FirstName' => array('string', 'notEmpty', 'min' => 2, 'max' => 40),
            'LastName' => array('skipIfUnset', 'string', "defaultValueIfUnset" => ""),
            'EMail' => array('isEmail', 'min' => 5, 'max' => 100),
            'Phone' => array('isPhone', 'skipIfUnset', 'defaultValueIfUnset' => Validate::getEmptyPhoneNumber()),
            'Password' => array('isPassword', 'min' => 8, 'max' => 30),
            'ConfirmPassword' => array('equalTo' => 'Password', 'notEmpty'),
            // permissions
            'p_CanAdmin' => array('bool', 'notEmpty'),
            'p_CanCreate' => array('bool', 'notEmpty'),
            'p_CanEdit' => array('bool', 'notEmpty'),
            'p_CanView' => array('bool', 'notEmpty'),
            'p_CanUpload' => array('bool', 'notEmpty'),
            'p_CanViewReports' => array('bool', 'notEmpty'),
            'p_CanAddUsers' => array('bool', 'notEmpty'),
            'p_CanMaintain' => array('bool', 'notEmpty')
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
                $dataUser["CustomerID"] = $app->getSite()->getRuntimeCustomerID();
                $dataUser["Password"] = Secure::EncodeUserPassword($validatedValues['Password']);
                $dataUser["ValidationString"] = Secure::EncodeUserPassword(time());
                // if (!$this->isEmailAllowedToRegister($validatedValues['EMail']))
                //     throw new Exception("EmailAlreadyInUse", 1);

                $app->getDB()->beginTransaction();

                $configCreateUser = dbquery::addUser($dataUser);
                $UserID = $app->getDB()->query($configCreateUser) ?: null;

                if (empty($UserID)) {
                    throw new Exception('UserCreateError');
                }

                // create permission
                $Permission = $this->createPermissions($UserID, $dataPermission);
                if (!$Permission['success']) {
                    throw new Exception(implode(';', $Permission['errors']));
                }

                $app->getDB()->commit();

                $result = $this->getUserByID($UserID);

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        if ($success && !empty($UserID))
            $result = $this->getUserByID($UserID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function updateUser ($UserID, $reqData) {
        global $app;

        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'FirstName' => array('skipIfUnset', 'string', 'notEmpty', 'min' => 2, 'max' => 40),
            'LastName' => array('skipIfUnset', 'string'),
            'Phone' => array('skipIfUnset', 'isPhone'),
            'Password' => array('skipIfUnset', 'isPassword', 'min' => 8, 'max' => 30, 'inPairWith' => 'ConfirmPassword'),
            'ConfirmPassword' => array('skipIfUnset', 'equalTo' => 'Password', 'notEmpty'),
            // permissions
            'p_CanAdmin' => array('bool', 'notEmpty'),
            'p_CanCreate' => array('bool', 'notEmpty'),
            'p_CanEdit' => array('bool', 'notEmpty'),
            'p_CanView' => array('bool', 'notEmpty'),
            'p_CanUpload' => array('bool', 'notEmpty'),
            'p_CanViewReports' => array('bool', 'notEmpty'),
            'p_CanAddUsers' => array('bool', 'notEmpty'),
            'p_CanMaintain' => array('bool', 'notEmpty')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $app->getDB()->beginTransaction();

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

                if (count($dataUser)) {
                    if (isset($dataUser['Password'])) {
                        $dataUser['Password'] = Secure::EncodeUserPassword($validatedValues['Password']);
                        unset($dataUser['ConfirmPassword']);
                    }
                    $configUpdateUser = dbquery::updateUser($UserID, $dataUser);
                    $app->getDB()->query($configUpdateUser);
                }

                $Permission = $this->updateUserPermissions($UserID, $dataPermission);
                if (!$Permission['success']) {
                    throw new Exception(implode(';', $Permission['errors']));
                }

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                // echo $app->getDB()->getLastErrorCode();
                // echo $e;
                // return glWrap("error", 'UserUpdateError');
                $errors[] = 'UserUpdateError';
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

    private function _disableUserByID ($UserID) {
        global $app;
        $app->getDB()->query(dbquery::disableUser($UserID));
        // disable all related addresses
        $user = $this->getUserByID($UserID);
        if ($user['Addresses'])
            foreach ($user['Addresses'] as $addr) {
                $this->_disableAddressByID($addr['ID']);
            }
        return glWrap("ok", true);
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
            $PermissionID = $app->getDB()->query($query) ?: null;
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
            $app->getDB()->query($query) ?: null;
        } catch (Exception $e) {
            $errors[] = 'PermissionsUpdateError';
        }
        $result = $this->getUserPermissionsByUserID($UserID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    private function _adjustPermissions ($perms) {
        $adjustedPerms = array();
        // adjust permission values
        foreach ($perms as $field => $value) {
            if (preg_match("/^Can/", $field) === 1) {
                $adjustedPerms[$field] = intval($value) === 1;
            }
        }
        // $this->permissions = $listOfDOs;
        return $adjustedPerms;
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

        if (empty($req->get['id']) && $allAccess) {
            $resp = $this->getUsers_List($req->get);
            return;
        } else {
            if (is_numeric($req->get['id']) && $allAccess) {
                $UserID = intval($req->get['id']);
                $resp = $this->getUserByID($UserID);
                return;
            } elseif (is_string($req->get['id'])) {
                $resp = $this->getUserByValidationString($req->get['id']);
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
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('AddUsers')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createUser($req->data);
    }

    public function patch (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('AddUsers')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (!empty($req->get['id'])) {
            $UserID = intval($req->get['id']);
            $resp = $this->updateUser($UserID, $req->data);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    public function delete (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('AddUsers')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (!empty($req->get['id'])) {
            $UserID = intval($req->get['id']);
            $resp = $this->_disableUserByID($UserID);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }
}

?>
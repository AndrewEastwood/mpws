<?php
namespace web\plugin\system\api;

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
        $user['Permissions'] = API::getAPI('system:permissions')->getPermissions($UserID);

        // adjust values
        $user['ID'] = $UserID;
        // $user['CustomerID'] = intval($user['CustomerID']);
        // $user['PermissionID'] = intval($user['PermissionID']);
        $user['IsOnline'] = intval($user['IsOnline']) === 1;
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
        $config = dbquery::getUserByValidationString($ValidationString);
        $user = $app->getDB()->query($config);
        // var_dump('getUserByValidationString', $config);
        if (is_null($user)) {
            return glWrap('error', 'User does not exist');
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
            'ConfirmPassword' => array('equalTo' => 'Password', 'notEmpty')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                // if (!$this->isEmailAllowedToRegister($validatedValues['EMail']))
                //     throw new Exception("EmailAlreadyInUse", 1);

                $app->getDB()->beginTransaction();

                $data = array();
                $data["CustomerID"] = $app->getSite()->getRuntimeCustomerID();
                $data["FirstName"] = $validatedValues['FirstName'];
                $data["LastName"] = $validatedValues['LastName'];
                $data["EMail"] = $validatedValues['EMail'];
                $data["Phone"] = $validatedValues['Phone'];
                $data["Password"] = Secure::EncodeUserPassword($validatedValues['Password']);
                $data["ValidationString"] = Secure::EncodeUserPassword(time());
                $configCreateUser = dbquery::addUser($data);
                $UserID = $app->getDB()->query($configCreateUser) ?: null;

                if (empty($UserID))
                    throw new Exception('UserCreateError');

                // create permission
                $PermissionID = API::getAPI('system:permissions')->createPermissions($UserID);
                if (empty($PermissionID))
                    throw new Exception('PermissionCreateError');

                $app->getDB()->commit();

                $result = $this->getUserByID($UserID);


                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    private function _updateUserByID ($UserID, $reqData) {
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
            'p_CanAdmin' => array('skipIfUnset', 'bool', 'notEmpty'),
            'p_CanCreate' => array('skipIfUnset', 'bool', 'notEmpty'),
            'p_CanEdit' => array('skipIfUnset', 'bool', 'notEmpty'),
            'p_CanView' => array('skipIfUnset', 'bool', 'notEmpty'),
            'p_CanAddUsers' => array('skipIfUnset', 'bool', 'notEmpty')
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
                    // var_dump($configUpdateUser);
                }

                if (count($dataPermission)) {
                    // foreach ($dataPermission as $key => $value) {
                    //     $dataPermission[$key] = $value ? 1: 0;
                    // }
                    API::getAPI('system:permissions')->updatePermissions($UserID, $dataPermission);
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

    private function _activateUserByValidationStyring ($ValidationString) {
        global $app;
        $user = $this->getUserByValidationString($ValidationString);
        if ($user['Status'] === "TEMP" || glIsToolbox()) {
            $app->getDB()->query(dbquery::activateUser($ValidationString));
            $user = $this->getUserByValidationString($ValidationString);
            if ($user['Status'] === 'ACTIVE')
                return $user;
        } elseif ($user['Status'] === 'REMOVED')
            return glWrap('error', 'UserIsRemoved');
        return $user;
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
        $app->getDB()->query(dbquery::offline($UserID));
    }

    public function setOnline ($UserID) {
        global $app;
        $app->getDB()->query(dbquery::online($UserID));
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
        if (!empty($req->get['id'])) {
            $UserID = intval($req->get['id']);
            $resp = $this->getUserByID($UserID);
            return;
        }
        if (!empty($req->get['hash'])) {
            $ValidationString = $req->get['hash'];
            $resp = $this->_activateUserByValidationStyring($ValidationString);
            return;
        }
    }

    public function post (&$resp, $req) {
        $resp = $this->createUser($req->data);
    }

    public function patch (&$resp, $req) {
        if (!empty($req->get['id'])) {
            $UserID = intval($req->get['id']);
            $resp = $this->_updateUserByID($UserID, $req->data);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    public function delete (&$resp, $req) {
        if (!glIsToolbox()) {
            $resp['error'] = 'AccessDenied';
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
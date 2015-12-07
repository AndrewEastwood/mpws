<?php
namespace static_\plugins\system\api;

use \engine\lib\api as API;
use \engine\lib\secure as Secure;
use \engine\lib\validate as Validate;
use \engine\lib\utils as Utils;
use Exception;

class users extends API {

    public function getEmptyUserName () {
        return 'No Name';
    }

    // private function __attachUserDetails ($user, $withCustomerID = false) {
    //     $UserID = intval($user['ID']);
    //     // get user info
    //     // get user addresses
    //     // $configPermissions = data::getPermissions($UserID);
    //     // $user['Permissions'] = $app->getDB()->query($configPermissions, true) ?: array();
    //     $user['Addresses'] = API::getAPI('system:address')->getAddresses($UserID);

    //     // adjust values
    //     $user['ID'] = $UserID;
    //     // $user['CustomerID'] = intval($user['CustomerID']);
    //     // $user['PermissionID'] = intval($user['PermissionID']);
    //     $user['IsOnline'] = intval($user['IsOnline']) === 1;
    //     $user['IsTemp'] = $user['Status'] === "TEMP";
    //     $user['isBlocked'] = $user['Status'] === "REMOVED";
    //     $user['isCurrent'] = API::getAPI('system:auth')->getAuthenticatedUserID() === $UserID;
    //     if (!$withCustomerID) {
    //         unset($user['CustomerID']);
    //     }
    //     unset($user['Password']);


    //     $permissions = $this->getUserPermissionsByUserID($UserID);
    //     unset($permissions['ID']);
    //     unset($permissions['UserID']);
    //     unset($permissions['DateUpdated']);
    //     unset($permissions['DateCreated']);

    //     foreach ($permissions as $key => $value) {
    //         $user['p_' . $key] = $value;
    //     }

    //     // attach plugin's permissions
    //     $plugins = API::getAPI('system:plugins');
    //     $user['_availableOtherPerms'] = $plugins->getPluginsPermissons();

    //     // customizations
    //     $user['FullName'] = $user['FirstName'] . ' ' . $user['LastName'];
    //     $user['ActiveAddressesCount'] = count(array_filter($user['Addresses'], function ($v) {
    //         return !$v['isRemoved'];
    //     }));
    //     return $user;
    // }

    // public function getUserByValidationString ($validationString) {
    //     return $this->data->fetchUserByValidationString($validationString);
    //     // global $app;
    //     // $config = data::getUserByValidationString($ValidationString);
    //     // $user = $app->getDB()->query($config);
    //     // // var_dump('getUserByValidationString', $config);
    //     // if (is_null($user)) {
    //     //     return null;
    //     // }
    //     // $user = $this->__attachUserDetails($user);
    //     // return $user;
    // }

    // public function getUserByID ($userID) {
    //     return $this->data->fetchUserByID($userID);
    // }

    // public function getUsers_List (array $options = array()) {
    //     return $this->data->fetchUserDataList($options);

    //     // global $app;
    //     // $config = data::getUserList($options);
    //     // $self = $this;
    //     // $callbacks = array(
    //     //     "parse" => function ($items) use($self) {
    //     //         $_items = array();
    //     //         foreach ($items as $key => $item) {
    //     //             $_items[] = $self->getUserByID($item['ID']);
    //     //         }
    //     //         return $_items;
    //     //     }
    //     // );
    //     // if (!API::getAPI('system:auth')->ifYouCan('Maintain')) {
    //     //     $options['useCustomerID'] = true;
    //     // }
    //     // $dataList = $app->getDB()->queryMatchedIDs($config, $options, $callbacks);
    //     // return $dataList;
    // }

    public function getActiveUserByCredentials ($login, $password) {
        return $this->data->fetchUserByCredentials($login, $password);
        // global $app;
        // $query = data::getUserByCredentials($login, $password);
        // // avoid removed account
        // // $query["fields"] = array("ID");
        // $query["condition"]["Status"] = $app->getDB()->createCondition('REMOVED', '!=');
        // $user = $app->getDB()->query($query, !$app->isToolbox());
        // // var_dump($user);
        // if (!is_null($user)) {
        //     $user = $this->__attachUserDetails($user, $withCustomerID);
        // }
        // return $user;
    }

    public function isEmailAllowedToRegister ($email) {
        return empty($this->data->fetchUserByEMail($email));
        // global $app;
        // $userWithEmail = $app->getDB()->query(data::getUserByEMail($email));
        // return empty($userWithEmail);
    }

    public function createUser ($reqData) {
        global $app;
        // $result = array();
        // $errors = array();
        // $success = false;
        $r = null;
        $rP = null;

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

        if ($$validatedDataObj->errorsCount == 0)
            try {

                $validatedValues = $validatedDataObj->validData;

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
                // $dataPermission = $this->_filterPermissions($dataPermission);
                $dataUser["CustomerID"] = $app->getSite()->getRuntimeCustomerID();
                $dataUser["Password"] = Secure::EncodeUserPassword($validatedValues['Password']);
                $dataUser["ValidationString"] = Secure::EncodeUserPassword(time());

                if (!$this->isEmailAllowedToRegister($validatedValues['EMail'])) {
                    throw new Exception("EmailAlreadyInUse", 1);
                }
                
                unset($dataUser['ConfirmPassword']);

                $app->getDB()->beginTransaction()
                    ->lockTransaction();

                $r = $this->data->createUser($dataUser);

                // $configCreateUser = data::addUser($dataUser);
                // $UserID = $app->getDB()->query($configCreateUser) ?: null;

                if ($r->isEmptyResult()) {
                    throw new Exception('UserCreateError');
                }

                // create permission (admins only)
                if (API::getAPI('system:auth')->ifYouCan('Admin') &&
                    API::getAPI('system:auth')->ifYouCanAny('AddUsers', 'Maintain')) {
                    $rP = $this->data->createUserPermissions($UserID, $dataPermission);
                } else {
                    $rP = $this->data->createUserPermissions($UserID, array());
                }

                if ($rP->isEmptyResult()) {
                    $r->copyErrorsFrom($rP);
                    throw new Exception('UserPermissionsCreateError');
                }

                $app->getDB()->unlockTransaction()
                    ->commit();

                // $result = $this->getUserByID($UserID);

                // $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $r->addError($e->getMessage());
            }
        else {
            // $errors = $$validatedDataObj->errorMessages;
            $r->addErrors($$validatedDataObj->errorMessages);
        }

        if ($r->hasResult()) {
            $customer = $this->data->fetchUserByID($r->getResult());
            $r->setResult($customer);
        }

        // if ($success && !empty($UserID))
        //     $result = $this->getUserByID($UserID);
        // $result['errors'] = $errors;
        // $result['success'] = $success;
        return $result;
    }

    public function updateUser ($userID, $reqData/*, $isUpdate = false*/) {
        global $app;

        // $errors = array();
        // $success = false;
        $r = null;

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

        if ($$validatedDataObj->errorsCount == 0)
            try {

                $validatedValues = $validatedDataObj->validData;

                // for multiple inserts use lockTransaction before
                $app->getDB()->beginTransaction()
                    ->lockTransaction();

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
                // $dataPermission = $this->_filterPermissions($dataPermission);

                if (!empty($dataUser)) {
                    if (isset($dataUser['Password'])) {
                        $dataUser['Password'] = Secure::EncodeUserPassword($validatedValues['Password']);
                        unset($dataUser['ConfirmPassword']);
                    }
                    $r = $this->data->updateUser($userID, $dataUser);
                    // $configUpdateUser = data::updateUser($userID, $dataUser);
                    // $app->getDB()->query($configUpdateUser);
                }

                if (!empty($dataPermission)) {
                    $rP = $this->data->updateUserPermissions($userID, $dataPermission);
                }

                if ($rP->isEmptyResult()) {
                    $r->copyErrorsFrom($rP);
                    throw new Exception("UserPermissionsUpdateError", 1);
                    // throw new Exception(implode(';', $Permission['errors']));
                }

                $app->getDB()->unlockTransaction()
                    ->commit();

                // $success = true;
            } catch (Exception $e) {
                $app->getDB()->unlockTransaction()
                    ->rollBack();
                // echo $app->getDB()->getLastErrorCode();
                // echo $e;
                $r->addError('UserUpdateError');
                $r->addError($e->getMessage());
                // $errors[] = 'UserUpdateError';
                // $errors['Others'] = Utils::formatExceptionMsg($e->getMessage());
            }
        else {
            // $errors = $$validatedDataObj->errorMessages;
            $r->addErrors($$validatedDataObj->errorMessages);
        }

        if ($r->hasResult()) {
            $user = $this->data->fetchUserByID($userID);
            $r->setResult($user);
        }
        // $result = $this->getUserByID($userID);
        // $result['errors'] = $errors;
        // $result['success'] = $success;
        return $r->toArray();
    }

    public function activateUserByValidationStyring ($validationString) {
        
        $r = $this->data->activateUser($validationString);
        if ($r->isSuccess()) {
            $r->setResult($this->data->fetchUserByValidationString($validationString));
        }

        return $r->toArray();
        
        // global $app;
        // $result = array();
        // $errors = array();
        // $success = false;
        // try {
        //     $app->getDB()->query(data::activateUser($ValidationString));
        // } catch (Exception $e) {
        //     $app->getDB()->rollBack();
        //     $errors[] = $e->getMessage();
        // }
        // $result = $this->data->fetchUserByValidationString($ValidationString);
        // $result['errors'] = $errors;
        // $result['success'] = $success;
        // return $result;
    }

    public function disableUserByID ($userID) {

        // $r = $this->data->disableUser($CustomerID);
        // if ($r->hasResult()) {
        //     $customer = $this->data->fetchUserByID($userID);
        //     $r->setResult($customer);
        // }
        // return $r->toArray();


        global $app;
        // $errors = array();
        // $success = false;
        $r = null;

        try {
            $user = $this->data->fetchUserByID($userID);
            $app->getDB()->beginTransaction()
                ->lockTransaction();

            // disable all related addresses
            if ($user['Addresses']) {
                foreach ($user['Addresses'] as $addr) {
                    $this->data->disableAddress($addr['ID']);
                }
            }
            // $config = data::disableUser($userID);
            // $app->getDB()->query($config, false);
            $r = $this->data->disableUser($userID);

            $app->getDB()->unlockTransaction()
                ->commit();

            // $success = true;
        } catch (Exception $e) {
            $app->getDB()->unlockTransaction()
                ->rollBack();
            $r->addError($e->getMessage());
            // $errors[] = 'UserDisableError';
        }

        $user = $this->data->fetchUserByID($userID);
        $r->setResult($user);
        // $result = $this->getUserByID($userID);
        // $result['errors'] = $errors;
        // $result['success'] = $success;
        return $r->toArray();
    }

    // public function setOffline ($userID) {
    //     $this->data->setUserOffline($userID);
    //     // global $app;
    //     // $app->getDB()->query(data::setUserOffline($UserID));
    // }

    // public function setOnline ($userID) {
    //     $this->data->setUserOnline($userID);
    //     // global $app;
    //     // $app->getDB()->query(data::setUserOnline($UserID));
    // }

    // permissions
    // -----------------------------------------------
    // private function getUserPermissionsByUserID ($UserID) {
    //     return $this->data->fetchUserPermissionsByUserID($UserID);
    //     // global $app;
    //     // $query = data::getUserPermissionsByUserID($UserID);
    //     // $userPermissions = $app->getDB()->query($query, false);
    //     // return $this->_adjustPermissions($userPermissions);
    // }

    // private function createUserPermissions ($UserID, $data = array()) {
    //     $r = $this->data->createUserPermissions($UserID, $data);
    //     if ($r->isSuccess()) {
    //         $r->setResult($this->data->fetchUserPermissionsByUserID($UserID));
    //     }
    //     return $r->toArray();
    //     // global $app;
    //     // // $perms = self::getNewPermissions($permissions);
    //     // $errors = array();
    //     // $success = false;
    //     // $PermissionID = null;
    //     // try {
    //     //     $query = data::createUserPermissions($UserID, $data);
    //     //     $PermissionID = $app->getDB()->query($query, false) ?: null;
    //     //     $success = true;
    //     // } catch (Exception $e) {
    //     //     $errors[] = 'PermissionsCreateError';
    //     // }
    //     // if ($success && !empty($PermissionID))
    //     //     $result = $this->getUserPermissionsByUserID($UserID);
    //     // $result['errors'] = $errors;
    //     // $result['success'] = $success;
    //     // return $result;
    // }

    // private function updateUserPermissions ($UserID, $data = array()) {
    //     $r = $this->data->updateUserPermissions($UserID, $data);
    //     if ($r->isSuccess()) {
    //         $r->setResult($this->data->fetchUserPermissionsByUserID($UserID));
    //     }
    //     return $r->toArray();
    //     // global $app;
    //     // $errors = array();
    //     // $success = false;
    //     // try {
    //     //     $query = data::updateUserPermissions($UserID, $data);
    //     //     $app->getDB()->query($query, false) ?: null;
    //     //     $success = true;
    //     // } catch (Exception $e) {
    //     //     $errors[] = 'PermissionsUpdateError';
    //     //     $errors[] = $e->getMessage();
    //     // }
    //     // $result = $this->getUserPermissionsByUserID($UserID);
    //     // $result['errors'] = $errors;
    //     // $result['success'] = $success;
    //     // return $result;
    // }

    // private function _adjustPermissions ($perms) {
    //     $adjustedPerms = array();
    //     // adjust permission values
    //     // var_dump($perms);
    //     if (!empty($perms)) {
    //         foreach ($perms as $field => $value) {
    //             if (preg_match("/^Can/", $field) === 1) {
    //                 $adjustedPerms[$field] = intval($value) === 1;
    //             }
    //             // if ($field === "Custom") {
    //             //     $customPerms = explode(';', $value);
    //             //     foreach ($customPerms as $cFiled => $cValue) {
    //             //         if (preg_match("/^Can/", $cFiled) === 1) {
    //             //             // in custom permission exsists then it's enabled by default
    //             //             $adjustedPerms[$cFiled] = true;
    //             //         }
    //             //     }
    //             // }
    //         }
    //     }
    //     $adjustedPerms['Others'] = array_filter(explode(';', $perms['Others'] ?: ''));
    //     // $this->permissions = $listOfDOs;
    //     return $adjustedPerms;
    // }

    // private function _filterPermissions ($dataPermission) {
    //     $dataPermission['Others'] = isset($dataPermission['Others']) ? $dataPermission['Others'] : array();
    //     $dataPermission['Others'] = implode(';', array_filter(
    //         $dataPermission['Others'],
    //         function ($v) {
    //             return trim($v);
    //         }
    //     ));
    //     return $dataPermission;
    // }

    // stats
    // -----------------------------------------------
    public function getStats_UsersOverview () {
        // global $app;
        if (!API::getAPI('system:auth')->ifYouCanAll('Admin')) {
            return null;
        }
        return $this->data->stat_UsersOverview();
        // $config = data::stat_UsersOverview();
        // $data = $app->getDB()->query($config) ?: array();
        // return $data;
    }

    public function getStats_UsersIntensityLastMonth ($status) {
        // global $app;
        if (!API::getAPI('system:auth')->ifYouCanAll('Admin')) {
            return null;
        }
        return $this->data->stat_UsersIntensityLastMonth($status);
        // $config = data::stat_UsersIntensityLastMonth($status);
        // $data = $app->getDB()->query($config) ?: array();
        // return $data;
    }

    public function get (&$resp, $req) {
        $allAccess = API::getAPI('system:auth')->ifYouCanAny('Admin', 'AddUsers', 'Maintain');
         // ||
         //    API::getAPI('system:auth')->ifYouCan('AddUsers') ||
         //    API::getAPI('system:auth')->ifYouCan('Maintain');


        // for specific customer item
        // by id
        if (Request::hasRequestedID() && $allAccess) {
            $resp = $this->data->fetchUserByID($req->id);
            return;
        }
        // or by ExternalKey
        if (Request::hasRequestedExternalKey()) {
            $resp = $this->data->fetchUserByValidationString($req->externalKey);
            return;
        }
        // or activate user
        if (empty($req->get['activate'])) {
            $resp = $this->data->activateUser($req->get['activate']);
            return;
        }
        // for the case when we have to fecth list with customers
        if (Request::noRequestedItem() && $allAccess) {
            $resp = $this->data->fetchUserDataList($req->get);
        }

        $resp['error'] = "AccessDenied";


        // // var_dump($req);
        // if (empty($req->id) && $allAccess) {
        //     $resp = $this->getUsers_List($req->get);
        //     return;
        // } else {
        //     if (is_numeric($req->id) && $allAccess) {
        //         $UserID = intval($req->id);
        //         $resp = $this->getUserByID($UserID);
        //         return;
        //     } elseif (is_string($req->id)) {
        //         $resp = $this->data->fetchUserByValidationString($req->id);
        //         return;
        //     } elseif (!empty($req->get['activate'])) {
        //         $ValidationString = $req->get['activate'];
        //         $resp = $this->activateUserByValidationStyring($ValidationString);
        //         return;
        //     } else {
        //         $resp['error'] = "AccessDenied";
        //         return;
        //     }
        // }
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

        // for specific user item
        // by id
        if (Request::hasRequestedID()) {
            $resp = $this->data->updateUser($req->id, $req->data);
            return;
        }

        // for the case when we have to fecth list with user
        if (Request::noRequestedItem()) {
            $resp['error'] = "MissedParameter_id";
            return;
        }
        // if (!empty($req->id)) {
        //     if (is_numeric($req->id)) {
        //         $UserID = intval($req->id);
        //         $resp = $this->updateUser($UserID, $req->data);
        //         return;
        //     } else {
        //         $resp['error'] = "MissedParameter_id";
        //         return;
        //     }
        // }
        $resp['error'] = 'UnknownAction';
    }

    public function patch (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('AddUsers')) {
            $resp['error'] = "AccessDenied";
            return;
        }

        // for specific user item
        // by id
        if (Request::hasRequestedID()) {
            $resp = $this->data->updateUser($req->id, $req->data, true);
            return;
        }

        // for the case when we have to fecth list with user
        if (Request::noRequestedItem()) {
            $resp['error'] = "MissedParameter_id";
            return;
        }

        // if (!empty($req->id)) {
        //     if (is_numeric($req->id)) {
        //         $UserID = intval($req->id);
        //         $resp = $this->updateUser($UserID, $req->data, true);
        //         return;
        //     } else {
        //         $resp['error'] = "MissedParameter_id";
        //         return;
        //     }
        // }
        $resp['error'] = 'UnknownAction';
    }

    public function delete (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('AddUsers')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        // for specific customer item
        // by id
        if (Request::hasRequestedID()) {
            $resp = $this->data->disableUser($req->id);
            return;
        }
        // for the case when we have to fecth list with customers
        if (Request::noRequestedItem()) {
            $resp['error'] = 'MissedParameter_id';
            return;
        }

        // $resp['error'] = 'WrongParameter_id';


        // if (!empty($req->id)) {
        //     $resp = $this->disableUserByID($req->id);
        //     return;
        // }
        $resp['error'] = 'UnknownAction';
    }
}

?>
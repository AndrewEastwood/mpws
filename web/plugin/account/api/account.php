<?php
namespace web\plugin\system\api;

class account {

    public function getEmptyUserName () {
        return 'No Name';
    }

    private function __attachAccountDetails ($account) {
        $AccountID = $account['ID'];
        // get account info
        // get account addresses
        $configAddresses = $this->getCustomerConfiguration()->data->jsapiGetAccountAddresses($AccountID, $this->getCustomer()->getApp()->isToolbox());
        $account['Addresses'] = $app->getDB()->query($configAddresses) ?: array();
        
        $configPermissions = $this->getCustomerConfiguration()->data->jsapiGetPermissions($AccountID);
        $account['Permissions'] = $app->getDB()->query($configPermissions, true) ?: array();

        // adjust values
        $account['ID'] = intval($account['ID']);
        // $account['CustomerID'] = intval($account['CustomerID']);
        // $account['PermissionID'] = intval($account['PermissionID']);
        $account['IsOnline'] = intval($account['IsOnline']) === 1;
        unset($account['CustomerID']);
        unset($account['Permissions']['ID']);
        unset($account['Permissions']['AccountID']);
        unset($account['Permissions']['DateUpdated']);
        unset($account['Permissions']['DateCreated']);
        unset($account['Password']);

        foreach ($account['Permissions'] as $key => $value) {
            $account['Permissions'][$key] = $value == "1";
        }

        if (!empty($account['Addresses']))
            foreach ($account['Addresses'] as &$item) {
                $item['ID'] = intval($item['ID']);
                $item['AccountID'] = intval($item['AccountID']);
            }

        return $account;
    }

    public function getAccountByValidationString ($ValidationString) {
        $config = $this->getCustomerConfiguration()->data->jsapiGetAccountByValidationString($ValidationString);
        $account = $app->getDB()->query($config);
        // var_dump('getAccountByValidationString', $config);
        if (is_null($account)) {
            return glWrap('error', 'Account does not exist');
        }

        $account = $this->__attachAccountDetails($account);

        return $account;
    }

    public function getAccountByID ($AccountID) {
        $config = $this->getCustomerConfiguration()->data->jsapiGetAccountByID($AccountID);
        $account = $app->getDB()->query($config);
        // var_dump('getAccountByID', $AccountID);
        if (!is_null($account))
            $account = $this->__attachAccountDetails($account);
        return $account;
    }

    public function isEmailAllowedToRegister ($email) {
        $config = $this->getCustomerConfiguration()->data->jsapiGetAccountByEMail($email);
        $accountWithEmail = $app->getDB()->query($config);
        return empty($accountWithEmail);
    }

    public function createAccount ($reqData) {
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
                $data["CustomerID"] = $this->getCustomer()->getCustomerID();
                $data["FirstName"] = $validatedValues['FirstName'];
                $data["LastName"] = $validatedValues['LastName'];
                $data["EMail"] = $validatedValues['EMail'];
                $data["Phone"] = $validatedValues['Phone'];
                $data["Password"] = Secure::EncodeAccountPassword($validatedValues['Password']);
                $data["ValidationString"] = Secure::EncodeAccountPassword(time());
                $configCreateAccount = $this->getCustomerConfiguration()->data->jsapiAddAccount($data);
                $AccountID = $app->getDB()->query($configCreateAccount) ?: null;

                if (empty($AccountID))
                    throw new Exception('AccountCreateError');

                // create permission
                $data = $this->getCustomerConfiguration()->data->jsapiGetNewPermission();
                $data['AccountID'] = $AccountID;
                $configCreatePermission = $this->getCustomerConfiguration()->data->jsapiAddPermissions($data);
                $PermissionID = $app->getDB()->query($configCreatePermission) ?: null;

                if (empty($PermissionID))
                    throw new Exception('PermissionCreateError');

                $app->getDB()->commit();

                $result = $this->getAccountByID($AccountID);

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

    private function _updateAccountByID ($AccountID, $reqData) {

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
                $dataAccount = array();
                $dataPermission = array();

                foreach ($validatedValues as $field => $value) {
                    if (preg_match("/^p_/", $field) === 1)
                        $dataPermission[substr($field, strlen("p_"))] = $value;
                    else
                        $dataAccount[$field] = $value;
                }

                if (count($dataAccount)) {
                    if (isset($dataAccount['Password'])) {
                        $dataAccount['Password'] = Secure::EncodeAccountPassword($validatedValues['Password']);
                        unset($dataAccount['ConfirmPassword']);
                    }
                    $configUpdateAccount = $this->getCustomerConfiguration()->data->jsapiUpdateAccount($AccountID, $dataAccount);
                    $app->getDB()->query($configUpdateAccount);
                    // var_dump($configUpdateAccount);
                }

                if (count($dataPermission)) {
                    // foreach ($dataPermission as $key => $value) {
                    //     $dataPermission[$key] = $value ? 1: 0;
                    // }
                    $configUpdatePermissions = $this->getCustomerConfiguration()->data->jsapiUpdatePermissions($AccountID, $dataPermission);
                    // var_dump($configUpdatePermissions);
                    $app->getDB()->query($configUpdatePermissions, true);
                }

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                // echo $this->getCustomerDataBase()->getLastErrorCode();
                // echo $e;
                // return glWrap("error", 'AccountUpdateError');
                $errors[] = 'AccountUpdateError';
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getAccountByID($AccountID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    private function _activateAccountByValidationStyring ($ValidationString) {
        $account = $this->getAccountByValidationString($ValidationString);
        if ($account['Status'] === "TEMP" || glIsToolbox()) {
            $config = $this->getCustomerConfiguration()->data->jsapiActivateAccount($ValidationString);
            $app->getDB()->query($config);
            $account = $this->getAccountByValidationString($ValidationString);
            if ($account['Status'] === 'ACTIVE')
                return $account;
        } elseif ($account['Status'] === 'REMOVED')
            return glWrap('error', 'AccountIsRemoved');
        return $account;
    }

    private function _disableAccountByID ($AccountID) {
        $config = $this->getCustomerConfiguration()->data->jsapiDisableAccount($AccountID);
        $app->getDB()->query($config);
        // disable all related addresses
        $account = $this->getAccountByID($AccountID);
        if ($account['Addresses'])
            foreach ($account['Addresses'] as $addr) {
                $this->_disableAddressByID($addr['ID']);
            }
        return glWrap("ok", true);
    }


    // stats
    // -----------------------------------------------
    private function _getStats_AccountsOverview () {
        if (!API::getAPI('system:auth')->ifYouCan('Admin')) {
            return null;
        }
        $config = $this->getCustomerConfiguration()->data->jsapiStat_AccountsOverview();
        $data = $app->getDB()->query($config) ?: array();
        return $data;
    }

    private function _getStats_AccountsIntensityLastMonth ($status) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin')) {
            return null;
        }
        $config = $this->getCustomerConfiguration()->data->jsapiStat_AccountsIntensityLastMonth($status);
        $data = $app->getDB()->query($config) ?: array();
        return $data;
    }

    public function get_account_overview (&$resp) {
        $resp['overview_accounts'] = $this->_getStats_AccountsOverview();
        $resp['accounts_intensity_last_month_active'] = $this->_getStats_AccountsIntensityLastMonth('ACTIVE');
        $resp['accounts_intensity_last_month_temp'] = $this->_getStats_AccountsIntensityLastMonth('TEMP');
        $resp['accounts_intensity_last_month_removed'] = $this->_getStats_AccountsIntensityLastMonth('REMOVED');
    }

    public function get_account_account (&$resp, $req) {
        if (!empty($req->get['id'])) {
            $AccountID = intval($req->get['id']);
            $resp = $this->getAccountByID($AccountID);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    public function get_account_activate (&$resp, $req) {
        if (!empty($req->get['hash'])) {
            $ValidationString = $req->get['hash'];
            $resp = $this->_activateAccountByValidationStyring($ValidationString);
            return;
        }
        $resp['error'] = 'MissedParameter_hash';
    }

    public function post_account_account (&$resp, $req) {
        $resp = $this->createAccount($req->data);
    }

    public function patch_account_account (&$resp, $req) {
        if (!empty($req->get['id'])) {
            $AccountID = intval($req->get['id']);
            $resp = $this->_updateAccountByID($AccountID, $req->data);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    public function delete_account_account (&$resp, $req) {
        if (!glIsToolbox()) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        if (!empty($req->get['id'])) {
            $AccountID = intval($req->get['id']);
            $resp = $this->_disableAccountByID($AccountID);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }
}

?>
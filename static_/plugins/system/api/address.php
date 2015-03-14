<?php
namespace static_\plugins\system\api;

use \engine\lib\validate as Validate;
use \engine\lib\api as API;
use \engine\lib\path as Path;
use Exception;

class address {

    private function __adjustAddress (&$address) {
        if (empty($address))
            return null;
        $address['ID'] = intval($address['ID']);
        $address['UserID'] = intval($address['UserID']);
        $address['isRemoved'] = $address['Status'] === 'REMOVED';
        return $address;
    }

    public function getAddressByID ($AddressID) {
        global $app;
        $config = dbquery::getAddress($AddressID);
        $address = $app->getDB()->query($config);
        // adjust values
        return $this->__adjustAddress($address);
    }

    public function getAddresses ($UserID) {
        global $app;
        $configAddresses = dbquery::getUserAddresses($UserID, $app->isToolbox());
        $addresses = $app->getDB()->query($configAddresses) ?: array();
        foreach ($addresses as &$item) {
            $this->__adjustAddress($item);
        }
        return $addresses;
    }

    public function createAddress ($UserID, $reqData, $allowStandalone = false) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Address' => array('string', 'notEmpty', 'min' => 2, 'max' => 100),
            'POBox' => array('notEmpty', 'min' => 2, 'max' => 100),
            'Country' => array('string', 'min' => 2, 'max' => 100),
            'City' => array('string', 'min' => 2, 'max' => 100)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                // TODO: if user is authorized and do not have maximum addresses
                // we must link new address to the user otherwise create unlinked user
                $user = API::getAPI('system:users')->getUserByID($UserID);
                if (empty($user))
                    throw new Exception("WrongUser", 1);
                elseif (count($user['Addresses']) >= 3) {
                    if (!$allowStandalone)
                        throw new Exception("AddressLimitExceeded", 1);
                    else
                        $UserID = null;
                }

                $app->getDB()->beginTransaction();

                $validatedValues = $validatedDataObj['values'];

                $data = array();
                $data["CustomerID"] = $app->getSite()->getRuntimeCustomerID();
                $data["UserID"] = $UserID;
                $data["Address"] = $validatedValues['Address'];
                $data["POBox"] = $validatedValues['POBox'];
                $data["Country"] = $validatedValues['Country'];
                $data["City"] = $validatedValues['City'];

                $configCreateUser = dbquery::createAddress($data);

                $AddressID = $app->getDB()->query($configCreateUser) ?: null;

                $app->getDB()->commit();

                $result = $this->getAddressByID($AddressID);

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = 'UserAddressCreateError';
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    private function _updateAddressByID ($AddressID, $reqData) {
        global $app;
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Address' => array('skipIfUnset', 'string', 'min' => 2, 'max' => 100),
            'POBox' => array('skipIfUnset', 'min' => 2, 'max' => 100),
            'Country' => array('skipIfUnset', 'string', 'min' => 2, 'max' => 100),
            'City' => array('skipIfUnset', 'string', 'min' => 2, 'max' => 100)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $app->getDB()->beginTransaction();

                $data = $validatedDataObj['values'];

                $configUpdateAddress = dbquery::updateAddress($AddressID, $data);

                $app->getDB()->query($configUpdateAddress);

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                // return glWrap("error", 'AddressUpdateError');
                $errors[] = 'AddressUpdateError';
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getAddressByID($AddressID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function disableAddressByID ($AddressID) {
        global $app;
        $errors = array();
        $success = false;

        try {
            $app->getDB()->beginTransaction();

            $config = dbquery::disableAddress($AddressID);
            $app->getDB()->query($config);

            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = 'AddressDisableError';
        }

        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    // we don't allow get user address
    public function get () {}

    public function put (&$resp, $req) {
        if (empty($req->get['params'])) {
            $resp['error'] = 'WrongIDParameter';
        } else {
            $UserID = null;
            if (is_numeric($req->data['UserID'])) {
                $UserID = intval($req->data['UserID']);
            } else {
                $resp['error'] = 'EmptyUserID';
                return;
            }
            ;
            if (!API::getAPI('system:auth')->ifYouCan('Maintain') && !API::getAPI('system:auth')->isUserIDAuthenticated($UserID)) {
                $resp['error'] = "AccessDenied";
                return;
            }
            if (is_numeric($req->get['params'])) {
                $addressID = intval($req->get['params']);
                $resp = $this->_updateAddressByID($addressID, $req->data);
            } else {
                $resp['error'] = 'WrongIDParameter';
            }
        }



        // if (!empty($req->get['id'])) {
        //     $AddressID = intval($req->get['id']);
        //     $resp = $this->_updateAddressByID($AddressID, $req->data);
        //     return;
        // }
        // $resp['error'] = 'WrongIDParameter';
    }
    // public function patch (&$resp, $req) {
    //     if (!empty($req->get['id'])) {
    //         $AddressID = intval($req->get['id']);
    //         $resp = $this->_updateAddressByID($AddressID, $req->data);
    //         return;
    //     }
    //     $resp['error'] = 'WrongIDParameter';
    // }

    public function post (&$resp, $req) {
        $UserID = null;
        if (is_numeric($req->data['UserID'])) {
            $UserID = intval($req->data['UserID']);
        } else {
            $resp['error'] = 'EmptyUserID';
            return;
        }
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') && !API::getAPI('system:auth')->isUserIDAuthenticated($UserID)) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createAddress($UserID, $req->data);
        // if (!empty($req->data['UserID'])) {
        //     $UserID = intval($req->data['UserID']);
        //     $resp = $this->createAddress($UserID, $req->data);
        //     return;
        // }
        // $resp['error'] = 'MissedParameter_UserID';
    }

    public function delete (&$resp, $req) {
        if (empty($req->get['params'])) {
            $resp['error'] = 'WrongIDParameter';
        } else {
            if (is_numeric($req->get['params'])) {
                $addressID = intval($req->get['params']);
                $address = $this->getAddressByID($addressID);
                if (empty($address)) {
                    $resp['error'] = 'WrongAddressID';
                    return;
                }
                if (!API::getAPI('system:auth')->ifYouCan('Maintain') && !API::getAPI('system:auth')->isUserIDAuthenticated($address['UserID'])) {
                    $resp['error'] = "AccessDenied";
                    return;
                }
                $resp = $this->disableAddressByID($addressID);
            } else {
                $resp['error'] = 'WrongIDParameter';
            }

            // $UserID = null;
            // if (is_numeric($req->data['UserID'])) {
            //     $UserID = intval($req->data['UserID']);
            // } else {
            //     $resp['error'] = 'EmptyUserID';
            //     return;
            // }
            // $resp = $this->createAddress($UserID, $req->data);
        }



        // if (!empty($req->get['id'])) {
        //     $AddressID = intval($req->get['id']);
        //     $resp = $this->disableAddressByID($AddressID);
        //     return;
        // }
        // $resp['error'] = 'WrongIDParameter';
    }
}

?>
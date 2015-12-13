<?php
namespace static_\plugins\system\api;

use \engine\lib\validate as Validate;
use \engine\lib\api as API;
use \engine\lib\path as Path;
use Exception;

class address extends API {

    // private function __adjustAddress (&$address) {
    //     if (empty($address))
    //         return null;
    //     $address['ID'] = intval($address['ID']);
    //     $address['UserID'] = intval($address['UserID']);
    //     $address['isRemoved'] = $address['Status'] === 'REMOVED';
    //     return $address;
    // }

    // public function getAddressByID ($AddressID) {
    //     global $app;
    //     $config = data::getAddress($AddressID);
    //     $address = $app->getDB()->query($config);
    //     // adjust values
    //     return $this->__adjustAddress($address);
    // }

    // public function getAddresses ($UserID) {
    //     global $app;
    //     $configAddresses = data::getUserAddresses($UserID, $app->isToolbox());
    //     $addresses = $app->getDB()->query($configAddresses) ?: array();
    //     foreach ($addresses as &$item) {
    //         $this->__adjustAddress($item);
    //     }
    //     return $addresses;
    // }

    public function createAddress ($UserID, $reqData, $allowStandalone = false) {
        // global $app;
        // $result = array();
        // $errors = array();
        // $success = false;
        $r = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Address' => array('string', 'notEmpty', 'min' => 2, 'max' => 100),
            'POBox' => array('notEmpty', 'min' => 2, 'max' => 100),
            'Country' => array('string', 'min' => 2, 'max' => 100),
            'City' => array('string', 'min' => 2, 'max' => 100)
        ));

        if ($validatedDataObj->errorsCount == 0)
            try {

                // TODO: if user is authorized and do not have maximum addresses
                // we must link new address to the user otherwise create unlinked user
                $userAddressesCount = $this->data->fetchUserAddressesCount($UserID);
                if (is_null($userAddressesCount)) {
                    throw new Exception("WrongUser", 1);
                } elseif ($userAddressesCount >= 3) {
                    if (!$allowStandalone) {
                        throw new Exception("AddressLimitExceeded", 1);
                    } else {
                        $UserID = null;
                    }
                }

                // $app->getDB()->beginTransaction();

                $validatedValues = $validatedDataObj->validData;

                $data = array();
                $data["CustomerID"] = $app->getSite()->getRuntimeCustomerID();
                $data["UserID"] = $UserID;
                $data["Address"] = $validatedValues['Address'];
                $data["POBox"] = $validatedValues['POBox'];
                $data["Country"] = $validatedValues['Country'];
                $data["City"] = $validatedValues['City'];

                $r = $this->data->createAddress($data);

                // $app->getDB()->commit();
                if ($r->isEmptyResult()) {
                    throw new Exception('AddressCreateError');
                }

                // $result = $this->getAddressByID($AddressID);

                // $success = true;
            } catch (Exception $e) {
                $r->addError($e->getMessage());
                // $app->getDB()->rollBack();
                // $errors[] = 'UserAddressCreateError';
                // $errors[] = $e->getMessage();
            }
        else {            $r->addErrors($validatedDataObj->errorMessages);
        }

        if ($r->hasResult()) {
            $addr = $this->data->fetchAddress($r->getResult());
            $r->setResult($addr);
        }
        // $result['errors'] = $errors;
        // $result['success'] = $success;
        // $result['success_address'] = $success;

        return $r->toArray();
    }

    private function _updateAddressByID ($AddressID, $reqData) {
        // global $app;
        // $errors = array();
        // $success = false;
        $r = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Address' => array('skipIfUnset', 'string', 'min' => 2, 'max' => 100),
            'POBox' => array('skipIfUnset', 'min' => 2, 'max' => 100),
            'Country' => array('skipIfUnset', 'string', 'min' => 2, 'max' => 100),
            'City' => array('skipIfUnset', 'string', 'min' => 2, 'max' => 100)
        ));

        if ($validatedDataObj->errorsCount == 0) {
            // $app->getDB()->beginTransaction();

            // $data = $validatedDataObj->validData;

            $r = $this->data->updateAddress($AddressID, $validatedDataObj->validData);

            // $app->getDB()->query($configUpdateAddress);

            // $app->getDB()->commit();

            // $success = true;
        } else {
            $r->addErrors($validatedDataObj->errorMessages);
        }

        if ($r->hasResult()) {
            $addr = $this->data->fetchAddress($AddressID);
            $r->setResult($addr);
        }

        // $result = $this->getAddressByID($AddressID);
        // $result['errors'] = $errors;
        // $result['success'] = $success;
        // $result['success_address'] = $success;

        return $result;
    }

    public function disableAddressByID ($AddressID) {
        // global $app;
        // $errors = array();
        // $success = false;
        $r = $this->data->disableAddress($AddressID);

        if ($r->hasResult()) {
            $addr = $this->data->fetchAddress($AddressID);
            $r->setResult($addr);
        }

        return $r->toArray();
        // try {
        //     // $app->getDB()->beginTransaction();

        //     // $config = data::disableAddress($AddressID);
        //     // $app->getDB()->query($config);

        //     // $app->getDB()->commit();

        //     // $success = true;
        // } catch (Exception $e) {
        //     $app->getDB()->rollBack();
        //     $errors[] = 'AddressDisableError';
        // }

        // $result = $this->getAddressByID($AddressID);
        // $result['errors'] = $errors;
        // $result['success'] = $success;
        // $result['success_address'] = $success;
        // return $result;
    }

    // we don't allow get user address
    public function get () {}

    public function put ($req, $resp) {
        $UserID = null;
        if (is_numeric($req->data['UserID'])) {
            $UserID = intval($req->data['UserID']);
        } else {
            $resp->setWrongItemIdError();
            return;
        }
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') &&
            !API::getAPI('system:auth')->isUserIDAuthenticated($UserID)) {
            return $resp->setAccessError();
            return;
        }
        if (Request::hasRequestedID()) {
            $resp->setResponse($this->createAddress($UserID, $req->data));
            return;
        }

        $resp->setWrongItemIdError();
        // $resp->setResponse($this->createAddress($UserID, $req->data));


        // if (Request::noRequestedItem()) {
        //     $resp->setError('WrongIDParameter');
        // } else {
        //     $UserID = null;
        //     if (is_numeric($req->data['UserID'])) {
        //         $UserID = intval($req->data['UserID']);
        //     } else {
        //         $resp->setWrongItemIdError();
        //         return;
        //     }
        //     ;
        //     if (!API::getAPI('system:auth')->ifYouCan('Maintain') &&
        //         !API::getAPI('system:auth')->isUserIDAuthenticated($UserID)) {
        //         return $resp->setAccessError();
        //         return;
        //     }
        //     if (Request::hasRequestedID()) {
        //         $resp->setResponse($this->_updateAddressByID($req->id, $req->data));
        //         return;
        //     } else {
        //         $resp->setError('WrongIDParameter');
        //     }
        // }



        // if (!empty($req->id)) {
        //     $AddressID = intval($req->id);
        //     $resp->setResponse($this->_updateAddressByID($AddressID, $req->data));
        //     return;
        // }
        // $resp->setError('WrongIDParameter');
    }
    // public function patch ($req, $resp) {
    //     if (!empty($req->id)) {
    //         $AddressID = intval($req->id);
    //         $resp->setResponse($this->_updateAddressByID($AddressID, $req->data));
    //         return;
    //     }
    //     $resp->setError('WrongIDParameter');
    // }

    public function post ($req, $resp) {
        $UserID = null;
        if (is_numeric($req->data['UserID'])) {
            $UserID = intval($req->data['UserID']);
        } else {
            $resp->setWrongItemIdError();
            return;
        }
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') &&
            !API::getAPI('system:auth')->isUserIDAuthenticated($UserID)) {
            return $resp->setAccessError();
            return;
        }
        $resp->setResponse($this->createAddress($UserID, $req->data));
        // if (!empty($req->data['UserID'])) {
        //     $UserID = intval($req->data['UserID']);
        //     $resp->setResponse($this->createAddress($UserID, $req->data));
        //     return;
        // }
        // $resp->setError('MissedParameter_UserID');
    }

    public function delete ($req, $resp) {
        if (Request::hasRequestedID()) {
            $address = $this->getAddressByID($req->id);
            if (empty($address)) {
                $resp->setError('WrongAddressID');
                return;
            }
            if (!API::getAPI('system:auth')->ifYouCan('Maintain') &&
                !API::getAPI('system:auth')->isUserIDAuthenticated($address['UserID'])) {
                return $resp->setAccessError();
                return;
            }
            $resp->setResponse($this->disableAddressByID($req->id));
        }
        $resp->setWrongItemIdError();

            // $UserID = null;
            // if (is_numeric($req->data['UserID'])) {
            //     $UserID = intval($req->data['UserID']);
            // } else {
            //     $resp->setWrongItemIdError();
            //     return;
            // }
            // $resp->setResponse($this->createAddress($UserID, $req->data));



        // if (!empty($req->id)) {
        //     $AddressID = intval($req->id);
        //     $resp->setResponse($this->disableAddressByID($AddressID));
        //     return;
        // }
        // $resp->setError('WrongIDParameter');
    }
}

?>
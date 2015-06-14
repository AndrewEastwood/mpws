<?php
namespace static_\plugins\system\api;

use \engine\lib\api as API;
use \engine\lib\validate as Validate;
use \engine\lib\path as Path;
use Exception;

class email {


    private function __adjustEmail (&$address) {
        if (empty($address))
            return null;
        $address['ID'] = intval($address['ID']);
        $address['UserID'] = intval($address['UserID']);
        $address['isRemoved'] = $address['Status'] === 'REMOVED';
        return $address;
    }

    public function getEmailByID ($EmailID) {
        global $app;
        $useCustomerDataOnly = !API::getAPI('system:auth')->ifYouCan('Maintain');
        $user = $app->getDB()->query(dbquery::getEmailByID($EmailID), $useCustomerDataOnly);
        // var_dump('getUserByID', $UserID);
        if (!is_null($user))
            $user = $this->__adjustEmail($user);
        return $user;
    }

    public function getAvailableEmails_List ($options = array()) {
        global $app;
        $config = dbquery::getEmailList($options);
        if (empty($config))
            return null;
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $orderRawItem) {
                    $_items[] = $self->getEmailByID($orderRawItem['ID']);
                }
                return $_items;
            }
        );
        $dataList = $app->getDB()->getDataList($config, $options, $callbacks);
        return $dataList;
    }


    public function createEmail ($reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'notEmpty', 'min' => 2, 'max' => 100),
            'Params' => array('string')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $app->getDB()->beginTransaction();

                $validatedValues = $validatedDataObj['values'];

                $data = array();
                $data["CustomerID"] = $app->getSite()->getRuntimeCustomerID();
                $data["Name"] = $validatedValues['Name'];
                $data["Params"] = $validatedValues['Params'];

                $configCreateEmail = dbquery::createEmail($data);

                $EmaiID = $app->getDB()->query($configCreateEmail) ?: null;

                $app->getDB()->commit();

                $result = $this->getAddressByID($EmaiID);

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = 'EmailCreateError';
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    private function _updateEmailByID ($EmailID, $reqData) {
        global $app;
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('skipIfUnset', 'string', 'min' => 2, 'max' => 100),
            'Params' => array('skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $app->getDB()->beginTransaction();

                $data = $validatedDataObj['values'];

                $configUpdateEmail = dbquery::updateEmail($EmailID, $data);

                $app->getDB()->query($configUpdateEmail);

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                // return glWrap("error", 'AddressUpdateError');
                $errors[] = 'EmailUpdateError';
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getEmailByID($AddressID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function archiveEmail ($EmailID) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        try {

            $app->getDB()->beginTransaction();

            $config = dbquery::archiveEmail($EmailID);
            $app->getDB()->query($config);

            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }

        $result = $this->getEmailByID($EmailID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }


    public function get (&$resp, $req) {
        // var_dump($req);
        if (isset($req->get['type'])) {
            switch ($req->get['type']) {
                case 'new': {
                    $resp = $this->getNewProducts_List($req->get);
                    break;
                }
            }
            return;
        }
        if (!empty($req->get['params'])) {
            if (is_numeric($req->get['params'])) {
                $EmailID = intval($req->get['params']);
                $resp = $this->getEmailByID($EmailID);
            }
        } else {
            $resp = $this->getAvailableEmails_List($req->get);
        }
    }

    public function post (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createEmail($req->data);
    }

    public function put (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['params'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $EmailID = intval($req->get['params']);
            $resp = $this->_updateEmailByID($EmailID, $req->data);
        }
    }

    public function delete (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->archiveEmail($req->data);
    }
}

?>
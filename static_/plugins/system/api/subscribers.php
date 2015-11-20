<?php
namespace static_\plugins\system\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class subscribers {

    private function __adjustSubscription (&$sub) {
        if (empty($sub))
            return null;
        $sub['ID'] = intval($sub['ID']);
        $sub['AccountID'] = intval($sub['AccountID']);
        $sub['isRemoved'] = $sub['Status'] === 'REMOVED';
        return $sub;
    }

    // public function getEmailByID ($EmailID) {
    //     global $app;
    //     $useCustomerDataOnly = !API::getAPI('system:auth')->ifYouCan('Maintain');
    //     $email = $app->getDB()->query(dbquery::getEmailByID($EmailID), $useCustomerDataOnly);
    //     if (!is_null($email))
    //         $email = $this->__adjustEmail($email);
    //     return $email;
    // }

    // public function getAvailableEmails_List ($options = array()) {
    //     global $app;
    //     $config = dbquery::getEmailList($options);
    //     if (empty($config))
    //         return null;
    //     $self = $this;
    //     $callbacks = array(
    //         "parse" => function ($items) use($self) {
    //             $_items = array();
    //             foreach ($items as $key => $orderRawItem) {
    //                 $_items[] = $self->getEmailByID($orderRawItem['ID']);
    //             }
    //             return $_items;
    //         }
    //     );
    //     $dataList = $app->getDB()->getDataList($config, $options, $callbacks);
    //     return $dataList;
    // }

    // public function getAvailableEmailsSimple_List ($options = array()) {
    //     global $app;
    //     $config = dbquery::getEmailListSimple($options);
    //     if (empty($config)) {
    //         return array();
    //     }
    //     $useCustomerDataOnly = !API::getAPI('system:auth')->ifYouCan('Maintain');
    //     $simpleList = $app->getDB()->query($config, $useCustomerDataOnly);
    //     return $simpleList;
    // }

    public function getSubscriptionByID ($SubscriptionID) {
        global $app;
        $sub = $app->getDB()->query(dbquery::getSubscriptionByID($SubscriptionID));
        if (!is_null($sub))
            $sub = $this->__adjustSubscription($sub);
        return $sub;
    }

    public function getSubscriptionByToken ($SubscriptionToken) {
        global $app;
        $sub = $app->getDB()->query(dbquery::getSubscriptionByToken($SubscriptionToken));
        if (!is_null($sub))
            $sub = $this->__adjustSubscription($sub);
        return $sub;
    }

    public function getActiveByContentSubscribers_List (array $options = array()) {
        global $app;
        // $options['_fshop_products.Status'] = join(',', dbquery::getProductStatusesWhenAvailable()) . ':IN';
        $config = dbquery::getSubscribersList($options);
        if (empty($config))
            return null;
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $orderRawItem) {
                    $_items[] = $self->getSubscriptionByID($orderRawItem['ID']);
                }
                return $_items;
            }
        );
        $dataList = $app->getDB()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function getSubscribers_List (array $options = array()) {
        global $app;
        $config = dbquery::getSubscribersList($options);
        if (empty($config))
            return null;
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $orderRawItem) {
                    $_items[] = $self->getSubscriptionByID($orderRawItem['ID']);
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
        if (!empty($req->id)) {
            if (is_numeric($req->id)) {
                $ProductID = intval($req->id);
                $resp = $this->getSubscriptionByID($ProductID);
            } else {
                $resp = $this->getSubscriptionByToken($req->id);
            }
        } else {
            if (isset($req->get['content'])) {
                switch ($req->get['content']) {
                    case 'contentent': {
                        $resp = $this->getActiveByContentSubscribers_List($req->get, $req->get['content']);
                        break;
                    }
                }
            } else {
                $resp = $this->getSubscribers_List($req->get);
            }
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
        if (empty($req->id)) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $EmailID = intval($req->id);
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
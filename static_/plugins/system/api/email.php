<?php
namespace static_\plugins\system\api;

use \engine\lib\api as API;
use \engine\lib\validate as Validate;
use \engine\lib\path as Path;
use Exception;

class email extends API {
    // private function __adjustEmail (&$address) {
    //     if (empty($address))
    //         return null;
    //     $address['ID'] = intval($address['ID']);
    //     $address['UserID'] = intval($address['UserID']);
    //     $address['isRemoved'] = $address['Status'] === 'REMOVED';
    //     return $address;
    // }

    // public function getEmailByID ($EmailID) {
    //     global $app;
    //     $useCustomerDataOnly = !API::getAPI('system:auth')->ifYouCan('Maintain');
    //     $email = $app->getDB()->query(data::getEmailByID($EmailID), $useCustomerDataOnly);
    //     if (!is_null($email))
    //         $email = $this->__adjustEmail($email);
    //     return $email;
    // }

    // public function getAvailableEmails_List ($options = array()) {
    //     global $app;
    //     $config = data::getEmailList($options);
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
    //     $dataList = $app->getDB()->queryMatchedIDs($config, $options, $callbacks);
    //     return $dataList;
    // }

    // public function getAvailableEmailsSimple_List ($options = array()) {
    //     global $app;
    //     $config = data::getEmailListSimple($options);
    //     if (empty($config)) {
    //         return array();
    //     }
    //     $useCustomerDataOnly = !API::getAPI('system:auth')->ifYouCan('Maintain');
    //     $simpleList = $app->getDB()->query($config, $useCustomerDataOnly);
    //     return $simpleList;
    // }


    // public function createEmail ($reqData) {
    //     global $app;
    //     $result = array();
    //     $errors = array();
    //     $success = false;

    //     $validatedDataObj = Validate::getValidData($reqData, array(
    //         'Name' => array('string', 'notEmpty', 'min' => 2, 'max' => 100),
    //         'Params' => array('string')
    //     ));

    //     if ($validatedDataObj->errorsCount == 0)
    //         try {

    //             $app->getDB()->beginTransaction();

    //             $validatedValues = $validatedDataObj->validData;

    //             $data = array();
    //             $data["CustomerID"] = $app->getSite()->getRuntimeCustomerID();
    //             $data["Name"] = $validatedValues['Name'];
    //             $data["Params"] = $validatedValues['Params'];

    //             $configCreateEmail = data::createEmail($data);

    //             $EmaiID = $app->getDB()->query($configCreateEmail) ?: null;

    //             $app->getDB()->commit();

    //             $result = $this->getAddressByID($EmaiID);

    //             $success = true;
    //         } catch (Exception $e) {
    //             $app->getDB()->rollBack();
    //             $errors[] = 'EmailCreateError';
    //             $errors[] = $e->getMessage();
    //         }
    //     else
    //         $r->addErrors($validatedDataObj->errorMessages);

    //     $result['errors'] = $errors;
    //     $result['success'] = $success;

    //     return $result;
    // }

    // private function _updateEmailByID ($EmailID, $reqData) {
    //     global $app;
    //     $errors = array();
    //     $success = false;

    //     $validatedDataObj = Validate::getValidData($reqData, array(
    //         'Name' => array('skipIfUnset', 'string', 'min' => 2, 'max' => 100),
    //         'Params' => array('skipIfUnset')
    //     ));

    //     if ($validatedDataObj->errorsCount == 0)
    //         try {

    //             $app->getDB()->beginTransaction();

    //             $data = $validatedDataObj->validData;

    //             $configUpdateEmail = data::updateEmail($EmailID, $data);

    //             $app->getDB()->query($configUpdateEmail);

    //             $app->getDB()->commit();

    //             $success = true;
    //         } catch (Exception $e) {
    //             $app->getDB()->rollBack();
    //             // return glWrap("error", 'AddressUpdateError');
    //             $errors[] = 'EmailUpdateError';
    //         }
    //     else
    //         $r->addErrors($validatedDataObj->errorMessages);

    //     $result = $this->getEmailByID($AddressID);
    //     $result['errors'] = $errors;
    //     $result['success'] = $success;

    //     return $result;
    // }

    // public function archiveEmail ($EmailID) {
    //     global $app;
    //     $result = array();
    //     $errors = array();
    //     $success = false;
    //     try {

    //         $app->getDB()->beginTransaction();

    //         $config = data::archiveEmail($EmailID);
    //         $app->getDB()->query($config);

    //         $app->getDB()->commit();

    //         $success = true;
    //     } catch (Exception $e) {
    //         $app->getDB()->rollBack();
    //         $errors[] = $e->getMessage();
    //     }

    //     $result = $this->getEmailByID($EmailID);
    //     $result['errors'] = $errors;
    //     $result['success'] = $success;

    //     return $result;
    // }


    // public function get ($req, $resp) {
    //     // var_dump($req);
    //     if (isset($req->get['type'])) {
    //         switch ($req->get['type']) {
    //             case 'simplelist': {
    //                 $resp->setResponse($this->getAvailableEmailsSimple_List($req->get));
    //                 break;
    //             }
    //         }
    //         return;
    //     }
    //     if (!empty($req->id)) {
    //         if (is_numeric($req->id)) {
    //             $EmailID = intval($req->id);
    //             $resp->setResponse($this->getEmailByID($EmailID));
    //         }
    //     } else {
    //         $resp->setResponse($this->getAvailableEmails_List($req->get));
    //     }
    // }

    // public function post ($req, $resp) {
    //     if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create')) {
    //         return $resp->setAccessError();
    //         return;
    //     }
    //     $resp->setResponse($this->createEmail($req->data));
    // }

    // public function put ($req, $resp) {
    //     if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
    //         return $resp->setAccessError();
    //         return;
    //     }
    //     if (empty($req->id)) {
    //         $resp->setWrongItemIdError();
    //     } else {
    //         $EmailID = intval($req->id);
    //         $resp->setResponse($this->_updateEmailByID($EmailID, $req->data));
    //     }
    // }

    // public function delete ($req, $resp) {
    //     if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
    //         return $resp->setAccessError();
    //         return;
    //     }
    //     $resp->setResponse($this->archiveEmail($req->data));
    // }
}

?>
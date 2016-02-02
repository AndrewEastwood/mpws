<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class delivery extends API {

    public function createDeliveryAgency ($reqData) {
        // global $app;
        $r = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 200),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300, 'defaultValueIfUnset' => '')
        ));

        if ($validatedDataObj->errorsCount == 0) {
            $validatedValues = $validatedDataObj->validData;
            // $validatedValues["CustomerID"] = $app->getSite()->getRuntimeCustomerID();
            $r = $this->data->createDeliveryAgent($validatedValues);
            if ($r->isEmptyResult()) {
                $r->addError("DeliveryCreateError");
            }
        } else {
            $r->addErrors($validatedDataObj->errorMessages);
        }

        if ($r->isSuccess() && $r->hasResult()) {
            $item = $this->data->fetchDeliveryAgencyByID($r->getResult());
            $r->setResult($item);
        }
        return $r->toArray();
    }

    public function updateDeliveryAgency ($id, $reqData) {
        $r = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'skipIfUnset', 'min' => 1, 'max' => 100),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300, 'defaultValueIfUnset' => ''),
            'Status' => array('string', 'skipIfUnset')
        ));

        if ($validatedDataObj->errorsCount == 0) {
            $validatedValues = $validatedDataObj->validData;
            $r = $this->data->updateDeliveryAgent($id, $validatedValues);
        } else {
            $r->addErrors($validatedDataObj->errorMessages);
        }

        $item = $this->data->fetchDeliveryAgencyByID($id);
        $r->setResult($item);

        return $r->toArray();
    }

    public function deleteDeliveryAgency ($id) {
        $r = $this->data->deleteDeliveryAgent($id);
        $item = $this->data->fetchDeliveryAgencyByID($id);
        $r->setResult($item);
        return $r->toArray();
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // WRAPPERS
    // -----------------------------------------------
    // -----------------------------------------------

    public function getActiveDeliveryArray () {
        return $this->data->fetchAllActiveDeliveriesArray();
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // REQUESTS
    // -----------------------------------------------
    // -----------------------------------------------

    public function get ($req, $resp) {
        if ($req->hasRequestedID() && $allAccess) {
            $resp->setResponse($this->data->fetchDeliveryAgencyByID($req->id));
            return;
        }
        $resp->setResponse($this->fetchDeliveriesDataList($req->get));
    }

    public function post ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCanCreateWithAllOthers('MENU_SETTINGS')) {
            return $resp->setAccessError();
        }
        // if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
        //     (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create'))) {
        //     return $resp->setAccessError();
        //     return;
        // }
        $resp->setResponse($this->createDeliveryAgency($req->data));
    }

    public function put ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCanEditWithAllOthers('MENU_SETTINGS')) {
            return $resp->setAccessError();
        }
        if ($req->hasRequestedID()) {
            $resp->setResponse($this->updateDeliveryAgency($req->id, $req->data));
            return;
        }
        $resp->setWrongItemIdError();
        // if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
        //     (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit'))) {
        //     return $resp->setAccessError();
        //     return;
        // }
        // if (isset($req->data['ID']) && is_numeric($req->data['ID'])) {
        //     $agencyID = intval($req->data['ID']);
        //     $resp->setResponse($this->updateDeliveryAgency($agencyID, $req->data));
        // } else {
        //     $resp->setWrongItemIdError();
        // }
    }

    public function delete ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCanEditWithAllOthers('shop_EDIT_PRODUCT')) {
            return $resp->setAccessError();
        }
        if ($req->hasRequestedID()) {
            $resp->setResponse($this->deleteDeliveryAgency($req->id));
            return;
        }
        $resp->setWrongItemIdError();
        // $resp->setResponse($this->deleteDeliveryAgency($req->data));
        // if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
        //     (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit'))) {
        //     return $resp->setAccessError();
        //     return;
        // }
        // // var_dump($req);
        // if (isset($req->id) && is_numeric($req->id)) {
        //     $agencyID = intval($req->id);
        //     $resp->setResponse($this->deleteDeliveryAgency($agencyID));
        // } else {
        //     $resp->setWrongItemIdError();
        // }
    }
}

?>
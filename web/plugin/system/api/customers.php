<?php
namespace web\plugin\system\api;

class customers {

    private $_statuses = array('ACTIVE','REMOVED');

    function __construct() {
        $this->shared = new shared();
    }

    public function getRuntimeCustomer () {
        global $app;
        $customer = null;
        // we can access to all customer via toolbox only
        if ($app->isToolbox()) {
            // ability to switch customers
            $activeCustomerID = $_SESSION['site_id'];
            if ($activeCustomerID >= 0)
                $customer = $this->getCustomerByID($activeCustomerID);
            else {
                $customer = $this->getCustomerByName($app->customerName());
                if (isset($customer['ID'])) {
                    $_SESSION['site_id'] = $customers['ID'];
                }
            }
        } else {
            $customer = $this->getCustomerByName($app->customerName());
        }
        return $customer;
    }

    public function getCustomerByID () {

    }

    public function getCustomerByName () {

    }

    public function getCustomers () {

    }

/*    public function get (&$resp, $req) {
        if (!empty($req->get['id'])) {
            if (is_numeric($req->get['id'])) {
                $ProductID = intval($req->get['id']);
                $resp = $this->getProductByID($ProductID);
            } else {
                $resp = $this->getProductByExternalKey($req->get['id']);
            }
        } else {
            if (isset($req->get['type'])) {
                switch ($req->get['type']) {
                    case 'latest': {
                        $resp = $this->getProducts_List_Latest($req->get);
                    }
                }
            } else {
                $resp = $this->getProducts_List($req->get);
            }
        }
    }

    public function post (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createProduct($req->data);
        // $this->_getOrSetCachedState('changed:product', true);
    }

    public function patch (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $ProductID = intval($req->get['id']);
            $resp = $this->updateProduct($ProductID, $req->data);
            // $this->_getOrSetCachedState('changed:product', true);
        }
    }

    public function delete (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->archiveProduct($req->data);
        // $this->_getOrSetCachedState('changed:product', true);
    }*/
}

?>
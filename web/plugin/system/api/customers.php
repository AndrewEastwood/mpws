<?php
namespace web\plugin\system\api;

class customers {

    var $customersCache = array();

    public function switchToDefaultCustomer () {
        global $app;
        return $this->switchToCustomerByName($app->customerName(););
    }

    public function switchToCustomerByName ($customerName) {
        if (isset($this->customersCache[$customerName])) {
            $_SESSION['site_id'] = $this->customersCache[$customerName]['ID'];
            return $this->customersCache[$customerName];
        }
        if (empty($customerName)) {
            return false
        }
        $customer = $this->getCustomerByName($customerName);
        if (!isset($customer)) {
            return false;
        }
        $id = $customer['ID'];
        $_SESSION['site_id'] = $id;
        $this->customersCache[$id] = $customer;
        $this->customersCache[$customer['Name']] = $customer;
        return $customer;
    }

    public function switchToCustomerByID ($id) {
        if (isset($this->customersCache[$id])) {
            $_SESSION['site_id'] = $id;
            return $this->customersCache[$id];
        }
        if (empty($id)) {
            return false
        }
        $customer = $this->getCustomerByID($id);
        if (!isset($customer)) {
            return false;
        }
        $_SESSION['site_id'] = $id;
        $this->customersCache[$id] = $customer;
        $this->customersCache[$customer['Name']] = $customer;
        return $customer;
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

    public function getRuntimeCustomerID () {
        $id = $_SESSION['site_id'];
        if (isset($this->customersCache[$id])) {
            return $id;
        }
        throw new Exception("Exception at getRuntimeCustomerID. Cannot find customer by current id=" . $id, 1);
        
    }

    public function getCustomerByID ($id) {

    }

    public function getCustomerByName ($name) {

    }

    public function getCustomers () {

    }

    public function getCustomerSettings () {

    }

    public function addCustomer () {

    }

    public function updateCustomer () {
        
    }

    public function removeCustomer () {
        
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
<?php
namespace web\plugin\system\api;

use Exception;

class customers {

    var $customersCache = array();

    public function switchToDefaultCustomer () {
        global $app;
        return $this->switchToCustomerByName($app->customerName());
    }

    public function switchToCustomerByName ($customerName) {
        if (isset($this->customersCache[$customerName])) {
            $_SESSION['site_id'] = $this->customersCache[$customerName]['ID'];
            return $this->customersCache[$customerName];
        }
        if (empty($customerName)) {
            return false;
        }
        $customer = $this->getCustomerByName($customerName);
        if (!isset($customer)) {
            return false;
        }
        $ID = $customer['ID'];
        $_SESSION['site_id'] = $ID;
        $this->customersCache[$ID] = $customer;
        $this->customersCache[$customer['Name']] = $customer;
        return $customer;
    }

    public function switchToCustomerByID ($ID) {
        if (isset($this->customersCache[$ID])) {
            $_SESSION['site_id'] = $ID;
            return $this->customersCache[$ID];
        }
        if (empty($ID)) {
            return false;
        }
        $customer = $this->getCustomerByID($ID);
        if (!isset($customer)) {
            return false;
        }
        $_SESSION['site_id'] = $ID;
        $this->customersCache[$ID] = $customer;
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
        $ID = isset($_SESSION['site_id']) ? $_SESSION['site_id'] : null;
        if (isset($this->customersCache[$ID])) {
            return $ID;
        }
        unset($this->customersCache[$ID]);
        throw new Exception("Exception at getRuntimeCustomerID. Cannot find customer by current id=" . $ID, 1);
    }

    private function __adjustCustomer (&$customer) {
        // adjusting
        $ID = intval($customer['ID']);
        $customer['ID'] = $ID;
        $customer['Settings'] = $this->getCustomerSettings($ID);
        // var_dump($customer);
        return $customer;
    }

    public function getCustomerByID ($ID) {
        global $app;
        $config = dbquery::getCustomer($ID);
        $customer = $app->getDB()->query($config);
        return $settings;

    }

    public function getCustomerByName ($customerName) {
        global $app;
        $config = dbquery::getCustomer();
        $config['condition']['Name'] = $app->getDB()->createCondition($customerName);
        $customer = $app->getDB()->query($config, false);
        // echo 2121212;
        // var_dump($customer);
        if (empty($customer))
            return null;
        return $this->__adjustCustomer($customer);
    }

    public function getCustomers_List (array $options = array()) {
        global $app;
        $config = dbquery::getCustomerList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $orderRawItem) {
                    $_items[] = $self->getCustomerByID($orderRawItem['ID']);
                }
                return $_items;
            }
        );
        $dataList = $app->getDB()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function getCustomerSettings ($ID) {
        global $app;
        $config = dbquery::getCustomerSettings($ID);
        $settingsRaw = $app->getDB()->query($config, false);
        $settings = array();
        foreach ($settingsRaw as $key => $value) {
            $settings[$value['Property']] = $value['Value'];
        }
        // var_dump($settings);
        return $settings;
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
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createProduct($req->data);
        // $this->_getOrSetCachedState('changed:product', true);
    }

    public function patch (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
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
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->archiveProduct($req->data);
        // $this->_getOrSetCachedState('changed:product', true);
    }*/
}

?>
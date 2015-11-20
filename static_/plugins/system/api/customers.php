<?php
namespace static_\plugins\system\api;

use \engine\lib\api as API;
use \engine\lib\validate as Validate;
use \engine\lib\path as Path;
use Exception;

class customers {

    var $customersCache = array();
    private $_statuses = array('ACTIVE', 'REMOVED');

    public function getCustomerUploadInnerDir ($host, $subDir = '') {
        global $app;
        $path = '';
        if (empty($subDir))
            $path = Path::createDirPath($host, 'customers');
        else
            $path = Path::createDirPath($host, 'customers', $subDir);
        return $path;
    }
    public function getCustomerUploadInnerImagePath ($host, $name, $subDir = false) {
        $path = $this->getCustomerUploadInnerDir($host, $subDir);
        return $path . $name;
    }

    public function getCustomerStatuses () {
        return $this->_statuses;
    }

    public function setCustomerSessionID ($id) {
        $_SESSION['site_id'] = $id;
        return $id;
    }

    public function getCustomerSessionID () {
        return isset($_SESSION['site_id']) ? $_SESSION['site_id'] : null;
    }

    public function loadActiveCustomer () {
        global $app;
        // echo 'loadActiveCustomer';
        // try to load session customer
        $sessionID = $this->getCustomerSessionID();
        $isSwitched = $this->switchToCustomerByID($sessionID);
        if (!$isSwitched) {
            // otherwise try to load default customer
            if (!$this->switchToDefaultCustomer()) {
                throw new Exception("Unable to load default customer", 1);
            }
        }
    }

    public function switchToDefaultCustomer () {
        global $app;
        return $this->switchToCustomerByName($app->customerName());
    }

    public function switchToCustomerByName ($customerName) {
        if (isset($this->customersCache[$customerName])) {
            $this->setCustomerSessionID($this->customersCache[$customerName]['ID']);
            // $_SESSION['site_id'] = ;
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
        // $_SESSION['site_id'] = $ID;
        $this->setCustomerSessionID($ID);
        $this->customersCache[$ID] = $customer;
        $this->customersCache[$customer['HostName']] = $customer;
        return $customer;
    }

    public function switchToCustomerByID ($ID) {
        if (isset($this->customersCache[$ID])) {
            $this->setCustomerSessionID($ID);
            // $_SESSION['site_id'] = $ID;
            return $this->customersCache[$ID];
        }
        if (empty($ID)) {
            return false;
        }
        // try to load customer by given ID
        $customer = $this->getCustomerByID($ID);
        if (!isset($customer)) {
            return false;
        }
        $this->setCustomerSessionID($ID);
        // $_SESSION['site_id'] = $ID;
        $this->customersCache[$ID] = $customer;
        $this->customersCache[$customer['HostName']] = $customer;
        return $customer;
    }

    public function getDefaultCustomer () {
        global $app;
        $customerName = $app->customerName();
        // QDfe6#(9
        if (isset($this->customersCache[$customerName])) {
            return $this->customersCache[$customerName];
        }
        $customer = $this->getCustomerByName($customerName);
        if (!isset($customer)) {
            return false;
        }
        $ID = $customer['ID'];
        $this->customersCache[$ID] = $customer;
        $this->customersCache[$customer['HostName']] = $customer;
        return $customer;
    }

    public function getRuntimeCustomer () {
        // echo 'getRuntimeCustomer';
        global $app;
        $customer = null;
        // we can access to all customer via toolbox only
        if ($app->isToolbox()) {
            // ability to switch customers
            $ID = $this->getCustomerSessionID();// $_SESSION['site_id'];
            if (!isset($this->customersCache[$ID])) {
                throw new Exception("Cannot get runtime customer by given id: " . $ID, 1);
            }
            $customer = $this->customersCache[$ID];
            // if ($activeCustomerID >= 0)
            //     $customer = $this->getCustomerByID($activeCustomerID);
            // else {
            //     $customer = $this->getCustomerByName($app->customerName());
            //     if (isset($customer['ID'])) {
            //         $this->setCustomerSessionID($customers['ID']);
            //         // $_SESSION['site_id'] = $customers['ID'];
            //     }
            // }
        } else {
            $customer = $this->getCustomerByName($app->customerName());
        }
        return $customer;
    }

    public function getRuntimeCustomerID () {
        $ID = $this->getCustomerSessionID();
        if (isset($this->customersCache[$ID])) {
            return $ID;
        }
        unset($this->customersCache[$ID]);
        throw new Exception("Exception at getRuntimeCustomerID. Cannot find customer by session id=" . $ID, 1);
    }

    public function isRunningCustomerDefault () {
        $defCustomer = $this->getDefaultCustomer();
        $runtimeID = $this->getRuntimeCustomerID();
        // var_dump($defCustomer);
        // var_dump($runtimeID);
        return $runtimeID === $defCustomer['ID'];
    }

    public function getCustomerSettings () {
        global $app;

        $customer = $this->getRuntimeCustomer();
        $urls = $app->getSettings('urls');
        $staticPath = $urls['static'];
        $staticPathCustomer = $staticPath . Path::createPath(Path::getDirNameCustomer(), $app->displayCustomer());
        $logoUrl = $staticPathCustomer . '/img/logo.png';
        if (!empty($customer['Logo'])) {
            $logoUrl = $customer['Logo']['normal'];
        }
        $settings = array(
            'lang' => $customer['Lang'],
            'locale' => $customer['Locale'],
            'plugins' => $customer['Plugins'],
            'homepage' => $customer['HomePage'],
            'host' => $customer['HostName'],
            'scheme' => $customer['Protocol'],
            'title' => $customer['Title'],
            'staticPathCustomer' => $staticPathCustomer,
            'logoUrl' => $logoUrl
        );
        return (object)$settings;
    }

    private function __adjustCustomer (&$customer) {
        // adjusting
        $ID = intval($customer['ID']);
        $customer['ID'] = $ID;
        // $customer['Settings'] = API::getAPI('system:settings')->getSettingsByCustomerID($ID);
        $customer['isBlocked'] = $customer['Status'] != 'ACTIVE';
        $customer['Plugins'] = explode(",", $customer['Plugins']);
        // var_dump($customer);
        if (!empty($customer['Logo'])) {
            $customer['Logo'] = array(
                'name' => $customer['Logo'],
                'normal' => '/' . Path::getUploadDirectory() . $this->getCustomerUploadInnerImagePath($customer['HostName'], $customer['Logo']),
                'sm' => '/' . Path::getUploadDirectory() . $this->getCustomerUploadInnerImagePath($customer['HostName'], $customer['Logo'], 'sm'),
                'xs' => '/' . Path::getUploadDirectory() . $this->getCustomerUploadInnerImagePath($customer['HostName'], $customer['Logo'], 'xs')
            );
        }
        return $customer;
    }

    public function getCustomerByID ($ID) {
        global $app;
        $config = dbquery::getCustomer($ID);
        $customer = $app->getDB()->query($config, false);
        return $this->__adjustCustomer($customer);
    }

    public function getCustomerByName ($customerName) {
        global $app;
        $config = dbquery::getCustomer();
        $config['condition']['HostName'] = $app->getDB()->createCondition($customerName);
        $customer = $app->getDB()->query($config, false);
        // echo 2121212;
        // var_dump($customer);
        if (empty($customer)) {
            return null;
        }
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
        $options['useCustomerID'] = false;
        $dataList = $app->getDB()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function createCustomer ($reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        $CustomerID = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'HostName' => array('string', 'notEmpty', 'max' => 100),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 200, 'defaultValueIfEmpty' => 'localhost'),
            'Title' => array('string', 'skipIfUnset', 'max' => 200, 'defaultValueIfEmpty' => 'Happy Site :)'),
            'AdminTitle' => array('string', 'skipIfUnset', 'max' => 200, 'defaultValueIfEmpty' => 'MPWS Admin'),
            'file1' => array('string', 'skipIfEmpty'),
            'Lang' => array('string', 'skipIfUnset', 'max' => 50, 'defaultValueIfEmpty' => 'en'),
            'Locale' => array('string', 'skipIfUnset', 'max' => 10, 'defaultValueIfEmpty' => 'en_us'),
            'Protocol' => array('string', 'skipIfUnset', 'max' => 10, 'defaultValueIfEmpty' => 'http'),
            'SnapshotURL' => array('string', 'skipIfUnset', 'max' => 300),
            'SitemapURL' => array('string', 'skipIfUnset', 'max' => 500),
            'Plugins' => array('string', 'skipIfUnset', 'max' => 500, 'defaultValueIfEmpty' => 'system')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                // set logo
                if (!empty($validatedValues['Logo'])) {
                    $newFileName = uniqid(time());
                    $fileName = $validatedValues['Logo'];
                    $smImagePath = 'sm' . Path::getDirectorySeparator() . $fileName;
                    $xsImagePath = 'xs' . Path::getDirectorySeparator() . $fileName;
                    $normalImagePath = $fileName;
                    $uploadInfo = Path::moveTemporaryFile($smImagePath, $this->getCategoryUploadInnerDir('sm'), $newFileName);
                    $uploadInfo = Path::moveTemporaryFile($xsImagePath, $this->getCategoryUploadInnerDir('xs'), $newFileName);
                    $uploadInfo = Path::moveTemporaryFile($normalImagePath, $this->getCategoryUploadInnerDir(), $newFileName);
                    $validatedValues['Logo'] = $uploadInfo['filename'];
                }
                unset($validatedValues['file1']);

                // adjust plugins
                $pList = array('system');
                if (isset($validatedValues['Plugins']) && !empty($validatedValues['Plugins'])) {
                    $reqPluginsList = explode(',', strtolower(trim($validatedValues['Plugins'])));
                    foreach ($reqPluginsList as $key => $value) {
                        $value = trim($value);
                        if (!empty($value) && $value !== 'system') {
                            $pList[] = $value;
                        }
                    }
                }
                $validatedValues['Plugins'] = implode(',', $pList);

                $app->getDB()->beginTransaction();

                $configCreateCustomer = dbquery::createCustomer($validatedValues);
                $CustomerID = $app->getDB()->query($configCreateCustomer, false) ?: null;

                if (empty($CustomerID))
                    throw new Exception('CustomerCreateError');

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        if ($success && !empty($CustomerID))
            $result = $this->getCustomerByID($CustomerID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateCustomer ($CustomerID, $reqData, $isPatch = false) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'HostName' => array('string', 'skipIfUnset', 'max' => 100),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 200),
            'Title' => array('string', 'skipIfUnset', 'max' => 200),
            'AdminTitle' => array('string', 'skipIfUnset', 'max' => 200),
            'Status' => array('string', 'skipIfEmpty'),
            'file1' => array('string', 'skipIfUnset'),
            'Lang' => array('string', 'skipIfUnset', 'max' => 50),
            'Locale' => array('string', 'skipIfUnset', 'max' => 10),
            'Protocol' => array('string', 'skipIfUnset', 'max' => 10),
            'SnapshotURL' => array('string', 'skipIfUnset', 'max' => 300),
            'SitemapURL' => array('string', 'skipIfUnset', 'max' => 500),
            'Plugins' => array('string', 'skipIfUnset', 'max' => 500)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                // update logo
                if (isset($reqData['file1'])) {
                    $customer = $this->getCustomerByID($CustomerID);

                    $currentFileName = empty($customer['Logo']) ? "" : $customer['Logo']['name'];
                    $newFileName = null;

                    if (!empty($validatedValues['file1'])) {
                        $newFileName = $validatedValues['file1'];
                    }

                    if ($newFileName !== $currentFileName) {
                        if (empty($newFileName) && !empty($currentFileName)) {
                            Path::deleteUploadedFile($this->getCustomerUploadInnerImagePath($customer['HostName'], $currentFileName, 'sm'));
                            Path::deleteUploadedFile($this->getCustomerUploadInnerImagePath($customer['HostName'], $currentFileName, 'xs'));
                            Path::deleteUploadedFile($this->getCustomerUploadInnerImagePath($customer['HostName'], $currentFileName));
                            $validatedValues['Logo'] = null;
                        }
                        if (!empty($newFileName)) {
                            $currentFileName = $newFileName;
                            $newFileName = uniqid(time());
                            $smImagePath = 'sm' . Path::getDirectorySeparator() . $currentFileName;
                            $xsImagePath = 'xs' . Path::getDirectorySeparator() . $currentFileName;
                            $normalImagePath = $currentFileName;
                            $uploadInfo = Path::moveTemporaryFile($smImagePath, $this->getCustomerUploadInnerDir($customer['HostName'], 'sm'), $newFileName);
                            $uploadInfo = Path::moveTemporaryFile($xsImagePath, $this->getCustomerUploadInnerDir($customer['HostName'], 'xs'), $newFileName);
                            $uploadInfo = Path::moveTemporaryFile($normalImagePath, $this->getCustomerUploadInnerDir($customer['HostName']), $newFileName);
                            $validatedValues['Logo'] = $uploadInfo['filename'];
                        }
                    }
                }

                // adjust fields
                if (array_key_exists('file1', $validatedValues)) {
                    unset($validatedValues['file1']);
                }

                // adjust plugins
                if (isset($validatedValues['Plugins'])) {
                    if (API::getAPI('system:auth')->ifYouCan('Maintain')) {
                        $pList = array('system');
                        if (!empty($validatedValues['Plugins'])) {
                            $reqPluginsList = explode(',', strtolower(trim($validatedValues['Plugins'])));
                            foreach ($reqPluginsList as $key => $value) {
                                $value = trim($value);
                                if (!empty($value) && $value !== 'system') {
                                    $pList[] = $value;
                                }
                            }
                        }
                        $validatedValues['Plugins'] = implode(',', $pList);
                    } else {
                        unset($validatedValues['Plugins']);
                    }
                }

                $app->getDB()->beginTransaction();

                $configCreateCustomer = dbquery::updateCustomer($CustomerID, $validatedValues);
                $app->getDB()->query($configCreateCustomer, false) ?: null;

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getCustomerByID($CustomerID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function archiveCustomer ($CustomerID) {
        global $app;
        $errors = array();
        $success = false;

        try {
            $app->getDB()->beginTransaction();

            $config = dbquery::archiveCustomer($CustomerID);
            $app->getDB()->query($config, false);

            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = 'CustomerArchiveError';
        }

        $result = $this->getCustomerByID($CustomerID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function get (&$resp, $req) {
        // var_dump($req);
        if (!empty($req->id)) {
            if (is_numeric($req->id)) {
                $CustomerID = intval($req->id);
                $resp = $this->getCustomerByID($CustomerID);
            } else {
                $resp = $this->getCustomerByName($req->id);
            }
        } else {
            $resp = $this->getCustomers_List($req->get);
        }
    }

    public function post (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') &&
            !API::getAPI('system:auth')->ifYouCan('Create') &&
            !API::getAPI('system:auth')->ifYouCan('Admin')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (isset($req->data['switchto'])) {
            if (API::getAPI('system:auth')->ifYouCan('Maintain')) {
                if (is_numeric($req->data['switchto'])) {
                    $CustomerID = intval($req->data['switchto']);
                    $resp = $this->switchToCustomerByID($CustomerID);
                } else {
                    $resp['error'] = "WrongCustomerID";
                    return;
                }
            } else {
                $resp['error'] = "AccessDenied";
                return;
            }
        } else {
            $resp = $this->createCustomer($req->data);
        }
        // $this->_getOrSetCachedState('changed:product', true);
    }

    public function put (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') &&
            !API::getAPI('system:auth')->ifYouCan('Edit') &&
            !API::getAPI('system:auth')->ifYouCan('Admin')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->id)) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            if (is_numeric($req->id)) {
                $CustomerID = intval($req->id);
                $resp = $this->updateCustomer($CustomerID, $req->data);
            } else {
                $resp['error'] = 'WrongParameter_id';
            }
            // $this->_getOrSetCachedState('changed:product', true);
        }
    }

    public function patch (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') &&
            !API::getAPI('system:auth')->ifYouCan('Edit') &&
            !API::getAPI('system:auth')->ifYouCan('Admin')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->id)) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            if (is_numeric($req->id)) {
                $CustomerID = intval($req->id);
                $resp = $this->updateCustomer($CustomerID, $req->data, true);
            } else {
                $resp['error'] = 'WrongParameter_id';
            }
        }
    }

    public function delete (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') &&
            !API::getAPI('system:auth')->ifYouCan('Edit') &&
            !API::getAPI('system:auth')->ifYouCan('Admin')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->id)) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            if (is_numeric($req->id)) {
                $CustomerID = intval($req->id);
                $resp = $this->archiveCustomer($CustomerID);
            } else {
                $resp['error'] = 'WrongParameter_id';
            }
        }
    }

/*    public function get (&$resp, $req) {
        if (!empty($req->id)) {
            if (is_numeric($req->id)) {
                $ProductID = intval($req->id);
                $resp = $this->getProductByID($ProductID);
            } else {
                $resp = $this->getProductByExternalKey($req->id);
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

*/
}

?>
<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\request as Request;
use \engine\lib\utils as Utils;
use Exception;
use ArrayObject;

class categories extends \engine\objects\api {

    private $_statuses = array('ACTIVE', 'REMOVED');

    // -----------------------------------------------
    // -----------------------------------------------
    // CATEGORIES
    // -----------------------------------------------
    // -----------------------------------------------
    private function __adjustCategory (&$category) {
        $category['ID'] = intval($category['ID']);
        $category['ParentID'] = is_null($category['ParentID']) ? null : intval($category['ParentID']);
        $category['_isRemoved'] = $category['Status'] === 'REMOVED';
        $category['_location'] = $this->getCategoryLocationByCategoryID($category['ID']);
        return $category;
    }

    public function getCategoryByID ($categoryID) {
        if (empty($categoryID) || !is_numeric($categoryID))
            return null;
        $config = $this->getPluginConfiguration()->data->jsapiShopGetCategoryItem($categoryID);
        $category = $this->getCustomer()->fetch($config);
        if (empty($category))
            return null;
        return $this->__adjustCategory($category);
    }

    public function getCategoryByName ($categoryName) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetCategoryItem();
        $config['condition']['Name'] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($categoryName);
        $category = $this->getCustomer()->fetch($config);
        if (empty($category))
            return null;
        return $this->__adjustCategory($category);
    }

    public function getCategories_List (array $options = array()) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetCategoryList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getCategoryByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function createCategory ($reqData) {
        $result = array();
        $errors = array();
        $success = false;
        $CategoryID = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 200),
            'ParentID' => array('int', 'skipIfUnset'),
            'Description' => array('string', 'skipIfUnset', 'max' => 5000)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $this->getCustomerDataBase()->beginTransaction();

                $validatedValues["CustomerID"] = $this->getCustomer()->getCustomerID();

                $configCreateCategory = $this->getPluginConfiguration()->data->jsapiShopCreateCategory($validatedValues);
                $CategoryID = $this->getCustomer()->fetch($configCreateCategory) ?: null;

                if (empty($CategoryID))
                    throw new Exception('CategoryCreateError');

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        if ($success && !empty($CategoryID))
            $result = $this->getCategoryByID($CategoryID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateCategory ($CategoryID, $reqData) {
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'skipIfUnset', 'min' => 1, 'max' => 200),
            'Description' => array('string', 'skipIfUnset', 'max' => 5000),
            'ParentID' => array('int', 'null', 'skipIfUnset'),
            'Status' => array('string', 'skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $this->getCustomerDataBase()->beginTransaction();

                $configCreateCategory = $this->getPluginConfiguration()->data->jsapiShopUpdateCategory($CategoryID, $validatedValues);
                $this->getCustomer()->fetch($configCreateCategory);

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getCategoryByID($CategoryID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function disableCategory ($CategoryID) {
        $errors = array();
        $success = false;

        try {
            $this->getCustomerDataBase()->beginTransaction();

            $config = $this->getPluginConfiguration()->data->jsapiShopDeleteCategory($CategoryID);
            $this->getCustomer()->fetch($config);

            $this->getCustomerDataBase()->commit();

            $success = true;
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
            $errors[] = 'CategoryUpdateError';
        }

        $result = $this->getCategoryByID($CategoryID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // CATEGORY BREADCRUMB
    // -----------------------------------------------
    // -----------------------------------------------
    public function getCategoryLocationByCategoryID ($categoryID) {
        // var_dump($categoryID);
        $configLocation = $this->getPluginConfiguration()->data->jsapiShopCategoryLocationGet($categoryID);
        $location = $this->getCustomer()->fetch($configLocation);
        if (isset($location['ID'])) {
            $location['ID'] = intval($location['ID']);
        }
        return $location;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // SHOP CATALOG TREE
    // -----------------------------------------------
    // -----------------------------------------------
    public function getCatalogTree ($selectedCategoryID = false) {

        function getTree (array &$elements, $parentId = null) {
            $branch = array();
            // echo "#######Looking for element where parentid ==", $parentId, PHP_EOL;
            foreach ($elements as $key => $element) {
                // echo "~~~Current element ID = ", $element['ParentID'], PHP_EOL;
                if ($element['ParentID'] == $parentId) {
                    // echo "Element is found".PHP_EOL;
                    // echo "Looking for element child nodes wherer ParentID = ", $key,PHP_EOL;
                    $element['childNodes'] = getTree($elements, $key);
                    $branch[$key] = $element;
                    // unset($elements[$key]);
                }
            }
            // echo PHP_EOL . "-=-=-=-=-=-=--=--Results for element where parentid ==", $parentId. PHP_EOL;
            // var_dump($branch);
            return $branch;
        }

        $config = $this->getPluginConfiguration()->data->jsapiShopCatalogTree($selectedCategoryID);
        $categories = $this->getCustomer()->fetch($config);
        $map = array();
        foreach ($categories as $key => $value)
            $map[$value['ID']] = $value;

        $tree = getTree($map);

        return $tree;
    }

    public function get (&$resp, $req) {
        if (isset($req->get['browse'])) {
            $resp = $this->getCatalogBrowse($req->get);
        } else if (isset($req->get['tree'])) {
            $resp = $this->getCatalogTree();
        } else if (empty($req->get['id'])) {
            $resp = $this->getCategories_List($req->get);
        } else {
            $CategoryID = intval($req->get['id']);
            $resp = $this->getCategoryByID($CategoryID);
        }
    }

    public function post (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createCategory($req->data);
        // $this->_getOrSetCachedState('changed:category', true);
    }

    public function patch (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $CategoryID = intval($req->get['id']);
            $resp = $this->updateCategory($CategoryID, $req->data);
            // $this->_getOrSetCachedState('changed:category', true);
        }
    }

    public function delete (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $CategoryID = intval($req->get['id']);
            $resp = $this->disableCategory($CategoryID);
            // $this->_getOrSetCachedState('changed:category', true);
        }
    }

}

?>
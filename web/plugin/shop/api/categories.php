<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\request as Request;
use \engine\lib\utils as Utils;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class categories {

    private $_statuses = array('ACTIVE', 'REMOVED');

    // -----------------------------------------------
    // -----------------------------------------------
    // CATEGORIES
    // -----------------------------------------------
    // -----------------------------------------------
    private function __adjustCategory (&$category) {
        global $app;
        $category['ID'] = intval($category['ID']);
        $category['ParentID'] = is_null($category['ParentID']) ? null : intval($category['ParentID']);
        $category['_isRemoved'] = $category['Status'] === 'REMOVED';
        $category['_location'] = $this->getCategoryLocationByCategoryID($category['ID']);
        return $category;
    }

    public function getCategoryByID ($categoryID) {
        global $app;
        if (empty($categoryID) || !is_numeric($categoryID))
            return null;
        $config = shared::jsapiShopGetCategoryItem($categoryID);
        $category = $app->getDB()->query($config);
        if (empty($category))
            return null;
        return $this->__adjustCategory($category);
    }

    public function getCategoryByName ($categoryName) {
        global $app;
        $config = shared::jsapiShopGetCategoryItem();
        $config['condition']['Name'] = shared::createCondition($categoryName);
        $category = $app->getDB()->query($config);
        if (empty($category))
            return null;
        return $this->__adjustCategory($category);
    }

    public function getCategoryByExternalKey ($externalKey) {
        global $app;
        $config = shared::jsapiShopGetCategoryItem();
        $config['condition']['ExternalKey'] = shared::createCondition($externalKey);
        $category = $app->getDB()->query($config);
        if (empty($category))
            return null;
        return $this->__adjustCategory($category);
    }

    public function getCategories_List (array $options = array()) {
        global $app;
        $config = shared::jsapiShopGetCategoryList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
        global $app;
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getCategoryByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $app->getDB()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function createCategory ($reqData) {
        global $app;
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

                $app->getDB()->beginTransaction();

                $validatedValues["CustomerID"] = $this->getCustomer()->getCustomerID();

                $configCreateCategory = shared::jsapiShopCreateCategory($validatedValues);
                $CategoryID = $app->getDB()->query($configCreateCategory) ?: null;

                if (empty($CategoryID))
                    throw new Exception('CategoryCreateError');

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
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
        global $app;
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

                $app->getDB()->beginTransaction();

                $configCreateCategory = shared::jsapiShopUpdateCategory($CategoryID, $validatedValues);
                $app->getDB()->query($configCreateCategory);

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
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
        global $app;
        $errors = array();
        $success = false;

        try {
            $app->getDB()->beginTransaction();

            $config = shared::jsapiShopDeleteCategory($CategoryID);
            $app->getDB()->query($config);

            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
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
        global $app;
        // var_dump($categoryID);
        $configLocation = shared::jsapiShopCategoryLocationGet($categoryID);
        $location = $app->getDB()->query($configLocation);
        foreach ($location as &$categoryItem) {
            $categoryItem['ID'] = intval($categoryItem['ID']);
        }
        return $location;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // SHOP CATALOG TREE
    // -----------------------------------------------
    // -----------------------------------------------
    public function getCatalogTree ($selectedCategoryID = false) {
        global $app;

        function getTree (array &$elements, $parentId = null) {
        global $app;
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

        $config = shared::jsapiShopCatalogTree($selectedCategoryID);
        $categories = $app->getDB()->query($config);
        $map = array();
        foreach ($categories as $key => $value)
            $map[$value['ID']] = $value;

        $tree = getTree($map);

        return $tree;
    }

    public function get (&$resp, $req) {
        global $app;
        if (isset($req->get['tree'])) {
            $resp = $this->getCatalogTree();
        } else if (empty($req->get['id'])) {
            $resp = $this->getCategories_List($req->get);
        } else {
            if (is_numeric($req->get['id'])) {
                $CategoryID = intval($req->get['id']);
                $resp = $this->getCategoryByID($CategoryID);
            } else {
                $resp = $this->getCategoryByExternalKey($req->get['id']);
            }
        }
    }

    public function post (&$resp, $req) {
        global $app;
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createCategory($req->data);
        // $this->_getOrSetCachedState('changed:category', true);
    }

    public function patch (&$resp, $req) {
        global $app;
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
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
        global $app;
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
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
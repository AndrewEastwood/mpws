<?php
namespace web\plugins\shop\api;

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

    public function getCategoryUploadInnerDir ($subDir = '') {
        $path = '';
        if (empty($subDir))
            $path = Path::createDirPath('shop', 'categories');
        else
            $path = Path::createDirPath('shop', 'categories', $subDir);
        return $path;
    }
    public function getCategoryUploadInnerImagePath ($name, $subDir = false) {
        $path = $this->getCategoryUploadInnerDir($subDir);
        return $path . $name;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // CATEGORIES
    // -----------------------------------------------
    // -----------------------------------------------
    private function __adjustCategory (&$category) {
        $categoryID = intval($category['ID']);
        $category['ID'] = $categoryID;
        $category['ParentID'] = is_null($category['ParentID']) ? null : intval($category['ParentID']);
        $category['_isRemoved'] = $category['Status'] === 'REMOVED';
        $category['_location'] = $this->getCategoryLocationByCategoryID($categoryID);
        if (!empty($category['Image'])) {
            $category['Image'] = array(
                'name' => $category['Image'],
                'normal' => '/' . Path::getUploadDirectory() . $this->getCategoryUploadInnerImagePath($category['Image']),
                'sm' => '/' . Path::getUploadDirectory() . $this->getCategoryUploadInnerImagePath($category['Image'], 'sm'),
                'xs' => '/' . Path::getUploadDirectory() . $this->getCategoryUploadInnerImagePath($category['Image'], 'xs')
            );
        }
        return $category;
    }

    public function getCategoryByID ($categoryID) {
        global $app;
        if (empty($categoryID) || !is_numeric($categoryID))
            return null;
        $config = dbquery::shopGetCategoryItem($categoryID);
        $category = $app->getDB()->query($config);
        if (empty($category))
            return null;
        return $this->__adjustCategory($category);
    }

    public function getCategoryByName ($categoryName) {
        global $app;
        $config = dbquery::shopGetCategoryItem();
        $config['condition']['Name'] = $app->getDB()->createCondition($categoryName);
        $category = $app->getDB()->query($config);
        if (empty($category))
            return null;
        return $this->__adjustCategory($category);
    }

    public function getCategoryByExternalKey ($externalKey) {
        global $app;
        $config = dbquery::shopGetCategoryItem();
        $config['condition']['ExternalKey'] = $app->getDB()->createCondition($externalKey);
        $category = $app->getDB()->query($config);
        if (empty($category))
            return null;
        return $this->__adjustCategory($category);
    }

    public function getCategories_List (array $options = array()) {
        global $app;
        $config = dbquery::shopGetCategoryList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
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
            'Description' => array('string', 'skipIfUnset', 'max' => 5000),
            'file1' => array('string', 'skipIfEmpty')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $app->getDB()->beginTransaction();

                $validatedValues["CustomerID"] = $app->getSite()->getRuntimeCustomerID();

                if (!empty($validatedValues['file1'])) {
                    $newFileName = uniqid(time());
                    $fileName = $validatedValues['file1'];
                    $smImagePath = 'sm' . Path::getDirectorySeparator() . $fileName;
                    $xsImagePath = 'xs' . Path::getDirectorySeparator() . $fileName;
                    $normalImagePath = $fileName;
                    $uploadInfo = Path::moveTemporaryFile($smImagePath, $this->getCategoryUploadInnerDir('sm'), $newFileName);
                    $uploadInfo = Path::moveTemporaryFile($xsImagePath, $this->getCategoryUploadInnerDir('xs'), $newFileName);
                    $uploadInfo = Path::moveTemporaryFile($normalImagePath, $this->getCategoryUploadInnerDir(), $newFileName);
                    $validatedValues['Image'] = $uploadInfo['filename'];
                }
                unset($validatedValues['file1']);

                $configCreateCategory = dbquery::shopCreateCategory($validatedValues);
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
            'Status' => array('string', 'skipIfUnset'),
            'file1' => array('string', 'skipIfEmpty')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                if (isset($reqData['file1'])) {
                    $category = $this->getCategoryByID($CategoryID);

                    $currentFileName = empty($category['Image']) ? "" : $category['Image']['name'];
                    $newFileName = null;

                    if (!empty($validatedValues['file1'])) {
                        $newFileName = $validatedValues['file1'];
                        unset($validatedValues['file1']);
                    }

                    if ($newFileName !== $currentFileName) {
                        if (empty($newFileName) && !empty($currentFileName)) {
                            Path::deleteUploadedFile($this->getCategoryUploadInnerImagePath($currentFileName, 'sm'));
                            Path::deleteUploadedFile($this->getCategoryUploadInnerImagePath($currentFileName, 'xs'));
                            Path::deleteUploadedFile($this->getCategoryUploadInnerImagePath($currentFileName));
                            $validatedValues['Image'] = null;
                        }
                        if (!empty($newFileName)) {
                            $currentFileName = $newFileName;
                            $newFileName = uniqid(time());
                            $smImagePath = 'sm' . Path::getDirectorySeparator() . $currentFileName;
                            $xsImagePath = 'xs' . Path::getDirectorySeparator() . $currentFileName;
                            $normalImagePath = $currentFileName;
                            $uploadInfo = Path::moveTemporaryFile($smImagePath, $this->getCategoryUploadInnerDir('sm'), $newFileName);
                            $uploadInfo = Path::moveTemporaryFile($xsImagePath, $this->getCategoryUploadInnerDir('xs'), $newFileName);
                            $uploadInfo = Path::moveTemporaryFile($normalImagePath, $this->getCategoryUploadInnerDir(), $newFileName);
                            $validatedValues['Image'] = $uploadInfo['filename'];
                        }
                    }
                }

                $app->getDB()->beginTransaction();

                $configCreateCategory = dbquery::shopUpdateCategory($CategoryID, $validatedValues);
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

            $config = dbquery::shopDeleteCategory($CategoryID);
            $app->getDB()->query($config);

            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = 'CategoryDeleteError';
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
        $configLocation = dbquery::shopCategoryLocationGet($categoryID);
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

        $config = dbquery::shopCatalogTree($selectedCategoryID);
        $categories = $app->getDB()->query($config);
        $map = array();
        foreach ($categories as $key => $value)
            $map[$value['ID']] = $this->__adjustCategory($value);

        $tree = getTree($map);

        return $tree;
    }

    public function get (&$resp, $req) {
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
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createCategory($req->data);
        // $this->_getOrSetCachedState('changed:category', true);
    }

    public function put (&$resp, $req) {
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
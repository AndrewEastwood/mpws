<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\request as Request;
use \engine\lib\utils as Utils;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class categories extends API {

    // private $_statuses = array('ACTIVE', 'REMOVED');


    // -----------------------------------------------
    // -----------------------------------------------
    // CATEGORIES
    // -----------------------------------------------
    // -----------------------------------------------
    // private function __adjustCategory (&$category) {
    // }

    // public function fetchCategoryByID ($categoryID) {
    //     global $app;
    //     if (empty($categoryID) || !is_numeric($categoryID))
    //         return null;
    //     $config = $this->data->fetchCategoryByID($categoryID);
    //     $category = $app->getDB()->query($config);
    //     if (empty($category))
    //         return null;
    //     return $this->__adjustCategory($category);
    // }

    // public function getCategoryByName ($categoryName) {
    //     global $app;
    //     $config = $this->data->fetchCategoryByID();
    //     $config['condition']['Name'] = $app->getDB()->createCondition($categoryName);
    //     $category = $app->getDB()->query($config);
    //     if (empty($category))
    //         return null;
    //     return $this->__adjustCategory($category);
    // }

    // public function getCategoryByExternalKey ($externalKey) {
    //     global $app;
    //     $config = $this->data->fetchCategoryByID();
    //     $config['condition']['ExternalKey'] = $app->getDB()->createCondition($externalKey);
    //     $category = $app->getDB()->query($config);
    //     if (empty($category))
    //         return null;
    //     return $this->__adjustCategory($category);
    // }

    // public function getCategories_List (array $options = array()) {
    //     global $app;
    //     $config = $this->data->fetchCategoryDataList($options);
    //     $self = $this;
    //     $callbacks = array(
    //         "parse" => function ($items) use($self) {
    //             $_items = array();
    //             foreach ($items as $val)
    //                 $_items[] = $self->fetchCategoryByID($val['ID']);
    //             return $_items;
    //         }
    //     );
    //     $dataList = $app->getDB()->queryMatchedIDs($config, $options, $callbacks);
    //     return $dataList;
    // }

    public function createCategory ($reqData) {
        global $app;
        // $result = array();
        // $errors = array();
        // $success = false;
        // $CategoryID = null;
        $r = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 200),
            'ParentID' => array('int', 'skipIfUnset'),
            'Description' => array('string', 'skipIfUnset', 'max' => 5000),
            'file1' => array('string', 'skipIfUnset')
        ));

        if ($validatedDataObj->errorsCount == 0) {
            // try {

                $validatedValues = $validatedDataObj->validData;

                // $app->getDB()->beginTransaction();

                $validatedValues["CustomerID"] = $app->getSite()->getRuntimeCustomerID();

                if (!empty($validatedValues['file1'])) {
                    $newFileName = uniqid(time());
                    $fileName = $validatedValues['file1'];
                    $mdImagePath = 'md' . Path::getDirectorySeparator() . $fileName;
                    $smImagePath = 'sm' . Path::getDirectorySeparator() . $fileName;
                    $xsImagePath = 'xs' . Path::getDirectorySeparator() . $fileName;
                    $microImagePath = 'micro' . Path::getDirectorySeparator() . $fileName;
                    $normalImagePath = $fileName;
                    $uploadInfo = Path::moveTemporaryFile($mdImagePath, $this->data->getCategoryUploadInnerDir('md'), $newFileName);
                    $uploadInfo = Path::moveTemporaryFile($smImagePath, $this->data->getCategoryUploadInnerDir('sm'), $newFileName);
                    $uploadInfo = Path::moveTemporaryFile($xsImagePath, $this->data->getCategoryUploadInnerDir('xs'), $newFileName);
                    $uploadInfo = Path::moveTemporaryFile($microImagePath, $this->data->getCategoryUploadInnerDir('micro'), $newFileName);
                    $uploadInfo = Path::moveTemporaryFile($normalImagePath, $this->data->getCategoryUploadInnerDir(), $newFileName);
                    $validatedValues['Image'] = $uploadInfo['filename'];
                }
                unset($validatedValues['file1']);

                $r = $this->data->createCategory($validatedValues);
                // $CategoryID = $app->getDB()->query($configCreateCategory) ?: null;
                if ($r->isEmptyResult()) {
                    $r->addError("CategoryCreateError");
                    // throw new Exception('CustomerCreateError');
                }
                // if ($r->isEmptyResult()) {
                //     throw new Exception('CategoryCreateError');
                // }

                // $app->getDB()->commit();

                // $success = true;
            // } catch (Exception $e) {
            //     // $app->getDB()->rollBack();
            //     // $errors[] = $e->getMessage();
            // }
        } else {
            $r->addErrors($validatedDataObj->errorMessages);
        }

        if ($r->isSuccess() && $r->hasResult()) {
            $category = $this->data->fetchCategoryByID($r->getResult());
            $r->setResult($category);
        // }
        // if ($success && !empty($CategoryID))
        //     $result = $this->fetchCategoryByID($CategoryID);
        // $result['errors'] = $errors;
        // $result['success'] = $success;

        return $r->toArray();
    }

    public function updateCategory ($CategoryID, $reqData) {
        global $app;
        // $result = array();
        // $errors = array();
        // $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'skipIfUnset', 'min' => 1, 'max' => 200),
            'Description' => array('string', 'skipIfUnset', 'max' => 5000),
            'ParentID' => array('int', 'null', 'skipIfUnset'),
            'Status' => array('string', 'skipIfUnset'),
            'file1' => array('string', 'skipIfEmpty')
        ));

        if ($validatedDataObj->errorsCount == 0) {
            // try {

                $validatedValues = $validatedDataObj->validData;

                if (isset($reqData['file1'])) {
                    $category = $this->data->fetchCategoryByID($CategoryID);

                    $currentFileName = empty($category['Image']) ? "" : $category['Image']['name'];
                    $newFileName = null;

                    if (!empty($validatedValues['file1'])) {
                        $newFileName = $validatedValues['file1'];
                    }

                    if ($newFileName !== $currentFileName) {
                        if (empty($newFileName) && !empty($currentFileName)) {
                            Path::deleteUploadedFile($this->data->getCategoryUploadInnerImagePath($currentFileName, 'sm'));
                            Path::deleteUploadedFile($this->data->getCategoryUploadInnerImagePath($currentFileName, 'xs'));
                            Path::deleteUploadedFile($this->data->getCategoryUploadInnerImagePath($currentFileName));
                            $validatedValues['Image'] = null;
                        }
                        if (!empty($newFileName)) {
                            $currentFileName = $newFileName;
                            $newFileName = uniqid(time());
                            $mdImagePath = 'md' . Path::getDirectorySeparator() . $currentFileName;
                            $smImagePath = 'sm' . Path::getDirectorySeparator() . $currentFileName;
                            $xsImagePath = 'xs' . Path::getDirectorySeparator() . $currentFileName;
                            $microImagePath = 'micro' . Path::getDirectorySeparator() . $currentFileName;
                            $normalImagePath = $currentFileName;
                            $uploadInfo = Path::moveTemporaryFile($mdImagePath, $this->data->getCategoryUploadInnerDir('md'), $newFileName);
                            $uploadInfo = Path::moveTemporaryFile($smImagePath, $this->data->getCategoryUploadInnerDir('sm'), $newFileName);
                            $uploadInfo = Path::moveTemporaryFile($xsImagePath, $this->data->getCategoryUploadInnerDir('xs'), $newFileName);
                            $uploadInfo = Path::moveTemporaryFile($microImagePath, $this->data->getCategoryUploadInnerDir('micro'), $newFileName);
                            $uploadInfo = Path::moveTemporaryFile($normalImagePath, $this->data->getCategoryUploadInnerDir(), $newFileName);
                            $validatedValues['Image'] = $uploadInfo['filename'];
                        }
                    }
                }
                if (array_key_exists('file1', $validatedValues)) {
                    unset($validatedValues['file1']);
                }

                // $app->getDB()->beginTransaction();

                $this->data->updateCategory($CategoryID, $validatedValues);
                // $configCreateCategory = $this->data->updateCategory($CategoryID, $validatedValues);
                // $app->getDB()->query($configCreateCategory);

                // $app->getDB()->commit();

                // $success = true;
            // } catch (Exception $e) {
            //     $app->getDB()->rollBack();
            //     $errors[] = $e->getMessage();
            // }
        } else {
            $r->addErrors($validatedDataObj->errorMessages);
        }

        $category = $this->data->fetchCategoryByID($CategoryID);
        $r->setResult($category);

        return $r->toArray();
        // $result = $this->fetchCategoryByID($CategoryID);
        // $result['errors'] = $errors;
        // $result['success'] = $success;

        // return $result;
    }

    public function disableCategory ($CategoryID) {
        global $app;

        $r = null;

        if ($validatedDataObj->errorsCount == 0) {
            $r = $this->data->deleteCategory($CategoryID);
        } else {
            $r->addErrors($validatedDataObj->errorMessages);
        }

        if ($r->hasResult()) {
            $item = $this->data->fetchCategoryByID($CategoryID);
            $r->setResult($item);
        }

        return $r->toArray();
        // global $app;
        // $errors = array();
        // $success = false;

        // try {
        //     $app->getDB()->beginTransaction();

        //     $config = $this->data->deleteCategory($CategoryID);
        //     $app->getDB()->query($config);

        //     $app->getDB()->commit();

        //     $success = true;
        // } catch (Exception $e) {
        //     $app->getDB()->rollBack();
        //     $errors[] = 'CategoryDeleteError';
        // }

        // $result = $this->fetchCategoryByID($CategoryID);
        // $result['errors'] = $errors;
        // $result['success'] = $success;
        // return $result;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // CATEGORY BREADCRUMB
    // -----------------------------------------------
    // -----------------------------------------------
    // public function getCategoryLocationByCategoryID ($categoryID) {
    //     global $app;
    //     // var_dump($categoryID);
    //     $configLocation = $this->data->fetchCategoryLocation($categoryID);
    //     $location = $app->getDB()->query($configLocation);
    //     foreach ($location as &$categoryItem) {
    //         $categoryItem['ID'] = intval($categoryItem['ID']);
    //     }
    //     return $location;
    // }

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
                    $element['SubIDs'] = array_keys($element['childNodes']);
                    if ($parentId !== null) {
                        $elements[$parentId]['SubIDs'] += array_keys($element['childNodes']);
                    }
                    $branch[$key] = $element;
                    // unset($elements[$key]);
                }
            }
            // echo PHP_EOL . "-=-=-=-=-=-=--=--Results for element where parentid ==", $parentId. PHP_EOL;
            // var_dump($branch);
            return $branch;
        }

        $categories = $this->data->fetchCatalogTreeDict($selectedCategoryID);
        // $categories = $app->getDB()->query($config);
        // $map = array();
        // if (!empty($categories))
        //     foreach ($categories as $key => $value)
        //         $map[$value['ID']] = $this->__adjustCategory($value);

        // $tree = getTree($map);
        $tree = getTree($categories);

        return $tree;
    }

    public function get ($req, $resp) {
        // for specific customer item
        // by id
        if (Request::hasRequestedID()) {
            $resp->setResponse($this->data->fetchCategoryByID($req->id));
            return;
        }
        // or by ExternalKey
        if (Request::hasRequestedExternalKey()) {
            $resp->setResponse($this->data->fetchCategoryByExternalKey($req->externalKey));
            return;
        }

        // additional params
        if (isset($req->get['tree'])) {
            $resp->setResponse($this->getCatalogTree());
            return;
        } 

        // for the case when we have to fecth list with customers
        if (Request::noRequestedItem()) {
            $resp->setResponse($this->data->fetchCategoryDataList($req->get));
            return;
        }


        //  if (empty($req->id)) {
        //     $resp->setResponse($this->data->fetchCategoryDataList($req->get));
        // } else {
        //     if (is_numeric($req->id)) {
        //         $CategoryID = intval($req->id);
        //         $resp->setResponse($this->fetchCategoryByID($CategoryID));
        //     } else {
        //         $resp->setResponse($this->getCategoryByExternalKey($req->id));
        //     }
        // }
    }

    public function post ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create'))) {
            return $resp->setAccessError();
            return;
        }
        $resp->setResponse($this->createCategory($req->data));
        // $this->_getOrSetCachedState('changed:category', true);
    }

    public function put ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit'))) {
            return $resp->setAccessError();
            return;
        }
        // for specific customer item
        // by id
        if (Request::hasRequestedID()) {
            $resp->setResponse($this->updateCategory($req->id, $req->data));
            return;
        }
        // if (empty($req->id)) {
        //     $resp->setWrongItemIdError();
        // } else {
        //     $CategoryID = intval($req->id);
        //     $resp->setResponse($this->updateCategory($CategoryID, $req->data));
        //     // $this->_getOrSetCachedState('changed:category', true);
        // }
        $resp->setWrongItemIdError();
    }

    public function delete ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit'))) {
            return $resp->setAccessError();
            return;
        }
        // for specific customer item
        // by id
        if (Request::hasRequestedID()) {
            $resp->setResponse($this->disableCategory($req->id));
            return;
        }
        // if (empty($req->id)) {
        //     $resp->setWrongItemIdError();
        // } else {
        //     $CategoryID = intval($req->id);
        //     $resp->setResponse($this->disableCategory($CategoryID));
        //     // $this->_getOrSetCachedState('changed:category', true);
        // }
        $resp->setWrongItemIdError();
    }

}

?>
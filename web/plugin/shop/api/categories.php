<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\request as Request;
use Exception;
use ArrayObject;

class categories extends \engine\objects\api {

    private $_statuses = array('ACTIVE', 'REMOVED');
    // -----------------------------------------------
    // -----------------------------------------------
    // CATEGORIES
    // -----------------------------------------------
    // -----------------------------------------------
    public function getCategoryByID ($categoryID) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetCategoryItem($categoryID);
        $category = $this->getCustomer()->fetch($config);
        if (empty($category))
            return null;
        $category['ID'] = intval($category['ID']);
        $category['ParentID'] = is_null($category['ParentID']) ? null : intval($category['ParentID']);
        $category['_isRemoved'] = $category['Status'] === 'REMOVED';
        $category['_location'] = $this->getCategoryLocation($categoryID);
        return $category;
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
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 100),
            'ParentID' => array('int', 'skipIfUnset'),
            'Description' => array('string', 'skipIfUnset', 'max' => 300)
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
            'Name' => array('string', 'skipIfUnset', 'min' => 1, 'max' => 100),
            'Description' => array('string', 'skipIfUnset', 'max' => 300),
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

    public function getCategoryLocation ($categoryID) {
        var_dump($categoryID);
        $configLocation = $this->getPluginConfiguration()->data->jsapiShopCategoryLocationGet($categoryID);
        $location = $this->getCustomer()->fetch($configLocation);
        return $location;
    }


    // -----------------------------------------------
    // -----------------------------------------------
    // BREADCRUMB
    // -----------------------------------------------
    // -----------------------------------------------


    // -----------------------------------------------
    // -----------------------------------------------
    // SHOP CATALOG TREE
    // -----------------------------------------------
    // -----------------------------------------------
    public function getCatalogTree () {

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

        $config = $this->getPluginConfiguration()->data->jsapiShopCatalogTree();
        $categories = $this->getCustomer()->fetch($config);
        $map = array();
        foreach ($categories as $key => $value)
            $map[$value['ID']] = $value;

        $tree = getTree($map);

        return $tree;
    }

    public function getCatalogBrowse () {

        $data = array();
        $categoryID = Request::fromGET('id', null);

        if (!is_numeric($categoryID)) {
            $data['error'] = '"id" parameter is missed';
            return $data;
        }

        $categoryID = intval($categoryID);

        $filterOptions = array(
            /* common options */
            "id" => $categoryID,
            "filter_viewSortBy" => null,
            "filter_viewItemsOnPage" => 16,
            "filter_viewPageNum" => 1,
            "filter_commonPriceMax" => null,
            "filter_commonPriceMin" => 0,
            "filter_commonStatus" => array(),
            "filter_commonFeatures" => array(),

            /* category based */
            "filter_categoryBrands" => array(),
            "filter_categorySubCategories" => array()
        );

        // filtering
        $filterOptionsApplied = new ArrayObject($filterOptions);
        $filterOptionsAvailable = new ArrayObject($filterOptions);

        // get all product available statuses
        $filterOptionsAvailable['filter_commonStatus'] = $this->getAPI()->products->getProductStatuses();

        // init filter
        foreach ($filterOptionsApplied as $key => $value) {
            $filterOptionsApplied[$key] = Request::fromGET($key, $filterOptions[$key]);
            if ($key == "filter_viewItemsOnPage" || $key == "filter_viewPageNum")
                $filterOptionsApplied[$key] = intval($filterOptionsApplied[$key]);
            if ($key === "filter_commonPriceMax" || $key == "filter_commonPriceMin")
                $filterOptionsApplied[$key] = floatval($filterOptionsApplied[$key]);
            if (is_string($filterOptionsApplied[$key])) {
                if ($key == "filter_commonStatus" || $key == "filter_categoryBrands")
                    $filterOptionsApplied[$key] = explode(',', $filterOptionsApplied[$key]);
                if ($key == "filter_categorySubCategories" || $key == "filter_commonFeatures") {
                    $IDs = explode(',', $filterOptionsApplied[$key]);
                    $filterOptionsApplied[$key] = array();
                    foreach ($IDs as $filterOptionID) {
                        $filterOptionsApplied[$key][] = intval($filterOptionID);
                    }
                }
            }
            // var_dump($filterOptionsApplied[$key]);
        }

        // var_dump($filterOptionsApplied['filter_commonFeatures']);

        $dataConfigCategoryPriceEdges = $this->getPluginConfiguration()->data->jsapiShopCategoryPriceEdgesGet($categoryID);
        $dataConfigCategoryAllSubCategories = $this->getPluginConfiguration()->data->jsapiShopCategoryAllSubCategoriesGet($categoryID);

        // get category sub-categories and origins
        $dataCategoryPriceEdges = $this->getCustomer()->fetch($dataConfigCategoryPriceEdges);
        $dataCategoryAllSubCategories = $this->getCustomer()->fetch($dataConfigCategoryAllSubCategories);

        $cetagorySubIDs = array($categoryID);
        if (!empty($dataCategoryAllSubCategories))
            foreach ($dataCategoryAllSubCategories as $value)
                $cetagorySubIDs[] = $value['ID'];

        //filter: get category price edges
        $filterOptionsAvailable['filter_commonPriceMax'] = floatval($dataCategoryPriceEdges['PriceMax'] ?: 0) + 10;
        $filterOptionsAvailable['filter_commonPriceMin'] = floatval($dataCategoryPriceEdges['PriceMin'] ?: 0) - 10;
        if ($filterOptionsAvailable['filter_commonPriceMin'] < 0) {
            $filterOptionsAvailable['filter_commonPriceMin'] = 0;
        }

        // get all brands for both current category and sub-categories
        $dataConfigCategoryAllBrands = $this->getPluginConfiguration()->data->jsapiShopCategoryAndSubCategoriesAllBrandsGet(implode(',', $cetagorySubIDs));
        $dataCategoryAllBrands = $this->getCustomer()->fetch($dataConfigCategoryAllBrands);

        // set categories and brands
        $filterOptionsAvailable['filter_categoryBrands'] = $dataCategoryAllBrands ?: array();
        $filterOptionsAvailable['filter_categorySubCategories'] = $dataCategoryAllSubCategories ?: array();

        // set data source
        // ---
        $dataConfigCategoryInfo = $this->getPluginConfiguration()->data->jsapiGetShopCategoryProductInfo($cetagorySubIDs);
        $dataConfigProducts = $this->getPluginConfiguration()->data->jsapiGetShopCategoryProductList($cetagorySubIDs);

        // filter: display intems count
        if (!empty($filterOptionsApplied['filter_viewItemsOnPage']))
            $dataConfigProducts['limit'] = $filterOptionsApplied['filter_viewItemsOnPage'];
        else
            $filterOptionsApplied['filter_viewItemsOnPage'] = $dataConfigProducts['limit'];

        if (!empty($filterOptionsApplied['filter_viewPageNum']))
            $dataConfigProducts['offset'] = ($filterOptionsApplied['filter_viewPageNum'] - 1) * $dataConfigProducts['limit'];
        else
            $filterOptionsApplied['filter_viewPageNum'] = $filterOptionsAvailable['filter_viewPageNum'];

        // filter: items sorting
        $_filterSorting = explode('_', strtolower($filterOptionsApplied['filter_viewSortBy']));
        if (count($_filterSorting) === 2 && !empty($_filterSorting[0]) && ($_filterSorting[1] === 'asc' || $_filterSorting[1] === 'desc'))
            $dataConfigProducts['order'] = array('field' => $dataConfigProducts['source'] . '.' . ucfirst($_filterSorting[0]), 'ordering' => strtoupper($_filterSorting[1]));
        else
            $filterOptionsApplied['filter_viewSortBy'] = null;

        // filter: price 
        if ($filterOptionsApplied['filter_commonPriceMax'] > $filterOptionsApplied['filter_commonPriceMin'] && $filterOptionsApplied['filter_commonPriceMax'] <= $filterOptionsAvailable['filter_commonPriceMax'])
            $dataConfigProducts['condition']['Price'][] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($filterOptionsApplied['filter_commonPriceMax'], '<=');
        else
            $filterOptionsApplied['filter_commonPriceMax'] = $filterOptionsAvailable['filter_commonPriceMax'];

        if ($filterOptionsApplied['filter_commonPriceMax'] > $filterOptionsApplied['filter_commonPriceMin'] && $filterOptionsApplied['filter_commonPriceMin'] >= $filterOptionsAvailable['filter_commonPriceMin'])
            $dataConfigProducts['condition']['Price'][] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($filterOptionsApplied['filter_commonPriceMin'], '>=');
        else
            $filterOptionsApplied['filter_commonPriceMin'] = $filterOptionsAvailable['filter_commonPriceMin'];

        // var_dump($filterOptionsApplied);
        if (count($filterOptionsApplied['filter_commonFeatures']))
            $dataConfigProducts['condition']["FeatureID"] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($filterOptionsApplied['filter_commonFeatures'], 'in');

        if (count($filterOptionsApplied['filter_commonStatus']))
            $dataConfigProducts['condition']["shop_products.Status"] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($filterOptionsApplied['filter_commonStatus'], 'in');

        // filter: brands
        if (count($filterOptionsApplied['filter_categoryBrands']))
            $dataConfigProducts['condition']['OriginID'] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($filterOptionsApplied['filter_categoryBrands'], 'in');

        // var_dump($dataConfigProducts);
        // get products
        $dataProducts = $this->getCustomer()->fetch($dataConfigProducts);
        // get category info according to product filter
        if (isset($dataConfigProducts['condition']['Price']))
            $dataConfigCategoryInfo['condition']['Price'] = $dataConfigProducts['condition']['Price'];
        // $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
        $dataCategoryInfo = $this->getCustomer()->fetch($dataConfigCategoryInfo);

        // TODO smth with this
        $products = array();
        if (!empty($dataProducts))
            foreach ($dataProducts as $val)
                $products[] = $this->getAPI()->products->getProductByID($val['ID']);

        $productsInfo = array();
        if (!empty($dataCategoryInfo))
            foreach ($dataCategoryInfo as $val)
                $productsInfo[] = $this->getAPI()->products->getProductByID($val['ID']);

        // adjust brands, categories and features
        $brands = array();
        $categories = array();
        $statuses = array();//$this->getCustomerDataBase()->getTableStatusFieldOptions($this->getPluginConfiguration()->data->Table_ShopProducts);
        $features = array();
        foreach ($filterOptionsAvailable['filter_categoryBrands'] as $brand) {
            $brands[$brand['ID']] = $brand;
            $brands[$brand['ID']]['ProductCount'] = 0;
        }
        foreach ($filterOptionsAvailable['filter_categorySubCategories'] as $category) {
            $categories[$category['ID']] = $category;
            $categories[$category['ID']]['ProductCount'] = 0;
        }
        foreach ($filterOptionsAvailable['filter_commonStatus'] as $status) {
            $statuses[$status]['ID'] = $status;
            $statuses[$status]['ProductCount'] = 0;
        }

        if ($productsInfo)
            foreach ($productsInfo as $obj) {
                $OriginID = $obj['OriginID'];
                $CategoryID = $obj['CategoryID'];
                $status = $obj['Status'];
                if (isset($statuses[$status]))
                    $statuses[$status]['ProductCount']++;
                if (isset($brands[$OriginID]))
                    $brands[$OriginID]['ProductCount']++;
                if (isset($categories[$CategoryID]))
                    $categories[$CategoryID]['ProductCount']++;
                foreach ($obj['Features'] as $featureGroup => $featureList) {
                    if (!isset($features[$featureGroup])) {
                        $features[$featureGroup] = array();
                    }
                    foreach ($featureList as $key => $featureName) {
                        if (!isset($features[$featureGroup][$key]['Count'])) {
                            $features[$featureGroup][$key] = array(
                                'Name' => $featureName,
                                'Count' => 1,
                                'ID' => $key
                            );
                        }
                        else {
                            // $features[$featureGroup][$key]['Name'] = $featureName
                            $features[$featureGroup][$key]['Count']++;
                            // $features[$featureGroup][$key]['ID'] = $featureID;
                        }
                    }
                }
            }

        $filterOptionsAvailable['filter_categoryBrands'] = $brands;
        $filterOptionsAvailable['filter_categorySubCategories'] = $categories;
        $filterOptionsAvailable['filter_commonStatus'] = $statuses;
        $filterOptionsAvailable['filter_commonFeatures'] = $features;

        // store data
        $data['items'] = $products;
        $data['filter'] = array(
            'filterOptionsAvailable' => $filterOptionsAvailable,
            'filterOptionsApplied' => $filterOptionsApplied,
            'info' => array(
                "count" => count($dataCategoryInfo)
            )
        );
        $data['_location'] = $this->getCategoryLocation($categoryID);
        // return data object
        return $data;
    }


    public function get (&$resp, $req) {
        if (isset($req->get['browse'])) {
            $resp = $this->getCatalogBrowse();
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
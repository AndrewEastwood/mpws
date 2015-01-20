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

class catalog {

    private function getCategoriesFromCategoryTree ($categoryTree, $selectedCategoryID, &$list = array(), $inSelectedNode = false) {
        foreach ($categoryTree as $key => $node) {
            $this->getCategoriesFromCategoryTree($node['childNodes'], $selectedCategoryID, $list, $key === $selectedCategoryID);
            if ($inSelectedNode || $key === $selectedCategoryID) {
                $list[] = $node;
            }
        }
        return $list;
    }

    public function getUniqueProductsCount ($productItems) {
        return count($this->getUniqueProductsIDs($productItems));
    }
    public function getUniqueProductsIDs ($productItems) {
        $currentProductsIDs = array();
        $productItems = $productItems ?: array();
        foreach ($productItems as $value) {
            $currentProductsIDs[] = intval($value['ID']);
        }
        $currentProductsIDs = array_unique($currentProductsIDs);
        return $currentProductsIDs;
    }

    public function getCatalogBrowse ($categoryID) {
        global $app;
        $data = array();
        $filterOptions = array(
            /* common options */
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
        $filterOptionsAvailable['filter_commonStatus'] = API::getAPI('shop:products')->getProductStatuses();

        // init filter
        foreach ($filterOptionsApplied as $key => $value) {
            $filterOptionsApplied[$key] = Request::pickFromGET($key, $filterOptions[$key]);
            if ($key == "filter_viewItemsOnPage" || $key == "filter_viewPageNum")
                $filterOptionsApplied[$key] = intval($filterOptionsApplied[$key]);
            if ($key === "filter_commonPriceMax" || $key == "filter_commonPriceMin")
                $filterOptionsApplied[$key] = floatval($filterOptionsApplied[$key]);
            if (is_string($filterOptionsApplied[$key])) {
                if ($key == "filter_commonStatus")
                    $filterOptionsApplied[$key] = explode(',', $filterOptionsApplied[$key]);
                if ($key == "filter_categorySubCategories" || $key == "filter_commonFeatures" || $key == "filter_categoryBrands") {
                    $IDs = explode(',', $filterOptionsApplied[$key]);
                    $filterOptionsApplied[$key] = array();
                    foreach ($IDs as $filterOptionID) {
                        $filterOptionsApplied[$key][] = intval($filterOptionID);
                    }
                }
            }
            // var_dump($filterOptionsApplied[$key]);
        }
        // var_dump($filterOptionsApplied);

        $filterOptionsApplied["id"] = $categoryID;
        $filterOptionsAvailable["id"] = $categoryID;

        // var_dump($filterOptionsApplied['filter_commonFeatures']);


        $activeTree = API::getAPI('shop:categories')->getCatalogTree();
        $cetegories = $this->getCategoriesFromCategoryTree($activeTree, $categoryID);

        if (empty($cetegories)) {
            header("HTTP/1.0 404 Not Found");
            die();
        }

        $cetegoriesIDs = array();
        $cetegoriesNodes = array();

        // var_dump($cetegories);

        foreach ($cetegories as $categoryItem) {
            $cetegoriesIDs[] = intval($categoryItem['ID']);
            $cetegoriesNodes[$categoryItem['ID']] = array(
                'ID' => intval($categoryItem['ID']),
                'Name' => $categoryItem['Name'],
                'ExternalKey' => $categoryItem['ExternalKey']
            );
        }

        // var_dump($activeTree);
        //filter: get category price edges
        $dataConfigCategoryPriceEdges = dbquery::getShopCatalogPriceEdges(implode(',', $cetegoriesIDs));
        $dataCategoryPriceEdges = $app->getDB()->query($dataConfigCategoryPriceEdges);
        $filterOptionsAvailable['filter_commonPriceMax'] = floatval($dataCategoryPriceEdges['PriceMax'] ?: 0) + 10;
        $filterOptionsAvailable['filter_commonPriceMin'] = floatval($dataCategoryPriceEdges['PriceMin'] ?: 0) - 10;
        if ($filterOptionsAvailable['filter_commonPriceMin'] < 0) {
            $filterOptionsAvailable['filter_commonPriceMin'] = 0;
        }
        // var_dump($dataConfigCategoryPriceEdges);

        // get all brands for both current category and sub-categories
        $dataConfigCategoryAllBrands = dbquery::shopCatalogBrands(implode(',', $cetegoriesIDs));
        $dataCategoryAllBrands = $app->getDB()->query($dataConfigCategoryAllBrands);
        if ($dataCategoryAllBrands)
            foreach ($dataCategoryAllBrands as $key => $brandItem) {
                $dataCategoryAllBrands[$key]['ID'] = intval($brandItem['ID']);
            }

        // set categories and brands
        $filterOptionsAvailable['filter_categoryBrands'] = $dataCategoryAllBrands ?: array();
        $filterOptionsAvailable['filter_categorySubCategories'] = $cetegoriesNodes ?: array();
        // var_dump($dataCategoryPriceEdges);
        // var_dump($dataCategoryAllBrands);

        // var_dump($this->getCustomerDataBase()->get_last_query());
        // return;

        // get catalog features
        $dataConfigAllMatchedProducts = dbquery::getShopCatalogProductList($cetegoriesIDs);
        $dataProductsMatches = $app->getDB()->query($dataConfigAllMatchedProducts);
        $catalogProductIDs = $this->getUniqueProductsIDs($dataProductsMatches);
        foreach ($catalogProductIDs as $productItemID) {
            $featureItems = API::getAPI('shop:products')->getProductFeatures($productItemID);
            foreach ($featureItems as $featureGroup => $featureList) {
                if (!isset($features[$featureGroup])) {
                    $filterOptionsAvailable['filter_commonFeatures'][$featureGroup] = array();
                }
                foreach ($featureList as $key => $featureName) {
                    $filterOptionsAvailable['filter_commonFeatures'][$featureGroup][$key] = $featureName;
                }
            }
        }

        // set data source
        // ---
        $dataConfigProducts = dbquery::getShopCatalogProductList($cetegoriesIDs);

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
            $dataConfigProducts['condition']['Price'][] = $app->getDB()->createCondition($filterOptionsApplied['filter_commonPriceMax'], '<=');
        else
            $filterOptionsApplied['filter_commonPriceMax'] = $filterOptionsAvailable['filter_commonPriceMax'];

        if ($filterOptionsApplied['filter_commonPriceMax'] > $filterOptionsApplied['filter_commonPriceMin'] && $filterOptionsApplied['filter_commonPriceMin'] >= $filterOptionsAvailable['filter_commonPriceMin'])
            $dataConfigProducts['condition']['Price'][] = $app->getDB()->createCondition($filterOptionsApplied['filter_commonPriceMin'], '>=');
        else
            $filterOptionsApplied['filter_commonPriceMin'] = $filterOptionsAvailable['filter_commonPriceMin'];

        // var_dump($filterOptionsApplied);
        if (count($filterOptionsApplied['filter_commonFeatures']))
            $dataConfigProducts['condition']["FeatureID"] = $app->getDB()->createCondition($filterOptionsApplied['filter_commonFeatures'], 'in');

        if (count($filterOptionsApplied['filter_commonStatus']))
            $dataConfigProducts['condition']["shop_products.Status"] = $app->getDB()->createCondition($filterOptionsApplied['filter_commonStatus'], 'in');

        // filter: brands
        if (count($filterOptionsApplied['filter_categoryBrands']))
            $dataConfigProducts['condition']['OriginID'] = $app->getDB()->createCondition($filterOptionsApplied['filter_categoryBrands'], 'in');

        // var_dump($dataConfigProducts);
        // get products
        $dataProducts = $app->getDB()->query($dataConfigProducts);

        // get products count for current filter
        $dataConfigAllMatchedProducts = dbquery::getShopCatalogProductList($cetegoriesIDs);
        $dataConfigAllMatchedProducts['condition'] = new ArrayObject($dataConfigProducts['condition']);
        $dataProductsMatches = $app->getDB()->query($dataConfigAllMatchedProducts);
        $currentProductCount = $this->getUniqueProductsCount($dataProductsMatches);

        // var_dump($dataProductsMatches);
        // var_dump($currentProductsIDs);

        // var_dump($dataProductsTotalCount);
        // get category info according to product filter
        // if (isset($dataConfigProducts['condition']['Price']))
        //     $dataConfigCategoryInfo['condition']['Price'] = $dataConfigProducts['condition']['Price'];
        // // $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
        // $dataCategoryInfo = $app->getDB()->query($dataConfigCategoryInfo);

        // TODO smth with this
        $products = array();
        if (!empty($dataProducts)) {
            foreach ($dataProducts as $val) {
                $products[] = API::getAPI('shop:products')->getProductByID($val['ID'], false, false);
            }
        }
        // var_dump($currentProductCount);

        // $productsInfo = array();
        // if (!empty($dataCategoryInfo))
        //     foreach ($dataCategoryInfo as $val)
        //         $productsInfo[] = API::getAPI('shop:products')->getProductByID($val['ID'], false, false);

        // adjust brands, categories and features
        $brands = array();
        $categories = array();
        $statuses = array();
        $features = array();
        // var_dump($filterOptionsApplied['filter_categoryBrands']);
        foreach ($filterOptionsAvailable['filter_categoryBrands'] as $brand) {
            $dataConfigCategoryInfo = dbquery::getShopCategoryProductInfo();
            $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
            $arrValues = array($brand['ID']);
            if (!empty($filterOptionsApplied['filter_categoryBrands'])) {
                $arrValues = array_merge($filterOptionsApplied['filter_categoryBrands'], $arrValues);
            }
            $arrValues = array_unique($arrValues);
            // var_dump($arrValues);
            $dataConfigCategoryInfo['condition']['OriginID'] = $app->getDB()->createCondition($arrValues, 'IN');
            $filterData = $app->getDB()->query($dataConfigCategoryInfo);
            $count = $this->getUniqueProductsCount($filterData);
            // if ($brand['Name'] === 'SONY') {
                // var_dump($brand);
                // var_dump($filterData);
                // var_dump($dataConfigCategoryInfo);
                // var_dump($this->getCustomerDataBase()->get_last_query());
                // echo PHP_EOL;
                // echo PHP_EOL;
            // }
            // if (isset($filterData) && isset($filterData['ItemsCount'])) {
            //     $count = $filterData['ItemsCount'];
            // }
            $brands[$brand['ID']] = $brand;
            $brands[$brand['ID']]['ProductCount'] = $count;
            $brands[$brand['ID']]['Active'] = false;
            if (!empty($filterOptionsApplied['filter_categoryBrands'])) {
                $brands[$brand['ID']]['ProductCount'] -= $currentProductCount;
                $brands[$brand['ID']]['Active'] = true;
            }

            $dataConfigCategoryInfo['condition']['OriginID'] = $app->getDB()->createCondition($brand['ID']);
            $filterData = $app->getDB()->query($dataConfigCategoryInfo);
            $brands[$brand['ID']]['Total'] = $this->getUniqueProductsCount($filterData);
        }
        foreach ($filterOptionsAvailable['filter_categorySubCategories'] as $categoryID => $categoryItem) {
            // $count = 0;
            $dataConfigCategoryInfo = dbquery::getShopCategoryProductInfo();
            $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
            $arrValues = array($categoryID);
            if (!empty($filterOptionsApplied['filter_categorySubCategories'])) {
                $arrValues = array_merge($filterOptionsApplied['filter_categorySubCategories'], $arrValues);
            }
            $arrValues = array_unique($arrValues);
            // var_dump(">>> values >>>>>>");
            // var_dump($arrValues);
            $dataConfigCategoryInfo['condition']['CategoryID'] = $app->getDB()->createCondition($arrValues, 'IN');
            $filterData = $app->getDB()->query($dataConfigCategoryInfo);
            $count = $this->getUniqueProductsCount($filterData);
            // var_dump(">>>>results>>>>>>>");
            // var_dump($filterData);
            // var_dump("-=-=-=-=-=-=-=-=-=-=-=-=-");
            // if (isset($filterData) && isset($filterData['ItemsCount'])) {
            //     $count = $filterData['ItemsCount'];
            // }
            $categories[$categoryItem['ExternalKey']] = $categoryItem;
            $categories[$categoryItem['ExternalKey']]['ProductCount'] = $count;
            $categories[$categoryItem['ExternalKey']]['Active'] = false;
            if (!empty($filterOptionsApplied['filter_categorySubCategories'])) {
                $categories[$categoryItem['ExternalKey']]['ProductCount'] -= $currentProductCount;
                $categories[$categoryItem['ExternalKey']]['Active'] = true;
            }

            $dataConfigCategoryInfo['condition']['CategoryID'] = $app->getDB()->createCondition($categoryID);
            $filterData = $app->getDB()->query($dataConfigCategoryInfo);
            $categories[$categoryItem['ExternalKey']]['Total'] = $this->getUniqueProductsCount($filterData);
        }
        foreach ($filterOptionsAvailable['filter_commonStatus'] as $status) {
            // $count = 0;
            $dataConfigCategoryInfo = dbquery::getShopCategoryProductInfo();
            $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
            $arrValues = array($status);
            if (!empty($filterOptionsApplied['filter_commonStatus'])) {
                $arrValues = array_merge($filterOptionsApplied['filter_commonStatus'], $arrValues);
            }
            $arrValues = array_unique($arrValues);
            // var_dump($arrValues);
            $dataConfigCategoryInfo['condition']['shop_products.Status'] = $app->getDB()->createCondition($arrValues, 'IN');
            $filterData = $app->getDB()->query($dataConfigCategoryInfo);
            $count = $this->getUniqueProductsCount($filterData);
            // if (isset($filterData) && isset($filterData['ItemsCount'])) {
            //     $count = $filterData['ItemsCount'];
            // }
            // var_dump($filterData);
            // var_dump($dataConfigCategoryInfo);
            // var_dump('-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=');
            // var_dump('-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=');
            // var_dump('-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=');
            // var_dump('-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=');
            $statuses[$status]['ID'] = $status;
            $statuses[$status]['ProductCount'] = $count;
            $statuses[$status]['Active'] = false;
            // var_dump($filterData);
            if (!empty($filterOptionsApplied['filter_commonStatus'])) {
                $statuses[$status]['ProductCount'] -= $currentProductCount;
                $statuses[$status]['Active'] = true;
            }

            $dataConfigCategoryInfo['condition']['shop_products.Status'] = $app->getDB()->createCondition($status);
            $filterData = $app->getDB()->query($dataConfigCategoryInfo);
            $statuses[$status]['Total'] = $this->getUniqueProductsCount($filterData);
        }
        foreach ($filterOptionsAvailable['filter_commonFeatures'] as $featureGroup => $featureList) {
            $group = array();
            foreach ($featureList as $key => $featureName) {
                $dataConfigCategoryInfo = dbquery::getShopCategoryProductInfo();
                $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
                $arrValues = array($key);
                if (!empty($filterOptionsApplied['filter_commonFeatures'])) {
                    $arrValues = array_merge($filterOptionsApplied['filter_commonFeatures'], $arrValues);
                }
                $arrValues = array_unique($arrValues);
                // var_dump($arrValues);
                $dataConfigCategoryInfo['condition']['FeatureID'] = $app->getDB()->createCondition($arrValues, 'IN');
                // var_dump($dataConfigCategoryInfo);
                $filterData = $app->getDB()->query($dataConfigCategoryInfo);
                $count = $this->getUniqueProductsCount($filterData);
                $group[$key]['ID'] = $key;
                $group[$key]['ProductCount'] = $count;
                $group[$key]['Active'] = false;
                $group[$key]['Name'] = $featureName;
                // var_dump($filterData);
                if (!empty($filterOptionsApplied['filter_commonFeatures'])) {
                    $group[$key]['ProductCount'] -= $currentProductCount;
                    $group[$key]['Active'] = true;
                }
                $dataConfigCategoryInfo['condition']['FeatureID'] = $app->getDB()->createCondition(array($key), 'IN');
                $filterData = $app->getDB()->query($dataConfigCategoryInfo);
                $group[$key]['Total'] = $this->getUniqueProductsCount($filterData);
            }
            $features[$featureGroup] = $group;
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
                "count" => $currentProductCount,
                "category" => API::getAPI('shop:categories')->getCategoryByID($categoryID)
            )
        );
        $data['_location'] = API::getAPI('shop:categories')->getCategoryLocationByCategoryID($categoryID);
        // return data object
        return $data;
    }

    public function get (&$resp, $req) {
        if (isset($req->get['id'])) {
            if (is_numeric($req->get['id'])) {
                $CategoryID = intval($req->get['id']);
                $resp = $this->getCatalogBrowse($CategoryID);
            } else {
                $category = API::getAPI('shop:categories')->getCategoryByExternalKey($req->get['id']);
                if (isset($category['ID'])) {
                    $resp = $this->getCatalogBrowse($category['ID']);
                } else {
                    $resp['error'] = 'UnknownCategory';
                }
            }
        } else {
            $resp['error'] = '"id" parameter is missed';
        }
    }

}

?>
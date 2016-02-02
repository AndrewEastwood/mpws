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

class catalog extends API {

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

    private function getAllChildNodesIDs () {

    }

    public function getCatalogBrowse ($categoryID) {
        global $app;
        $data = array();
        $filterOptions = array(
            /* common options */
            "id" => $categoryID,
            "filter_viewSortBy" => '-DateUpdated',
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

        // var_dump($filterOptionsApplied);

        // get all product available statuses
        $filterOptionsAvailable['filter_commonStatus'] = $this->data->getProductStatuses();

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

        // $filterOptionsApplied["id"] = $categoryID;
        // $filterOptionsAvailable["id"] = $categoryID;

        // var_dump($filterOptionsApplied['filter_commonFeatures']);


        $activeTree = API::getAPI('shop:categories')->getCatalogTree();
        $cetegories = $this->getCategoriesFromCategoryTree($activeTree, $categoryID);

        if (empty($cetegories)) {
            header("HTTP/1.0 404 Not Found");
            die();
        }

        $categoriesIDs = array();
        $categoriesNodes = array();
        // var_dump($cetegories);
        foreach ($cetegories as $categoryItem) {
            $categoriesIDs[] = intval($categoryItem['ID']);
            $categoriesNodes[$categoryItem['ID']] = array(
                'ID' => intval($categoryItem['ID']),
                'Name' => $categoryItem['Name'],
                'ExternalKey' => $categoryItem['ExternalKey'],
                'SubIDs' => $categoryItem['SubIDs']
            );
            if (!empty($categoryItem['SubIDs'])) {
                $categoriesIDs += $categoryItem['SubIDs'];
            }
        }

        // var_dump($categoriesNodes);
        // var_dump($activeTree);
        //filter: get category price edges
        $dataCategoryPriceEdges = $this->data->fetchCatalogPriceEdges(implode(',', $categoriesIDs));
        $filterOptionsAvailable['filter_commonPriceMax'] = intval($dataCategoryPriceEdges['PriceMax'] + 10);
        $filterOptionsAvailable['filter_commonPriceMin'] = intval($dataCategoryPriceEdges['PriceMin'] - 10);
        if ($filterOptionsAvailable['filter_commonPriceMin'] < 0) {
            $filterOptionsAvailable['filter_commonPriceMin'] = 0;
        }
        // var_dump($dataConfigCategoryPriceEdges);

        // get all brands for both current category and sub-categories
        $dataCategoryAllBrands = $this->data->fetchShopCatalogBrandsArray(implode(',', $categoriesIDs));
        // if ($dataCategoryAllBrands)
        //     foreach ($dataCategoryAllBrands as $key => $brandItem) {
        //         $dataCategoryAllBrands[$key]['ID'] = $brandItem['ID'];
        //     }

        // set categories and brands
        $filterOptionsAvailable['filter_categoryBrands'] = $dataCategoryAllBrands ?: array();
        $filterOptionsAvailable['filter_categorySubCategories'] = $categoriesNodes ?: array();

        // get catalog features
        // $dataConfigAllMatchedProducts = $this->data->getShopCatalogProductList($categoriesIDs);
        // $dataProductsMatches = $app->getDB()->query($dataConfigAllMatchedProducts);
        // $catalogProductIDs = $this->getUniqueProductsIDs($dataProductsMatches);
        // foreach ($catalogProductIDs as $productItemID) {
        //     $featureItems = API::getAPI('shop:products')->fetchProductFeaturesArray($productItemID);
        //     foreach ($featureItems as $featureGroup => $featureList) {
        //         if (!isset($features[$featureGroup])) {
        //             $filterOptionsAvailable['filter_commonFeatures'][$featureGroup] = array();
        //         }
        //         foreach ($featureList as $key => $featureName) {
        //             $filterOptionsAvailable['filter_commonFeatures'][$featureGroup][$key] = $featureName;
        //         }
        //     }
        // }
        $filterOptionsAvailable['filter_commonFeatures'] = $this->data->fetchCatalogFeatures($categoryID);

        // set data source
        // ---
        $productsDataList = $this->data->fetchCatalogProductDataListByCatalogFilter($categoriesIDs,
            $filterOptionsApplied, $filterOptionsAvailable);
        // $dataConfigProducts = $this->data->getShopCatalogProductList($categoriesIDs);



        // get products count for current filter
        $currentProductCount = $productsDataList['total_entries'];
        // $dataConfigAllMatchedProducts = $this->data->getShopCatalogProductList($categoriesIDs);
        // $dataConfigAllMatchedProducts['condition'] = new ArrayObject($dataConfigProducts['condition']);
        // $dataProductsMatches = $app->getDB()->query($dataConfigAllMatchedProducts);
        // $currentProductCount = $this->getUniqueProductsCount($dataProductsMatches);

        // TODO smth with this
        // $products = array();
        // if (!empty($dataProducts)) {
        //     foreach ($dataProducts as $val) {
        //         $products[] = $this->data->fetchSingleProductByID($val['ID']);
        //     }
        // }

        // store data
        $data['items'] = $products;
        $data['filter'] = array(
            'filterOptionsAvailable' => $filterOptionsAvailable,
            'filterOptionsApplied' => $filterOptionsApplied,
            'info' => array(
                "count" => $currentProductCount,
                "category" => $this->data->fetchCategoryByID($categoryID),
                "priceEdgesAvailableConverted" => array(
                    "min" => API::getAPI('shop:exchangerates')->convertToRates($filterOptionsAvailable['filter_commonPriceMin']),
                    "max" => API::getAPI('shop:exchangerates')->convertToRates($filterOptionsAvailable['filter_commonPriceMax'])
                ),
                "priceEdgesAppliedConverted" => array(
                    "min" => API::getAPI('shop:exchangerates')->convertToRates($filterOptionsApplied['filter_commonPriceMin']),
                    "max" => API::getAPI('shop:exchangerates')->convertToRates($filterOptionsApplied['filter_commonPriceMax'])
                )
            )
        );
        $data['_location'] = $this->data->fetchCategoryLocation($categoryID);
        // return data object
        return $data;
    }

    public function get ($req, $resp) {
        if ($req->hasRequestedID()) {
            // if (is_numeric($req->id)) {
            //     $CategoryID = intval($req->id);
            $resp->setResponse($this->getCatalogBrowse($CategoryID));
            return;
            // } else {
            //     $category = $this->data->fetchCategoryByExternalKey($req->id);
            //     if (isset($category['ID'])) {
            //         $resp->setResponse($this->getCatalogBrowse($category['ID']));
            //     } else {
            //         $resp->setError('UnknownCategory');
            //     }
            // }
        }
        if ($req->hasRequestedExternalKey()) {
            $category = $this->data->fetchCategoryByExternalKey($req->externalKey);
            if (isset($category['ID'])) {
                $resp->setResponse($this->getCatalogBrowse($category['ID']));
            } else {
                $resp->setError('UnknownCategory');
            }
            return;
        }
        $resp->setWrongItemIdError();

        // if (isset($req->id)) {
        //     if (is_numeric($req->id)) {
        //         $CategoryID = intval($req->id);
        //         $resp->setResponse($this->getCatalogBrowse($CategoryID));
        //     } else {
        //         $category = $this->data->fetchCategoryByExternalKey($req->id);
        //         if (isset($category['ID'])) {
        //             $resp->setResponse($this->getCatalogBrowse($category['ID']));
        //         } else {
        //             $resp->setError('UnknownCategory');
        //         }
        //     }
        // } else {
        //     $resp->setError('"id" parameter is missed');
        // }
    }

}

?>
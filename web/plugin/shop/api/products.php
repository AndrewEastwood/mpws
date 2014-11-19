<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\request as Request;
use Exception;
use ArrayObject;

class products extends \engine\objects\api {

    private $_listKey_Recent = 'shop:listRecent';
    private $_statuses = array('ACTIVE','ARCHIVED','DISCOUNT','DEFECT','WAITING','PREORDER');


    public function getProductUploadInnerDir ($productID, $subDir = '') {
        $path = '';
        if (empty($subDir))
            $path = Path::createDirPath('shop', 'products', $productID);
        else
            $path = Path::createDirPath('shop', 'products', $productID, $subDir);
        return $path;
    }
    public function getProductUploadInnerImagePath ($name,$productID, $subDir = false) {
        $path = $this->getProductUploadInnerDir($productID, $subDir);
        return $path . $name;
    }
    public function getProductStatuses () {
        return $this->_statuses;
    }
    public function getProductStatusesWhenAvailable () {
        return array("ACTIVE", "DISCOUNT", "PREORDER", "DEFECT");
    }
    public function getProductStatusesWhenDisabled () {
        return array("ARCHIVED");
    }
    // -----------------------------------------------
    // -----------------------------------------------
    // PRODUCTS
    // -----------------------------------------------
    // -----------------------------------------------
    // product standalone item (short or full)
    // -----------------------------------------------

    private function __adjustProduct (&$product, $skipRelations = false) {
        // adjusting
        $productID = intval($product['ID']);
        $product['ID'] = intval($product['ID']);
        $product['OriginID'] = intval($product['OriginID']);
        $product['CategoryID'] = intval($product['CategoryID']);
        $product['_category'] = $this->getAPI()->categories->getCategoryByID($product['CategoryID']);
        $product['_origin'] = $this->getAPI()->origins->getOriginByID($product['OriginID']);
        $product['Attributes'] = $this->getProductAttributes($productID);
        $product['IsPromo'] = intval($product['IsPromo']) === 1;
        $product['Price'] = floatval($product['Price']);

        // misc data
        if (!$skipRelations) {
            $product['Relations'] = $this->getProductRelations($productID);
        }
        $product['Prices'] = $this->getProductPriceHistory($productID);
        $product['Features'] = $this->getProductFeatures($productID);

        // media
        $product['Images'] = $this->getProductImages($productID);
        $product['Videos'] = $this->getProductVideos($productID);

        // Utils
        $product['_viewExtras'] = array();
        $product['_viewExtras']['InWish'] = $this->getAPI()->wishlists->productIsInWishList($productID);
        $product['_viewExtras']['InCompare'] = $this->getAPI()->comparelists->productIsInCompareList($productID);
        $product['_viewExtras']['InCartCount'] = $this->getAPI()->orders->productCountInCart($productID);

        // promo
        $promo = $this->getAPI()->promos->getSessionPromo();
        $product['_promoIsApplied'] = false;
        if ($product['IsPromo'] && !empty($promo) && !empty($promo['Discount']) && $promo['Discount'] > 0) {
            $product['_promoIsApplied'] = true;
            $product['DiscountPrice'] = (100 - intval($promo['Discount'])) / 100 * $product['Price'];
            $product['_promo'] = $promo;
        }

        $product['SellingPrice'] = isset($product['DiscountPrice']) ? $product['DiscountPrice'] : $product['Price'];
        $product['SellingPrice'] = floatval($product['SellingPrice']);

        // is available
        $product['_available'] = in_array($product['Status'], $this->getProductStatusesWhenAvailable());
        $product['_archived'] = in_array($product['Status'], $this->getProductStatusesWhenDisabled());
        // need to use as separate request
        // $product['_featuresTree'] = $this->getAPI()->productfeatures->getFeatures_Tree();

        // $product['_statuses'] = $this->_getCachedTableStatuses($this->getPluginConfiguration()->data->Table_ShopProducts);
        // save product into recently viewed list
        if (Request::isGET() && !$this->getApp()->isToolbox()) {
            $recentProducts = isset($_SESSION[$this->_listKey_Recent]) ? $_SESSION[$this->_listKey_Recent] : array();
            $recentProducts[$productID] = $product;
            $_SESSION[$this->_listKey_Recent] = $recentProducts;
        }
        return $product;
    }

    public function getProductByID ($productID, $skipRelations = false) {
        if (empty($productID) || !is_numeric($productID))
            return null;
        $config = $this->getPluginConfiguration()->data->jsapiShopGetProductItem($productID);
        $product = $this->getCustomer()->fetch($config);
        if (empty($product))
            return null;
        return $this->__adjustProduct($product, $skipRelations);
    }

    public function getProductByName ($productName, $skipRelations = false) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetProductItem();
        $config['condition']['Name'] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($productName);
        $product = $this->getCustomer()->fetch($config);
        if (empty($product))
            return null;
        return $this->__adjustProduct($product, $skipRelations);
    }

    public function getProductByModel ($productModel, $skipRelations = false) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetProductItem();
        $config['condition']['Model'] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($productModel);
        $product = $this->getCustomer()->fetch($config);
        if (empty($product))
            return null;
        return $this->__adjustProduct($product, $skipRelations);
    }

    public function getProductByModelAndOriginName ($productName, $originName, $skipRelations = false) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetProductItem();
        $config['condition']['Name'] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($productName);
        $config['condition']['OriginName'] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($originName);
        $config['additional'] = array(
            "shop_origins" => array(
                "constraint" => array("shop_origins.ID", "=", "shop_products.OriginID"),
                "fields" => array(
                    "OriginName" => "Name"
                )
            )
        );
        $product = $this->getCustomer()->fetch($config);
        if (empty($product))
            return null;
        return $this->__adjustProduct($product, $skipRelations);
    }

    public function getProductIDByModelAndOriginName ($productName, $originName) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetProductItem();
        $config['fields'] = array("ID");
        $config['condition']['Model'] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($productName);
        $config['condition']['shop_origins.Name'] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($originName);
        $config['additional'] = array(
            "shop_origins" => array(
                "constraint" => array("shop_origins.ID", "=", "shop_products.OriginID"),
                "fields" => array("Name")
            )
        );
        $product = $this->getCustomer()->fetch($config);
        if (empty($product))
            return null;
        return intval($product['ID']);
    }

    public function getProductIDByID ($productID) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetProductItem();
        $config['fields'] = array("ID");
        $config['condition']['ID'] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($productID);
        $product = $this->getCustomer()->fetch($config);
        if (empty($product))
            return null;
        return intval($product['ID']);
    }

    public function getProductImages ($productID) {
        $images = array();
        $config = $this->getPluginConfiguration()->data->jsapiShopGetProductAttributes($productID, 'IMAGE');
        $data = $this->getCustomer()->fetch($config);
        // var_dump($data);
        if (!empty($data)) {
            foreach ($data as $item) {
                $images[] = array(
                    'name' => $item['Value'],
                    'normal' => '/' . Path::getUploadDirectory() . $this->getProductUploadInnerImagePath($item['Value'], $productID),
                    'sm' => '/' . Path::getUploadDirectory() . $this->getProductUploadInnerImagePath($item['Value'], $productID, 'sm'),
                    'xs' => '/' . Path::getUploadDirectory() . $this->getProductUploadInnerImagePath($item['Value'], $productID, 'xs')
                );
            }
        }
        return $images;
    }

    public function getProductVideos ($productID) {
        $videos = array();
        $config = $this->getPluginConfiguration()->data->jsapiShopGetProductAttributes($productID, 'VIDEO');
        $data = $this->getCustomer()->fetch($config);
        if (!empty($data)) {
            foreach ($data as $item) {
                $videos[] = $item['Value'];
            }
        }
        return $videos;
    }

    public function getProductAttributes ($productID) {
        $attr = array();
        $config = $this->getPluginConfiguration()->data->jsapiShopGetProductAttributes($productID);
        $data = $this->getCustomer()->fetch($config);
        if (!empty($data)) {
            foreach ($data as $item) {
                if ($item['Attribute'] === 'IMAGE' || $item['Attribute'] === 'VIDEO') {
                    continue;
                }
                $attr[$item['Attribute']] = $item['Value'];
            }
        }
        return $attr;
    }

    public function getProductFeatures ($productID) {
        $featuresGroups = array();
        $config = $this->getPluginConfiguration()->data->jsapiShopGetProductFeatures($productID);
        $data = $this->getCustomer()->fetch($config);
        if (!empty($data)) {
            foreach ($data as $value) {
                if (!isset($featuresGroups[$value['GroupName']])) {
                    $featuresGroups[$value['GroupName']] = array();
                }
                $featuresGroups[$value['GroupName']][$value['ID']] = $value['FieldName'];
            }
        }
        return $featuresGroups;
    }

    public function getProductPriceHistory ($productID) {
        $prices = array();
        $config = $this->getPluginConfiguration()->data->jsapiShopGetProductPriceStats($productID);
        $data = $this->getCustomer()->fetch($config);
        if (!empty($data)) {
            foreach ($data as $item) {
                $prices[$item['DateCreated']] = floatval($item['Price']);
            }
        }
        return $prices;
    }

    public function getProductRelations ($productID) {
        $relations = array();
        $configProductsRelations = $this->getPluginConfiguration()->data->jsapiShopGetProductRelations($productID);
        $relatedItemsIDs = $this->getCustomer()->fetch($configProductsRelations);
        if (isset($relatedItemsIDs)) {
            foreach ($relatedItemsIDs as $relationItem) {
                $relatedProductID = intval($relationItem['ProductB_ID']);
                if ($relatedProductID === $productID)
                    continue;
                $relatedProduct = $this->getProductByID($relatedProductID, true);
                if (isset($relatedProduct))
                    $relations[] = $relatedProduct;
            }
        }
        return $relations;
    }

    public function getProducts_List (array $options = array(), $saveIntoRecent = false, $skipRelations = false) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetProductList($options);
        $self = $this;

        $callbacks = array(
            "parse" => function ($items) use($self, $saveIntoRecent, $skipRelations) {
                $_items = array();
                foreach ($items as $key => $orderRawItem) {
                    $_items[] = $self->getProductByID($orderRawItem['ID'], $skipRelations);
                }
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);

        // $dataList['_category'] = null;

        // if (isset($options['_pStats'])) {
        //     $filter = array();
        //     if (isset($options['_fCategoryID'])) {
        //         $filter['_fCategoryID'] = $options['_fCategoryID'];
        //         $dataList['_category'] = $this->getAPI()->categories->getCategoryByID($options['_fCategoryID']);
        //     }
        //     $dataList['stats'] = $this->getStats_ProductsOverview($filter);
        // }

        return $dataList;
    }

    public function createProduct ($reqData) {
        $result = array();
        $errors = array();
        $success = false;
        $ProductID = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'CategoryID' => array('int'),
            'OriginID' => array('int'),
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 300),
            'Description' => array('string', 'skipIfEmpty', 'max' => 10000),
            'Model' => array('skipIfEmpty'),
            'SKU' => array('skipIfEmpty'),
            'Price' => array('numeric', 'notEmpty'),
            'IsPromo' => array('bool', 'skipIfEmpty'),
            'Status' => array('string', 'skipIfEmpty'),
            'Tags' => array('string', 'skipIfEmpty'),
            'ISBN' => array('skipIfEmpty'),
            'WARRANTY' => array('skipIfEmpty'),
            'Features' =>  array('array', 'notEmpty'),
            'file1' => array('string', 'skipIfEmpty'),
            'file2' => array('string', 'skipIfEmpty'),
            'file3' => array('string', 'skipIfEmpty'),
            'file4' => array('string', 'skipIfEmpty'),
            'file5' => array('string', 'skipIfEmpty')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {
                $validatedValues = $validatedDataObj['values'];
                $CustomerID = $this->getCustomer()->getCustomerID();
                $attributes = array();
                $attributes["IMAGE"] = array();
                $features = array();
                $productFeaturesIDs = array();

                // $keyToStripQuotes = array('Name', 'Description', 'Model', 'SKU', 'ISBN');
                // foreach ($keyToStripQuotes as $key) {
                //     if (isset($validatedValues[$key]))
                //         $validatedValues[$key] = mysqli_real_escape_string($validatedValues[$key]);
                // }

                // var_dump('::::: create products with values:::::::');
                // var_dump($validatedValues);

                // extract attributes
                if (isset($validatedValues['Tags'])) {
                    $attributes["TAGS"] = $validatedValues['Tags'];
                }
                if (isset($validatedValues['ISBN'])) {
                    $attributes["ISBN"] = $validatedValues['ISBN'];
                }
                if (isset($validatedValues['WARRANTY'])) {
                    $attributes["WARRANTY"] = $validatedValues['WARRANTY'];
                }
                // extract features
                if (isset($validatedValues['Features'])) {
                    $features = $validatedValues['Features'];
                }
                // I don't think loop for 5 items is better for perfomance
                if (!empty($validatedValues['file1'])) {
                    $attributes["IMAGE"][] = $validatedValues['file1'];
                }
                if (!empty($validatedValues['file2'])) {
                    $attributes["IMAGE"][] = $validatedValues['file2'];
                }
                if (!empty($validatedValues['file3'])) {
                    $attributes["IMAGE"][] = $validatedValues['file3'];
                }
                if (!empty($validatedValues['file4'])) {
                    $attributes["IMAGE"][] = $validatedValues['file4'];
                }
                if (!empty($validatedValues['file5'])) {
                    $attributes["IMAGE"][] = $validatedValues['file5'];
                }

                // cleanup fields
                unset($validatedValues['Tags']);
                unset($validatedValues['ISBN']);
                unset($validatedValues['WARRANTY']);
                unset($validatedValues['Features']);
                unset($validatedValues['file2']);
                unset($validatedValues['file1']);
                unset($validatedValues['file3']);
                unset($validatedValues['file4']);
                unset($validatedValues['file5']);

                $this->getCustomerDataBase()->beginTransaction();

                // adjust features
                foreach ($features as $groupName => $value) {
                    $features[$groupName] = explode(',', $value);
                }

                // add new features
                $featureMap = $this->getAPI()->productfeatures->getFeatures();
                foreach ($features as $groupName => $featureList) {
                    if (isset($featureMap[$groupName])) {
                        foreach ($featureList as $featureName) {
                            $featureID = array_search($featureName, $featureMap[$groupName]);
                            if ($featureID === false) {
                                $data = array();
                                $data["CustomerID"] = $CustomerID;
                                $data["FieldName"] = substr($featureName, 0, 200);
                                $data["GroupName"] = substr($groupName, 0, 100);
                                $featureID = $this->getAPI()->productfeatures->createFeature($data);
                                $productFeaturesIDs[] = intval($featureID);
                            } else {
                                $productFeaturesIDs[] = $featureID;
                            }
                        }
                    } else {
                        foreach ($featureList as $featureName) {
                            $data = array();
                            $data["CustomerID"] = $CustomerID;
                            $data["FieldName"] = substr($featureName, 0, 200);
                            $data["GroupName"] = substr($groupName, 0, 100);
                            $featureID = $this->getAPI()->productfeatures->createFeature($data);
                            $productFeaturesIDs[] = $featureID;
                        }
                    }
                }

                // // add new features
                // foreach ($features as $value) {
                //     if (is_numeric($value)) {
                //         $productFeaturesIDs[] = $value;
                //     } else {
                //         $data["FieldName"] = $value;
                //         $data["CustomerID"] = $CustomerID;
                //         $config = $this->getPluginConfiguration()->data->jsapiShopCreateFeature($data);
                //         $featureID = $this->getCustomer()->fetch($config) ?: null;
                //         if (isset($featureID) && $featureID >= 0) {
                //             $productFeaturesIDs[] = $featureID;
                //             // $this->_getOrSetCachedState('changed:features', true);
                //         }
                //     }
                // }

                // create product
                $validatedValues["CustomerID"] = $CustomerID;
                if (isset($validatedValues["IsPromo"])) {
                    $validatedValues["IsPromo"] = $validatedValues["IsPromo"] ? 1 : 0;
                }
                // var_dump($validatedValues);
                $config = $this->getPluginConfiguration()->data->jsapiShopCreateProduct($validatedValues);
                // var_dump($config);
                $ProductID = null;
                try {
                    $ProductID = $this->getCustomer()->fetch($config) ?: null;
                } catch (Exception $ep) {
                    $errors[] = $ep->getMessage();
                }
                // var_dump($productID);
                // var_dump($this->getCustomerDataBase()->get_last_query());
                if (empty($ProductID)) {
                    throw new Exception('ProductCreateError');
                }

                // set new features (actually this condition must return always true)
                if (count($productFeaturesIDs)) {
                    $featureData['ProductID'] = $ProductID;
                    $featureData['CustomerID'] = $CustomerID;
                    foreach ($productFeaturesIDs as $value) {
                        $featureData['FeatureID'] = $value;
                        $config = $this->getPluginConfiguration()->data->jsapiShopAddFeatureToProduct($featureData);
                        $this->getCustomer()->fetch($config);
                    }
                }

                // add attributes
                if (!empty($attributes)) {
                    $initAttrData = new ArrayObject(array(
                        'ProductID' => $ProductID,
                        'CustomerID' => $CustomerID
                    ));
                    // -- IMAGE
                    if (isset($attributes["IMAGE"])) {
                        foreach ($attributes["IMAGE"] as $fileName) {

                            // $uploadInfo = $this->saveOwnTemporaryUploadedFile(, $this->getProductUploadInnerDir($ProductID, 'sm'), $newFileName);

                            // $this->saveOwnTemporaryUploadedFile('xs' . Path::getDirectorySeparator() . $fileName, 
                            //     $this->getProductUploadInnerDir($ProductID, 'xs'), $newFileName);

                            // $this->saveOwnTemporaryUploadedFile($fileName, $this->getProductUploadInnerDir($ProductID), $newFileName);


                            $newFileName = $ProductID . uniqid(time());
                            $smImagePath = 'sm' . Path::getDirectorySeparator() . $fileName;
                            $xsImagePath = 'xs' . Path::getDirectorySeparator() . $fileName;
                            $normalImagePath = $fileName;

                            $uploadInfo = Path::moveTemporaryFile($smImagePath, $this->getProductUploadInnerDir($ProductID, 'sm'), $newFileName);
                            $uploadInfo = Path::moveTemporaryFile($xsImagePath, $this->getProductUploadInnerDir($ProductID, 'xs'), $newFileName);
                            $uploadInfo = Path::moveTemporaryFile($normalImagePath, $this->getProductUploadInnerDir($ProductID), $newFileName);

                            $attrData = $initAttrData->getArrayCopy();
                            $attrData['Attribute'] = 'IMAGE';
                            $attrData['Value'] = $uploadInfo['filename'];
                            $config = $this->getPluginConfiguration()->data->jsapiShopAddAttributeToProduct($attrData);
                            $this->getCustomer()->fetch($config);
                        }
                    }
                    // -- ISBN
                    // -- EXPIRE
                    // -- TAGS
                    $commonAttributeKeys = array('ISBN', 'EXPIRE', 'TAGS', 'WARRANTY');
                    foreach ($commonAttributeKeys as $key) {
                        if (!isset($attributes[$key])) {
                            continue;
                        }
                        $attrData = $initAttrData->getArrayCopy();
                        $attrData['Attribute'] = $key;
                        $attrData['Value'] = $value;
                        $config = $this->getPluginConfiguration()->data->jsapiShopAddAttributeToProduct($attrData);
                        $this->getCustomer()->fetch($config);
                    }
                }

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        if ($success && !empty($ProductID))
            $result = $this->getProductByID($ProductID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateProduct ($ProductID, $reqData) {
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'CategoryID' => array('int', 'skipIfUnset'),
            'OriginID' => array('int', 'skipIfUnset'),
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 300, 'skipIfUnset'),
            'Description' => array('string', 'skipIfUnset', 'max' => 1000),
            'Model' => array('skipIfUnset'),
            'SKU' => array('skipIfUnset'),
            'Price' => array('numeric', 'notEmpty', 'skipIfUnset'),
            'IsPromo' => array('bool', 'skipIfUnset'),
            'Status' => array('string', 'skipIfEmpty'),
            'Tags' => array('string', 'skipIfUnset'),
            'ISBN' => array('skipIfUnset'),
            'WARRANTY' => array('skipIfEmpty'),
            'Features' =>  array('array', 'notEmpty', 'skipIfUnset'),
            'file1' => array('string', 'skipIfUnset'),
            'file2' => array('string', 'skipIfUnset'),
            'file3' => array('string', 'skipIfUnset'),
            'file4' => array('string', 'skipIfUnset'),
            'file5' => array('string', 'skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];
                $CustomerID = $this->getCustomer()->getCustomerID();
                $attributes = array();
                $attributes["IMAGE"] = array();
                $features = array();
                $productFeaturesIDs = array();

                // $keyToStripQuotes = array('Name', 'Description', 'Model', 'SKU', 'ISBN');
                // foreach ($keyToStripQuotes as $key) {
                //     if (isset($validatedValues[$key]))
                //         $validatedValues[$key] = mysqli_real_escape_string($validatedValues[$key]);
                // }

                // extract attributes
                if (isset($validatedValues['Tags'])) {
                    $attributes["TAGS"] = $validatedValues['Tags'];
                }
                if (isset($validatedValues['ISBN'])) {
                    $attributes["ISBN"] = $validatedValues['ISBN'];
                }
                if (isset($validatedValues['WARRANTY'])) {
                    $attributes["WARRANTY"] = $validatedValues['WARRANTY'];
                }
                // extract features
                if (isset($validatedValues['Features'])) {
                    $features = $validatedValues['Features'];
                }
                // I don't think loop for 5 items is better for perfomance
                if (!empty($validatedValues['file1'])) {
                    $attributes["IMAGE"][] = $validatedValues['file1'];
                }
                if (!empty($validatedValues['file2'])) {
                    $attributes["IMAGE"][] = $validatedValues['file2'];
                }
                if (!empty($validatedValues['file3'])) {
                    $attributes["IMAGE"][] = $validatedValues['file3'];
                }
                if (!empty($validatedValues['file4'])) {
                    $attributes["IMAGE"][] = $validatedValues['file4'];
                }
                if (!empty($validatedValues['file5'])) {
                    $attributes["IMAGE"][] = $validatedValues['file5'];
                }

                // cleanup fields
                unset($validatedValues['Tags']);
                unset($validatedValues['ISBN']);
                unset($validatedValues['WARRANTY']);
                unset($validatedValues['Features']);
                unset($validatedValues['file1']);
                unset($validatedValues['file2']);
                unset($validatedValues['file3']);
                unset($validatedValues['file4']);
                unset($validatedValues['file5']);

                $this->getCustomerDataBase()->beginTransaction();

                // adjust features
                foreach ($features as $groupName => $value) {
                    $features[$groupName] = explode(',', $value);
                }

                // add new features
                $featureMap = $this->getAPI()->productfeatures->getFeatures();
                foreach ($features as $groupName => $featureList) {
                    if (isset($featureMap[$groupName])) {
                        foreach ($featureList as $featureName) {
                            $featureID = array_search($featureName, $featureMap[$groupName]);
                            if ($featureID === false) {
                                $data = array();
                                $data["CustomerID"] = $CustomerID;
                                $data["FieldName"] = substr($featureName, 0, 200);
                                $data["GroupName"] = substr($groupName, 0, 100);
                                $featureID = $this->getAPI()->productfeatures->createFeature($data);
                                $productFeaturesIDs[] = intval($featureID);
                            } else {
                                $productFeaturesIDs[] = $featureID;
                            }
                        }
                    } else {
                        foreach ($featureList as $featureName) {
                            $data = array();
                            $data["CustomerID"] = $CustomerID;
                            $data["FieldName"] = substr($featureName, 0, 200);
                            $data["GroupName"] = substr($groupName, 0, 100);
                            $featureID = $this->getAPI()->productfeatures->createFeature($data);
                            $productFeaturesIDs[] = $featureID;
                        }
                    }
                }

                // var_dump($features);
                // var_dump($featureMap);
                // var_dump($productFeaturesIDs);

                // update product
                $validatedValues["CustomerID"] = $CustomerID;
                if (isset($validatedValues["IsPromo"])) {
                    $validatedValues["IsPromo"] = $validatedValues["IsPromo"] ? 1 : 0;
                }
                $config = $this->getPluginConfiguration()->data->jsapiShopUpdateProduct($ProductID, $validatedValues);
                try {
                    $this->getCustomer()->fetch($config);
                } catch (Exception $ep) {
                    $errors[] = $ep->getMessage();
                }

                // set new features
                if (count($productFeaturesIDs)) {
                    // clear existed features before adding new
                    $config = $this->getPluginConfiguration()->data->jsapiShopClearProductFeatures($ProductID);
                    $this->getCustomer()->fetch($config);
                    $featureData['ProductID'] = $ProductID;
                    $featureData['CustomerID'] = $CustomerID;
                    foreach ($productFeaturesIDs as $value) {
                        $featureData['FeatureID'] = $value;
                        // var_dump($featureData);
                        $config = $this->getPluginConfiguration()->data->jsapiShopAddFeatureToProduct($featureData);
                        $this->getCustomer()->fetch($config);
                    }
                }

                // get previous product data
                // we need this to re-adjust images for the product
                $currentImages = $this->getProductImages($ProductID);
                $filesUploaded = array();
                $filesToDelete = array();
                $filesToKeep = array();
                $filesToUpload = array();


                foreach ($currentImages as $currentImageItem) {
                    $filesUploaded[] = $currentImageItem['name'];
                }

                $filesToKeep = array_intersect($filesUploaded, $attributes["IMAGE"]);
                $filesToDelete = array_diff($filesUploaded, $attributes["IMAGE"]);
                $filesToUpload = array_diff($attributes["IMAGE"], $filesUploaded);

                // // var_dump($previousAttributesImages);
                // var_dump($attributes["IMAGE"]);

                // foreach ($currentImages as $currentImageItem) {
                //     if (in_array($currentImageItem['name'], $attributes["IMAGE"])) {
                //         $filesToKeep[] = $currentImageItem['name'];
                //     } else {
                //         $filesToDelete[] = $currentImageItem['name'];
                //     }
                // }

                // var_dump('current>>>>>>>');
                // var_dump($currentImages);
                // var_dump('delete>>>>>>>');
                // var_dump($filesToDelete);
                // var_dump('keep>>>>>>>');
                // var_dump($filesToKeep);
                // var_dump('upload>>>>>>>');
                // var_dump($filesToUpload);

                $uploadedFileNames = array();
                foreach ($filesToUpload as $fileName) {

                    $newFileName = $ProductID . uniqid(time());
                    $smImagePath = 'sm' . Path::getDirectorySeparator() . $fileName;
                    $xsImagePath = 'xs' . Path::getDirectorySeparator() . $fileName;
                    $normalImagePath = $fileName;

                    $uploadInfo = Path::moveTemporaryFile($smImagePath, $this->getProductUploadInnerDir($ProductID, 'sm'), $newFileName);
                    $uploadInfo = Path::moveTemporaryFile($xsImagePath, $this->getProductUploadInnerDir($ProductID, 'xs'), $newFileName);
                    $uploadInfo = Path::moveTemporaryFile($normalImagePath, $this->getProductUploadInnerDir($ProductID), $newFileName);

                    // var_dump($uploadInfo);
                    // $attrData = $initAttrData->getArrayCopy();
                    // $attrData['Attribute'] = 'IMAGE';
                    // $attrData['Value'] = $uploadInfo['filename'];
                    // $config = $this->getPluginConfiguration()->data->jsapiShopAddAttributeToProduct($attrData);
                    // $this->getCustomer()->fetch($config);

                    // $newFileName = $ProductID . uniqid(time());
                    // $uploadInfo = $this->saveOwnTemporaryUploadedFile('sm' . Path::getDirectorySeparator() . $fileName, $this->getProductUploadInnerDir($ProductID, 'sm'), $newFileName);
                    // $this->saveOwnTemporaryUploadedFile('xs' . Path::getDirectorySeparator() . $fileName, $this->getProductUploadInnerDir($ProductID, 'xs'), $newFileName);
                    // $this->saveOwnTemporaryUploadedFile($fileName, $this->getProductUploadInnerDir($ProductID), $newFileName);
                    $uploadedFileNames[] = $uploadInfo['filename'];
                }
                foreach ($filesToDelete as $fileName) {

                    Path::deleteUploadedFile($this->getProductUploadInnerImagePath($fileName, $ProductID, 'sm'));
                    Path::deleteUploadedFile($this->getProductUploadInnerImagePath($fileName, $ProductID, 'xs'));
                    Path::deleteUploadedFile($this->getProductUploadInnerImagePath($fileName, $ProductID));

                    // $this->deleteOwnUploadedFile($fileName, $this->getProductUploadInnerDir($ProductID, 'sm'));
                    // $this->deleteOwnUploadedFile($fileName, $this->getProductUploadInnerDir($ProductID, 'xs'));
                    // $this->deleteOwnUploadedFile($fileName, $this->getProductUploadInnerDir($ProductID));
                }

                $attributes["IMAGE"] = array_merge($filesToKeep, $uploadedFileNames);

                // var_dump($attributes["IMAGE"]);

                // throw new Exception("Error Processing Request", 1);
                // set new attributes
                if (!empty($attributes)) {
                    $initAttrData = new ArrayObject(array(
                        'ProductID' => $ProductID,
                        'CustomerID' => $CustomerID
                    ));
                    // -- IMAGE
                    if (isset($attributes["IMAGE"])) {
                        $config = $this->getPluginConfiguration()->data->jsapiShopClearProductAttributes($ProductID, 'IMAGE');
                        $this->getCustomer()->fetch($config);
                        foreach ($attributes["IMAGE"] as $imageName) {
                            $attrData = $initAttrData->getArrayCopy();
                            $attrData['Attribute'] = 'IMAGE';
                            $attrData['Value'] = $imageName;
                            $config = $this->getPluginConfiguration()->data->jsapiShopAddAttributeToProduct($attrData);
                            $this->getCustomer()->fetch($config);
                        }
                    }
                    // -- ISBN
                    // -- EXPIRE
                    // -- TAGS
                    $commonAttributeKeys = array('ISBN', 'EXPIRE', 'TAGS', 'WARRANTY');
                    foreach ($commonAttributeKeys as $key) {
                        if (!isset($attributes[$key])) {
                            continue;
                        }
                        // clear existed tags before adding new ones
                        $config = $this->getPluginConfiguration()->data->jsapiShopClearProductAttributes($ProductID, $key);
                        $this->getCustomer()->fetch($config);
                        $attrData = $initAttrData->getArrayCopy();
                        $attrData['Attribute'] = $key;
                        $attrData['Value'] = $attributes[$key];
                        $config = $this->getPluginConfiguration()->data->jsapiShopAddAttributeToProduct($attrData);
                        $this->getCustomer()->fetch($config);
                    }
                }

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getProductByID($ProductID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateOrInsertProduct ($data) {
        $result = array();
        $errors = array();
        $product = null;
        $category = null;
        $origin = null;
        $productID = null;
        // get category by name
        $category = $this->getAPI()->categories->getCategoryByName($data['CategoryName']);
        // get origin by name
        $origin = $this->getAPI()->origins->getOriginByName($data['OriginName']);
        // create non-existent category and/or origin
        if ($category === null) {
            $category = $this->getAPI()->categories->createCategory(array(
                'Name' => $data['CategoryName']
            ));
        }
        if ($origin === null) {
            $origin = $this->getAPI()->origins->createOrigin(array(
                'Name' => $data['OriginName']
            ));
        }
        if (isset($category['ID']) && isset($origin['ID'])) {
            // we have the product item already in db
            if (isset($data['ID'])) {
                echo "[INFO] using product ID " . $data['ID'] . PHP_EOL;
                $productID = $this->getProductIDByID($data['ID']);
                // try to get product item by name and model
            } elseif (isset($data['Model']) && isset($data['OriginName'])) {
                echo "[INFO] using product Model and OriginName " . $data['Model'] . ' + ' . $data['OriginName'] . PHP_EOL;
                $productID = $this->getProductIDByModelAndOriginName($data['Model'], $data['OriginName']);
            }
            // var_dump($category);
            // var_dump($origin);
            // set category
            $data['CategoryID'] = $category['ID'];
            // set origin
            $data['OriginID'] = $origin['ID'];
            // downlod images
            // TODO: goes here :)
            // parse other images and skip own using hostname
            // var_dump($product);
            unset($data['CategoryName']);
            unset($data['OriginName']);
            // var_dump($data);
            // var_dump($productID);
            if ($productID === null) {
                $result = $this->createProduct($data);
            } else {
                $result = $this->updateProduct($productID, $data);
            }
            $result['created'] = $result['success'] && $productID === null;
            $result['updated'] = $result['success'] && $productID !== null;
            $errors = array_merge($errors, $result['errors']);
        } else {
            if (!isset($category['success']))
                $errors[] = 'Unable to create category';
            if (!isset($origin['success']))
                $errors[] = 'Unable to create origin';
            // var_dump($origin);
            // var_dump($category);
        }
        $result['errors'] = $errors;
        return $result;
    }

    public function archiveProduct ($ProductID) {
        $result = array();
        $errors = array();
        $success = false;
        try {

            $CustomerID = $this->getCustomer()->getCustomerID();

            $this->getCustomerDataBase()->beginTransaction();

            $data = array(
                'CustomerID' => $CustomerID,
                'Status' => 'ARCHIVED'
            );

            $config = $this->getPluginConfiguration()->data->jsapiShopUpdateProduct($ProductID, $data);
            $this->getCustomer()->fetch($config);

            $this->getCustomerDataBase()->commit();

            $success = true;
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
            $errors[] = $e->getMessage();
        }

        $result = $this->getProductByID($ProductID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function archiveAllProducts () {
        $result = array();
        $errors = array();
        $success = false;
        try {

            $CustomerID = $this->getCustomer()->getCustomerID();

            $this->getCustomerDataBase()->beginTransaction();

            $data = array(
                'CustomerID' => $CustomerID,
                'Status' => 'ARCHIVED'
            );

            $config = $this->getPluginConfiguration()->data->jsapiShopUpdateProduct(null, $data);
            $config['condition'] = null;
            $this->getCustomer()->fetch($config);

            $this->getCustomerDataBase()->commit();

            $success = true;
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
            $errors[] = $e->getMessage();
        }

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function getProducts_TopNonPopular () {
        // get non-popuplar 15 products
        $config = $this->getPluginConfiguration()->data->jsapiShopStat_NonPopularProducts();
        $productIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($productIDs)) {
            foreach ($productIDs as $val) {
                $data[] = $this->getProductByID($val['ID']);
            }
        }
        return $data;
    }

    public function getProducts_TopPopular () {
        // get top 15 products
        $config = $this->getPluginConfiguration()->data->jsapiShopStat_PopularProducts();
        $productIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($productIDs)) {
            foreach ($productIDs as $val) {
                $product = $this->getProductByID($val['ProductID']);
                $product['SoldTotal'] = floatval($val['SoldTotal']);
                $product['SumTotal'] = floatval($val['SumTotal']);
                $data[] = $product;
            }
        }
        return $data;
    }

    public function getStats_ProductsOverview ($filter = null) {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        // get shop products overview:
        $config = $this->getPluginConfiguration()->data->jsapiShopStat_ProductsOverview($filter);
        $data = $this->getCustomer()->fetch($config) ?: array();
        $total = 0;
        $res = array();
        $availableStatuses = $this->getProductStatuses();
        foreach ($availableStatuses as $key) {
            if (isset($data[$key])) {
                $res[$key] = intval($data[$key]);
                $total += $data[$key];
            } else
                $res[$key] = 0;
        }
        // get total
        $res['TOTAL'] = $total;
        return $res;
    }

    public function getStats_ProductsIntensityActiveLastMonth () {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        $config = $this->getPluginConfiguration()->data->jsapiShopStat_ProductsIntensityLastMonth('ACTIVE');
        $data = $this->getCustomer()->fetch($config) ?: array();
        return $data;
    }
    public function getStats_ProductsIntensityPreorderLastMonth () {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        $config = $this->getPluginConfiguration()->data->jsapiShopStat_ProductsIntensityLastMonth('PREORDER');
        $data = $this->getCustomer()->fetch($config) ?: array();
        return $data;
    }
    public function getStats_ProductsIntensityDiscountLastMonth () {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        $config = $this->getPluginConfiguration()->data->jsapiShopStat_ProductsIntensityLastMonth('DISCOUNT');
        $data = $this->getCustomer()->fetch($config) ?: array();
        return $data;
    }

    public function get (&$resp, $req) {
        if (empty($req->get['id'])) {
            $resp = $this->getProducts_List($req->get);
        } else {
            $ProductID = intval($req->get['id']);
            $resp = $this->getProductByID($ProductID);
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
    }

}

?>
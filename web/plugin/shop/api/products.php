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


    public function getProductUploadInnerDir ($productID, $subDir) {
        $path = '';
        if (empty($subDir))
            $path = Path::createDirPath('shop', 'products', $productID);
        else
            $path = Path::createDirPath('shop', 'products', $productID, $subDir);
        return Path::getUploadDirectory($path);
    }
    public function getProductUploadedImagePath ($name,$productID, $subDir = false) {
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
    public function getProductByID ($productID, $skipRelations = false) {
        if (empty($productID) || !is_numeric($productID))
            return null;

        $config = $this->getPluginConfiguration()->data->jsapiShopGetProductItem($productID);
        $product = $this->getCustomer()->fetch($config);

        if (empty($product))
            return null;

        // adjusting
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

    public function getProductImages ($productID) {
        $images = array();
        $config = $this->getPluginConfiguration()->data->jsapiShopGetProductAttributes($productID, 'IMAGE');
        $data = $this->getCustomer()->fetch($config);
        if (!empty($data)) {
            foreach ($data as $item) {
                $images[] = array(
                    'name' => $item['Value'],
                    'normal' => $this->getProductUploadedImagePath($item['Value'], $productID),
                    'sm' => $this->getProductUploadedImagePath($item['Value'], $productID, 'sm'),
                    'xs' => $this->getProductUploadedImagePath($item['Value'], $productID, 'xs')
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

        $dataList['_category'] = null;

        if (isset($options['_pStats'])) {
            $filter = array();
            if (isset($options['_fCategoryID'])) {
                $filter['_fCategoryID'] = $options['_fCategoryID'];
                $dataList['_category'] = $this->getAPI()->categories->getCategoryByID($options['_fCategoryID']);
            }
            $dataList['stats'] = $this->getStats_ProductsOverview($filter);
        }

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
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 100),
            'Description' => array('string', 'skipIfUnset', 'max' => 500),
            'Model' => array('skipIfUnset'),
            'SKU' => array('skipIfUnset'),
            'Price' => array('numeric', 'notEmpty'),
            'IsPromo' => array('bool'),
            'Status' => array('string', 'skipIfUnset'),
            'Tags' => array('string', 'skipIfUnset'),
            'ISBN' => array('skipIfUnset'),
            'Features' =>  array('string', 'notEmpty'),
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

                // extract attributes
                if (isset($validatedValues['Tags'])) {
                    $attributes["TAGS"] = $validatedValues['Tags'];
                    unset($validatedValues['Tags']);
                }
                if (isset($validatedValues['ISBN'])) {
                    $attributes["ISBN"] = $validatedValues['ISBN'];
                    unset($validatedValues['ISBN']);
                }
                // I don't think loop for 5 items is better for perfomance
                if (!empty($validatedValues['file1'])) {
                    $attributes["IMAGE"][] = $validatedValues['file1'];
                }
                unset($validatedValues['file1']);
                if (!empty($validatedValues['file2'])) {
                    $attributes["IMAGE"][] = $validatedValues['file2'];
                }
                unset($validatedValues['file2']);
                if (!empty($validatedValues['file3'])) {
                    $attributes["IMAGE"][] = $validatedValues['file3'];
                }
                unset($validatedValues['file3']);
                if (!empty($validatedValues['file4'])) {
                    $attributes["IMAGE"][] = $validatedValues['file4'];
                }
                unset($validatedValues['file4']);
                if (!empty($validatedValues['file5'])) {
                    $attributes["IMAGE"][] = $validatedValues['file5'];
                }
                unset($validatedValues['file5']);
                // extract features
                if (isset($validatedValues['Features'])) {
                    $features = $validatedValues['Features'];
                    unset($validatedValues['Features']);
                }

                $this->getCustomerDataBase()->beginTransaction();

                // add new features
                foreach ($features as $value) {
                    if (is_numeric($value)) {
                        $productFeaturesIDs[] = $value;
                    } else {
                        $data["FieldName"] = $value;
                        $data["CustomerID"] = $CustomerID;
                        $config = $this->getPluginConfiguration()->data->jsapiShopCreateFeature($data);
                        $featureID = $this->getCustomer()->fetch($config) ?: null;
                        if (isset($featureID) && $featureID >= 0) {
                            $productFeaturesIDs[] = $featureID;
                            // $this->_getOrSetCachedState('changed:features', true);
                        }
                    }
                }

                // create product
                $validatedValues["CustomerID"] = $CustomerID;
                if (isset($validatedValues["IsPromo"])) {
                    $validatedValues["IsPromo"] = $validatedValues["IsPromo"] ? 1 : 0;
                }
                $config = $this->getPluginConfiguration()->data->jsapiShopCreateProduct($validatedValues);
                $ProductID = $this->getCustomer()->fetch($config) ?: null;
                if (empty($ProductID)) {
                    throw new Exception('ProductCreateError');
                }

                // append features (actually this condition must return always true)
                if (!empty($features)) {
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
                    $commonAttributeKeys = array('ISBN', 'EXPIRE', 'TAGS');
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
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 100, 'skipIfUnset'),
            'Description' => array('string', 'skipIfUnset', 'max' => 500),
            'Model' => array('skipIfUnset'),
            'SKU' => array('skipIfUnset'),
            'Price' => array('numeric', 'notEmpty', 'skipIfUnset'),
            'IsPromo' => array('bool', 'skipIfUnset'),
            'Status' => array('string', 'skipIfUnset'),
            'Tags' => array('string', 'skipIfUnset'),
            'ISBN' => array('skipIfUnset'),
            'Features' =>  array('string', 'notEmpty', 'skipIfUnset'),
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

                // extract attributes
                if (isset($validatedValues['Tags'])) {
                    $attributes["TAGS"] = $validatedValues['Tags'];
                    unset($validatedValues['Tags']);
                }
                if (isset($validatedValues['ISBN'])) {
                    $attributes["ISBN"] = $validatedValues['ISBN'];
                    unset($validatedValues['ISBN']);
                }
                // extract features
                if (isset($validatedValues['Features'])) {
                    $features = $validatedValues['Features'];
                    unset($validatedValues['Features']);
                }
                // I don't think loop for 5 items is better for perfomance
                if (!empty($validatedValues['file1'])) {
                    $attributes["IMAGE"][] = $validatedValues['file1'];
                }
                unset($validatedValues['file1']);
                if (!empty($validatedValues['file2'])) {
                    $attributes["IMAGE"][] = $validatedValues['file2'];
                }
                unset($validatedValues['file2']);
                if (!empty($validatedValues['file3'])) {
                    $attributes["IMAGE"][] = $validatedValues['file3'];
                }
                unset($validatedValues['file3']);
                if (!empty($validatedValues['file4'])) {
                    $attributes["IMAGE"][] = $validatedValues['file4'];
                }
                unset($validatedValues['file4']);
                if (!empty($validatedValues['file5'])) {
                    $attributes["IMAGE"][] = $validatedValues['file5'];
                }
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
                                $data["FieldName"] = $featureName;
                                $data["GroupName"] = $groupName;
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
                            $data["FieldName"] = $featureName;
                            $data["GroupName"] = $groupName;
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
                $this->getCustomer()->fetch($config);

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

                    Path::deleteUploadedFile($this->getProductUploadedImagePath($fileName, $ProductID, 'sm'));
                    Path::deleteUploadedFile($this->getProductUploadedImagePath($fileName, $ProductID, 'xs'));
                    Path::deleteUploadedFile($this->getProductUploadedImagePath($fileName, $ProductID));

                    // $this->deleteOwnUploadedFile($fileName, $this->getProductUploadInnerDir($ProductID, 'sm'));
                    // $this->deleteOwnUploadedFile($fileName, $this->getProductUploadInnerDir($ProductID, 'xs'));
                    // $this->deleteOwnUploadedFile($fileName, $this->getProductUploadInnerDir($ProductID));
                }

                $attributes["IMAGE"] = array_merge($filesToKeep, $uploadedFileNames);

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
                    $commonAttributeKeys = array('ISBN', 'EXPIRE', 'TAGS');
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

    public function archiveProduct ($ProductID) {

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
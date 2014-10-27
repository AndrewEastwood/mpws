<?php

class pluginShop extends objectPlugin {

    private $_listKey_Wish = 'shop:wishList';
    private $_listKey_Recent = 'shop:listRecent';
    private $_listKey_Compare = 'shop:listCompare';
    private $_listKey_Cart = 'shop:cart';
    private $_listKey_Promo = 'shop:promo';
    private $_states = array(
        'changed:category' => false,
        'changed:origin' => false,
        'changed:order' => false,
        'changed:product' => false,
        'changed:promo' => false,
        'changed:features' => false,
        'changed:agencies' => false,
        'changed:settings' => false
    );

    public function getName () {
        return 'shop';
    }

    public function beforeRun () {
        $this->_getCachedTableStatuses();
        // sleep(5);
        $this->_getCachedTableData(configurationShopDataSource::$Table_ShopCategories);
        $this->_getCachedTableData(configurationShopDataSource::$Table_ShopOrigins);
    }


    // -----------------------------------------------
    // -----------------------------------------------
    // PRODUCTS
    // -----------------------------------------------
    // -----------------------------------------------
    // product standalone item (short or full)
    // -----------------------------------------------
    public function getProductByID ($productID, $saveIntoRecent = false, $skipRelations = false) {
        if (empty($productID) || !is_numeric($productID))
            return null;

        $config = configurationShopDataSource::jsapiShopGetProductItem($productID);
        $product = $this->getCustomer()->fetch($config);

        if (empty($product))
            return null;

        // adjusting
        $product['ID'] = intval($product['ID']);
        $product['CategoryID'] = intval($product['CategoryID']);
        $product['OriginID'] = intval($product['OriginID']);
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
        $product['_viewExtras']['InWish'] = $this->__productIsInWishList($productID);
        $product['_viewExtras']['InCompare'] = $this->__productIsInCompareList($productID);
        $product['_viewExtras']['InCartCount'] = $this->__productCountInCart($productID);

        // promo
        $promo = $this->_getSessionPromo();
        $product['_promoIsApplied'] = false;
        if ($product['IsPromo'] && !empty($promo) && !empty($promo['Discount']) && $promo['Discount'] > 0) {
            $product['_promoIsApplied'] = true;
            $product['DiscountPrice'] = (100 - intval($promo['Discount'])) / 100 * $product['Price'];
            $product['promo'] = $promo;
        }

        $product['SellingPrice'] = isset($product['DiscountPrice']) ? $product['DiscountPrice'] : $product['Price'];
        $product['SellingPrice'] = floatval($product['SellingPrice']);

        // is available
        $product['_available'] = in_array($product['Status'], array("ACTIVE", "DISCOUNT", "PREORDER", "DEFECT"));
        $product['_archived'] = in_array($product['Status'], array("ARCHIVED"));
        $product['_featuresTree'] = $this->getFeatures_Tree();

        // $product['_statuses'] = $this->_getCachedTableStatuses(configurationShopDataSource::$Table_ShopProducts);
        // save product into recently viewed list
        if ($saveIntoRecent && !glIsToolbox()) {
            $recentProducts = isset($_SESSION[$this->_listKey_Recent]) ? $_SESSION[$this->_listKey_Recent] : array();
            $recentProducts[$productID] = $product;
            $_SESSION[$this->_listKey_Recent] = $recentProducts;
        }
        return $product;
    }

    public function getProductImages ($productID) {
        $images = array();
        $config = configurationShopDataSource::jsapiShopGetProductAttributes($productID, 'IMAGE');
        $data = $this->getCustomer()->fetch($config);
        if (!empty($data)) {
            foreach ($data as $item) {
                $images[] = array(
                    'name' => $item['Value'],
                    'normal' => $this->getUploadedFileForWeb($item['Value'], $this->getProductUploadDir($productID)),
                    'sm' => $this->getUploadedFileForWeb($item['Value'], $this->getProductUploadDir($productID, 'sm')),
                    'xs' => $this->getUploadedFileForWeb($item['Value'], $this->getProductUploadDir($productID, 'xs'))
                );
            }
        }
        return $images;
    }

    public function getProductVideos ($productID) {
        $videos = array();
        $config = configurationShopDataSource::jsapiShopGetProductAttributes($productID, 'VIDEO');
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
        $config = configurationShopDataSource::jsapiShopGetProductAttributes($productID);
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
        $config = configurationShopDataSource::jsapiShopGetProductFeatures($productID);
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
        $config = configurationShopDataSource::jsapiShopGetProductPriceStats($productID);
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
        $configProductsRelations = configurationShopDataSource::jsapiShopGetProductRelations($productID);
        $relatedItemsIDs = $this->getCustomer()->fetch($configProductsRelations);
        if (isset($relatedItemsIDs)) {
            foreach ($relatedItemsIDs as $relationItem) {
                $relatedProductID = intval($relationItem['ProductB_ID']);
                if ($relatedProductID === $productID)
                    continue;
                $relatedProduct = $this->getProductByID($relatedProductID, false, true);
                if (isset($relatedProduct))
                    $relations[] = $relatedProduct;
            }
        }
        return $relations;
    }

    public function getProductUploadDir ($productID, $mode = false) {
        if (!empty($mode))
            return 'products' . DS . $productID . DS . $mode;
        return 'products' . DS . $productID;
    }

    public function getProducts_List (array $options = array()) {
        $config = configurationShopDataSource::jsapiShopGetProductList($options);
        $self = $this;

        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $orderRawItem) {
                    $_items[] = $self->getProductByID($orderRawItem['ID']);
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
                $dataList['_category'] = $this->getCategoryByID($options['_fCategoryID']);
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

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
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
                        $config = configurationShopDataSource::jsapiShopCreateFeature($data);
                        $featureID = $this->getCustomer()->fetch($config) ?: null;
                        if (isset($featureID) && $featureID >= 0) {
                            $productFeaturesIDs[] = $featureID;
                            $this->_getOrSetCachedState('changed:features', true);
                        }
                    }
                }

                // create product
                $validatedValues["CustomerID"] = $CustomerID;
                if (isset($validatedValues["IsPromo"])) {
                    $validatedValues["IsPromo"] = $validatedValues["IsPromo"] ? 1 : 0;
                }
                $config = configurationShopDataSource::jsapiShopCreateProduct($validatedValues);
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
                        $config = configurationShopDataSource::jsapiShopAddFeatureToProduct($featureData);
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
                            $newFileName = $ProductID . uniqid(time());
                            $uploadInfo = $this->saveUploadedFile('sm' . DS . $fileName, $this->getProductUploadDir($ProductID, 'sm'), $newFileName);
                            $this->saveUploadedFile('xs' . DS . $fileName, $this->getProductUploadDir($ProductID, 'xs'), $newFileName);
                            $this->saveUploadedFile($fileName, $this->getProductUploadDir($ProductID), $newFileName);
                            $attrData = $initAttrData->getArrayCopy();
                            $attrData['Attribute'] = 'IMAGE';
                            $attrData['Value'] = $uploadInfo['filename'];
                            $config = configurationShopDataSource::jsapiShopAddAttributeToProduct($attrData);
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
                        $config = configurationShopDataSource::jsapiShopAddAttributeToProduct($attrData);
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

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
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
                $featureMap = $this->getFeatures_Tree();
                foreach ($features as $groupName => $featureList) {
                    if (isset($featureMap[$groupName])) {
                        foreach ($featureList as $featureName) {
                            $featureID = array_search($featureName, $featureMap[$groupName]);
                            if ($featureID === false) {
                                $data = array();
                                $data["CustomerID"] = $CustomerID;
                                $data["FieldName"] = $featureName;
                                $data["GroupName"] = $groupName;
                                $config = configurationShopDataSource::jsapiShopCreateFeature($data);
                                $featureID = $this->getCustomer()->fetch($config);
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
                            $config = configurationShopDataSource::jsapiShopCreateFeature($data);
                            $featureID = $this->getCustomer()->fetch($config);
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
                $config = configurationShopDataSource::jsapiShopUpdateProduct($ProductID, $validatedValues);
                $this->getCustomer()->fetch($config);

                // set new features
                if (count($productFeaturesIDs)) {
                    // clear existed features before adding new
                    $config = configurationShopDataSource::jsapiShopClearProductFeatures($ProductID);
                    $this->getCustomer()->fetch($config);
                    $featureData['ProductID'] = $ProductID;
                    $featureData['CustomerID'] = $CustomerID;
                    foreach ($productFeaturesIDs as $value) {
                        $featureData['FeatureID'] = $value;
                        // var_dump($featureData);
                        $config = configurationShopDataSource::jsapiShopAddFeatureToProduct($featureData);
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
                    $uploadInfo = $this->saveUploadedFile('sm' . DS . $fileName, $this->getProductUploadDir($ProductID, 'sm'), $newFileName);
                    $this->saveUploadedFile('xs' . DS . $fileName, $this->getProductUploadDir($ProductID, 'xs'), $newFileName);
                    $this->saveUploadedFile($fileName, $this->getProductUploadDir($ProductID), $newFileName);
                    $uploadedFileNames[] = $uploadInfo['filename'];
                }
                foreach ($filesToDelete as $fileName) {
                    $this->deleteUploadedFile($fileName, $this->getProductUploadDir($ProductID, 'sm'));
                    $this->deleteUploadedFile($fileName, $this->getProductUploadDir($ProductID, 'xs'));
                    $this->deleteUploadedFile($fileName, $this->getProductUploadDir($ProductID));
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
                        $config = configurationShopDataSource::jsapiShopClearProductAttributes($ProductID, 'IMAGE');
                        $this->getCustomer()->fetch($config);
                        foreach ($attributes["IMAGE"] as $imageName) {
                            $attrData = $initAttrData->getArrayCopy();
                            $attrData['Attribute'] = 'IMAGE';
                            $attrData['Value'] = $imageName;
                            $config = configurationShopDataSource::jsapiShopAddAttributeToProduct($attrData);
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
                        $config = configurationShopDataSource::jsapiShopClearProductAttributes($ProductID, $key);
                        $this->getCustomer()->fetch($config);
                        $attrData = $initAttrData->getArrayCopy();
                        $attrData['Attribute'] = $key;
                        $attrData['Value'] = $attributes[$key];
                        $config = configurationShopDataSource::jsapiShopAddAttributeToProduct($attrData);
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

        $result = $this->getProductByID($ProductID, false, false);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function archiveProduct ($ProductID) {

    }

    public function getProducts_TopNonPopular () {
        // get non-popuplar 15 products
        $config = configurationShopDataSource::jsapiShopStat_NonPopularProducts();
        $productIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($productIDs)) {
            foreach ($productIDs as $val) {
                $data[] = $this->getProductByID($val['ID'], false, false);
            }
        }
        return $data;
    }

    public function getProducts_TopPopular () {
        // get top 15 products
        $config = configurationShopDataSource::jsapiShopStat_PopularProducts();
        $productIDs = $this->getCustomer()->fetch($config);
        $data = array();
        if (!empty($productIDs)) {
            foreach ($productIDs as $val) {
                $product = $this->getProductByID($val['ProductID'], false, false);
                $product['SoldTotal'] = floatval($val['SoldTotal']);
                $product['SumTotal'] = floatval($val['SumTotal']);
                $data[] = $product;
            }
        }
        return $data;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // FEATURES
    // -----------------------------------------------
    // -----------------------------------------------
    public function getFeatures_Tree () {
        $tree = array();
        $config = configurationShopDataSource::jsapiShopGetFeatures();
        $data = $this->getCustomer()->fetch($config);
        if (!empty($data)) {
            foreach ($data as $value) {
                if (!isset($tree[$value['GroupName']])) {
                    $tree[$value['GroupName']] = array();
                }
                $tree[$value['GroupName']][$value['ID']] = $value['FieldName'];
            }
        }
        return $tree;
    }




    // -----------------------------------------------
    // -----------------------------------------------
    // ORIGINS
    // -----------------------------------------------
    // -----------------------------------------------
    public function getOriginByID ($originID) {
        if (empty($originID) || !is_numeric($originID))
            return null;

        $config = configurationShopDataSource::jsapiShopGetOriginItem($originID);
        $origin = $this->getCustomer()->fetch($config);

        if (empty($origin))
            return null;

        $origin['ID'] = intval($origin['ID']);
        $origin['_isRemoved'] = $origin['Status'] === 'REMOVED';
        // $origin['_statuses'] = $this->_getCachedTableStatuses(configurationShopDataSource::$Table_ShopOrigins);
        return $origin;
    }

    public function getOrigins_List (array $options = array()) {
        $config = configurationShopDataSource::jsapiShopGetOriginList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getOriginByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function createOrigin ($reqData) {
        $result = array();
        $errors = array();
        $success = false;
        $OriginID = null;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 100),
            'Description' => array('string', 'skipIfUnset'),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $validatedValues["CustomerID"] = $this->getCustomer()->getCustomerID();

                $configCreateOrigin = configurationShopDataSource::jsapiShopCreateOrigin($validatedValues);

                $this->getCustomerDataBase()->beginTransaction();
                $OriginID = $this->getCustomer()->fetch($configCreateOrigin) ?: null;
                // var_dump($OriginID);

                if (empty($OriginID))
                    throw new Exception('OriginCreateError');

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        if ($success && !empty($OriginID))
            $result = $this->getOriginByID($OriginID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateOrigin ($OriginID, $reqData) {
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'Name' => array('string', 'skipIfUnset', 'min' => 1, 'max' => 100),
            'Description' => array('string', 'skipIfUnset'),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300),
            'Status' => array('string', 'skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                if (count($validatedValues)) {
                    $this->getCustomerDataBase()->beginTransaction();
                    $configCreateCategory = configurationShopDataSource::jsapiShopUpdateOrigin($OriginID, $validatedValues);
                    $this->getCustomer()->fetch($configCreateCategory);
                    $this->getCustomerDataBase()->commit();
                }

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getOriginByID($OriginID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function disableOrigin ($OriginID) {
        $errors = array();
        $success = false;

        try {
            $this->getCustomerDataBase()->beginTransaction();

            $config = configurationShopDataSource::jsapiShopDeleteOrigin($OriginID);
            $this->getCustomer()->fetch($config);

            $this->getCustomerDataBase()->commit();

            $success = true;
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
            $errors[] = 'OriginUpdateError';
        }

        $result = $this->getOriginByID($OriginID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // CATEGORIES
    // -----------------------------------------------
    // -----------------------------------------------
    public function getCategoryByID ($categoryID) {
        $config = configurationShopDataSource::jsapiShopGetCategoryItem($categoryID);
        $category = $this->getCustomer()->fetch($config);

        if (empty($category))
            return null;

        $category['ID'] = intval($category['ID']);
        // $category['RootID'] = is_null($category['RootID']) ? null : intval($category['RootID']);
        $category['ParentID'] = is_null($category['ParentID']) ? null : intval($category['ParentID']);
        $category['_isRemoved'] = $category['Status'] === 'REMOVED';
        // $category['CustomerID'] = intval($category['CustomerID']);
        // $category['_statuses'] = $this->_getCachedTableStatuses(configurationShopDataSource::$Table_ShopCategories);

        return $category;
    }

    public function getCategories_List (array $options = array()) {
        $config = configurationShopDataSource::jsapiShopGetCategoryList($options);
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

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 100),
            'ParentID' => array('int', 'skipIfUnset'),
            'Description' => array('string', 'skipIfUnset', 'max' => 300)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $this->getCustomerDataBase()->beginTransaction();

                $validatedValues["CustomerID"] = $this->getCustomer()->getCustomerID();

                $configCreateCategory = configurationShopDataSource::jsapiShopCreateCategory($validatedValues);
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

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'Name' => array('string', 'skipIfUnset', 'min' => 1, 'max' => 100),
            'Description' => array('string', 'skipIfUnset', 'max' => 300),
            'ParentID' => array('int', 'null', 'skipIfUnset'),
            'Status' => array('string', 'skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $this->getCustomerDataBase()->beginTransaction();

                $configCreateCategory = configurationShopDataSource::jsapiShopUpdateCategory($CategoryID, $validatedValues);
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

            $config = configurationShopDataSource::jsapiShopDeleteCategory($CategoryID);
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
    // ORDERS
    // -----------------------------------------------
    // -----------------------------------------------
    public function getOrderByID ($orderID) {
        $config = configurationShopDataSource::jsapiShopGetOrderItem($orderID);
        $order = null;
        // if ($this->getCustomer()->ifYouCan('Admin')) {
            $order = $this->getCustomer()->fetch($config);
        // } else {
        //     $config['condition']['AccountID'] = configurationShopDataSource::jsapiCreateDataSourceCondition($this->getCustomer()->getAuthID());
        //     $order = $this->getCustomer()->fetch($config);
        // }

        if (empty($order)) {
            return glWrap('error', 'OrderDoesNotExist');
        }

        $order['ID'] = intval($order['ID']);
        $this->__attachOrderDetails($order);

        if ($this->getCustomer()->ifYouCan('Admin')) {
            $order['_statuses'] = $this->_getCachedTableStatuses(configurationShopDataSource::$Table_ShopOrders);
        }
        return $order;
    }

    public function getOrderByHash ($orderHash) {
        $config = configurationShopDataSource::jsapiGetShopOrderByHash($orderHash);
        $order = $this->getCustomer()->fetch($config);

        if (empty($order)) {
            return glWrap('error', 'OrderDoesNotExist');
        }

        $order['ID'] = intval($order['ID']);
        $this->__attachOrderDetails($order);
        return $order;
    }

    private function _getOrderTemp () {
        $order['temp'] = true;
        $this->__attachOrderDetails($order);
        return $order;
    }

    private function _resetOrderTemp () {
        $this->_resetSessionPromo();
        $this->_resetSessionOrderProducts();
    }

    public function getOrders_ListExpired (array $options = array()) {
        // get expired orders
        $config = configurationShopDataSource::jsapiGetShopOrderList_Expired();
        // check permissions to display either all or user's orders only
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $config['condition']['AccountID'] = configurationShopDataSource::jsapiCreateDataSourceCondition($this->getCustomer()->getAuthID());
        }
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $orderRawItem)
                    $_items[] = $self->getOrderByID($orderRawItem['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function getOrders_ListTodays (array $options = array()) {
        // get todays orders
        $config = configurationShopDataSource::jsapiGetShopOrderList_Todays();
        // set permissions
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $config['condition']['AccountID'] = configurationShopDataSource::jsapiCreateDataSourceCondition($this->getCustomer()->getAuthID());
        }
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $orderRawItem)
                    $_items[] = $self->getOrderByID($orderRawItem['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function getOrders_ListPending (array $options = array()) {
        // get expired orders
        $config = configurationShopDataSource::jsapiGetShopOrderList_Pending();
        // check permissions
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $config['condition']['AccountID'] = configurationShopDataSource::jsapiCreateDataSourceCondition($this->getCustomer()->getAuthID());
        }
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $orderRawItem)
                    $_items[] = $self->getOrderByID($orderRawItem['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function getOrders_List (array $options = array()) {
        // get all orders
        $config = configurationShopDataSource::jsapiGetShopOrderList($options);
        // check permissions
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $config['condition']['AccountID'] = configurationShopDataSource::jsapiCreateDataSourceCondition($this->getCustomer()->getAuthID());
        }
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $orderRawItem) {
                    $_items[] = $self->getOrderByID($orderRawItem['ID']);
                }
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);

        if (isset($options['_pStats']))
            $dataList['stats'] = $this->getStats_OrdersOverview();

        return $dataList;
    }

    public function createOrder ($reqData) {

        $result = array();
        $errors = array();
        $success = false;

        // var_dump($order);
        // var_dump($reqData);

        $accountToken =  "";
        $formAccountToken = "";
        $formAddressID = "";

        if (!empty($reqData['account']))
            $accountToken = $reqData['account']['ValidationString'];

        if (!empty($reqData['form']['shopCartAccountValidationString']))
            $formAccountToken = $reqData['form']['shopCartAccountValidationString'];

        if (!empty($reqData['form']['shopCartAccountValidationString']))
            $formAddressID = $reqData['form']['shopCartAccountAddressID'];

        $pluginAccount = $this->getAnotherPlugin('account');

        $formSettings = $this->api->settings->getSettingsMapFormOrder();

        try {
            $this->getCustomerDataBase()->beginTransaction();
            $this->getCustomerDataBase()->disableTransactions();


            // check if matches
            if ($accountToken !== $formAccountToken)
                throw new Exception("WrongTokensOccured", 1);

            // create new profile
            if (empty($accountToken) && empty($formAccountToken)) {

                // reset address id becuase empty account is occured
                $formAddressID = null;

                // create new account
                $new_password = librarySecure::generateStrongPassword();

                $account = $pluginAccount->createAccount(array(
                    "FirstName" => $formSettings['ShowName']['_isActive'] ? $reqData['form']['shopCartUserName'] : $pluginAccount->getEmptyUserName(),
                    "EMail" => $formSettings['ShowEMail']['_isActive'] ? $reqData['form']['shopCartUserEmail'] : libraryValidate::getEmptyEmail(),
                    "Phone" => $formSettings['ShowPhone']['_isActive'] ? $reqData['form']['shopCartUserPhone'] : libraryValidate::getEmptyPhoneNumber(),
                    "Password" => $new_password,
                    "ConfirmPassword" => $new_password
                ));

                if (count($account['errors']))
                    $errors['Account'] = $account['errors'];

                if ($account['success'] === false)
                    throw new Exception("AccountCreateError", 1);

            } else {

                // get account by token string (ValidationString)
                $account = $pluginAccount->getAccountByValidationString($formAccountToken);

                if (empty($account))
                    throw new Exception("WrongAccount", 1);

                if ($account['Status'] !== "ACTIVE")
                    throw new Exception("AccountIsBlocked", 1);

                // need to validate account
                // if account exits
                // if account is active
                if ($account["FirstName"] !== $reqData["form"]["shopCartAccountFirstName"] ||
                    $account["LastName"] !== $reqData["form"]["shopCartAccountLastName"] ||
                    $account["EMail"] !== $reqData["form"]["shopCartAccountEMail"] ||
                    $account["Phone"] !== $reqData["form"]["shopCartAccountPhone"])
                    throw new Exception("AccountDataMismatch", 1);

                // at this point we have a valid account
            }

            $accountID = $account['ID'];

            // create new address for account
            if (empty($formAddressID) || empty($formAccountToken)) {

                // create account address
                $accountAddress = $pluginAccount->createAddress($accountID, array(
                    "Address" => $formSettings['ShowAddress']['_isActive'] ? $reqData['form']['shopCartUserAddress'] : 'n/a',
                    "POBox" => $formSettings['ShowPOBox']['_isActive'] ? $reqData['form']['shopCartUserPOBox'] : 'n/a',
                    "Country" => $formSettings['ShowCountry']['_isActive'] ? $reqData['form']['shopCartUserCountry'] : 'n/a',
                    "City" => $formSettings['ShowCity']['_isActive'] ? $reqData['form']['shopCartUserCity'] : 'n/a'
                ), true); // <= this allows creating unliked addresses or add new address to account when it's possible

                if (count($accountAddress['errors']))
                    $errors['Account'] = $accountAddress['errors'];

                if ($accountAddress['success'] === false)
                    throw new Exception("AccountAddressCreateError", 1);

            } else {
                // validate provided address id for account
                // we must check it if the authenticated account has this address id
                if (!isset($account['Addresses'][$formAddressID]))
                    throw new Exception("WrongAccountAddressID", 1);
                else
                    $accountAddress = $account['Addresses'][$formAddressID];
            }

            $addressID = $accountAddress['ID'];

            $order = $this->_getOrderTemp();
            // $sessionOrderProducts = $this->_getSessionOrderProducts();

            // var_dump($this->_getSessionOrderProducts());
            if (empty($order['items']))
                 throw new Exception("NoProudctsToPurchase", 1);

            $orderPromoID = empty($order['promo']) ? null : $order['promo']['ID'];

            // start creating order
            $dataOrder = array();
            $dataOrder["AccountID"] = $accountID;
            $dataOrder["AccountAddressesID"] = $addressID;
            $dataOrder["CustomerID"] = $this->getCustomer()->getCustomerID();
            $dataOrder["DeliveryID"] = $formSettings['ShowDeliveryAganet']['_isActive'] ? $reqData['form']['shopCartLogistic'] : null;
            $dataOrder["Warehouse"] = $formSettings['ShowDeliveryAganet']['_isActive'] ? $reqData['form']['shopCartWarehouse'] : null;
            $dataOrder["Comment"] = $formSettings['ShowComment']['_isActive'] ? $reqData['form']['shopCartComment'] : '';
            $dataOrder["PromoID"] = $orderPromoID;

            $configOrder = configurationShopDataSource::jsapiShopCreateOrder($dataOrder);
            $orderID = $this->getCustomer()->fetch($configOrder);

            if (empty($orderID))
                throw new Exception("OrderCreateError", 1);

            // save products
            // -----------------------
            // ProductID
            // OrderID
            // ProductPrice
            // _orderQuantity
            foreach ($order['items'] as $productItem) {
                $dataBought = array();
                $dataBought["CustomerID"] = $this->getCustomer()->getCustomerID();
                $dataBought["ProductID"] = $productItem["ID"];
                $dataBought["OrderID"] = $orderID;
                $dataBought["Price"] = $productItem["Price"];
                $dataBought["SellingPrice"] = $productItem["SellingPrice"];
                $dataBought["Quantity"] = $productItem["_orderQuantity"];
                $dataBought["IsPromo"] = $productItem["IsPromo"];
                $configBought = configurationShopDataSource::jsapiShopCreateOrderBought($dataBought);
                $boughtID = $this->getCustomer()->fetch($configBought);

                // check for created bought
                if (empty($boughtID))
                    throw new Exception("BoughtCreateError", 1);
            }

            // if (empty($accountID) || empty($addressID))
            //     throw new Exception("UnableToLinkAccountOrAddress", 1);

            $this->getCustomerDataBase()->enableTransactions();
            $this->getCustomerDataBase()->commit();

            $success = true;
        } catch (Exception $e) {
            $this->getCustomerDataBase()->enableTransactions();
            $this->getCustomerDataBase()->rollBack();
            $errors['Order'][] = $e->getMessage();
            $success = false;
        }

        if ($success) {
            // reset temp order
            $this->_resetOrderTemp();
            // get created order

            $result = $this->getOrderByID($orderID);
        }

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateOrder ($OrderID, $reqData) {
        $errors = array();
        $success = false;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'Status' => array('skipIfUnset', 'string', 'notEmpty'),
            'InternalComment' => array('skipIfUnset', 'string')
        ));

        // var_dump($validatedDataObj);

        // return;
        if ($validatedDataObj['count'] > 0) {
            if ($validatedDataObj["totalErrors"] == 0)
                try {

                    $this->getCustomerDataBase()->beginTransaction();

                    $validatedValues = $validatedDataObj['values'];

                    $configUpdateOrder = configurationShopDataSource::jsapiShopUpdateOrder($OrderID, $validatedValues);

                    $this->getCustomer()->fetch($configUpdateOrder, true);

                    $this->getCustomerDataBase()->commit();

                    $success = true;
                } catch (Exception $e) {
                    $this->getCustomerDataBase()->rollBack();
                    $errors[] = 'OrderUpdateError';
                }
            else
                $errors = $validatedDataObj["errors"];
        }

        $result = $this->getOrderByID($OrderID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function disableOrderByID ($OrderID) {
        $errors = array();
        $success = false;

        try {

            $this->getCustomerDataBase()->beginTransaction();

            $config = configurationShopDataSource::jsapiShopDisableOrder($OrderID);
            $this->getCustomer()->fetch($config);

            $this->getCustomerDataBase()->commit();

            $success = true;
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
            $errors[] = 'OrderUpdateError';
        }

        $result = $this->getOrderByID($OrderID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // STATISTIC
    // -----------------------------------------------
    // -----------------------------------------------
    public function getStats_OrdersOverview () {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        // get orders count for each states
        $config = configurationShopDataSource::jsapiShopStat_OrdersOverview();
        $data = $this->getCustomer()->fetch($config) ?: array();
        $total = 0;
        $res = array();
        $availableStatuses = $this->_getCachedTableStatuses(configurationShopDataSource::$Table_ShopOrders);
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

    public function getStats_OrdersIntensityLastMonth ($status, $comparator = null) {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        $config = configurationShopDataSource::jsapiShopStat_OrdersIntensityLastMonth($status, $comparator);
        $data = $this->getCustomer()->fetch($config) ?: array();
        // var_dump($config);
        return $data;
    }

    public function getStats_ProductsOverview ($filter = null) {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        // get shop products overview:
        $config = configurationShopDataSource::jsapiShopStat_ProductsOverview($filter);
        $data = $this->getCustomer()->fetch($config) ?: array();
        $total = 0;
        $res = array();
        $availableStatuses = $this->_getCachedTableStatuses(configurationShopDataSource::$Table_ShopProducts);
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

    public function getStats_ProductsIntensityLastMonth ($status) {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        $config = configurationShopDataSource::jsapiShopStat_ProductsIntensityLastMonth($status);
        $data = $this->getCustomer()->fetch($config) ?: array();
        // var_dump($config);
        return $data;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // BREADCRUMB
    // -----------------------------------------------
    // -----------------------------------------------
    public function _getCatalogLocation ($productID = null, $categoryID = null) {
        $location = null;

        if (empty($productID) && empty($categoryID))
            return $location;

        if ($productID) {
            // get product entry
            $configProduct = configurationShopDataSource::jsapiShopGetProductItem($productID, false);

            $productDataEntry = $this->getCustomer()->fetch($configProduct);
            if (isset($productDataEntry['CategoryID'])) {
                $configLocation = configurationShopDataSource::jsapiShopCategoryLocationGet($productDataEntry['CategoryID']);
                $location['items'] = $this->getCustomer()->fetch($configLocation);
                $location['product'] = $productDataEntry;
            }
        } else {
            $configLocation = configurationShopDataSource::jsapiShopCategoryLocationGet($categoryID);
            $location['items'] = $this->getCustomer()->fetch($configLocation);
        }
        return $location;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // SHOP CATALOG TREE
    // -----------------------------------------------
    // -----------------------------------------------
    public function _getCatalogTree () {

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

        $config = configurationShopDataSource::jsapiShopCatalogTree();
        $categories = $this->getCustomer()->fetch($config);
        $map = array();
        foreach ($categories as $key => $value)
            $map[$value['ID']] = $value;

        $tree = getTree($map);

        return $tree;
    }

    public function _getCatalogBrowse () {

        $data = array();
        $categoryID = libraryRequest::fromGET('id', null);

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
        $filterOptionsAvailable['filter_commonStatus'] = $this->_getCachedTableStatuses(configurationShopDataSource::$Table_ShopProducts);

        // init filter
        foreach ($filterOptionsApplied as $key => $value) {
            $filterOptionsApplied[$key] = libraryRequest::fromGET($key, $filterOptions[$key]);
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

        $dataConfigCategoryPriceEdges = configurationShopDataSource::jsapiShopCategoryPriceEdgesGet($categoryID);
        $dataConfigCategoryAllSubCategories = configurationShopDataSource::jsapiShopCategoryAllSubCategoriesGet($categoryID);

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
        $dataConfigCategoryAllBrands = configurationShopDataSource::jsapiShopCategoryAndSubCategoriesAllBrandsGet(implode(',', $cetagorySubIDs));
        $dataCategoryAllBrands = $this->getCustomer()->fetch($dataConfigCategoryAllBrands);

        // set categories and brands
        $filterOptionsAvailable['filter_categoryBrands'] = $dataCategoryAllBrands ?: array();
        $filterOptionsAvailable['filter_categorySubCategories'] = $dataCategoryAllSubCategories ?: array();

        // set data source
        // ---
        $dataConfigCategoryInfo = configurationShopDataSource::jsapiGetShopCategoryProductInfo($cetagorySubIDs);
        $dataConfigProducts = configurationShopDataSource::jsapiGetShopCategoryProductList($cetagorySubIDs);

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
            $dataConfigProducts['condition']['Price'][] = configurationShopDataSource::jsapiCreateDataSourceCondition($filterOptionsApplied['filter_commonPriceMax'], '<=');
        else
            $filterOptionsApplied['filter_commonPriceMax'] = $filterOptionsAvailable['filter_commonPriceMax'];

        if ($filterOptionsApplied['filter_commonPriceMax'] > $filterOptionsApplied['filter_commonPriceMin'] && $filterOptionsApplied['filter_commonPriceMin'] >= $filterOptionsAvailable['filter_commonPriceMin'])
            $dataConfigProducts['condition']['Price'][] = configurationShopDataSource::jsapiCreateDataSourceCondition($filterOptionsApplied['filter_commonPriceMin'], '>=');
        else
            $filterOptionsApplied['filter_commonPriceMin'] = $filterOptionsAvailable['filter_commonPriceMin'];

        // var_dump($filterOptionsApplied);
        if (count($filterOptionsApplied['filter_commonFeatures']))
            $dataConfigProducts['condition']["FeatureID"] = configurationShopDataSource::jsapiCreateDataSourceCondition($filterOptionsApplied['filter_commonFeatures'], 'in');

        if (count($filterOptionsApplied['filter_commonStatus']))
            $dataConfigProducts['condition']["shop_products.Status"] = configurationShopDataSource::jsapiCreateDataSourceCondition($filterOptionsApplied['filter_commonStatus'], 'in');

        // filter: brands
        if (count($filterOptionsApplied['filter_categoryBrands']))
            $dataConfigProducts['condition']['OriginID'] = configurationShopDataSource::jsapiCreateDataSourceCondition($filterOptionsApplied['filter_categoryBrands'], 'in');

        // var_dump($dataConfigProducts);
        // get products
        $dataProducts = $this->getCustomer()->fetch($dataConfigProducts);
        // get category info according to product filter
        if (isset($dataConfigProducts['condition']['Price']))
            $dataConfigCategoryInfo['condition']['Price'] = $dataConfigProducts['condition']['Price'];
        // $dataConfigCategoryInfo['condition'] = new ArrayObject($dataConfigProducts['condition']);
        $dataCategoryInfo = $this->getCustomer()->fetch($dataConfigCategoryInfo);

        $products = array();
        if (!empty($dataProducts))
            foreach ($dataProducts as $val)
                $products[] = $this->getProductByID($val['ID'], false, false);

        $productsInfo = array();
        if (!empty($dataCategoryInfo))
            foreach ($dataCategoryInfo as $val)
                $productsInfo[] = $this->getProductByID($val['ID'], false, false);

        // adjust brands, categories and features
        $brands = array();
        $categories = array();
        $statuses = array();//$this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopProducts);
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
        // return data object
        return $data;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // PROMO
    // -----------------------------------------------
    // -----------------------------------------------
    public function getPromoByID ($promoID) {
        $config = configurationShopDataSource::jsapiShopGetPromoByID($promoID);
        $data = $this->getCustomer()->fetch($config);
        $data['ID'] = intval($data['ID']);
        $data['Discount'] = floatval($data['Discount']);
        $data['_isExpired'] = strtotime(configurationShopDataSource::getDate()) > strtotime($data['DateExpire']);
        $data['_isFuture'] = strtotime(configurationShopDataSource::getDate()) < strtotime($data['DateStart']);
        $data['_isActive'] = !$data['_isExpired'] && !$data['_isFuture'];
        return $data;
    }

    public function getPromoByHash ($hash, $activeOnly = false) {
        $config = configurationShopDataSource::jsapiShopGetPromoByHash($hash, $activeOnly);
        $data = $this->getCustomer()->fetch($config);
        $data['ID'] = intval($data['ID']);
        $data['Discount'] = floatval($data['Discount']);
        return $data;
    }

    public function getPromoCodes_List (array $options = array()) {
        $config = configurationShopDataSource::jsapiShopGetPromoList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getPromoByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function createPromo ($reqData) {
        $result = array();
        $errors = array();
        $success = false;
        $promoID = null;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'DateStart' => array('string'),
            'DateExpire' => array('string'),
            'Discount' => array('numeric')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];
                $validatedValues["Code"] = rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999);
                $validatedValues["CustomerID"] = $this->getCustomer()->getCustomerID();

                $configCreatePromo = configurationShopDataSource::jsapiShopCreatePromo($validatedValues);

                $this->getCustomerDataBase()->beginTransaction();
                $promoID = $this->getCustomer()->fetch($configCreatePromo) ?: null;

                if (empty($promoID))
                    throw new Exception('PromoCreateError');

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        if ($success && !empty($promoID))
            $result = $this->getPromoByID($promoID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updatePromo ($promoID, $reqData) {
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'DateStart' => array('string', 'skipIfUnset'),
            'DateExpire' => array('string', 'skipIfUnset'),
            'Discount' => array('numeric')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                if (count($validatedValues)) {
                    $this->getCustomerDataBase()->beginTransaction();
                    $configCreateCategory = configurationShopDataSource::jsapiShopUpdatePromo($promoID, $validatedValues);
                    $this->getCustomer()->fetch($configCreateCategory);
                    $this->getCustomerDataBase()->commit();
                }

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getPromoByID($promoID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function expirePromo ($promoID) {
        $result = array();
        $errors = array();
        $success = false;

        try {
            $this->getCustomerDataBase()->beginTransaction();
            $config = configurationShopDataSource::jsapiShopExpirePromo($promoID);
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

    // -----------------------------------------------
    // -----------------------------------------------
    // SESSION DATA
    // -----------------------------------------------
    // -----------------------------------------------
    private function _setSessionPromo ($promo) {
        $_SESSION[$this->_listKey_Promo] = $promo;
    }

    private function _getSessionPromo () {
        if (!isset($_SESSION[$this->_listKey_Promo]))
            $_SESSION[$this->_listKey_Promo] = null;
        return $_SESSION[$this->_listKey_Promo];
    }

    private function _resetSessionPromo () {
        $_SESSION[$this->_listKey_Promo] = null;
    }

    private function _setSessionOrderProducts ($order) {
        $_SESSION[$this->_listKey_Cart] = $order;
    }

    private function _getSessionOrderProducts () {
        if (!isset($_SESSION[$this->_listKey_Cart]))
            $_SESSION[$this->_listKey_Cart] = array();
        return $_SESSION[$this->_listKey_Cart];
    }

    private function _resetSessionOrderProducts () {
        $_SESSION[$this->_listKey_Cart] = array();
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // DATA CACHING UTILS
    // -----------------------------------------------
    // -----------------------------------------------
    private function _getCachedTableData ($tableName) {
        $self = $this;
        $list = array();
        $refreshFromDB = false;
        $stateKey = false;
        $fn = false;
        $options = array(
            'limit' => 0
        );

        if ($tableName === configurationShopDataSource::$Table_ShopCategories) {
            $stateKey = 'category';
            $fn = function ($options) use ($self) {
                return $self->getCategories_List($options);
            };
        }
        if ($tableName === configurationShopDataSource::$Table_ShopOrigins) {
            $stateKey = 'origin';
            $fn = function ($options) use ($self) {
                return $self->getOrigins_List($options);
            };
        }
        if ($tableName === configurationShopDataSource::$Table_ShopFeatures) {
            $stateKey = 'features';
            // var_dump($this->_states);
            $fn = function ($options) use ($self) {
                return $self->getAllAvailableFeatures($options);
            };
        }
        if ($tableName === configurationShopDataSource::$Table_ShopDeliveryAgencies) {
            $stateKey = 'agencies';
            // var_dump($this->_states);
            $fn = function ($options) use ($self) {
                return $self->getDeliveries_List($options);
            };
        }

        if (!empty($tableName)) {
            $refreshFromDB = !isset($_SESSION[$tableName]) || $this->_getOrSetCachedState('changed:' . $stateKey);
            if ($refreshFromDB) {
                $list = $fn($options);
            } else {
                $list = $_SESSION[$tableName];
            }
            $_SESSION[$tableName] = $list;
            $this->_getOrSetCachedState('changed:' . $stateKey, false);
        }

        return $list;
    }

    private function _getCachedTableStatuses ($tableName = null, $force = false) {
        $statusDump = array ();
        $isExpired = true;
        if (isset($_SESSION['shop:statuses:expire'])) {
            //$isExpired = mktime() > intval($_SESSION['shop:statuses:expire']);
        }
        // daily update
        if ($force || $isExpired || empty($_SESSION['shop:statuses:list'])) {
            $statusDump[configurationShopDataSource::$Table_ShopOrders] = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopOrders);
            $statusDump[configurationShopDataSource::$Table_ShopProducts] = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopProducts);
            $statusDump[configurationShopDataSource::$Table_ShopOrigins] = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopOrigins);
            $statusDump[configurationShopDataSource::$Table_ShopCategories] = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopCategories);
            $statusDump[configurationShopDataSource::$Table_ShopDeliveryAgencies] = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopDeliveryAgencies);
            // $statusDump[configurationShopDataSource::$Table_ShopSettings] = $this->getCustomerDataBase()->getTableStatusFieldOptions(configurationShopDataSource::$Table_ShopSettings);
            $_SESSION['shop:statuses:list'] = $statusDump;
            $_SESSION['shop:statuses:expire'] = mktime() + 24 * 60 * 60;
        } else {
            $statusDump = $_SESSION['shop:statuses:list'];
        }
        if (!empty($tableName)) {
            if (isset($statusDump[$tableName])) {
                return $statusDump[$tableName];
            } else {
                throw new Exception("Unknown table name for status list dump", 1);
            }
        }
        setcookie("shop:statuses:list", json_encode($statusDump), $_SESSION['shop:statuses:expire'], "/");
    }

    private function _getOrSetCachedState ($key = null, $value = null) {
        if (!isset($_SESSION['shop:states'])) {
            $_SESSION['shop:states'] = $this->_states;
        }
        $_states = $_SESSION['shop:states'];
        if (is_null($key)) {
            return;
        } else {
            if (is_null($value)) {
                return $_states[$key];
            } else {
                $_states[$key] = $value;
                $_SESSION['shop:states'] = $_states;
            }
        }
    }

















    public function get_shop_stats (&$resp, $req) {

        $self = $this;
        $sources = array();
        // $sources['orders_new'] = function ($req) use ($self) {
        //     return $self->getOrders_ListPending($req);
        // };
        $sources['orders_list_pending'] = function ($req) use ($self) {
            return $self->getOrders_ListPending($req->get);
        };
        $sources['orders_list_todays'] = function ($req) use ($self) {
            return $self->getOrders_ListTodays($req->get);
        };
        $sources['orders_list_expired'] = function ($req) use ($self) {
            return $self->getOrders_ListExpired($req->get);
        };
        $sources['orders_intensity_last_month'] = function ($req) use ($self) {
            $res = array();
            $res['OPEN'] = $self->getStats_OrdersIntensityLastMonth('SHOP_CLOSED', '!=');
            $res['CLOSED'] = $self->getStats_OrdersIntensityLastMonth('SHOP_CLOSED');
            return $res;
        };
        $sources['overview_orders'] = function () use ($self) {
            return $self->getStats_OrdersOverview();
        };
        $sources['overview_products'] = function () use ($self) {
            return $self->getStats_ProductsOverview();
        };
        $sources['products_list_popular'] = function () use ($self) {
            $res = array();
            $res['items'] = $self->getProducts_TopPopular();
            return $res;
        };
        $sources['products_list_non_popular'] = function () use ($self) {
            $res = array();
            $res['items'] = $self->getProducts_TopNonPopular();
            return $res;
        };
        $sources['products_intensity_last_month'] = function () use ($self) {
            $res = array();
            $res['ACTIVE'] = $self->getStats_ProductsIntensityLastMonth('ACTIVE');
            $res['PREORDER'] = $self->getStats_ProductsIntensityLastMonth('PREORDER');
            $res['DISCOUNT'] = $self->getStats_ProductsIntensityLastMonth('DISCOUNT');
            return $res;
        };

        $type = false;
        if (!empty($req->get['type']))
            $type = $req->get['type'];

        if (isset($sources[$type]))
            $resp = $sources[$type]($req);
        else
            $resp['error'] = 'WrongType';
    }

    public function get_shop_location (&$resp, $req) {
        if (!isset($req->get['productID']) && !isset($req->get['categoryID'])) {
            $resp['error'] = 'The request must contain at least one of parameters: "productID" or "categoryID"';
            return;
        }
        $resp['location'] = $this->_getCatalogLocation(libraryRequest::fromGET('productID'), libraryRequest::fromGET('categoryID'));
    }





    // we have get_shop_categories or keep it for catalog only
    public function get_shop_catalog (&$resp, $req) {
        if (empty($req->get['type'])) {
            $resp['error'] = "MissedParameter_type";
        } else {
            switch ($req->get['type']) {
                case "tree": // this is already exists in get_shop_categories
                    $resp['tree'] = $this->_getCatalogTree();
                    break;
                case "browse":
                    $resp['browse'] = $this->_getCatalogBrowse();
                    break;
            }
        }
    }















    public function get_shop_promo (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $promoID = intval($req->get['id']);
            $resp = $this->getPromoByID($promoID);
        }
    }

    public function get_shop_promos (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->getPromoCodes_List($req->get);
    }

    public function post_shop_promo (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createPromo($req->data);
    }

    public function patch_shop_promo (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $promoID = intval($req->get['id']);
            $resp = $this->updatePromo($promoID, $req->data);
        }
    }

    public function delete_shop_promo (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $promoID = intval($req->get['id']);
            $resp = $this->expirePromo($promoID);
        }
    }














    public function get_shop_product (&$resp, $req) {
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $ProductID = intval($req->get['id']);
            $resp = $this->getProductByID($ProductID);
        }
    }

    public function get_shop_products (&$resp, $req) {
        $resp = $this->getProducts_List($req->get);
    }

    public function post_shop_product (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }

        $resp = $this->createProduct($req->data);
        $this->_getOrSetCachedState('changed:product', true);
    }
    public function patch_shop_product (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }

        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $ProductID = intval($req->get['id']);
            $resp = $this->updateProduct($ProductID, $req->data);
            $this->_getOrSetCachedState('changed:product', true);
        }
    }

    public function delete_shop_product (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }

        $resp = $this->archiveProduct($req->data);
        $this->_getOrSetCachedState('changed:product', true);
    }











    public function get_shop_features (&$resp, $req) {
        $resp = $this->getFeatures_Tree();
    }














    public function get_shop_category (&$resp, $req) {
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $CategoryID = intval($req->get['id']);
            $resp = $this->getCategoryByID($CategoryID);
        }
    }

    public function get_shop_categories (&$resp, $req) {
        $resp = $this->getCategories_List($req->get);
    }

    public function post_shop_category (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }

        $resp = $this->createCategory($req->data);
        $this->_getOrSetCachedState('changed:category', true);
    }

    public function patch_shop_category (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }

        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $CategoryID = intval($req->get['id']);
            $resp = $this->updateCategory($CategoryID, $req->data);
            $this->_getOrSetCachedState('changed:category', true);
        }
    }

    public function delete_shop_category (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $CategoryID = intval($req->get['id']);
            $resp = $this->disableCategory($CategoryID);
            $this->_getOrSetCachedState('changed:category', true);
        }
    }





    public function get_shop_origin (&$resp, $req) {
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $OriginID = intval($req->get['id']);
            $resp = $this->getOriginByID($OriginID);
        }
    }

    public function get_shop_origins (&$resp, $req) {
        $resp = $this->getOrigins_List($req->get);
    }

    public function post_shop_origin (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createOrigin($req->data);
        $this->_getOrSetCachedState('changed:origin', true);
    }

    public function patch_shop_origin (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $OriginID = intval($req->get['id']);
            $resp = $this->updateOrigin($OriginID, $req->data);
            $this->_getOrSetCachedState('changed:origin', true);
        }
    }

    public function delete_shop_origin (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $OriginID = intval($req->get['id']);
            $resp = $this->disableOrigin($OriginID);
            $this->_getOrSetCachedState('changed:origin', true);
        }
    }
























    public function get_shop_wish (&$resp) {
        $resp['items'] = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
    }

    public function post_shop_wish (&$resp, $req) { 
        $resp['items'] = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        if (isset($req->data['productID'])) {
            $productID = $req->data['productID'];
            if (!isset($resp['items'][$productID])) {
                $product = $this->getProductByID($productID, false, false);
                $resp['items'][$productID] = $product;
                $_SESSION[$this->_listKey_Wish] = $resp['items'];
            }
        } else
            $resp['error'] = "MissedParameter_productID";
    }

    public function delete_shop_wish (&$resp, $req) {
        $resp['items'] = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        if (isset($req->get['productID'])) {
            $productID = $req->get['productID'];
            if ($productID === "*") {
                $resp['items'] = array();
            } elseif (isset($resp['items'][$productID])) {
                unset($resp['items'][$productID]);
            }
            $_SESSION[$this->_listKey_Wish] = $resp['items'];
        }
    }

    private function __productIsInWishList ($id) {
        $list = array();
        $this->get_shop_wish($list);
        return isset($list['items'][$id]);
    }

    public function get_shop_compare (&$resp) {
        $resp['items'] = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        $resp['limit'] = 10;
    }

    public function post_shop_compare (&$resp, $req) {
        $resp['items'] = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        if (count($resp['items']) >= 10) {
            $resp['error'] = "ProductLimitExceeded";
            return;
        }
        if (isset($req->data['productID'])) {
            $productID = $req->data['productID'];
            if (!isset($resp['items'][$productID])) {
                $product = $this->getProductByID($productID, false, false);
                $resp['items'][$productID] = $product;
                $_SESSION[$this->_listKey_Compare] = $resp['items'];
            }
        }
    }

    public function delete_shop_compare (&$resp, $req) {
        $resp['items'] = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        if (isset($req->get['productID'])) {
            $productID = $req->get['productID'];
            if ($productID === "*") {
                $resp['items'] = array();
            } elseif (isset($resp['items'][$productID])) {
                unset($resp['items'][$productID]);
            }
            $_SESSION[$this->_listKey_Compare] = $resp['items'];
        }
    }

    private function __productIsInCompareList ($id) {
        $list = array();
        $this->get_shop_compare($list);
        return isset($list['items'][$id]);
    }




















    public function get_shop_order (&$resp, $req) {
        if (isset($req->get['id']) && $req->get['id'] !== "temp") {
            if ($this->getCustomer()->ifYouCan('Admin'))
                $resp = $this->getOrderByID($req->get['id']);
            else
                $resp['error'] = 'AccessDenied';
            return;
        } else if (isset($req->get['hash'])) {
            $resp = $this->getOrderByHash($req->get['hash']);
            return;
        } else {
            $resp = $this->_getOrderTemp();
        }
    }

    public function get_shop_orders (&$resp, $req) {
        $resp = $this->getOrders_List($req->get);
    }
    // create new order
    // public useres do that
    public function post_shop_order (&$resp, $req) {
        $resp = $this->createOrder($req->data);
    }

    // modify existent order status or
    // product quantity in the shopping cart list of temporary order
    // both admin can update any order and public uses as well
    public function patch_shop_order (&$resp, $req) {
        // var_dump($req);
        // var_dump($_SERVER['REQUEST_METHOD']);
        // var_dump($_POST);
        // var_dump(file_get_contents('php://input'));
        // $options = array();
        $isTemp = !isset($req->get['id']);

        if (!$isTemp && glIsToolbox()) {
            // if ($this->getCustomer()->ifYouCan('Admin')) {
                // here we're gonna change order's status only
        // check permissions
            if (!$this->getCustomer()->ifYouCan('Edit')) {
                $resp["error"] = "AccessDenied";
            } else {
                $resp = $this->updateOrder($req->get['id'], $req->data);
            }
            // } else {
                // $resp['error'] = 'AccessDenied';
            // }
            return;
        }

        // for temp order (site side only)
        if ($isTemp) {
            if (isset($req->data['productID'])) {
                $sessionOrderProducts = $this->_getSessionOrderProducts();
                $productID = $req->data['productID'];
                $newQuantity = floatval($req->data['_orderQuantity']);
                if (isset($sessionOrderProducts[$productID])) {
                    $sessionOrderProducts[$productID]['_orderQuantity'] = $newQuantity;
                    if ($sessionOrderProducts[$productID]['_orderQuantity'] <= 0)
                        unset($sessionOrderProducts[$productID]);
                    $this->_setSessionOrderProducts($sessionOrderProducts);
                } elseif ($newQuantity > 0) {
                    $product['ID'] = $productID;
                    $product['_orderQuantity'] = $newQuantity;
                    $sessionOrderProducts[$productID] = $product;
                    $this->_setSessionOrderProducts($sessionOrderProducts);
                } elseif ($req->data['productID'] === "*") {
                    $this->_resetSessionOrderProducts();
                }
            } elseif (isset($req->data['promo'])) {
                if (empty($req->data['promo']))
                    $this->_resetSessionPromo();
                else
                    $this->_setSessionPromo($this->getPromoByHash($req->data['promo'], true));
            }
            $resp = $this->_getOrderTemp();
        }
    }

    // removes particular product or clears whole shopping cart
    public function delete_shop_cart (&$resp, $req) { // ????? we must keep all orders
        if (!glIsToolbox()) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        if (!empty($req->get['id'])) {
            $OrderID = intval($req->get['id']);
            $resp = $this->disableOrderByID($OrderID);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    private function __productCountInCart ($id) {
        // $order = $this->_getOrderTemp();
        $sessionOrderProducts = $this->_getSessionOrderProducts();
        return isset($sessionOrderProducts[$id]) ? $sessionOrderProducts[$id]['_orderQuantity'] : 0;
    }

    private function __attachOrderDetails (&$order) {
        // echo "__attachOrderDetails";
        if (empty($order))
            return;

        $orderID = isset($order['ID']) ? $order['ID'] : null;
        $order['promo'] = null;
        $order['account'] = null;
        $order['address'] = null;
        $order['delivery'] = null;
        $productItems = array();
        // var_dump($order);
        // if orderID is set then the order is saved
        if (isset($orderID) && !isset($order['temp'])) {
            // attach account and address
            if ($this->getCustomer()->hasPlugin('account')) {
                if (isset($order['AccountAddressesID']))
                    $order['address'] = $this->getAnotherPlugin('account')->getAddressByID($order['AccountAddressesID']);
                if (isset($order['AccountID']))
                    $order['account'] = $this->getAnotherPlugin('account')->getAccountByID($order['AccountID']);
                unset($order['AccountID']);
                unset($order['AccountAddressesID']);
            }
            // get promo
            if (!empty($order['PromoID']))
                $order['promo'] = $this->getPromoByID($order['PromoID']);
            if (!empty($order['DeliveryID']))
                $order['delivery'] = $this->api->delivery->getDeliveryAgencyByID($order['DeliveryID']);
            // $order['items'] = array();
            $configBoughts = configurationShopDataSource::jsapiShopGetOrderBoughts($orderID);
            $boughts = $this->getCustomer()->fetch($configBoughts) ?: array();
            if (!empty($boughts))
                foreach ($boughts as $soldItem) {
                    $product = $this->getProductByID($soldItem['ProductID'], false, false);
                    // save current product info
                    $product["CurrentIsPromo"] = $product['IsPromo'];
                    $product["CurrentPrice"] = $product['Price'];
                    $product["CurrentSellingPrice"] = $product['SellingPrice'];
                    // restore product info at purchase moment
                    $product["Price"] = floatval($soldItem['Price']);
                    $product["SellingPrice"] = floatval($soldItem['SellingPrice']);
                    $product["IsPromo"] = intval($soldItem['IsPromo']) === 1;
                    // get purchased product quantity
                    $product["_orderQuantity"] = floatval($soldItem['Quantity']);
                    // actual price (with discount if promo is active)
                    // $price = isset($product['DiscountPrice']) ? $product['DiscountPrice'] : $product['Price'];
                    // set product gross and net totals
                    $product["_orderProductSubTotal"] = $product['Price'] * $product['_orderQuantity'];
                    $product["_orderProductTotal"] = $product['SellingPrice'] * $product['_orderQuantity'];
                    // add into list
                    $productItems[$product['ID']] = $product;
                }
        } else {
            // $productItems = !empty($order['items']) ? $order['items'] : array();
            $sessionPromo = $this->_getSessionPromo();
            $sessionOrderProducts = $this->_getSessionOrderProducts();
            // re-validate promo
            if (!empty($sessionPromo) && isset($sessionPromo['Code'])) {
                $sessionPromo = $this->getPromoByHash($sessionPromo['Code'], true);
                if (!empty($sessionPromo) && isset($sessionPromo['Code'])) {
                    $this->_setSessionPromo($sessionPromo);
                    $order['promo'] = $sessionPromo;
                } else {
                    $this->_resetSessionPromo();
                    $order['promo'] = null;
                }
            }
            // get prodcut items
            foreach ($sessionOrderProducts as $purchasingProduct) {
                $product = $this->getProductByID($purchasingProduct['ID'], false, false);
                // actual price (with discount if promo is active)
                // set product gross and net totals
                // get purchased product quantity
                $product["_orderQuantity"] = $purchasingProduct['_orderQuantity'];
                $product["_orderProductSubTotal"] = $product['Price'] * $purchasingProduct['_orderQuantity'];
                $product["_orderProductTotal"] = $product['SellingPrice'] * $purchasingProduct['_orderQuantity'];
                // add into list
                $productItems[$product['ID']] = $product;
            }
        }
        // append info
        $info = array(
            "subTotal" => 0.0,
            "total" => 0.0,
            "productCount" => 0,
            "productUniqueCount" => count($productItems),
            "hasPromo" => isset($order['promo']['Discount']) && $order['promo']['Discount'] > 0,
            "allProductsWithPromo" => true,
            "deliveries" => $this->api->delivery->getActiveDeliveryList()
        );
        // calc order totals
        foreach ($productItems as $product) {
            // update order totals
            $info["total"] += floatval($product['_orderProductTotal']);
            $info["subTotal"] += floatval($product['_orderProductSubTotal']);
            $info["productCount"] += intval($product['_orderQuantity']);
            $info["allProductsWithPromo"] = $info["allProductsWithPromo"] && $product['IsPromo'];
        }
        $order['items'] = $productItems;
        $order['info'] = $info;
    }
}

?>
<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\request as Request;
use \engine\lib\api as API;
use \static_\plugins\shop\api\shoputils as ShopUtils;
use Exception;
use ArrayObject;

class products extends API {

    private $_listKey_Recent = 'shop:listRecent';

    // -----------------------------------------------
    // -----------------------------------------------
    // PRODUCTS
    // -----------------------------------------------
    // -----------------------------------------------
    // product standalone item (short or full)
    // -----------------------------------------------

    private function _addProductIntoLastViewedList ($productID) {
        $recentProducts = isset($_SESSION[$this->_listKey_Recent]) ? $_SESSION[$this->_listKey_Recent] : array();
        $recentProducts[] = $productID;
        $_SESSION[$this->_listKey_Recent] = array_unique($recentProducts);
    }

    // TODO: optimmize list query
    public function getProducts_List (array $options = array()) {
        return $this->data->fetchProductsDataList($options);


        // global $app;
        // $config = $this->data->dbqProducts_MatchedIDs($options);
        // if (empty($config))
        //     return null;
        // $self = $this;
        // $callbacks = array(
        //     "parse" => function ($items) use($self, $saveIntoRecent) {
        //         $_items = array();
        //         foreach ($items as $key => $productRawItem) {
        //             $_items[] = $self->fetchSingleProductByID($productRawItem['ID']);
        //         }
        //         return $_items;
        //     }
        // );
        // $dataList = $app->getDB()->queryMatchedIDs($config, $options, $callbacks);
        // return $dataList;
    }

    // TODO: optimmize list query
    public function getNewProducts_List (array $options = array()) {
        global $app;
        // var_dump($options);
        $config = $this->data->dbqProducts_List_NewItems($options);
        if (empty($config))
            return null;
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $orderRawItem) {
                    $_items[] = $self->fetchSingleProductByID($orderRawItem['ID']);
                }
                return $_items;
            }
        );
        $dataList = $app->getDB()->queryMatchedIDs($config, $options, $callbacks);
        return $dataList;
    }

    // TODO: optimmize list query
    public function getTopProducts_List (array $options = array()) {
        global $app;
        $config = $this->data->shopStat_PopularProducts();
        if (empty($config))
            return null;
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $key => $orderRawItem) {
                    $_items[] = $self->fetchSingleProductByID($orderRawItem['ProductID']);
                }
                return $_items;
            }
        );
        $dataList = $app->getDB()->queryMatchedIDs($config, $options, $callbacks);
        return $dataList;
    }

    // TODO: optimmize list query
    public function getViewedProducts_List () {
        return $this->data->
        // global $app;
        // $_items = array();
        // $viewedProductsIDs = isset($_SESSION[$this->_listKey_Recent]) ? $_SESSION[$this->_listKey_Recent] : array();
        // foreach ($viewedProductsIDs as $productID) {
        //     $_items[] = $this->data->fetchSingleProductByID($productID);
        // }
        // $dataList = $app->getDB()->arrayToDataList($_items);
        // return $dataList;
    }

    // TODO: optimize list quer
    public function getOnSaleProducts_List (array $listOptions = array()) {
        // $self = $this;
        // $params = array();
        // $params['list'] = $listOptions;
        // $params['callbacks'] = array(
        //     'parse' => function ($dbRawItem) use ($self) {
        //         return $self->fetchSingleProductByID($dbRawItem['ID']);
        //     }
        // );
        return $this->data->fetchOnSaleProducts_List($listOptions);
    }

    // TODO: optimmize list query
    public function getFeaturedProducts_List (array $listOptions = array()) {
        // $self = $this;
        // $params = array();
        // $params['list'] = $listOptions;
        // $params['callbacks'] = array(
        //     'parse' => function ($dbRawItem) use ($self) {
        //         return $self->fetchSingleProductByID($dbRawItem['ID']);
        //     }
        // );
        return $this->data->fetchFeaturedProducts_List($listOptions);
        // global $app;
        // $options['sort'] = 'shop_products.DateUpdated';
        // $options['order'] = 'DESC';
        // $options['_fshop_products.Status'] = join(',', $this->data->getProductStatusesWhenAvailable()) . ':IN';
        // $options['_fIsFeatured'] = true;
        // // var_dump($options);
        // $config = $this->data->dbqProducts_MatchedIDs($options);
        // if (empty($config))
        //     return null;
        // $self = $this;
        // $callbacks = array(
        //     "parse" => function ($items) use($self) {
        //         $_items = array();
        //         foreach ($items as $key => $orderRawItem) {
        //             $_items[] = $self->fetchSingleProductByID($orderRawItem['ID']);
        //         }
        //         return $_items;
        //     }
        // );
        // $dataList = $app->getDB()->queryMatchedIDs($config, $options, $callbacks);
        // return $dataList;
    }

    // TODO: optimmize list query
    public function getOffersProducts_List (array $listOptions = array()) {
        $self = $this;
        $params = array();
        $params['list'] = $listOptions;
        $params['callbacks'] = array(
            'parse' => function ($dbRawItem) use ($self) {
                return $self->fetchSingleProductByID($dbRawItem['ID']);
            }
        );
        return $this->data->fetchSpecialOffersProducts_List($params);
        // global $app;
        // $options['sort'] = 'shop_products.DateUpdated';
        // $options['order'] = 'DESC';
        // $options['_fshop_products.Status'] = join(',', $this->data->getProductStatusesWhenAvailable()) . ':IN';
        // $options['_fIsOffer'] = true;
        // // $options['_fPrevPrice'] = 'Price:>';
        // $config = $this->data->dbqProducts_MatchedIDs($options);
        // if (empty($config))
        //     return null;
        // $self = $this;
        // $callbacks = array(
        //     "parse" => function ($items) use($self) {
        //         $_items = array();
        //         foreach ($items as $key => $orderRawItem) {
        //             $_items[] = $self->fetchSingleProductByID($orderRawItem['ID']);
        //         }
        //         return $_items;
        //     }
        // );
        // $dataList = $app->getDB()->queryMatchedIDs($config, $options, $callbacks);
        // return $dataList;
    }

    // TODO: optimmize list query
    public function getSearchProducts_List ($text) {
        return API::getAPI('shop:search')->search($text);
    }

    public function createProduct ($reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        $ProductID = null;

        // adjust verify/create category
        $adjustedRes = $this->adjustCategoryAndOriginIDs($reqData);
        $reqData['CategoryID'] = $adjustedRes['CategoryID'];
        $reqData['OriginID'] = $adjustedRes['OriginID'];
        $errors += $adjustedRes['errors'];

        $validatedDataObj = Validate::getValidData($reqData, array(
            'CategoryID' => array('int'),
            'OriginID' => array('int'),
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 300),
            'Description' => array('string', 'skipIfEmpty', 'max' => 10000, 'defaultValueIfEmpty' => ''),
            'Synopsis' => array('string', 'skipIfEmpty', 'max' => 350),
            'Model' => array('skipIfEmpty', 'max' => 300),
            'SKU' => array('skipIfUnset', 'max' => 300),
            'Price' => array('numeric', 'notEmpty'),
            'IsPromo' => array('sqlbool', 'skipIfUnset'),
            'IsOffer' => array('sqlbool', 'skipIfUnset'),
            'IsFeatured' => array('sqlbool', 'skipIfUnset'),
            'ShowBanner' => array('sqlbool', 'skipIfUnset'),
            'Status' => array('string', 'skipIfEmpty'),
            'Tags' => array('skipIfEmpty'),
            'ISBN' => array('skipIfEmpty'),
            'WARRANTY' => array('skipIfEmpty', 'defaultValueIfEmpty' => ''),
            'Features' =>  array('array', 'notEmpty'),
            'file1' => array('string', 'skipIfEmpty'),
            'file2' => array('string', 'skipIfEmpty'),
            'file3' => array('string', 'skipIfEmpty'),
            'file4' => array('string', 'skipIfEmpty'),
            'file5' => array('string', 'skipIfEmpty'),
            'promoText' => array('skipIfUnset'),
            'fileBannerLarge' => array('string', 'skipIfUnset'),
            'fileBannerMedium' => array('string', 'skipIfUnset'),
            'fileBannerSmall' => array('string', 'skipIfUnset'),
            'fileBannerMicro' => array('string', 'skipIfUnset'),
            'bannerTextLine1' => array('skipIfUnset'),
            'bannerTextLine2' => array('skipIfUnset'),
            'relatedProductIds' => array('array', 'skipIfUnset')
        ));

        if ($validatedDataObj->errorsCount == 0)
            try {
                $validatedValues = $validatedDataObj->validData;
                $CustomerID = $app->getSite()->getRuntimeCustomerID();
                $attributes = array();
                $attributes["IMAGE"] = array();
                $attributes["BANNER"] = array();
                $features = array();
                $productFeaturesIDs = array();
                $relatedIDs = array();

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
                if (isset($validatedValues['SKU'])) {
                    $attributes["SKU"] = $validatedValues['SKU'];
                }
                if (isset($validatedValues['WARRANTY'])) {
                    $attributes["WARRANTY"] = $validatedValues['WARRANTY'];
                }
                if (isset($validatedValues['relatedProductIds'])) {
                    $relatedIDs = $validatedValues['relatedProductIds'];
                }
                // promo and banners
                if (isset($validatedValues['promoText'])) {
                    $attributes['PROMO_TEXT'] = $validatedValues['promoText'];
                }
                if (isset($validatedValues['bannerTextLine1'])) {
                    $attributes['BANNER_TEXT_LINE1'] = $validatedValues['bannerTextLine1'];
                }
                if (isset($validatedValues['bannerTextLine2'])) {
                    $attributes['BANNER_TEXT_LINE2'] = $validatedValues['bannerTextLine2'];
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
                // I don't think loop for 5 items is better for perfomance
                if (!empty($validatedValues['fileBannerLarge'])) {
                    $attributes["BANNER"]["BANNER_LARGE"] = $validatedValues['fileBannerLarge'];
                }
                if (!empty($validatedValues['fileBannerMedium'])) {
                    $attributes["BANNER"]["BANNER_MEDIUM"] = $validatedValues['fileBannerMedium'];
                }
                if (!empty($validatedValues['fileBannerSmall'])) {
                    $attributes["BANNER"]["BANNER_SMALL"] = $validatedValues['fileBannerSmall'];
                }
                if (!empty($validatedValues['fileBannerMicro'])) {
                    $attributes["BANNER"]["BANNER_MICRO"] = $validatedValues['fileBannerMicro'];
                }

                // cleanup fields
                unset($validatedValues['Tags']);
                unset($validatedValues['ISBN']);
                unset($validatedValues['SKU']);
                unset($validatedValues['WARRANTY']);
                unset($validatedValues['Features']);
                unset($validatedValues['file1']);
                unset($validatedValues['file2']);
                unset($validatedValues['file3']);
                unset($validatedValues['file4']);
                unset($validatedValues['file5']);
                unset($validatedValues['promoText']);
                unset($validatedValues['fileBannerLarge']);
                unset($validatedValues['fileBannerMedium']);
                unset($validatedValues['fileBannerSmall']);
                unset($validatedValues['fileBannerMicro']);
                unset($validatedValues['bannerTextLine1']);
                unset($validatedValues['bannerTextLine2']);
                unset($validatedValues['relatedProductIds']);

                $app->getDB()->beginTransaction();

                // adjust features
                foreach ($features as $groupName => $value) {
                    $features[$groupName] = explode(',', $value);
                }

                // add new features
                $featureMap = API::getAPI('shop:productfeatures')->getFeatures();
                foreach ($features as $groupName => $featureList) {
                    if (isset($featureMap[$groupName])) {
                        foreach ($featureList as $featureName) {
                            $featureID = array_search($featureName, $featureMap[$groupName]);
                            if ($featureID === false) {
                                $data = array();
                                $data["CustomerID"] = $CustomerID;
                                $data["FieldName"] = substr($featureName, 0, 200);
                                $data["GroupName"] = substr($groupName, 0, 100);
                                $featureID = API::getAPI('shop:productfeatures')->createFeature($data);
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
                            $featureID = API::getAPI('shop:productfeatures')->createFeature($data);
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
                //         $config = $this->data->shopCreateFeature($data);
                //         $featureID = $app->getDB()->query($config) ?: null;
                //         if (isset($featureID) && $featureID >= 0) {
                //             $productFeaturesIDs[] = $featureID;
                //             // $this->_getOrSetCachedState('changed:features', true);
                //         }
                //     }
                // }

                // create product
                $validatedValues["CustomerID"] = $CustomerID;
                // if (isset($validatedValues["IsPromo"])) {
                //     $validatedValues["IsPromo"] = $validatedValues["IsPromo"] ? 1 : 0;
                // }
                // if (isset($validatedValues["IsOffer"])) {
                //     $validatedValues["IsOffer"] = $validatedValues["IsOffer"] ? 1 : 0;
                // }
                // if (isset($validatedValues["IsFeatured"])) {
                //     $validatedValues["IsFeatured"] = $validatedValues["IsFeatured"] ? 1 : 0;
                // }
                
                $validatedValues["PrevPrice"] = 0;
                $validatedValues["SearchText"] = $validatedValues["Name"] . ' ' . $validatedValues["Model"];
                // var_dump($validatedValues);
                $config = $this->data->createProduct($validatedValues);
                // var_dump($config);
                $ProductID = null;
                try {
                    $ProductID = $app->getDB()->query($config) ?: null;
                } catch (Exception $ep) {
                    $errors[] = $ep->getMessage();
                }
                // var_dump($productID);
                // var_dump($app->getDB()->get_last_query());
                if (empty($ProductID)) {
                    throw new Exception('ProductCreateError');
                }

                // set new features (actually this condition must return always true)
                if (count($productFeaturesIDs)) {
                    $featureData['ProductID'] = $ProductID;
                    $featureData['CustomerID'] = $CustomerID;
                    foreach ($productFeaturesIDs as $value) {
                        $featureData['FeatureID'] = $value;
                        $config = $this->data->addFeatureToProduct($featureData);
                        $app->getDB()->query($config);
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
                            $mdImagePath = 'md' . Path::getDirectorySeparator() . $fileName;
                            $smImagePath = 'sm' . Path::getDirectorySeparator() . $fileName;
                            $xsImagePath = 'xs' . Path::getDirectorySeparator() . $fileName;
                            $microImagePath = 'micro' . Path::getDirectorySeparator() . $fileName;
                            $normalImagePath = $fileName;

                            $uploadInfo = Path::moveTemporaryFile($mdImagePath, $this->data->getProductUploadInnerDir($ProductID, 'md'), $newFileName);
                            $uploadInfo = Path::moveTemporaryFile($smImagePath, $this->data->getProductUploadInnerDir($ProductID, 'sm'), $newFileName);
                            $uploadInfo = Path::moveTemporaryFile($xsImagePath, $this->data->getProductUploadInnerDir($ProductID, 'xs'), $newFileName);
                            $uploadInfo = Path::moveTemporaryFile($microImagePath, $this->data->getProductUploadInnerDir($ProductID, 'micro'), $newFileName);
                            $uploadInfo = Path::moveTemporaryFile($normalImagePath, $this->data->getProductUploadInnerDir($ProductID), $newFileName);

                            $attrData = $initAttrData->getArrayCopy();
                            $attrData['Attribute'] = 'IMAGE';
                            $attrData['Value'] = $uploadInfo['filename'];
                            $config = $this->data->shopAddAttributeToProduct($attrData);
                            $app->getDB()->query($config);
                        }
                    }
                    // -- BANNER_XXXX
                    $bannerTypes = $this->data->getProductBannerTypes();
                    foreach ($bannerTypes as $bannerType) {
                        if (!empty($attributes["BANNER"][$bannerType])) {
                            $uploadInfo = Path::moveTemporaryFile($attributes["BANNER"][$bannerType],
                                $this->data->getProductUploadInnerDir($ProductID), strtolower($bannerType));
                            $attrData = $initAttrData->getArrayCopy();
                            $attrData['Attribute'] = $bannerType;
                            $attrData['Value'] = $uploadInfo['filename'];
                            $config = $this->data->shopAddAttributeToProduct($attrData);
                            $app->getDB()->query($config);
                        }
                    }
                    // -- ISBN
                    // -- EXPIRE
                    // -- TAGS
                    // -- WARRANTY
                    // -- BANNER_TEXT_LINE1
                    // -- BANNER_TEXT_LINE2
                    // -- PROMO_TEXT
                    $commonAttributeKeys = array('ISBN', 'SKU', 'EXPIRE', 'TAGS', 'WARRANTY',
                        'BANNER_TEXT_LINE1', 'BANNER_TEXT_LINE2', 'PROMO_TEXT');
                    foreach ($commonAttributeKeys as $key) {
                        if (!isset($attributes[$key])) {
                            continue;
                        }
                        $attrData = $initAttrData->getArrayCopy();
                        $attrData['Attribute'] = $key;
                        $attrData['Value'] = $attributes[$key];
                        $config = $this->data->shopAddAttributeToProduct($attrData);
                        $app->getDB()->query($config);
                    }
                }

                // update related products
                if (isset($reqData['relatedProductIds'])) {
                    $config = $this->data->deleteAllProductRelations($ProductID);
                    $app->getDB()->query($config);
                    foreach ($relatedIDs as $relatedID) {
                        $app->getDB()->query($this->data->addRelatedProduct($CustomerID, $ProductID, $relatedID));
                    }
                }

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors += $validatedDataObj->errorMessages;

        if ($success && !empty($ProductID)) {
            $result = $this->data->fetchSingleProductByID($ProductID);
        }
        $result['errors'] = $errors;
        $result['success'] = $success;

        $this->updateProductSearchTextByID($ProductID);
        $this->refreshProductExternalKeyByID($ProductID);

        return $result;
    }

    public function updateProduct ($ProductID, $reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        // adjust verify/create category
        $adjustedRes = $this->adjustCategoryAndOriginIDs($reqData, true);
        if (isset($adjustedRes['CategoryID'])) {
            $reqData['CategoryID'] = $adjustedRes['CategoryID'];
        }
        if (isset($adjustedRes['OriginID'])) {
            $reqData['OriginID'] = $adjustedRes['OriginID'];
        }
        $errors += $adjustedRes['errors'];

        $validatedDataObj = Validate::getValidData($reqData, array(
            'CategoryID' => array('int', 'skipIfUnset'),
            'OriginID' => array('int', 'skipIfUnset'),
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 300, 'skipIfUnset'),
            'Description' => array('string', 'null', 'skipIfUnset', 'max' => 10000, 'defaultValueIfEmpty' => ''),
            'Synopsis' => array('string', 'skipIfEmpty', 'max' => 350),
            'Model' => array('skipIfUnset', 'max' => 50),
            'SKU' => array('skipIfUnset', 'max' => 50),
            'Price' => array('numeric', 'notEmpty', 'skipIfUnset'),
            'IsPromo' => array('sqlbool', 'skipIfUnset'),
            'IsOffer' => array('sqlbool', 'skipIfUnset'),
            'IsFeatured' => array('sqlbool', 'skipIfUnset'),
            'ShowBanner' => array('sqlbool', 'skipIfUnset'),
            'Status' => array('string', 'skipIfEmpty', 'skipIfUnset'),
            'Tags' => array('skipIfUnset'),
            'ISBN' => array('skipIfUnset'),
            'WARRANTY' => array('skipIfUnset', 'defaultValueIfEmpty' => ''),
            'Features' =>  array('array', 'notEmpty', 'skipIfUnset'),
            'file1' => array('string', 'skipIfUnset'),
            'file2' => array('string', 'skipIfUnset'),
            'file3' => array('string', 'skipIfUnset'),
            'file4' => array('string', 'skipIfUnset'),
            'file5' => array('string', 'skipIfUnset'),
            'promoText' => array('skipIfUnset'),
            'fileBannerLarge' => array('string', 'skipIfUnset'),
            'fileBannerMedium' => array('string', 'skipIfUnset'),
            'fileBannerSmall' => array('string', 'skipIfUnset'),
            'fileBannerMicro' => array('string', 'skipIfUnset'),
            'bannerTextLine1' => array('skipIfUnset'),
            'bannerTextLine2' => array('skipIfUnset'),
            'relatedProductIds' => array('array', 'skipIfUnset')
        ));

        if ($validatedDataObj->errorsCount == 0)
            try {

                $validatedValues = $validatedDataObj->validData;
                $CustomerID = $app->getSite()->getRuntimeCustomerID();
                $attributes = array();
                $attributes["IMAGE"] = array();
                $attributes["BANNER"] = array();
                $features = array();
                $productFeaturesIDs = array();
                $relatedIDs = array();


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
                if (isset($validatedValues['SKU'])) {
                    $attributes["SKU"] = $validatedValues['SKU'];
                }
                if (isset($validatedValues['WARRANTY'])) {
                    $attributes["WARRANTY"] = $validatedValues['WARRANTY'];
                }
                if (isset($validatedValues['relatedProductIds'])) {
                    $relatedIDs = $validatedValues['relatedProductIds'];
                }
                // promo and banners
                if (isset($validatedValues['promoText'])) {
                    $attributes['PROMO_TEXT'] = $validatedValues['promoText'];
                }
                if (isset($validatedValues['bannerTextLine1'])) {
                    $attributes['BANNER_TEXT_LINE1'] = $validatedValues['bannerTextLine1'];
                }
                if (isset($validatedValues['bannerTextLine2'])) {
                    $attributes['BANNER_TEXT_LINE2'] = $validatedValues['bannerTextLine2'];
                }
                // extract features
                if (isset($validatedValues['Features'])) {
                    $features = $validatedValues['Features'];
                }

                $updateImages = isset($reqData['file1']) || isset($reqData['file2'])
                    || isset($reqData['file3']) || isset($reqData['file4']) || isset($reqData['file5']);

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

                $updateBanners = isset($reqData['fileBannerLarge']) || isset($reqData['fileBannerMedium'])
                    || isset($reqData['fileBannerSmall']) || isset($reqData['fileBannerMicro']);

                // I don't think loop for 5 items is better for perfomance
                if (!empty($validatedValues['fileBannerLarge'])) {
                    $attributes["BANNER"]["BANNER_LARGE"] = $validatedValues['fileBannerLarge'];
                }
                if (!empty($validatedValues['fileBannerMedium'])) {
                    $attributes["BANNER"]["BANNER_MEDIUM"] = $validatedValues['fileBannerMedium'];
                }
                if (!empty($validatedValues['fileBannerSmall'])) {
                    $attributes["BANNER"]["BANNER_SMALL"] = $validatedValues['fileBannerSmall'];
                }
                if (!empty($validatedValues['fileBannerMicro'])) {
                    $attributes["BANNER"]["BANNER_MICRO"] = $validatedValues['fileBannerMicro'];
                }

                // cleanup fields
                unset($validatedValues['Tags']);
                unset($validatedValues['ISBN']);
                unset($validatedValues['SKU']);
                unset($validatedValues['WARRANTY']);
                unset($validatedValues['Features']);
                unset($validatedValues['file1']);
                unset($validatedValues['file2']);
                unset($validatedValues['file3']);
                unset($validatedValues['file4']);
                unset($validatedValues['file5']);
                unset($validatedValues['promoText']);
                unset($validatedValues['fileBannerLarge']);
                unset($validatedValues['fileBannerMedium']);
                unset($validatedValues['fileBannerSmall']);
                unset($validatedValues['fileBannerMicro']);
                unset($validatedValues['bannerTextLine1']);
                unset($validatedValues['bannerTextLine2']);
                unset($validatedValues['relatedProductIds']);

                $app->getDB()->beginTransaction();

                // adjust features
                foreach ($features as $groupName => $value) {
                    if (is_array($value))
                        $features[$groupName] = array_values($value);
                    else
                        $features[$groupName] = explode(',', $value);
                }

                // add new features
                $featureMap = API::getAPI('shop:productfeatures')->getFeatures();
                foreach ($features as $groupName => $featureList) {
                    if (isset($featureMap[$groupName])) {
                        foreach ($featureList as $featureName) {
                            $featureID = array_search($featureName, $featureMap[$groupName]);
                            if ($featureID === false) {
                                $data = array();
                                $data["CustomerID"] = $CustomerID;
                                $data["FieldName"] = substr($featureName, 0, 200);
                                $data["GroupName"] = substr($groupName, 0, 100);
                                $featureID = API::getAPI('shop:productfeatures')->createFeature($data);
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
                            $featureID = API::getAPI('shop:productfeatures')->createFeature($data);
                            $productFeaturesIDs[] = $featureID;
                        }
                    }
                }

                // var_dump($features);
                // var_dump($featureMap);
                // var_dump($productFeaturesIDs);

                // update product
                $validatedValues["CustomerID"] = $CustomerID;
                // if (isset($validatedValues["IsPromo"])) {
                //     $validatedValues["IsPromo"] = $validatedValues["IsPromo"] ? 1 : 0;
                // }
                // if (isset($validatedValues["IsOffer"])) {
                //     $validatedValues["IsOffer"] = $validatedValues["IsOffer"] ? 1 : 0;
                // }
                // if (isset($validatedValues["IsFeatured"])) {
                //     $validatedValues["IsFeatured"] = $validatedValues["IsFeatured"] ? 1 : 0;
                // }

                if (isset($validatedValues['Price'])) {
                    $oldPrice = $this->fetchProductPrice($ProductID);
                    $newPrice = floatval($validatedValues['Price']);
                    // move current price into previous
                    if ($oldPrice != $newPrice) {
                        $validatedValues["PrevPrice"] = $oldPrice;
                    }
                    // mark product as offer when new price is better than current
                    if (!isset($validatedValues['IsOffer'])) {
                        if ($newPrice < $oldPrice) {
                            $validatedValues['IsOffer'] = $app->getDB()->getSqlBooleanValue(true);
                        } else {
                            $validatedValues['IsOffer'] = $app->getDB()->getSqlBooleanValue(false);
                        }
                    }
                }

                $config = $this->data->updateProduct($ProductID, $validatedValues);
                try {
                    $app->getDB()->query($config);
                } catch (Exception $ep) {
                    $errors[] = $ep->getMessage();
                }

                // set new features
                if (count($productFeaturesIDs)) {
                    // clear existed features before adding new
                    $config = $this->data->shopClearProductFeatures($ProductID);
                    $app->getDB()->query($config);
                    $featureData['ProductID'] = $ProductID;
                    $featureData['CustomerID'] = $CustomerID;
                    foreach ($productFeaturesIDs as $value) {
                        $featureData['FeatureID'] = $value;
                        // var_dump($featureData);
                        $config = $this->data->addFeatureToProduct($featureData);
                        $app->getDB()->query($config);
                    }
                }

                if ($updateImages) {
                    // get previous product data
                    // we need this to re-adjust images for the product
                    $currentImages = $this->data->fetchProductImages($ProductID);
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
                        $mdImagePath = 'md' . Path::getDirectorySeparator() . $fileName;
                        $smImagePath = 'sm' . Path::getDirectorySeparator() . $fileName;
                        $xsImagePath = 'xs' . Path::getDirectorySeparator() . $fileName;
                        $microImagePath = 'micro' . Path::getDirectorySeparator() . $fileName;
                        $normalImagePath = $fileName;

                        $uploadInfo = Path::moveTemporaryFile($mdImagePath, $this->data->getProductUploadInnerDir($ProductID, 'md'), $newFileName);
                        $uploadInfo = Path::moveTemporaryFile($smImagePath, $this->data->getProductUploadInnerDir($ProductID, 'sm'), $newFileName);
                        $uploadInfo = Path::moveTemporaryFile($xsImagePath, $this->data->getProductUploadInnerDir($ProductID, 'xs'), $newFileName);
                        $uploadInfo = Path::moveTemporaryFile($microImagePath, $this->data->getProductUploadInnerDir($ProductID, 'micro'), $newFileName);
                        $uploadInfo = Path::moveTemporaryFile($normalImagePath, $this->data->getProductUploadInnerDir($ProductID), $newFileName);

                        // var_dump($uploadInfo);
                        // $attrData = $initAttrData->getArrayCopy();
                        // $attrData['Attribute'] = 'IMAGE';
                        // $attrData['Value'] = $uploadInfo['filename'];
                        // $config = $this->data->shopAddAttributeToProduct($attrData);
                        // $app->getDB()->query($config);

                        // $newFileName = $ProductID . uniqid(time());
                        // $uploadInfo = $this->saveOwnTemporaryUploadedFile('sm' . Path::getDirectorySeparator() . $fileName, $this->data->getProductUploadInnerDir($ProductID, 'sm'), $newFileName);
                        // $this->saveOwnTemporaryUploadedFile('xs' . Path::getDirectorySeparator() . $fileName, $this->data->getProductUploadInnerDir($ProductID, 'xs'), $newFileName);
                        // $this->saveOwnTemporaryUploadedFile($fileName, $this->data->getProductUploadInnerDir($ProductID), $newFileName);
                        $uploadedFileNames[] = $uploadInfo['filename'];
                    }
                    foreach ($filesToDelete as $fileName) {

                        Path::deleteUploadedFile($this->data->getProductUploadInnerImagePath($fileName, $ProductID, 'md'));
                        Path::deleteUploadedFile($this->data->getProductUploadInnerImagePath($fileName, $ProductID, 'sm'));
                        Path::deleteUploadedFile($this->data->getProductUploadInnerImagePath($fileName, $ProductID, 'xs'));
                        Path::deleteUploadedFile($this->data->getProductUploadInnerImagePath($fileName, $ProductID, 'micro'));
                        Path::deleteUploadedFile($this->data->getProductUploadInnerImagePath($fileName, $ProductID));

                        // $this->deleteOwnUploadedFile($fileName, $this->data->getProductUploadInnerDir($ProductID, 'sm'));
                        // $this->deleteOwnUploadedFile($fileName, $this->data->getProductUploadInnerDir($ProductID, 'xs'));
                        // $this->deleteOwnUploadedFile($fileName, $this->data->getProductUploadInnerDir($ProductID));
                    }

                    $attributes["IMAGE"] = array_merge($filesToKeep, $uploadedFileNames);
                } else {
                    unset($attributes["IMAGE"]);
                }

                // update product banners
                if ($updateBanners) {
                    $currentBanners = $this->fetchProductBanners($ProductID);
                    $bannerTypes = $this->data->getProductBannerTypes();

                    foreach ($bannerTypes as $bannerType) {
                        // if current typ is already set for product
                        if (isset($currentBanners[$bannerType])) {
                            $currentBannerFileName = $currentBanners[$bannerType];
                            // if new on si empty we just delete existent
                            if (empty($attributes["BANNER"][$bannerType])) {
                                Path::deleteUploadedFile($this->data->getProductUploadInnerImagePath($currentBannerFileName, $ProductID));
                                $attributes["BANNER"][$bannerType] = null;
                            } elseif ($currentBanners[$bannerType]['name'] != $attributes["BANNER"][$bannerType]) {
                                Path::deleteUploadedFile($this->data->getProductUploadInnerImagePath($currentBannerFileName, $ProductID));
                                $uploadInfo = Path::moveTemporaryFile($attributes["BANNER"][$bannerType],
                                    $this->data->getProductUploadInnerDir($ProductID), strtolower($bannerType));
                                $attributes["BANNER"][$bannerType] = $uploadInfo['filename'];
                            }
                        } elseif (!empty($attributes["BANNER"][$bannerType])) {
                            $uploadInfo = Path::moveTemporaryFile($attributes["BANNER"][$bannerType],
                                $this->data->getProductUploadInnerDir($ProductID), strtolower($bannerType));
                            $attributes["BANNER"][$bannerType] = $uploadInfo['filename'];
                        }
                    }
                } else {
                    unset($attributes["BANNER"]);
                }

                // throw new Exception("Error Processing Request", 1);
                // set new attributes
                if (!empty($attributes)) {
                    $initAttrData = new ArrayObject(array(
                        'ProductID' => $ProductID,
                        'CustomerID' => $CustomerID
                    ));
                    // -- IMAGE
                    if (isset($attributes["IMAGE"])) {
                        $config = $this->data->shopClearProductAttributes($ProductID, 'IMAGE');
                        $app->getDB()->query($config);
                        foreach ($attributes["IMAGE"] as $imageName) {
                            $attrData = $initAttrData->getArrayCopy();
                            $attrData['Attribute'] = 'IMAGE';
                            $attrData['Value'] = $imageName;
                            $config = $this->data->shopAddAttributeToProduct($attrData);
                            $app->getDB()->query($config);
                        }
                    }
                    // -- BANNER_XXXX
                    $bannerTypes = $this->data->getProductBannerTypes();
                    foreach ($bannerTypes as $bannerType) {
                        $config = $this->data->shopClearProductAttributes($ProductID, $bannerType);
                        $app->getDB()->query($config);
                        if (!empty($attributes["BANNER"][$bannerType])) {
                            $attrData = $initAttrData->getArrayCopy();
                            $attrData['Attribute'] = $bannerType;
                            $attrData['Value'] = $attributes["BANNER"][$bannerType];
                            $config = $this->data->shopAddAttributeToProduct($attrData);
                            $app->getDB()->query($config);
                        }
                    }
                    // -- ISBN
                    // -- EXPIRE
                    // -- TAGS
                    // -- WARRANTY
                    // -- BANNER_TEXT_LINE1
                    // -- BANNER_TEXT_LINE2
                    // -- PROMO_TEXT
                    $commonAttributeKeys = array('ISBN', 'SKU', 'EXPIRE', 'TAGS', 'WARRANTY',
                        'BANNER_TEXT_LINE1', 'BANNER_TEXT_LINE2', 'PROMO_TEXT');
                    foreach ($commonAttributeKeys as $key) {
                        if (!isset($attributes[$key])) {
                            continue;
                        }
                        // clear existed tags before adding new ones
                        $config = $this->data->shopClearProductAttributes($ProductID, $key);
                        $app->getDB()->query($config);
                        $attrData = $initAttrData->getArrayCopy();
                        $attrData['Attribute'] = $key;
                        $attrData['Value'] = $attributes[$key];
                        $config = $this->data->shopAddAttributeToProduct($attrData);
                        $app->getDB()->query($config);
                    }
                }

                // update related products
                if (isset($reqData['relatedProductIds'])) {
                    $config = $this->data->deleteAllProductRelations($ProductID);
                    $app->getDB()->query($config);
                    foreach ($relatedIDs as $relatedID) {
                        $app->getDB()->query($this->data->addRelatedProduct($CustomerID, $ProductID, $relatedID));
                    }
                }

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors += $validatedDataObj->errorMessages;

        $result = $this->data->fetchSingleProductByID($ProductID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        $this->updateProductSearchTextByID($ProductID);
        $this->refreshProductExternalKeyByID($ProductID);

        return $result;
    }

    private function adjustCategoryAndOriginIDs ($data, $skipIfEmpty = false) {
        $category = null;
        $origin = null;
        $errors = array();
        $res = array();

        // verify/create origin
        $originID = null;
        $originName = null;
        if (isset($data['OriginID'])) {
            if (is_numeric($data['OriginID'])) {
                $origin = API::getAPI('shop:origins')->getOriginByID($data['OriginID']);
            } else {
                $origin = API::getAPI('shop:origins')->getOriginByName($data['OriginID']);
            }
            $originName = $data['OriginID'];
        } elseif (isset($data['OriginName'])) {
            $origin = API::getAPI('shop:origins')->getOriginByName($data['OriginName']);
            $originName = $data['OriginName'];
        }
        // create new origin
        if ($origin === null) {
            if (!$skipIfEmpty) {
                $origin = API::getAPI('shop:origins')->getOriginByName('EmptyOrigin');
                if (empty($origin)) {
                    $origin = API::getAPI('shop:origins')->createOrigin(array(
                        'Name' => empty($originName) ? 'EmptyOrigin' : $originName
                    ));
                    $originID = $origin['ID'];
                    $errors += $origin['errors'];
                } else {
                    $originID = $origin['ID'];
                }
            }
        } else {
            $originID = $origin['ID'];
        }

        // verify/create category
        $categoryID = null;
        $categoryName = null;
        if (isset($data['CategoryID'])) {
            if (is_numeric($data['CategoryID'])) {
                $category = API::getAPI('shop:categories')->getCategoryByID($data['CategoryID']);
            } else {
                $category = API::getAPI('shop:categories')->getCategoryByName($data['CategoryID']);
            }
            $categoryName = $data['CategoryID'];
        } elseif (isset($data['CategoryName'])) {
            $category = API::getAPI('shop:categories')->getCategoryByName($data['CategoryName']);
            $categoryName = $data['CategoryName'];
        }
        // create new origin
        if ($category === null) {
            if (!$skipIfEmpty) {
                $category = API::getAPI('shop:categories')->getCategoryByName('Uncategorized');
                if (empty($category)) {
                    $category = API::getAPI('shop:categories')->createCategory(array(
                        'Name' => empty($categoryName) ? 'Uncategorized' : $categoryName
                    ));
                    $categoryID = $category['ID'];
                    $errors += $category['errors'];
                } else {
                    $categoryID = $category['ID'];
                }
            }
        } else {
            $categoryID = $category['ID'];
        }

        $res['errors'] = $errors;
        if (!is_null($categoryID))
            $res['CategoryID'] = $categoryID;
        if (!is_null($originID))
            $res['OriginID'] = $originID;

        return $res;

        // return array(
        //     'errors' => ,
        //     'OriginID' => $originID,
        //     'CategoryID' => $categoryID
        // );
    }

    public function updateOrInsertProduct ($data) {
        global $app;
        $result = array();
        $errors = array();
        $product = null;
        $productID = null;

        // we have the product item already in db
        if (isset($data['ID'])) {
            //-- echo "[INFO] using product ID " . $data['ID'] . PHP_EOL;
            $productID = $this->data->productExistsByID($data['ID']);
            // try to get product item by name and model
        } elseif (isset($data['Model']) && isset($data['OriginName'])) {
            //-- echo "[INFO] using product Model and OriginName " . $data['Model'] . ' + ' . $data['OriginName'] . PHP_EOL;
            $exKey = ShopUtils::genProductExternalKey($data);
            $productIDByExternalKey = $this->productExistsByExternalKey($exKey);
            $productIDByModelAndOrigin = $this->productExistsByModelAndOrigin($data['Model'], $data['OriginName']);
            // echo '# ... productIDByExternalKey = ' . $productIDByExternalKey . PHP_EOL;
            // echo '# ... productIDByModelAndOrigin = ' . $productIDByModelAndOrigin . PHP_EOL;
            // try to get duplicated product and mark them as removed
            // $result = $this->markProductAsRemovedByModelAndOrigin(str_replace(' ', '%', $data['Model']), $data['OriginName']);
            // $result = $this->markProductAsRemovedByModelAndOrigin(str_replace(' ', '%', $data['Model']), strtolower($data['OriginName']));
            // $result = $this->markProductAsRemovedByModelAndOrigin(str_replace(' ', '%', $data['Model']), strtoupper($data['OriginName']));
            // $result = $this->markProductAsRemovedByModelAndOrigin(str_replace(' ', '', $data['Model']), $data['OriginName']);
            // $result = $this->markProductAsRemovedByModelAndOrigin(str_replace(' ', '', $data['Model']), strtolower($data['OriginName']));
            // $result = $this->markProductAsRemovedByModelAndOrigin(str_replace(' ', '', $data['Model']), strtoupper($data['OriginName']));
            $result = $this->markProductAsRemovedByModelAndOrigin(implode("%", str_split(str_replace(' ', '', $data['Model']))), $data['OriginName']);

            if ($productIDByExternalKey === null) {
                $productID = $productIDByModelAndOrigin;
            } else {
                $productID = $productIDByExternalKey;
            }
        }

        // var_dump($data);
        // echo "# ... current key " . $exKey . PHP_EOL;
        if ($productID === null) {
            $result = $this->createProduct($data);
        } else {
            // echo "# ... updating [" . $productID . "] " . $exKey . PHP_EOL;
            $result = $this->updateProduct($productID, $data);
        }

        $result['created'] = $result['success'] && $productID === null;
        $result['updated'] = $result['success'] && $productID !== null;
        return $result;
    }

    public function refreshProductExternalKeyByID ($productID) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        try {

            $productInfo = $this->data->fetchSingleProductShortInfo($productID);
            $exKey = ShopUtils::genProductExternalKey($productInfo);

            // echo 'refreshProductExternalKeyByID(' . $productID . '); >>';
            // var_dump($productInfo);
            // echo $exKey;
            // echo PHP_EOL;

            $app->getDB()->beginTransaction();

            $config = $this->data->updateProductExternalKeyByID($productID, $exKey);
            $app->getDB()->query($config);

            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateProductSearchTextByID ($productID) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        try {

            $app->getDB()->beginTransaction();
            $this->data->updateProductSearchTextByID($productID);
            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateProductSearchTextByOriginID ($originID) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        try {

            $app->getDB()->beginTransaction();
            $this->data->updateProductSearchTextByOriginID($originID);
            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function archiveProduct ($ProductID) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        try {

            // $CustomerID = $app->getSite()->getRuntimeCustomerID();

            $app->getDB()->beginTransaction();

            // $data = array(
            //     'CustomerID' => $CustomerID,
            //     'Status' => 'ARCHIVED'
            // );

            $this->data->archiveProduct($ProductID);

            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }

        $result = $this->data->fetchSingleProductByID($ProductID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function archiveAllProducts () {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        try {

            $app->getDB()->beginTransaction();
            $this->data->archiveAllProducts();
            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function markProductAsRemoved ($ProductID) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        try {

            // $CustomerID = $app->getSite()->getRuntimeCustomerID();

            $app->getDB()->beginTransaction();

            // $data = array(
            //     'CustomerID' => $CustomerID,
            //     'Status' => 'REMOVED'
            // );

            // $config = 
            $this->data->setProductAsRemovedByID($ProductID);
            // $app->getDB()->query($config);

            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }

        $result = $this->data->fetchSingleProductByID($ProductID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function markProductAsRemovedByModelAndOrigin ($model, $originName) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        try {

            $app->getDB()->beginTransaction();
            $this->data->setProductAsRemovedByModelAndOrigin($model, $originName);
            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = $e->getMessage();
        }

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }



    public function getProductsArray_TopNonPopular () {
        global $app;
        // get non-popuplar 15 products
        $config = $this->data->shopStat_NonPopularProducts();
        $productIDs = $app->getDB()->query($config);
        $data = array();
        if (!empty($productIDs)) {
            foreach ($productIDs as $val) {
                $data[] = $this->data->fetchSingleProductByID($val['ID']);
            }
        }
        return $data;
    }

    public function getProductsArray_TopPopular () {
        global $app;
        // get top 15 products
        $config = $this->data->shopStat_PopularProducts();
        $productIDs = $app->getDB()->query($config);
        $data = array();
        if (!empty($productIDs)) {
            foreach ($productIDs as $val) {
                $product = $this->data->fetchSingleProductByID($val['ProductID']);
                $product['SoldTotal'] = floatval($val['SoldTotal']);
                $product['SumTotal'] = floatval($val['SumTotal']);
                $data[] = $product;
            }
        }
        return $data;
    }

    public function getStats_ProductsOverview ($filter = null) {
        global $app;
        if (!API::getAPI('system:auth')->ifYouCan('Admin')) {
            return null;
        }
        // get shop products overview:
        $config = $this->data->shopStat_ProductsOverview($filter);
        $data = $app->getDB()->query($config) ?: array();
        $total = 0;
        $res = array();
        $availableStatuses = $this->data->getProductStatuses();
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
        global $app;
        if (!API::getAPI('system:auth')->ifYouCan('Admin') &&
            !API::getAPI('system:auth')->ifYouCan('Maintain')) {
            return null;
        }
        $config = $this->data->shopStat_ProductsIntensityLastMonth('ACTIVE');
        $data = $app->getDB()->query($config) ?: array();
        return $data;
    }
    public function getStats_ProductsIntensityPreorderLastMonth () {
        global $app;
        if (!API::getAPI('system:auth')->ifYouCan('Admin') &&
            !API::getAPI('system:auth')->ifYouCan('Maintain')) {
            return null;
        }
        $config = $this->data->shopStat_ProductsIntensityLastMonth('PREORDER');
        $data = $app->getDB()->query($config) ?: array();
        return $data;
    }
    public function getStats_ProductsIntensityDiscountLastMonth () {
        global $app;
        if (!API::getAPI('system:auth')->ifYouCan('Admin') &&
            !API::getAPI('system:auth')->ifYouCan('Maintain')) {
            return null;
        }
        $config = $this->data->shopStat_ProductsIntensityLastMonth('DISCOUNT');
        $data = $app->getDB()->query($config) ?: array();
        return $data;
    }

    public function get ($req, $resp) {
        // for specific product item
        // by id
        if (Request::hasRequestedID()) {
            $resp->setResponse($this->data->fetchSingleProductByID($req->id));
            return;
        }
        // or by ExternalKey
        if (Request::hasRequestedExternalKey()) {
            $resp->setResponse($this->data->fetchSingleProductByExternalKey($req->externalKey));
            return;
        }
        // for the case when we have to fecth list with products
        if (Request::noRequestedItem()) {
            $listOptions = $app->getDB()->pickDataListParamsFromRequest($req->get);
            if (isset($req->get['type'])) {
                switch ($req->get['type']) {
                    case 'new': {
                        $resp->setResponse($this->getNewProducts_List($listOptions));
                        break;
                    }
                    case 'top': {
                        $resp->setResponse($this->getTopProducts_List($listOptions));
                        break;
                    }
                    case 'viewed': {
                        $resp->setResponse($this->getViewedProducts_List());
                        break;
                    }
                    case 'onsale': {
                        $resp->setResponse($this->getOnSaleProducts_List($listOptions));
                        break;
                    }
                    case 'featured': {
                        $resp->setResponse($this->getFeaturedProducts_List($listOptions));
                        break;
                    }
                    case 'offers': {
                        $resp->setResponse($this->getOffersProducts_List($listOptions));
                        break;
                    }
                    case 'search': {
                        $resp->setResponse($this->getSearchProducts_List($listOptions/*$req->get['text']*/));
                        break;
                    }
                }
            } else {
                $resp->setResponse($this->getProducts_List($listOptions));
            }

        }
        // TODO: cleanup
        // if (!empty($req->id)) {
        //     if (is_numeric($req->id)) {
        //         $ProductID = intval($req->id);
        //         $resp->setResponse($this->data->fetchSingleProductByID($ProductID));
        //     } else {
        //         $resp->setResponse($this->getProductByExternalKey($req->id));
        //     }
        // } else {
        // }
    }

    public function post ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') &&
                !API::getAPI('system:auth')->ifYouCan('shop_CREATE_PRODUCT'))) {
            $resp->setError('AccessDenied');
            return;
        }
        $resp->setResponse($this->createProduct($req->data));
    }

    public function put ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') &&
                !API::getAPI('system:auth')->ifYouCan('shop_EDIT_PRODUCT'))) {
            $resp->setError('AccessDenied');
            return;
        }
        if (empty($req->id)) {
            $resp->setError('MissedParameter_id');
        } else {
            $ProductID = intval($req->id);
            $resp->setResponse($this->updateProduct($ProductID, $req->data));
        }
    }

    public function delete ($req, $resp) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') &&
                !API::getAPI('system:auth')->ifYouCan('shop_EDIT_PRODUCT'))) {
            $resp->setError('AccessDenied');
            return;
        }
        $resp->setResponse($this->archiveProduct($req->data));
    }

}

?>
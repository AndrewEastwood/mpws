<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use Exception;
use ArrayObject;

class dbquery {

    // var $Table_ShopOrders = "shop_orders";
    // var $Table_ShopProducts = "shop_products";
    // var $Table_ShopOrigins = "shop_origins";
    // var $Table_ShopCategories = "shop_categories";
    // var $Table_ShopProductAttr = "shop_productAttributes";
    // var $Table_ShopDeliveryAgencies = "shop_deliveryAgencies";
    // var $Table_ShopFeatures = "shop_features";
    // var $Table_ShopSettings = "shop_settings";

    // products >>>>>
    public static function shopGetProductItem ($ProductID = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_products",
            "fields" => array("ID", "CategoryID", "OriginID", "ExternalKey", "Name", "Description", "Model", "SKU", "Price", "IsPromo", "IsFeatured", "IsOffer", "Status", "DateUpdated", "DateCreated"),
            "offset" => 0,
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));

        if (!is_null($ProductID))
            $config["condition"] = array(
                "shop_products.ID" => $app->getDB()->createCondition($ProductID)
            );

        return $config;
    }

    public static function shopGetProductItemByExternalKey ($externalKey) {
        global $app;
        $config = self::shopGetProductItem();
        $config['condition']["shop_products.ExternalKey"] = $app->getDB()->createCondition($externalKey);
        return $config;
    }

    public static function shopGetProductList (array $options = array()) {
        global $app;
        $config = self::shopGetProductItem();
        $config['condition'] = array();
        $config["fields"] = array("ID");
        $config['limit'] = 64;
        $config['group'] = 'shop_products.ID';
        $config['additional'] = array(
            "shop_categories" => array(
                "constraint" => array("shop_products.CategoryID", "=", "shop_categories.ID"),
                "fields" => array("@shop_categories.Status AS CategoryStatus")
            ),
            "shop_origins" => array(
                "constraint" => array("shop_products.OriginID", "=", "shop_origins.ID"),
                "fields" => array("@shop_origins.Status AS OriginStatus")
            ),
            "shop_productFeatures" => array(
                "constraint" => array("shop_products.ID", "=", "shop_productFeatures.ProductID"),
                "fields" => array("FeatureID")
            )
        );
        $config['order'] = array(
            "expr" => "shop_products.Status"
        );
        unset($config['options']);

        if (!empty($options['useFeatures'])) {
            unset($config['additional']['shop_productFeatures']);
        }

        if (!empty($options['_pSearch'])) {
            if (is_string($options['_pSearch'])) {
                $config['condition']["shop_products.Name"] = $app->getDB()->createCondition('%' . $options['_pSearch'] . '%', 'like');
                // $config['condition']["Model"] = $app->getDB()->createCondition('%' . $options['search'] . '%', 'like');
                // $config['condition']["SKU"] = $app->getDB()->createCondition('%' . $options['search'] . '%', 'like');
            } elseif (is_array($options['_pSearch'])) {
                foreach ($options['_pSearch'] as $value) {
                    $chunks = explode('=', $value);
                    // var_dump($chunks);
                    if (count($chunks) === 2) {
                        $keyToSearch = strtolower($chunks[0]);
                        $valToSearch = $chunks[1];
                        $conditionField = '';
                        $conditionOp = '=';
                        switch ($keyToSearch) {
                            case 'id':
                                $conditionField = "shop_products.ID";
                                $valToSearch = intval($valToSearch);
                                break;
                            case 'n':
                                $conditionField = "shop_products.Name";
                                $valToSearch = '%' . $valToSearch . '%';
                                $conditionOp = 'like';
                                break;
                            case 'd':
                                $conditionField = "shop_products.Description";
                                $valToSearch = '%' . $valToSearch . '%';
                                $conditionOp = 'like';
                                break;
                            case 'm':
                                $conditionField = "shop_products.Model";
                                $valToSearch = '%' . $valToSearch . '%';
                                $conditionOp = 'like';
                                break;
                        }
                        // var_dump($conditionField);
                        // var_dump($valToSearch);
                        // var_dump($conditionOp);
                        if (!empty($conditionField)) {
                            $config['condition'][$conditionField] = $app->getDB()->createCondition($valToSearch, $conditionOp);
                        }
                    }
                    // $config['condition']["shop_products.Name"] = $app->getDB()->createCondition('%' . $value . '%', 'like');
                    // $config['condition']["shop_products.Model"] = $app->getDB()->createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["shop_products.Description"] = $app->getDB()->createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["shop_products.SKU"] = $app->getDB()->createCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["Model"] = $app->getDB()->createCondition('%' . $value . '%', 'like');
                    // $config['condition']["SKU"] = $app->getDB()->createCondition('%' . $value . '%', 'like');
                }
            }
        }

        if (!empty($options['_pStatus'])) {
            $config['condition']["shop_products.Status"] = $app->getDB()->createCondition($options['_pStatus']);
        }

        // var_dump($config['condition']);
        return $config;
    }

    public static function shopGetLatestProductsList () {
        $config = self::shopGetProductItem();
        $config['condition'] = array();
        $config["fields"] = array("ID");
        $config['limit'] = 64;
        $config['group'] = 'shop_products.ID';
        $config['additional'] = array(
            "shop_categories" => array(
                "constraint" => array("shop_products.CategoryID", "=", "shop_categories.ID"),
                "fields" => array("@shop_categories.Status AS CategoryStatus")
            ),
            "shop_origins" => array(
                "constraint" => array("shop_products.OriginID", "=", "shop_origins.ID"),
                "fields" => array("@shop_origins.Status AS OriginStatus")
            ),
            "shop_productFeatures" => array(
                "constraint" => array("shop_products.ID", "=", "shop_productFeatures.ProductID"),
                "fields" => array("FeatureID")
            )
        );
        $config['order'] = array(
            "expr" => "shop_products.Status, shop_products.DateCreated DESC"
        );
        unset($config['options']);
        return $config;
    }

    public static function shopCreateProduct ($data) {
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        $ExternalKey = array();
        if (isset($data['Name']))
            $ExternalKey[] = $data['Name'];
        if (isset($data['Model']))
            $ExternalKey[] = $data['Model'];
        if (isset($data['SKU']))
            $ExternalKey[] = $data['SKU'];
        if (!empty($ExternalKey)) {
            $data["ExternalKey"] = \engine\lib\utils::url_slug(implode('_', $ExternalKey), array('transliterate' => true));
            $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
        }
        $data["Name"] = substr($data["Name"], 0, 300);
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_products",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopUpdateProduct ($ProductID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $ExternalKey = array();
        if (isset($data['Name'])) {
            $ExternalKey[] = $data['Name'];
            $data["Name"] = substr($data["Name"], 0, 300);
        }
        if (isset($data['Model']))
            $ExternalKey[] = $data['Model'];
        if (isset($data['SKU']))
            $ExternalKey[] = $data['SKU'];
        if (!empty($ExternalKey)) {
            $data["ExternalKey"] = \engine\lib\utils::url_slug(implode('_', $ExternalKey), array('transliterate' => true));
            $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
        }
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_products",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($ProductID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopDeleteProduct ($ProductID) {
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_products",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($ProductID)
            ),
            "data" => array(
                "Status" => 'ARCHIVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }
    // products >>>>>


    // Product category (catalog)
    public static function getShopCatalogProductList ($ids) {
        global $app;
        $config = self::shopGetProductList();
        $config['condition']["shop_products.CategoryID"] = $app->getDB()->createCondition($ids, "IN");
        return $config;
    }

    // public static function getShopCategoryProductInfo ($ids) {
        // global $app;
    //     $config = self::getShopCategoryProductList($ids);
    //     $config["fields"] = array("ID", "CategoryID", "OriginID");
    //     $config['limit'] = 0;
    //     return $config;
    // }

    // public static function getShopCategoryProductInfo ($ids) {
        // global $app;
    //     $config = self::getShopCategoryProductList($ids);
    //     $config["fields"] = array("ID", "CategoryID", "OriginID");
    //     $config['limit'] = 0;
    //     return $config;
    // }

    public static function getShopCategoryProductInfo () {
        // global $app;
        $config = self::shopGetProductList();
        // $config['fields'] = array("ID", "Name");
        $config['fields'] = array("ID");
        $config['limit'] = 0;
        $config['group'] = null;
        // $config['group'] = "shop_products.ID";
        $config['options'] = null;
        // array(
        //     "expandSingleRecord" => true
        // );
        return $config;
    }







    // Product price stats >>>>>
    public static function shopGetProductPriceStats ($id) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_productPrices",
            "condition" => array(
                "ProductID" => $app->getDB()->createCondition($id)
            ),
            "fields" => array("ID", "ProductID", "Price", "DateCreated"),
            "offset" => 0,
            "limit" => 50,
            "order" => array(
                "field" => "shop_productPrices.DateCreated",
                "ordering" => "ASC"
            ),
            "options" => array()
        ));
    }
    // <<<< Product price stats















    // Product relations >>>>>
    public static function shopGetProductRelations ($id) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_relations",
            "condition" => array(
                "ProductA_ID" => $app->getDB()->createCondition($id)
            ),
            "fields" => array("ProductB_ID"),
            "offset" => 0,
            "limit" => 0
        ));
    }
    // <<<< Product relations













    // product features & attributes >>>>>
    public static function shopGetProductFeatures ($id) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_productFeatures",
            "fields" => array("FeatureID"),
            'additional' => array(
                "shop_features" => array(
                    "constraint" => array("shop_productFeatures.FeatureID", "=", "shop_features.ID"),
                    "fields" => array("ID", "FieldName", "GroupName")
                )
            ),
            "condition" => array(
                "ProductID" => $app->getDB()->createCondition($id)
            ),
            "limit" => 0,
            "options" => array()
        ));
    }

    public static function shopGetProductAttributes ($id = null, $type = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_productAttributes",
            "condition" => array(),
            "fields" => array("ProductID", "Attribute", "Value"),
            "offset" => 0,
            "limit" => 20,
            "options" => array(
                "expandSingleRecord" => false
            )
        ));

        if (!empty($id)) {
            $config['condition']['ProductID'] = $app->getDB()->createCondition($id);
        }
        if (!empty($type)) {
            $config['condition']['Attribute'] = $app->getDB()->createCondition($type);
        }

        return $config;
    }

    public static function shopCreateFeature ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        $data["FieldName"] = substr($data["FieldName"], 0, 200);
        $data["GroupName"] = substr($data["GroupName"], 0, 100);
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_features",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopGetFeatures () {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_features",
            "fields" => array("ID", "FieldName", "GroupName"),
            "limit" => 0,
            "options" => array()
        ));
    }

    public static function shopAddFeatureToProduct ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_productFeatures",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopAddAttributeToProduct ($data) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_productAttributes",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopClearProductFeatures ($ProductID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_productFeatures",
            "action" => "delete",
            "condition" => array(
                "ProductID" => $app->getDB()->createCondition($ProductID)
            ),
            "options" => null
        ));
    }

    public static function shopClearProductAttributes ($ProductID, $attributeType = false) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "source" => "shop_productAttributes",
            "action" => "delete",
            "condition" => array(
                "ProductID" => $app->getDB()->createCondition($ProductID)
            ),
            "options" => null
        ));
        if (!empty($attributeType)) {
            $config['condition']['Attribute'] = $app->getDB()->createCondition(strtoupper($attributeType));
        }
        return $config;
    }
    // <<<< product features & attributes








    // Product category (catalog) >>>>>
    public static function shopCatalogBrands ($categoryID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCatalogBrands",
                "parameters" => array($categoryID)
            )
        ));
    }

    // public static function shopCategoryAllSubCategoriesGet ($categoryID) {
        // global $app;
    //     return $app->getDB()->createDBQuery(array(
    //         "action" => "call",
    //         "procedure" => array(
    //             "name" => "getAllShopCategorySubCategories",
    //             "parameters" => array($categoryID)
    //         )
    //     ));
    // }

    public static function getShopCatalogPriceEdges ($categoryID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCatalogPriceEdges",
                "parameters" => array($categoryID)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }
    // <<<< Product category (catalog)






    // Additional: category location >>>>>
    public static function shopCategoryLocationGet ($id) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCatalogLocation",
                "parameters" => array($id)
            )
        ));
    }
    // <<<< Additional: category location







    // Shop catalog tree >>>>>
    public static function shopCatalogTree ($selectedCategoryID = false) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_categories",
            "condition" => array(
                "Status" => $app->getDB()->createCondition("ACTIVE")
            ),
            "fields" => array("ID", "ParentID", "ExternalKey", "Name", "Image", "Status"),
        ));
        if ($selectedCategoryID !== false) {
            $config["condition"]["ID"] = $app->getDB()->createCondition($selectedCategoryID);
        }
        return $config;
    }
    // <<<< Shop catalog tree
















    // shop cetegories >>>>>
    public static function shopGetCategoryItem ($id = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_categories",
            "condition" => array(),
            "fields" => array("ID", "ParentID", "ExternalKey", "Name", "Description", "Image", "Status", "DateCreated", "DateUpdated"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));

        if (!is_null($id)) {
            $config["condition"] = array(
                "shop_categories.ID" => $app->getDB()->createCondition($id)
            );
        }

        return $config;
    }

    public static function shopGetCategoryList (array $options = array()) {
        global $app;
        $config = self::shopGetCategoryItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['removed'])) {
            $config['condition']['Status'] = $app->getDB()->createCondition('ACTIVE');
        }
        return $config;
    }

    public static function shopCreateCategory ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        $data["Description"] = empty($data["Description"]) ? "" : $data["Description"];
        $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name'], array('transliterate' => true));
        $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
        $data["Name"] = substr($data["Name"], 0, 300);
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_categories",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopUpdateCategory ($CategoryID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        if (isset($data['Name'])) {
            $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name'], array('transliterate' => true));
            $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
            $data["Name"] = substr($data["Name"], 0, 300);
        }
        // var_dump($data);
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_categories",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($CategoryID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopDeleteCategory ($CategoryID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_categories",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($CategoryID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }
    // shop cetegories <<<<<












    // shop origins <<<<<
    public static function shopGetOriginItem ($id = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_origins",
            "condition" => array(),
            "fields" => array("ID", "ExternalKey", "Name", "Description", "HomePage", "Status", "DateCreated", "DateUpdated"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));

        if (!is_null($id)) {
            $config["condition"] = array(
                "shop_origins.ID" => $app->getDB()->createCondition($id)
            );
        }

        return $config;
    }

    public static function shopGetOriginList (array $options = array()) {
        global $app;
        $config = self::shopGetOriginItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['removed'])) {
            $config['condition']['Status'] = $app->getDB()->createCondition('ACTIVE');
        }
        return $config;
    }

    public static function shopCreateOrigin ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        $data["Description"] = empty($data["Description"]) ? "" : $data["Description"];
        $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name'], array('transliterate' => true));
        $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
        $data["Name"] = substr($data["Name"], 0, 300);
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_origins",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopUpdateOrigin ($OriginID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        if (isset($data['Name'])) {
            $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name'], array('transliterate' => true));
            $data["ExternalKey"] = substr($data["ExternalKey"], 0, 50);
        }
        if (isset($data["Name"])) {
            $data["Name"] = substr($data["Name"], 0, 300);
        }
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_origins",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($OriginID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopDeleteOrigin ($OriginID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_origins",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($OriginID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }
    // shop origins <<<<<




















    // shop delivery agencies >>>>>
    public static function shopGetDeliveryAgencyByID ($id = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_deliveryAgencies",
            "condition" => array(),
            "fields" => array("ID", "Name", "HomePage", "Status", "DateCreated", "DateUpdated"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));

        if (!is_null($id))
            $config["condition"]["ID"] = $app->getDB()->createCondition($id);

        return $config;
    }

    public static function shopGetDeliveriesList (array $options = array()) {
        global $app;
        $config = self::shopGetDeliveryAgencyByID();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        return $config;
    }

    public static function shopCreateDeliveryAgent ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_deliveryAgencies",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopUpdateDeliveryAgent ($id, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_deliveryAgencies",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopDeleteDeliveryAgent ($id) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_deliveryAgencies",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }
    // <<<<< shop delivery agencies









    // shop settings >>>>>
    // public static function setting
    public static $ALLOW_MULTIPLE_SETTINGS = array('ADDRESS', 'EXCHANAGERATESDISPLAY'/*, 'PHONES', 'OPENHOURS', 'INFO'*/);
    public static $ALLOW_SETTINGS_TO_DELETE = array('ADDRESS', 'EXCHANAGERATESDISPLAY'/*, 'PHONES'*/);
    public static $SETTING_TYPE_TO_DBTABLE_MAP = array(
        'ADDRESS' => 'shop_settingsAddress',
        'ALERTS' => 'shop_settingsAlerts',
        'EXCHANAGERATES' => '',
        'EXCHANAGERATESDISPLAY' => 'shop_settingsExchangeRatesDisplay',
        'FORMORDER' => 'shop_settingsFormOrder',
        'MISC' => 'shop_settingsMisc',
        'PRODUCT' => 'shop_settingsProduct',
        'SEO' => 'shop_settingsSeo',
        'WEBSITE' => 'shop_settingsWebsite'
    );

    public static function isOneForCustomer ($type) {
        return !in_array($type, self::$ALLOW_MULTIPLE_SETTINGS);
    }
    public static function settingCanBeRemoved ($type) {
        return in_array($type, self::$ALLOW_SETTINGS_TO_DELETE);
    }

    public static function getVerifiedSettingsType ($type) {
        return isset(self::$SETTING_TYPE_TO_DBTABLE_MAP[$type]) ? $type : null;
    }

    public static function getSettingsDBTableNameByType ($type) {
        if (self::getVerifiedSettingsType($type)) {
            return self::$SETTING_TYPE_TO_DBTABLE_MAP[$type];
        }
        throw new Exception("Unknown shop settings type", 1);
    }

    public static function customerSettingsCount ($type) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => self::getSettingsDBTableNameByType($type),
            "fields" => array("@COUNT(*) AS ItemsCount"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));
        return $config;
    }

    public static function shopGetSettingByID ($type, $id) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => self::getSettingsDBTableNameByType($type),
            "condition" => array(),
            "fields" => array("*"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));
        // if (!is_null($id))
        $config["condition"]["ID"] = $app->getDB()->createCondition($id);
        return $config;
    }
    public static function shopGetSettingByType ($type) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => self::getSettingsDBTableNameByType($type),
            "fields" => array("*"),
            "limit" => 0,
            "options" => array()
        ));
        if (self::isOneForCustomer($type)) {
            $config["limit"] = 1;
            $config["options"]["expandSingleRecord"] = true;
        }
        return $config;
    }
    public static function shopGetSettingsAddressActive () {
        global $app;
        $config = self::shopGetSettingByType('ADDRESS');
        $config["condition"]["Status"] =  $app->getDB()->createCondition('ACTIVE');
        return $config;
    }
    public static function shopGetSettingsAddressPhones ($addressID) {
        global $app;
        $config = self::shopGetSettingByType('PHONES');
        $config["condition"]["ShopAddressID"] = $app->getDB()->createCondition($addressID);
        return $config;
    }
    public static function shopGetSettingsAddressOpenHours ($addressID) {
        global $app;
        $config = self::shopGetSettingByType('OPENHOURS');
        $config["condition"]["ShopAddressID"] = $app->getDB()->createCondition($addressID);
        $config["limit"] = 1;
        $config["options"]["expandSingleRecord"] = true;
        return $config;
    }
    public static function shopGetSettingsAddressInfo ($addressID) {
        global $app;
        $config = self::shopGetSettingByType('INFO');
        $config["condition"]["ShopAddressID"] = $app->getDB()->createCondition($addressID);
        $config["limit"] = 1;
        $config["options"]["expandSingleRecord"] = true;
        return $config;
    }
    public static function shopCreateSetting ($type, $data) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => self::getSettingsDBTableNameByType($type),
            "action" => "insert",
            "data" => $data,
            "options" => null,
            "saveOptions" => array(
                "useInsertIgnore" => true
            )
        ));
    }

    public static function shopUpdateSetting ($type, $id, $data) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "source" => self::getSettingsDBTableNameByType($type),
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
        return $config;
    }

    public static function shopRemoveSetting ($type, $id) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => self::getSettingsDBTableNameByType($type),
            "action" => "delete",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            )
        ));
    }
    // <<<<< shop delivery agencies










    // Shop order >>>>>
    public static function shopGetOrderItem ($orderID = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_orders",
            "condition" => array(),
            "fields" => array("ID", "UserID", "UserAddressesID", "DeliveryID", "ExchangeRateID", "CustomerCurrencyRate", "CustomerCurrencyName", "Warehouse", "Comment", "InternalComment", "Status", "Hash", "PromoID", "DateCreated", "DateUpdated"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));

        if (!is_null($orderID))
            $config["condition"] = array(
                "shop_orders.ID" => $app->getDB()->createCondition($orderID)
            );

        return $config;
    }
    public static function getShopOrderList (array $options = array()) {
        global $app;
        $config = self::shopGetOrderItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (!empty($options['_pSearch'])) {
            if (is_string($options['_pSearch'])) {
                $config['condition']["Hash"] = $app->getDB()->createCondition($options['_pSearch'] . '%', 'like');
                // $config['condition']["Model"] = $app->getDB()->createCondition('%' . $options['search'] . '%', 'like');
                // $config['condition']["SKU"] = $app->getDB()->createCondition('%' . $options['search'] . '%', 'like');
            } elseif (is_array($options['_pSearch'])) {
                foreach ($options['_pSearch'] as $value) {
                    $config['condition']["Hash"] = $app->getDB()->createCondition($value . '%', 'like');
                    // $config['condition']["Model"] = $app->getDB()->createCondition('%' . $value . '%', 'like');
                    // $config['condition']["SKU"] = $app->getDB()->createCondition('%' . $value . '%', 'like');
                }
            }
        }
        return $config;
    }
    public static function getShopOrderList_Pending () {
        global $app;
        $config = self::getShopOrderList();
        $config['condition']['Status'] = $app->getDB()->createCondition('NEW');
        return $config;
    }
    public static function getShopOrderList_Todays () {
        global $app;
        $config = self::getShopOrderList();
        $config['condition']['DateCreated'] = $app->getDB()->createCondition(date('Y-m-d'), ">");
        return $config;
    }
    public static function getShopOrderList_Expired () {
        global $app;
        $config = self::getShopOrderList();
        $config['condition']['Status'] = $app->getDB()->createCondition(array("SHOP_CLOSED", "SHOP_REFUNDED", "CUSTOMER_CANCELED"), "NOT IN");
        $config['condition']['DateCreated'] = $app->getDB()->createCondition(date('Y-m-d', strtotime("-1 week")), "<");
        return $config;
    }
    public static function shopCreateOrder ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        $data["Hash"] = substr(md5(time() . md5(time())), 0, 5);
        // adjust values
        if (is_string($data["DeliveryID"])) {
            $data["DeliveryID"] = null;
        }
        if (is_string($data["Warehouse"])) {
            $data["Warehouse"] = null;
        }
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_orders",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }
    public static function shopCreateOrderBought ($data) {
        global $app;
        $data["DateCreated"] = $app->getDB()->getDate();
        $data["IsPromo"] = empty($data["IsPromo"]) ? 0 : 1;
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_boughts",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }
    public static function shopGetOrderBoughts ($orderID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_boughts",
            "condition" => array(
                "OrderID" => $app->getDB()->createCondition($orderID)
            ),
            "fields" => array("ID", "ProductID", "Price", "SellingPrice", "Quantity", "IsPromo", "DateCreated"),
            "offset" => 0,
            "limit" => 0
        ));
    }
    public static function getShopOrderByHash ($orderHash) {
        global $app;
        $config = self::shopGetOrderItem();
        $config['condition'] = array(
            "Hash" => $app->getDB()->createCondition($orderHash)
        );
        $config['options'] = array(
            "expandSingleRecord" => true
        );
        $config['limit'] = 1;
        return $config;
    }
    public static function shopUpdateOrder ($orderID, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "action" => "update",
            "source" => "shop_orders",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($orderID)
            ),
            "data" => $data,
            "options" => null
        ));
    }
    public static function shopDisableOrder ($OrderID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_orders",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($OrderID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }
    // <<<< Shop order













    // >>>> Shop statistics
    public static function shopStat_PopularProducts (array $options = array()) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_boughts",
            "fields" => array("ProductID", "@SUM(Quantity) AS SoldTotal", "@SUM(Price * Quantity) AS SumTotal"),
            "order" => array(
                "field" => "SoldTotal",
                "ordering" => "DESC"
            ),
            "limit" => !empty($options['limit']) ? $options['limit'] : 15,
            "group" => "ProductID",
            "options" => null
        ));
    }

    public static function shopStat_NonPopularProducts (array $options = array()) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_products",
            "fields" => array("ID"),
            "condition" => array(
                "Status" => $app->getDB()->createCondition("ACTIVE"),
                "ID" => $app->getDB()->createCondition("SELECT ProductID AS ID FROM shop_boughts", "NOT IN")
            ),
            "order" => array(
                "field" => "DateCreated",
                "ordering" => "ASC"
            ),
            "limit" => !empty($options['limit']) ? $options['limit'] : 15,
            "options" => null
        ));
    }

    public static function shopStat_ProductsOverview ($filter = null) {
        global $app;
        $config = self::shopGetProductItem();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "Status");
        $config['group'] = "Status";
        $config['limit'] = 0;
        $config['options'] = array(
            'asDict' => array(
                'keys' => 'Status',
                'values' => 'ItemsCount'
            )
        );
        $config['condition'] = array();
        unset($config['additional']);
        // var_dump($requestGetData);
        if (!empty($filter)) {
            if (isset($filter['_fCategoryID']))
                $config['condition']['CategoryID'] = $app->getDB()->createCondition($filter['_fCategoryID']);
        }
        return $config;
    }

    public static function shopStat_OrdersOverview () {
        global $app;
        $config = self::shopGetOrderItem();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "Status");
        $config['group'] = "Status";
        $config['limit'] = 0;
        $config['options'] = array(
            'asDict' => array(
                'keys' => 'Status',
                'values' => 'ItemsCount'
            )
        );
        $config['condition'] = array();
        unset($config['additional']);
        return $config;
    }

    public static function shopStat_OrdersIntensityLastMonth ($status, $comparator = null) {
        global $app;
        if (!is_string($comparator))
            $comparator = $app->getDB()->DEFAULT_COMPARATOR;
        $config = self::shopGetOrderItem();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "@Date(DateUpdated) AS CloseDate");
        $config['condition'] = array(
            'Status' => $app->getDB()->createCondition($status, $comparator),
            'DateUpdated' => $app->getDB()->createCondition(date('Y-m-d', strtotime("-1 month")), ">")
        );
        $config['options'] = array(
            'asDict' => array(
                'keys' => 'CloseDate',
                'values' => 'ItemsCount'
            )
        );
        $config['group'] = 'Date(DateUpdated)';
        $config['limit'] = 0;
        unset($config['additional']);
        // var_dump($config);
        return $config;
    }

    public static function shopStat_ProductsIntensityLastMonth ($status) {
        global $app;
        $config = self::shopGetProductItem();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "@Date(DateUpdated) AS CloseDate");
        $config['condition'] = array(
            'Status' => $app->getDB()->createCondition($status),
            'DateUpdated' => $app->getDB()->createCondition(date('Y-m-d', strtotime("-1 month")), ">")
        );
        $config['options'] = array(
            'asDict' => array(
                'keys' => 'CloseDate',
                'values' => 'ItemsCount'
            )
        );
        $config['group'] = 'Date(DateCreated)';
        $config['limit'] = 0;
        unset($config['additional']);
        return $config;
    }
    // <<<< Shop statistics



















    // <<<< Promo area
    public static function shopGetPromoByHash ($hash, $activeOnly) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_promo",
            "condition" => array(
                "Code" => $app->getDB()->createCondition($hash)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));

        if ($activeOnly) {
            $config['condition']['DateStart'] = $app->getDB()->createCondition($app->getDB()->getDate(), '<=');
            $config['condition']['DateExpire'] = $app->getDB()->createCondition($app->getDB()->getDate(), '>=');
        }

        return $config;
    }

    public static function shopGetPromoByID ($promoID = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_promo",
            "condition" => array(),
            "fields" => array("ID", "Code", "DateStart", "DateExpire", "Discount", "DateCreated"),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));

        if (!is_null($promoID))
            $config["condition"] = array(
                "ID" => $app->getDB()->createCondition($promoID)
            );
        return $config;
    }

    public static function shopGetPromoList (array $options = array()) {
        global $app;
        $config = self::shopGetPromoByID();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['expired'])) {
            $config['condition']['DateExpire'] = $app->getDB()->createCondition($app->getDB()->getDate(), '>=');
        }
        // if (empty($options['future'])) {
            // $config['condition']['DateStart'] = $app->getDB()->createCondition($app->getDB()->getDate(), '<=');
        // }
        return $config;
    }

    public static function shopCreatePromo ($data) {
        global $app;
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "action" => "insert",
            "source" => "shop_promo",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopUpdatePromo ($promoID, $data) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "update",
            "source" => "shop_promo",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($promoID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopExpirePromo ($promoID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "update",
            "source" => "shop_promo",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($promoID)
            ),
            "data" => array(
                "DateExpire" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }
    // Promo area >>>>>






















    // shop delivery agencies >>>>>
    public static function shopGetExchangeRateByID ($id = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_currency",
            "condition" => array(),
            "fields" => array("ID", "CurrencyA", "CurrencyB", "Rate"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));

        if (!is_null($id))
            $config["condition"]["ID"] = $app->getDB()->createCondition($id);

        return $config;
    }
    public static function shopGetExchangeRateTo_ByCurrencyName ($currencyNameTo = null) {
        global $app;
        $config = self::shopGetExchangeRateByID();
        $config["condition"] = array(
            "CurrencyB" => $app->getDB()->createCondition($currencyNameTo)
        );
        return $config;
    }
    public static function shopGetExchangeRateFrom_ByCurrencyName ($currencyNameFrom = null) {
        global $app;
        $config = self::shopGetExchangeRateByID();
        $config["condition"] = array(
            "CurrencyA" => $app->getDB()->createCondition($currencyNameFrom)
        );
        return $config;
    }
    public static function shopGetExchangeRateByBothNames ($currencyNameFrom, $currencyNameTo) {
        global $app;
        $config = self::shopGetExchangeRateByID();
        $config["condition"] = array(
            "CurrencyA" => $app->getDB()->createCondition($currencyNameFrom),
            "CurrencyB" => $app->getDB()->createCondition($currencyNameTo)
        );
        return $config;
    }

    public static function shopGetExchangeRatesList (array $options = array()) {
        global $app;
        $config = self::shopGetExchangeRateByID();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (isset($options['fields'])) {
            $config['fields'] = $options['fields'];
        }
        if (isset($options['limit'])) {
            $config['limit'] = $options['limit'];
        }
        if (empty($options['removed'])) {
            $config['condition']['Status'] = $app->getDB()->createCondition('ACTIVE');
        }
        return $config;
    }

    public static function shopGetUniqueAvailableCurrencyNamesByField ($fieldToGroupBy) {
        global $app;
        $config = self::shopGetExchangeRateByID();
        $config['fields'] = array($fieldToGroupBy);
        $config['limit'] = 0;
        $config['group'] = $fieldToGroupBy;
        $config['options']['expandSingleRecord'] = false;
        return $config;
    }

    public static function shopCreateExchangeRate ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_currency",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopUpdateExchangeRate ($id, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_currency",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function shopDeleteExchangeRate ($id) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_currency",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $app->getDB()->getDate()
            ),
            "options" => null
        ));
    }
    // <<<<< shop delivery agencies





}

?>
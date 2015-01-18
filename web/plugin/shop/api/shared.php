<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use Exception;
use ArrayObject;

class shared {

    // var $Table_ShopOrders = "shop_orders";
    // var $Table_ShopProducts = "shop_products";
    // var $Table_ShopOrigins = "shop_origins";
    // var $Table_ShopCategories = "shop_categories";
    // var $Table_ShopProductAttr = "shop_productAttributes";
    // var $Table_ShopDeliveryAgencies = "shop_deliveryAgencies";
    // var $Table_ShopFeatures = "shop_features";
    // var $Table_ShopSettings = "shop_settings";

    // products >>>>>
    public static function jsapiShopGetProductItem ($ProductID = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_products",
            "fields" => array("ID", "CategoryID", "OriginID", "ExternalKey", "Name", "Description", "Model", "SKU", "Price", "IsPromo", "Status", "DateUpdated", "DateCreated"),
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

    public static function jsapiShopGetProductItemByExternalKey ($externalKey) {
        global $app;
        $config = self::jsapiShopGetProductItem();
        $config['condition']["shop_products.ExternalKey"] = $app->getDB()->createCondition($externalKey);
        return $config;
    }

    public static function jsapiShopGetProductList (array $options = array()) {
        global $app;
        $config = self::jsapiShopGetProductItem();
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

        // var_dump($config['condition']);
        return $config;
    }

    public static function jsapiShopGetLatestProductsList () {
        global $app;
        $config = self::jsapiShopGetProductItem();
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

    public static function jsapiShopCreateProduct ($data) {
        global $app;
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

    public static function jsapiShopUpdateProduct ($ProductID, $data) {
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

    public static function jsapiShopDeleteProduct ($ProductID) {
        global $app;
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
    public static function jsapiGetShopCatalogProductList ($ids) {
        global $app;
        $config = self::jsapiShopGetProductList();
        $config['condition']["shop_products.CategoryID"] = $app->getDB()->createCondition($ids, "IN");
        return $config;
    }

    // public static function jsapiGetShopCategoryProductInfo ($ids) {
        global $app;
    //     $config = self::jsapiGetShopCategoryProductList($ids);
    //     $config["fields"] = array("ID", "CategoryID", "OriginID");
    //     $config['limit'] = 0;
    //     return $config;
    // }

    // public static function jsapiGetShopCategoryProductInfo ($ids) {
        global $app;
    //     $config = self::jsapiGetShopCategoryProductList($ids);
    //     $config["fields"] = array("ID", "CategoryID", "OriginID");
    //     $config['limit'] = 0;
    //     return $config;
    // }

    public static function jsapiGetShopCategoryProductInfo () {
        global $app;
        $config = self::jsapiShopGetProductList();
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
    public static function jsapiShopGetProductPriceStats ($id) {
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
    public static function jsapiShopGetProductRelations ($id) {
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
    public static function jsapiShopGetProductFeatures ($id) {
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

    public static function jsapiShopGetProductAttributes ($id = null, $type = null) {
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

    public static function jsapiShopCreateFeature ($data) {
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

    public static function jsapiShopGetFeatures () {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_features",
            "fields" => array("ID", "FieldName", "GroupName"),
            "limit" => 0,
            "options" => array()
        ));
    }

    public static function jsapiShopAddFeatureToProduct ($data) {
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

    public static function jsapiShopAddAttributeToProduct ($data) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_productAttributes",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public static function jsapiShopClearProductFeatures ($ProductID) {
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

    public static function jsapiShopClearProductAttributes ($ProductID, $attributeType = false) {
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
    public static function jsapiShopCatalogBrands ($categoryID) {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCatalogBrands",
                "parameters" => array($categoryID)
            )
        ));
    }

    // public static function jsapiShopCategoryAllSubCategoriesGet ($categoryID) {
        global $app;
    //     return $app->getDB()->createDBQuery(array(
    //         "action" => "call",
    //         "procedure" => array(
    //             "name" => "getAllShopCategorySubCategories",
    //             "parameters" => array($categoryID)
    //         )
    //     ));
    // }

    public static function jsapiGetShopCatalogPriceEdges ($categoryID) {
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
    public static function jsapiShopCategoryLocationGet ($id) {
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
    public static function jsapiShopCatalogTree ($selectedCategoryID = false) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_categories",
            "condition" => array(
                "Status" => $app->getDB()->createCondition("ACTIVE")
            ),
            "fields" => array("ID", "ParentID", "ExternalKey", "Name", "Status"),
        ));
        if ($selectedCategoryID !== false) {
            $config["condition"]["ID"] = $app->getDB()->createCondition($selectedCategoryID);
        }
        return $config;
    }
    // <<<< Shop catalog tree
















    // shop cetegories >>>>>
    public static function jsapiShopGetCategoryItem ($id = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_categories",
            "condition" => array(),
            "fields" => array("ID", "ParentID", "ExternalKey", "Name", "Description", "Status", "DateCreated", "DateUpdated"),
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

    public static function jsapiShopGetCategoryList (array $options = array()) {
        global $app;
        $config = self::jsapiShopGetCategoryItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['removed'])) {
            $config['condition']['Status'] = $app->getDB()->createCondition('ACTIVE');
        }
        return $config;
    }

    public static function jsapiShopCreateCategory ($data) {
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

    public static function jsapiShopUpdateCategory ($CategoryID, $data) {
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

    public static function jsapiShopDeleteCategory ($CategoryID) {
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
    public static function jsapiShopGetOriginItem ($id = null) {
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

    public static function jsapiShopGetOriginList (array $options = array()) {
        global $app;
        $config = self::jsapiShopGetOriginItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['removed'])) {
            $config['condition']['Status'] = $app->getDB()->createCondition('ACTIVE');
        }
        return $config;
    }

    public static function jsapiShopCreateOrigin ($data) {
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

    public static function jsapiShopUpdateOrigin ($OriginID, $data) {
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

    public static function jsapiShopDeleteOrigin ($OriginID) {
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
    public static function jsapiShopGetDeliveryAgencyByID ($id = null) {
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

    public static function jsapiShopGetDeliveriesList (array $options = array()) {
        global $app;
        $config = self::jsapiShopGetDeliveryAgencyByID();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        return $config;
    }

    public static function jsapiShopCreateDeliveryAgent ($data) {
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

    public static function jsapiShopUpdateDeliveryAgent ($id, $data) {
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

    public static function jsapiShopDeleteDeliveryAgent ($id) {
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















    // shop delivery agencies >>>>>
    public static function jsapiShopGetSettingByID ($id = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_settings",
            "condition" => array(),
            "fields" => array("ID", "Property", "Label", "Value", "Status", "Type", "DateCreated"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));

        if (!is_null($id))
            $config["condition"]["ID"] = $app->getDB()->createCondition($id);

        return $config;
    }

    public static function jsapiShopGetSettingByName ($name = null) {
        global $app;
        $config = self::jsapiShopGetSettingByID();
        unset($config['condition']['ID']);
        $config['condition']['Property'] = $app->getDB()->createCondition($name);
        return $config;
    }

    public static function jsapiShopGetSettingByType ($type = null) {
        global $app;
        $config = self::jsapiShopGetSettingByID();
        unset($config['condition']['ID']);
        $config['limit'] = 0;
        $config['condition']['Type'] = $app->getDB()->createCondition($type);
        return $config;
    }

    public static function jsapiShopGetSettingsList (array $options = array()) {
        global $app;
        $config = self::jsapiShopGetSettingByID();
        $config['fields'] = array("ID");
        $config['limit'] = 0;
        $config['options']['expandSingleRecord'] = false;
        return $config;
    }

    public static function jsapiShopCreateSetting ($data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["DateCreated"] = $app->getDB()->getDate();
        $data["Status"] = 'ACTIVE';
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_settings",
            "action" => "insert",
            "data" => $data,
            "options" => null,
            "saveOptions" => array(
                "useInsertIgnore" => true
            )
        ));
    }

    public static function jsapiShopUpdateSetting ($id, $data) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_settings",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public static function jsapiShopUpdateSettingByName ($id, $data) {
        global $app;
        $config = self::jsapiShopUpdateSetting($id, $data);
        unset($config['condition']['ID']);
        $config['condition']['Property'] = $app->getDB()->createCondition($id);
        return $config;
    }

    public static function jsapiShopRemoveSetting ($id) {
        global $app;
        $data["DateUpdated"] = $app->getDB()->getDate();
        $data["Status"] = 'REMOVED';
        return $app->getDB()->createDBQuery(array(
            "source" => "shop_settings",
            "action" => "update",
            "condition" => array(
                "ID" => $app->getDB()->createCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
    }
    // <<<<< shop delivery agencies










    // Shop order >>>>>
    public static function jsapiShopGetOrderItem ($orderID = null) {
        global $app;
        $config = $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_orders",
            "condition" => array(),
            "fields" => array("ID", "AccountID", "AccountAddressesID", "DeliveryID", "ExchangeRateID", "CustomerCurrencyRate", "CustomerCurrencyName", "Warehouse", "Comment", "InternalComment", "Status", "Hash", "PromoID", "DateCreated", "DateUpdated"),
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
    public static function jsapiGetShopOrderList (array $options = array()) {
        global $app;
        $config = self::jsapiShopGetOrderItem();
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
    public static function jsapiGetShopOrderList_Pending () {
        global $app;
        $config = self::jsapiGetShopOrderList();
        $config['condition']['Status'] = $app->getDB()->createCondition('NEW');
        return $config;
    }
    public static function jsapiGetShopOrderList_Todays () {
        global $app;
        $config = self::jsapiGetShopOrderList();
        $config['condition']['DateCreated'] = $app->getDB()->createCondition(date('Y-m-d'), ">");
        return $config;
    }
    public static function jsapiGetShopOrderList_Expired () {
        global $app;
        $config = self::jsapiGetShopOrderList();
        $config['condition']['Status'] = $app->getDB()->createCondition(array("SHOP_CLOSED", "SHOP_REFUNDED", "CUSTOMER_CANCELED"), "NOT IN");
        $config['condition']['DateCreated'] = $app->getDB()->createCondition(date('Y-m-d', strtotime("-1 week")), "<");
        return $config;
    }
    public static function jsapiShopCreateOrder ($data) {
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
    public static function jsapiShopCreateOrderBought ($data) {
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
    public static function jsapiShopGetOrderBoughts ($orderID) {
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
    public static function jsapiGetShopOrderByHash ($orderHash) {
        global $app;
        $config = self::jsapiShopGetOrderItem();
        $config['condition'] = array(
            "Hash" => $app->getDB()->createCondition($orderHash)
        );
        $config['options'] = array(
            "expandSingleRecord" => true
        );
        $config['limit'] = 1;
        return $config;
    }
    public static function jsapiShopUpdateOrder ($orderID, $data) {
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
    public static function jsapiShopDisableOrder ($OrderID) {
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
    public static function jsapiShopStat_PopularProducts () {
        global $app;
        return $app->getDB()->createDBQuery(array(
            "action" => "select",
            "source" => "shop_boughts",
            "fields" => array("ProductID", "@SUM(Quantity) AS SoldTotal", "@SUM(Price * Quantity) AS SumTotal"),
            "order" => array(
                "field" => "SoldTotal",
                "ordering" => "DESC"
            ),
            "limit" => 15,
            "group" => "ProductID",
            "options" => null
        ));
    }

    public static function jsapiShopStat_NonPopularProducts () {
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
            "limit" => 15,
            "options" => null
        ));
    }

    public static function jsapiShopStat_ProductsOverview ($filter = null) {
        global $app;
        $config = self::jsapiShopGetProductItem();
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

    public static function jsapiShopStat_OrdersOverview () {
        global $app;
        $config = self::jsapiShopGetOrderItem();
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

    public static function jsapiShopStat_OrdersIntensityLastMonth ($status, $comparator = null) {
        global $app;
        if (!is_string($comparator))
            $comparator = self::DEFAULT_COMPARATOR;
        $config = self::jsapiShopGetOrderItem();
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

    public static function jsapiShopStat_ProductsIntensityLastMonth ($status) {
        global $app;
        $config = self::jsapiShopGetProductItem();
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
    public static function jsapiShopGetPromoByHash ($hash, $activeOnly) {
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

    public static function jsapiShopGetPromoByID ($promoID = null) {
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

    public static function jsapiShopGetPromoList (array $options = array()) {
        global $app;
        $config = self::jsapiShopGetPromoByID();
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

    public static function jsapiShopCreatePromo ($data) {
        global $app;
        $data["DateCreated"] = $app->getDB()->getDate();
        return $app->getDB()->createDBQuery(array(
            "action" => "insert",
            "source" => "shop_promo",
            "data" => $data,
            "options" => null
        ));
    }

    public static function jsapiShopUpdatePromo ($promoID, $data) {
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

    public static function jsapiShopExpirePromo ($promoID) {
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
    public static function jsapiShopGetExchangeRateByID ($id = null) {
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
    public static function jsapiShopGetExchangeRateTo_ByCurrencyName ($currencyNameTo = null) {
        global $app;
        $config = self::jsapiShopGetExchangeRateByID();
        $config["condition"] = array(
            "CurrencyB" => $app->getDB()->createCondition($currencyNameTo)
        );
        return $config;
    }
    public static function jsapiShopGetExchangeRateFrom_ByCurrencyName ($currencyNameFrom = null) {
        global $app;
        $config = self::jsapiShopGetExchangeRateByID();
        $config["condition"] = array(
            "CurrencyA" => $app->getDB()->createCondition($currencyNameFrom)
        );
        return $config;
    }
    public static function jsapiShopGetExchangeRateByBothNames ($currencyNameFrom, $currencyNameTo) {
        global $app;
        $config = self::jsapiShopGetExchangeRateByID();
        $config["condition"] = array(
            "CurrencyA" => $app->getDB()->createCondition($currencyNameFrom),
            "CurrencyB" => $app->getDB()->createCondition($currencyNameTo)
        );
        return $config;
    }

    public static function jsapiShopGetExchangeRatesList (array $options = array()) {
        global $app;
        $config = self::jsapiShopGetExchangeRateByID();
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

    public static function jsapiShopGetUniqueAvailableCurrencyNamesByField ($fieldToGroupBy) {
        global $app;
        $config = self::jsapiShopGetExchangeRateByID();
        $config['fields'] = array($fieldToGroupBy);
        $config['limit'] = 0;
        $config['group'] = $fieldToGroupBy;
        $config['options']['expandSingleRecord'] = false;
        return $config;
    }

    public static function jsapiShopCreateExchangeRate ($data) {
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

    public static function jsapiShopUpdateExchangeRate ($id, $data) {
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

    public static function jsapiShopDeleteExchangeRate ($id) {
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
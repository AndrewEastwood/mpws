<?php

class configurationShopDataSource extends objectConfiguration {

    static $Table_ShopOrders = "shop_orders";
    static $Table_ShopProducts = "shop_products";
    static $Table_ShopOrigins = "shop_origins";
    static $Table_ShopCategories = "shop_categories";
    static $Table_ShopProductAttr = "shop_productAttributes";
    static $Table_ShopDeliveryAgencies = "shop_deliveryAgencies";
    static $Table_ShopFeatures = "shop_features";
    static $Table_ShopSettings = "shop_settings";

    // products >>>>>
    static function jsapiShopGetProductItem ($ProductID = null, $fullInfo = true) {
        $config = self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_products",
            "fields" => array("ID", "CategoryID", "OriginID", "ExternalKey", "Name", "Description", "Model", "SKU", "Price", "IsPromo", "Status", "DateUpdated", "DateCreated"),
            "offset" => 0,
            "limit" => 1,
            "additional" => array(
                "shop_categories" => array(
                    "constraint" => array("shop_categories.ID", "=", "shop_products.CategoryID"),
                    "fields" => array(
                        "CategoryName" => "Name",
                        "CategoryDescription" => "Description",
                        "CategoryStatus" => "Status"
                    )
                ),
                "shop_origins" => array(
                    "constraint" => array("shop_origins.ID", "=", "shop_products.OriginID"),
                    "fields" => array(
                        "OriginName" => "Name",
                        "OriginDescription" => "Description",
                        "OriginStatus" => "Status"
                    )
                )
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));

        if (!is_null($ProductID))
            $config["condition"] = array(
                "shop_products.ID" => self::jsapiCreateDataSourceCondition($ProductID)
            );

        if (!$fullInfo) {
            unset($config['additional']);
        }

        return $config;
    }

    static function jsapiShopGetProductList (array $options = array()) {
        $config = self::jsapiShopGetProductItem();
        $config['condition'] = array();
        $config["fields"] = array("ID");
        $config['limit'] = 64;
        $config['group'] = 'shop_products.ID';
        $config['additional'] = array(
            "shop_categories" => array(
                "constraint" => array("shop_products.CategoryID", "=", "shop_categories.ID"),
                "fields" => array("Status")
            ),
            "shop_origins" => array(
                "constraint" => array("shop_products.OriginID", "=", "shop_origins.ID"),
                "fields" => array("Status")
            ),
            "shop_productFeatures" => array(
                "constraint" => array("shop_products.ID", "=", "shop_productFeatures.ProductID"),
                "fields" => array("FeatureID")
            )
        );
        unset($config['options']);

        if (!empty($options['useFeatures'])) {
            unset($config['additional']['shop_productFeatures']);
        }

        if (!empty($options['_pSearch'])) {
            if (is_string($options['_pSearch'])) {
                $config['condition']["Name"] = self::jsapiCreateDataSourceCondition('%' . $options['_pSearch'] . '%', 'like');
                // $config['condition']["Model"] = self::jsapiCreateDataSourceCondition('%' . $options['search'] . '%', 'like');
                // $config['condition']["SKU"] = self::jsapiCreateDataSourceCondition('%' . $options['search'] . '%', 'like');
            } elseif (is_array($options['_pSearch'])) {
                foreach ($options['_pSearch'] as $value) {
                    $config['condition']["Name"] = self::jsapiCreateDataSourceCondition('%' . $value . '%', 'like');
                    // $config['condition']["Model"] = self::jsapiCreateDataSourceCondition('%' . $value . '%', 'like');
                    // $config['condition']["SKU"] = self::jsapiCreateDataSourceCondition('%' . $value . '%', 'like');
                }
            }
        }

        return $config;
    }

    static function jsapiShopCreateProduct ($data) {
        $data["DateUpdated"] = self::getDate();
        $data["DateCreated"] = self::getDate();
        $ExternalKey = array();
        if (isset($data['Name']))
            $ExternalKey[] = $data['Name'];
        if (isset($data['Model']))
            $ExternalKey[] = $data['Model'];
        if (isset($data['SKU']))
            $ExternalKey[] = $data['SKU'];
        if (!empty($ExternalKey))
            $data["ExternalKey"] = libraryUtils::url_slug(implode('_', $ExternalKey));
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_products",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopUpdateProduct ($ProductID, $data) {
        $data["DateUpdated"] = self::getDate();
        $ExternalKey = array();
        if (isset($data['Name']))
            $ExternalKey[] = $data['Name'];
        if (isset($data['Model']))
            $ExternalKey[] = $data['Model'];
        if (isset($data['SKU']))
            $ExternalKey[] = $data['SKU'];
        if (!empty($ExternalKey))
            $data["ExternalKey"] = libraryUtils::url_slug(implode('_', $ExternalKey));
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_products",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($ProductID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopDeleteProduct ($ProductID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_products",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($ProductID)
            ),
            "data" => array(
                "Status" => 'ARCHIVED',
                "DateUpdated" => self::getDate()
            ),
            "options" => null
        ));
    }
    // products >>>>>


    // Product category (catalog)
    static function jsapiGetShopCategoryProductList ($ids) {
        $config = self::jsapiShopGetProductList();
        $config['condition']["shop_products.CategoryID"] = self::jsapiCreateDataSourceCondition($ids, "IN");
        return $config;
    }

    static function jsapiGetShopCategoryProductInfo ($ids) {
        $config = self::jsapiGetShopCategoryProductList($ids);
        $config["fields"] = array("ID", "CategoryID", "OriginID");
        $config['limit'] = 0;
        return $config;
    }









    // Product price stats >>>>>
    static function jsapiShopGetProductPriceStats ($id) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_productPrices",
            "condition" => array(
                "ProductID" => self::jsapiCreateDataSourceCondition($id)
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
    static function jsapiShopGetProductRelations ($id) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_relations",
            "condition" => array(
                "ProductA_ID" => self::jsapiCreateDataSourceCondition($id)
            ),
            "fields" => array("ProductB_ID"),
            "offset" => 0,
            "limit" => 0
        ));
    }
    // <<<< Product relations













    // product features & attributes >>>>>
    static function jsapiShopGetProductFeatures ($id) {
        return self::jsapiGetDataSourceConfig(array(
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
                "ProductID" => self::jsapiCreateDataSourceCondition($id)
            ),
            "limit" => 0,
            "options" => array()
        ));
    }

    static function jsapiShopGetProductAttributes ($id = null, $type = null) {
        $config = self::jsapiGetDataSourceConfig(array(
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
            $config['condition']['ProductID'] = self::jsapiCreateDataSourceCondition($id);
        }
        if (!empty($type)) {
            $config['condition']['Attribute'] = self::jsapiCreateDataSourceCondition($type);
        }

        return $config;
    }

    static function jsapiShopCreateFeature ($data) {
        $data["DateUpdated"] = self::getDate();
        $data["DateCreated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_features",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopGetFeatures () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_features",
            "fields" => array("ID", "FieldName", "GroupName"),
            "limit" => 0,
            "options" => array()
        ));
    }

    static function jsapiShopAddFeatureToProduct ($data) {
        $data["DateUpdated"] = self::getDate();
        $data["DateCreated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_productFeatures",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopAddAttributeToProduct ($data) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_productAttributes",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopClearProductFeatures ($ProductID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_productFeatures",
            "action" => "delete",
            "condition" => array(
                "ProductID" => self::jsapiCreateDataSourceCondition($ProductID)
            ),
            "options" => null
        ));
    }

    static function jsapiShopClearProductAttributes ($ProductID, $attributeType = false) {
        $config = self::jsapiGetDataSourceConfig(array(
            "source" => "shop_productAttributes",
            "action" => "delete",
            "condition" => array(
                "ProductID" => self::jsapiCreateDataSourceCondition($ProductID)
            ),
            "options" => null
        ));
        if (!empty($attributeType)) {
            $config['condition']['Attribute'] = self::jsapiCreateDataSourceCondition(strtoupper($attributeType));
        }
        return $config;
    }
    // <<<< product features & attributes








    // Product category (catalog) >>>>>
    static function jsapiShopCategoryAndSubCategoriesAllBrandsGet ($categoryID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getAllShopCategoryBrandsWithSubCategories",
                "parameters" => array($categoryID)
            )
        ));
    }

    static function jsapiShopCategoryAllSubCategoriesGet ($categoryID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getAllShopCategorySubCategories",
                "parameters" => array($categoryID)
            )
        ));
    }

    static function jsapiShopCategoryPriceEdgesGet ($categoryID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCategoryPriceEdges",
                "parameters" => array($categoryID)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }
    // <<<< Product category (catalog)






    // Additional: category location >>>>>
    static function jsapiShopCategoryLocationGet ($id) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCategoryLocation",
                "parameters" => array($id)
            )
        ));
    }
    // <<<< Additional: category location







    // Shop catalog tree >>>>>
    static function jsapiShopCatalogTree () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_categories",
            "condition" => array(
                "Status" => self::jsapiCreateDataSourceCondition("ACTIVE")
            ),
            "fields" => array("ID", "ParentID", "ExternalKey", "Name", "Status"),
        ));
    }
    // <<<< Shop catalog tree
















    // shop cetegories >>>>>
    static function jsapiShopGetCategoryItem ($id = null) {
        $config = self::jsapiGetDataSourceConfig(array(
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
                "shop_categories.ID" => self::jsapiCreateDataSourceCondition($id)
            );
        }

        return $config;
    }

    static function jsapiShopGetCategoryList (array $options = array()) {
        $config = self::jsapiShopGetCategoryItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['removed'])) {
            $config['condition']['Status'] = self::jsapiCreateDataSourceCondition('ACTIVE');
        }
        return $config;
    }

    static function jsapiShopCreateCategory ($data) {
        $data["DateUpdated"] = self::getDate();
        $data["DateCreated"] = self::getDate();
        $data["ExternalKey"] = libraryUtils::url_slug($data['Name']);
        $data["Description"] = empty($data["Description"]) ? "" : $data["Description"];
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_categories",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopUpdateCategory ($CategoryID, $data) {
        $data["DateUpdated"] = self::getDate();
        if (isset($data['Name']))
            $data["ExternalKey"] = libraryUtils::url_slug($data['Name']);
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_categories",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($CategoryID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopDeleteCategory ($CategoryID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_categories",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($CategoryID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => self::getDate()
            ),
            "options" => null
        ));
    }
    // shop cetegories <<<<<












    // shop origins <<<<<
    static function jsapiShopGetOriginItem ($id = null) {
        $config = self::jsapiGetDataSourceConfig(array(
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
                "shop_origins.ID" => self::jsapiCreateDataSourceCondition($id)
            );
        }

        return $config;
    }

    static function jsapiShopGetOriginList (array $options = array()) {
        $config = self::jsapiShopGetOriginItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['removed'])) {
            $config['condition']['Status'] = self::jsapiCreateDataSourceCondition('ACTIVE');
        }
        return $config;
    }

    static function jsapiShopCreateOrigin ($data) {
        $data["DateUpdated"] = self::getDate();
        $data["DateCreated"] = self::getDate();
        $data["ExternalKey"] = libraryUtils::url_slug($data['Name']);
        $data["Description"] = empty($data["Description"]) ? "" : $data["Description"];
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_origins",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopUpdateOrigin ($OriginID, $data) {
        $data["DateUpdated"] = self::getDate();
        if (isset($data['Name']))
            $data["ExternalKey"] = libraryUtils::url_slug($data['Name']);
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_origins",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($OriginID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopDeleteOrigin ($OriginID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_origins",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($OriginID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => self::getDate()
            ),
            "options" => null
        ));
    }
    // shop origins <<<<<




















    // shop delivery agencies >>>>>
    static function jsapiShopGetDeliveryAgencyByID ($id = null) {
        $config = self::jsapiGetDataSourceConfig(array(
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
            $config["condition"]["ID"] = self::jsapiCreateDataSourceCondition($id);

        return $config;
    }

    static function jsapiShopGetDeliveriesList (array $options = array()) {
        $config = self::jsapiShopGetDeliveryAgencyByID();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        return $config;
    }

    static function jsapiShopCreateDeliveryAgent ($data) {
        $data["DateUpdated"] = self::getDate();
        $data["DateCreated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_deliveryAgencies",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopUpdateDeliveryAgent ($id, $data) {
        $data["DateUpdated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_deliveryAgencies",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopDeleteDeliveryAgent ($id) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_deliveryAgencies",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($id)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => self::getDate()
            ),
            "options" => null
        ));
    }
    // <<<<< shop delivery agencies















    // shop delivery agencies >>>>>
    static function jsapiShopGetSettingByID ($id = null) {
        $config = self::jsapiGetDataSourceConfig(array(
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
            $config["condition"]["ID"] = self::jsapiCreateDataSourceCondition($id);

        return $config;
    }

    static function jsapiShopGetSettingByName ($name = null) {
        $config = self::jsapiShopGetSettingByID();
        unset($config['condition']['ID']);
        $config['condition']['Property'] = self::jsapiCreateDataSourceCondition($name);
        return $config;
    }

    static function jsapiShopGetSettingByType ($type = null) {
        $config = self::jsapiShopGetSettingByID();
        unset($config['condition']['ID']);
        $config['limit'] = 0;
        $config['condition']['Type'] = self::jsapiCreateDataSourceCondition($type);
        return $config;
    }

    static function jsapiShopGetSettingsList (array $options = array()) {
        $config = self::jsapiShopGetSettingByID();
        $config['fields'] = array("ID");
        $config['limit'] = -1;
        $config['options']['expandSingleRecord'] = false;
        return $config;
    }

    static function jsapiShopCreateSetting ($data) {
        $data["DateUpdated"] = self::getDate();
        $data["DateCreated"] = self::getDate();
        $data["Status"] = 'ACTIVE';
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_settings",
            "action" => "insert",
            "data" => $data,
            "options" => null,
            "saveOptions" => array(
                "useInsertIgnore" => true
            )
        ));
    }

    static function jsapiShopUpdateSetting ($id, $data) {
        $data["DateUpdated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_settings",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopUpdateSettingByName ($id, $data) {
        $config = self::jsapiShopUpdateSetting($id, $data);
        unset($config['condition']['ID']);
        $config['condition']['Property'] = self::jsapiCreateDataSourceCondition($id);
        return $config;
    }

    static function jsapiShopRemoveSetting ($id) {
        $data["DateUpdated"] = self::getDate();
        $data["Status"] = 'REMOVED';
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_settings",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
    }
    // <<<<< shop delivery agencies










    // Shop order >>>>>
    static function jsapiShopGetOrderItem ($orderID = null) {
        $config = self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_orders",
            "condition" => array(),
            "fields" => array("ID", "AccountID", "AccountAddressesID", "DeliveryID", "Warehouse", "Comment", "InternalComment", "Status", "Hash", "PromoID", "DateCreated", "DateUpdated"),
            "options" => array(
                "expandSingleRecord" => true
            ),
            "limit" => 1
        ));

        if (!is_null($orderID))
            $config["condition"] = array(
                "shop_orders.ID" => self::jsapiCreateDataSourceCondition($orderID)
            );

        return $config;
    }
    static function jsapiGetShopOrderList (array $options = array()) {
        $config = self::jsapiShopGetOrderItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (!empty($options['_pSearch'])) {
            if (is_string($options['_pSearch'])) {
                $config['condition']["Hash"] = self::jsapiCreateDataSourceCondition($options['_pSearch'] . '%', 'like');
                // $config['condition']["Model"] = self::jsapiCreateDataSourceCondition('%' . $options['search'] . '%', 'like');
                // $config['condition']["SKU"] = self::jsapiCreateDataSourceCondition('%' . $options['search'] . '%', 'like');
            } elseif (is_array($options['_pSearch'])) {
                foreach ($options['_pSearch'] as $value) {
                    $config['condition']["Hash"] = self::jsapiCreateDataSourceCondition($value . '%', 'like');
                    // $config['condition']["Model"] = self::jsapiCreateDataSourceCondition('%' . $value . '%', 'like');
                    // $config['condition']["SKU"] = self::jsapiCreateDataSourceCondition('%' . $value . '%', 'like');
                }
            }
        }
        return $config;
    }
    static function jsapiGetShopOrderList_Pending () {
        $config = self::jsapiGetShopOrderList();
        $config['condition']['Status'] = self::jsapiCreateDataSourceCondition('NEW');
        return $config;
    }
    static function jsapiGetShopOrderList_Todays () {
        $config = self::jsapiGetShopOrderList();
        $config['condition']['DateCreated'] = self::jsapiCreateDataSourceCondition(date('Y-m-d'), ">");
        return $config;
    }
    static function jsapiGetShopOrderList_Expired () {
        $config = self::jsapiGetShopOrderList();
        $config['condition']['Status'] = self::jsapiCreateDataSourceCondition(array("SHOP_CLOSED", "SHOP_REFUNDED", "CUSTOMER_CANCELED"), "NOT IN");
        $config['condition']['DateCreated'] = self::jsapiCreateDataSourceCondition(date('Y-m-d', strtotime("-1 week")), "<");
        return $config;
    }
    static function jsapiShopCreateOrder ($data) {
        $data["DateUpdated"] = self::getDate();
        $data["DateCreated"] = self::getDate();
        $data["Hash"] = md5(time() . md5(time()));
        // adjust values
        if (is_string($data["DeliveryID"])) {
            $data["DeliveryID"] = null;
        }
        if (is_string($data["Warehouse"])) {
            $data["Warehouse"] = null;
        }
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_orders",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }
    static function jsapiShopCreateOrderBought ($data) {
        $data["DateCreated"] = self::getDate();
        $data["IsPromo"] = empty($data["IsPromo"]) ? 0 : 1;
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_boughts",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }
    static function jsapiShopGetOrderBoughts ($orderID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_boughts",
            "condition" => array(
                "OrderID" => self::jsapiCreateDataSourceCondition($orderID)
            ),
            "fields" => array("ID", "ProductID", "Price", "SellingPrice", "Quantity", "IsPromo", "DateCreated"),
            "offset" => 0,
            "limit" => 0
        ));
    }
    static function jsapiGetShopOrderByHash ($orderHash) {
        $config = self::jsapiShopGetOrderItem();
        $config['condition'] = array(
            "Hash" => self::jsapiCreateDataSourceCondition($orderHash)
        );
        $config['options'] = array(
            "expandSingleRecord" => true
        );
        $config['limit'] = 1;
        return $config;
    }
    static function jsapiShopUpdateOrder ($orderID, $data) {
        $data["DateUpdated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "action" => "update",
            "source" => "shop_orders",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($orderID)
            ),
            "data" => $data,
            "options" => null
        ));
    }
    static function jsapiShopDisableOrder ($OrderID) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "shop_orders",
            "action" => "update",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($OrderID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => self::getDate()
            ),
            "options" => null
        ));
    }
    // <<<< Shop order













    // >>>> Shop statistics
    static function jsapiShopStat_PopularProducts () {
        return self::jsapiGetDataSourceConfig(array(
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

    static function jsapiShopStat_NonPopularProducts () {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_products",
            "fields" => array("ID"),
            "condition" => array(
                "Status" => self::jsapiCreateDataSourceCondition("ACTIVE"),
                "ID" => self::jsapiCreateDataSourceCondition("SELECT ProductID AS ID FROM shop_boughts", "NOT IN")
            ),
            "order" => array(
                "field" => "DateCreated",
                "ordering" => "ASC"
            ),
            "limit" => 15,
            "options" => null
        ));
    }

    static function jsapiShopStat_ProductsOverview ($filter = null) {
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
                $config['condition']['CategoryID'] = self::jsapiCreateDataSourceCondition($filter['_fCategoryID']);
        }
        return $config;
    }

    static function jsapiShopStat_OrdersOverview () {
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

    static function jsapiShopStat_OrdersIntensityLastMonth ($status, $comparator = null) {
        if (!is_string($comparator))
            $comparator = self::$DEFAULT_COMPARATOR;
        $config = self::jsapiShopGetOrderItem();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "@Date(DateUpdated) AS CloseDate");
        $config['condition'] = array(
            'Status' => self::jsapiCreateDataSourceCondition($status, $comparator),
            'DateUpdated' => self::jsapiCreateDataSourceCondition(date('Y-m-d', strtotime("-1 month")), "<=")
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
        return $config;
    }

    static function jsapiShopStat_ProductsIntensityLastMonth ($status) {
        $config = self::jsapiShopGetProductItem();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "@Date(DateUpdated) AS CloseDate");
        $config['condition'] = array(
            'Status' => self::jsapiCreateDataSourceCondition($status),
            'DateUpdated' => self::jsapiCreateDataSourceCondition(date('Y-m-d', strtotime("-1 month")), "<=")
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
    static function jsapiShopGetPromoByHash ($hash, $activeOnly) {
        $config = self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_promo",
            "condition" => array(
                "Code" => self::jsapiCreateDataSourceCondition($hash)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));

        if ($activeOnly) {
            $config['condition']['DateStart'] = self::jsapiCreateDataSourceCondition(self::getDate(), '<=');
            $config['condition']['DateExpire'] = self::jsapiCreateDataSourceCondition(self::getDate(), '>=');
        }

        return $config;
    }

    static function jsapiShopGetPromoByID ($promoID = null) {
        $config = self::jsapiGetDataSourceConfig(array(
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
                "ID" => self::jsapiCreateDataSourceCondition($promoID)
            );
        return $config;
    }

    static function jsapiShopGetPromoList (array $options = array()) {
        $config = self::jsapiShopGetPromoByID();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['expired'])) {
            $config['condition']['DateExpire'] = self::jsapiCreateDataSourceCondition(self::getDate(), '>=');
        }
        // if (empty($options['future'])) {
            // $config['condition']['DateStart'] = self::jsapiCreateDataSourceCondition(self::getDate(), '<=');
        // }
        return $config;
    }

    static function jsapiShopCreatePromo ($data) {
        $data["DateCreated"] = self::getDate();
        return self::jsapiGetDataSourceConfig(array(
            "action" => "insert",
            "source" => "shop_promo",
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopUpdatePromo ($promoID, $data) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "update",
            "source" => "shop_promo",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($promoID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    static function jsapiShopExpirePromo ($promoID) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "update",
            "source" => "shop_promo",
            "condition" => array(
                "ID" => self::jsapiCreateDataSourceCondition($promoID)
            ),
            "data" => array(
                "DateExpire" => self::getDate()
            ),
            "options" => null
        ));
    }
    // Promo area >>>>>








}

?>
<?php

namespace web\plugin\shop\config;

class data extends \engine\objects\configuration {

    var $Table_ShopOrders = "shop_orders";
    var $Table_ShopProducts = "shop_products";
    var $Table_ShopOrigins = "shop_origins";
    var $Table_ShopCategories = "shop_categories";
    var $Table_ShopProductAttr = "shop_productAttributes";
    var $Table_ShopDeliveryAgencies = "shop_deliveryAgencies";
    var $Table_ShopFeatures = "shop_features";
    var $Table_ShopSettings = "shop_settings";

    // products >>>>>
    public function jsapiShopGetProductItem ($ProductID = null/*, $fullInfo = true*/) {
        $config = $this->jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_products",
            "fields" => array("ID", "CategoryID", "OriginID", "ExternalKey", "Name", "Description", "Model", "SKU", "Price", "IsPromo", "Status", "DateUpdated", "DateCreated"),
            "offset" => 0,
            "limit" => 1,
            // "additional" => array(
            //     "shop_categories" => array(
            //         "constraint" => array("shop_categories.ID", "=", "shop_products.CategoryID"),
            //         "fields" => array(
            //             "CategoryName" => "Name",
            //             "CategoryDescription" => "Description",
            //             "CategoryStatus" => "Status"
            //         )
            //     ),
            //     "shop_origins" => array(
            //         "constraint" => array("shop_origins.ID", "=", "shop_products.OriginID"),
            //         "fields" => array(
            //             "OriginName" => "Name",
            //             "OriginDescription" => "Description",
            //             "OriginStatus" => "Status"
            //         )
            //     )
            // ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));

        if (!is_null($ProductID))
            $config["condition"] = array(
                "shop_products.ID" => $this->jsapiCreateDataSourceCondition($ProductID)
            );

        // if (!$fullInfo) {
        //     unset($config['additional']);
        // }

        return $config;
    }

    public function jsapiShopGetProductList (array $options = array()) {
        $config = $this->jsapiShopGetProductItem();
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
                $config['condition']["shop_products.Name"] = $this->jsapiCreateDataSourceCondition('%' . $options['_pSearch'] . '%', 'like');
                // $config['condition']["Model"] = $this->jsapiCreateDataSourceCondition('%' . $options['search'] . '%', 'like');
                // $config['condition']["SKU"] = $this->jsapiCreateDataSourceCondition('%' . $options['search'] . '%', 'like');
            } elseif (is_array($options['_pSearch'])) {
                foreach ($options['_pSearch'] as $value) {
                    $config['condition']["shop_products.ID"] = $this->jsapiCreateDataSourceCondition($value);
                    // $config['condition']["shop_products.Name"] = $this->jsapiCreateDataSourceCondition('%' . $value . '%', 'like');
                    // $config['condition']["shop_products.Model"] = $this->jsapiCreateDataSourceCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["shop_products.Description"] = $this->jsapiCreateDataSourceCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["shop_products.SKU"] = $this->jsapiCreateDataSourceCondition('%' . $value . '%', 'like', 'OR');
                    // $config['condition']["Model"] = $this->jsapiCreateDataSourceCondition('%' . $value . '%', 'like');
                    // $config['condition']["SKU"] = $this->jsapiCreateDataSourceCondition('%' . $value . '%', 'like');
                }
            }
        }

        return $config;
    }

    public function jsapiShopCreateProduct ($data) {
        $data["DateUpdated"] = $this->getDate();
        $data["DateCreated"] = $this->getDate();
        $ExternalKey = array();
        if (isset($data['Name']))
            $ExternalKey[] = $data['Name'];
        if (isset($data['Model']))
            $ExternalKey[] = $data['Model'];
        if (isset($data['SKU']))
            $ExternalKey[] = $data['SKU'];
        if (!empty($ExternalKey))
            $data["ExternalKey"] = \engine\lib\utils::url_slug(implode('_', $ExternalKey));
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_products",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public function jsapiShopUpdateProduct ($ProductID, $data) {
        $data["DateUpdated"] = $this->getDate();
        $ExternalKey = array();
        if (isset($data['Name']))
            $ExternalKey[] = $data['Name'];
        if (isset($data['Model']))
            $ExternalKey[] = $data['Model'];
        if (isset($data['SKU']))
            $ExternalKey[] = $data['SKU'];
        if (!empty($ExternalKey))
            $data["ExternalKey"] = \engine\lib\utils::url_slug(implode('_', $ExternalKey));
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_products",
            "action" => "update",
            "condition" => array(
                "ID" => $this->jsapiCreateDataSourceCondition($ProductID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public function jsapiShopDeleteProduct ($ProductID) {
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_products",
            "action" => "update",
            "condition" => array(
                "ID" => $this->jsapiCreateDataSourceCondition($ProductID)
            ),
            "data" => array(
                "Status" => 'ARCHIVED',
                "DateUpdated" => $this->getDate()
            ),
            "options" => null
        ));
    }
    // products >>>>>


    // Product category (catalog)
    public function jsapiGetShopCategoryProductList ($ids) {
        $config = $this->jsapiShopGetProductList();
        $config['condition']["shop_products.CategoryID"] = $this->jsapiCreateDataSourceCondition($ids, "IN");
        return $config;
    }

    public function jsapiGetShopCategoryProductInfo ($ids) {
        $config = $this->jsapiGetShopCategoryProductList($ids);
        $config["fields"] = array("ID", "CategoryID", "OriginID");
        $config['limit'] = 0;
        return $config;
    }









    // Product price stats >>>>>
    public function jsapiShopGetProductPriceStats ($id) {
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_productPrices",
            "condition" => array(
                "ProductID" => $this->jsapiCreateDataSourceCondition($id)
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
    public function jsapiShopGetProductRelations ($id) {
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_relations",
            "condition" => array(
                "ProductA_ID" => $this->jsapiCreateDataSourceCondition($id)
            ),
            "fields" => array("ProductB_ID"),
            "offset" => 0,
            "limit" => 0
        ));
    }
    // <<<< Product relations













    // product features & attributes >>>>>
    public function jsapiShopGetProductFeatures ($id) {
        return $this->jsapiGetDataSourceConfig(array(
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
                "ProductID" => $this->jsapiCreateDataSourceCondition($id)
            ),
            "limit" => 0,
            "options" => array()
        ));
    }

    public function jsapiShopGetProductAttributes ($id = null, $type = null) {
        $config = $this->jsapiGetDataSourceConfig(array(
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
            $config['condition']['ProductID'] = $this->jsapiCreateDataSourceCondition($id);
        }
        if (!empty($type)) {
            $config['condition']['Attribute'] = $this->jsapiCreateDataSourceCondition($type);
        }

        return $config;
    }

    public function jsapiShopCreateFeature ($data) {
        $data["DateUpdated"] = $this->getDate();
        $data["DateCreated"] = $this->getDate();
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_features",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public function jsapiShopGetFeatures () {
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_features",
            "fields" => array("ID", "FieldName", "GroupName"),
            "limit" => 0,
            "options" => array()
        ));
    }

    public function jsapiShopAddFeatureToProduct ($data) {
        $data["DateUpdated"] = $this->getDate();
        $data["DateCreated"] = $this->getDate();
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_productFeatures",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public function jsapiShopAddAttributeToProduct ($data) {
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_productAttributes",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public function jsapiShopClearProductFeatures ($ProductID) {
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_productFeatures",
            "action" => "delete",
            "condition" => array(
                "ProductID" => $this->jsapiCreateDataSourceCondition($ProductID)
            ),
            "options" => null
        ));
    }

    public function jsapiShopClearProductAttributes ($ProductID, $attributeType = false) {
        $config = $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_productAttributes",
            "action" => "delete",
            "condition" => array(
                "ProductID" => $this->jsapiCreateDataSourceCondition($ProductID)
            ),
            "options" => null
        ));
        if (!empty($attributeType)) {
            $config['condition']['Attribute'] = $this->jsapiCreateDataSourceCondition(strtoupper($attributeType));
        }
        return $config;
    }
    // <<<< product features & attributes








    // Product category (catalog) >>>>>
    public function jsapiShopCategoryAndSubCategoriesAllBrandsGet ($categoryID) {
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getAllShopCategoryBrandsWithSubCategories",
                "parameters" => array($categoryID)
            )
        ));
    }

    public function jsapiShopCategoryAllSubCategoriesGet ($categoryID) {
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getAllShopCategorySubCategories",
                "parameters" => array($categoryID)
            )
        ));
    }

    public function jsapiShopCategoryPriceEdgesGet ($categoryID) {
        return $this->jsapiGetDataSourceConfig(array(
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
    public function jsapiShopCategoryLocationGet ($id) {
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getShopCategoryLocation",
                "parameters" => array($id)
            )
        ));
    }
    // <<<< Additional: category location







    // Shop catalog tree >>>>>
    public function jsapiShopCatalogTree () {
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_categories",
            "condition" => array(
                "Status" => $this->jsapiCreateDataSourceCondition("ACTIVE")
            ),
            "fields" => array("ID", "ParentID", "ExternalKey", "Name", "Status"),
        ));
    }
    // <<<< Shop catalog tree
















    // shop cetegories >>>>>
    public function jsapiShopGetCategoryItem ($id = null) {
        $config = $this->jsapiGetDataSourceConfig(array(
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
                "shop_categories.ID" => $this->jsapiCreateDataSourceCondition($id)
            );
        }

        return $config;
    }

    public function jsapiShopGetCategoryList (array $options = array()) {
        $config = $this->jsapiShopGetCategoryItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['removed'])) {
            $config['condition']['Status'] = $this->jsapiCreateDataSourceCondition('ACTIVE');
        }
        return $config;
    }

    public function jsapiShopCreateCategory ($data) {
        $data["DateUpdated"] = $this->getDate();
        $data["DateCreated"] = $this->getDate();
        $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name']);
        $data["Description"] = empty($data["Description"]) ? "" : $data["Description"];
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_categories",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public function jsapiShopUpdateCategory ($CategoryID, $data) {
        $data["DateUpdated"] = $this->getDate();
        if (isset($data['Name']))
            $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name']);
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_categories",
            "action" => "update",
            "condition" => array(
                "ID" => $this->jsapiCreateDataSourceCondition($CategoryID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public function jsapiShopDeleteCategory ($CategoryID) {
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_categories",
            "action" => "update",
            "condition" => array(
                "ID" => $this->jsapiCreateDataSourceCondition($CategoryID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $this->getDate()
            ),
            "options" => null
        ));
    }
    // shop cetegories <<<<<












    // shop origins <<<<<
    public function jsapiShopGetOriginItem ($id = null) {
        $config = $this->jsapiGetDataSourceConfig(array(
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
                "shop_origins.ID" => $this->jsapiCreateDataSourceCondition($id)
            );
        }

        return $config;
    }

    public function jsapiShopGetOriginList (array $options = array()) {
        $config = $this->jsapiShopGetOriginItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['removed'])) {
            $config['condition']['Status'] = $this->jsapiCreateDataSourceCondition('ACTIVE');
        }
        return $config;
    }

    public function jsapiShopCreateOrigin ($data) {
        $data["DateUpdated"] = $this->getDate();
        $data["DateCreated"] = $this->getDate();
        $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name']);
        $data["Description"] = empty($data["Description"]) ? "" : $data["Description"];
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_origins",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public function jsapiShopUpdateOrigin ($OriginID, $data) {
        $data["DateUpdated"] = $this->getDate();
        if (isset($data['Name']))
            $data["ExternalKey"] = \engine\lib\utils::url_slug($data['Name']);
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_origins",
            "action" => "update",
            "condition" => array(
                "ID" => $this->jsapiCreateDataSourceCondition($OriginID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public function jsapiShopDeleteOrigin ($OriginID) {
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_origins",
            "action" => "update",
            "condition" => array(
                "ID" => $this->jsapiCreateDataSourceCondition($OriginID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $this->getDate()
            ),
            "options" => null
        ));
    }
    // shop origins <<<<<




















    // shop delivery agencies >>>>>
    public function jsapiShopGetDeliveryAgencyByID ($id = null) {
        $config = $this->jsapiGetDataSourceConfig(array(
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
            $config["condition"]["ID"] = $this->jsapiCreateDataSourceCondition($id);

        return $config;
    }

    public function jsapiShopGetDeliveriesList (array $options = array()) {
        $config = $this->jsapiShopGetDeliveryAgencyByID();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        return $config;
    }

    public function jsapiShopCreateDeliveryAgent ($data) {
        $data["DateUpdated"] = $this->getDate();
        $data["DateCreated"] = $this->getDate();
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_deliveryAgencies",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }

    public function jsapiShopUpdateDeliveryAgent ($id, $data) {
        $data["DateUpdated"] = $this->getDate();
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_deliveryAgencies",
            "action" => "update",
            "condition" => array(
                "ID" => $this->jsapiCreateDataSourceCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public function jsapiShopDeleteDeliveryAgent ($id) {
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_deliveryAgencies",
            "action" => "update",
            "condition" => array(
                "ID" => $this->jsapiCreateDataSourceCondition($id)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $this->getDate()
            ),
            "options" => null
        ));
    }
    // <<<<< shop delivery agencies















    // shop delivery agencies >>>>>
    public function jsapiShopGetSettingByID ($id = null) {
        $config = $this->jsapiGetDataSourceConfig(array(
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
            $config["condition"]["ID"] = $this->jsapiCreateDataSourceCondition($id);

        return $config;
    }

    public function jsapiShopGetSettingByName ($name = null) {
        $config = $this->jsapiShopGetSettingByID();
        unset($config['condition']['ID']);
        $config['condition']['Property'] = $this->jsapiCreateDataSourceCondition($name);
        return $config;
    }

    public function jsapiShopGetSettingByType ($type = null) {
        $config = $this->jsapiShopGetSettingByID();
        unset($config['condition']['ID']);
        $config['limit'] = 0;
        $config['condition']['Type'] = $this->jsapiCreateDataSourceCondition($type);
        return $config;
    }

    public function jsapiShopGetSettingsList (array $options = array()) {
        $config = $this->jsapiShopGetSettingByID();
        $config['fields'] = array("ID");
        $config['limit'] = -1;
        $config['options']['expandSingleRecord'] = false;
        return $config;
    }

    public function jsapiShopCreateSetting ($data) {
        $data["DateUpdated"] = $this->getDate();
        $data["DateCreated"] = $this->getDate();
        $data["Status"] = 'ACTIVE';
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_settings",
            "action" => "insert",
            "data" => $data,
            "options" => null,
            "saveOptions" => array(
                "useInsertIgnore" => true
            )
        ));
    }

    public function jsapiShopUpdateSetting ($id, $data) {
        $data["DateUpdated"] = $this->getDate();
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_settings",
            "action" => "update",
            "condition" => array(
                "ID" => $this->jsapiCreateDataSourceCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public function jsapiShopUpdateSettingByName ($id, $data) {
        $config = $this->jsapiShopUpdateSetting($id, $data);
        unset($config['condition']['ID']);
        $config['condition']['Property'] = $this->jsapiCreateDataSourceCondition($id);
        return $config;
    }

    public function jsapiShopRemoveSetting ($id) {
        $data["DateUpdated"] = $this->getDate();
        $data["Status"] = 'REMOVED';
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_settings",
            "action" => "update",
            "condition" => array(
                "ID" => $this->jsapiCreateDataSourceCondition($id)
            ),
            "data" => $data,
            "options" => null
        ));
    }
    // <<<<< shop delivery agencies










    // Shop order >>>>>
    public function jsapiShopGetOrderItem ($orderID = null) {
        $config = $this->jsapiGetDataSourceConfig(array(
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
                "shop_orders.ID" => $this->jsapiCreateDataSourceCondition($orderID)
            );

        return $config;
    }
    public function jsapiGetShopOrderList (array $options = array()) {
        $config = $this->jsapiShopGetOrderItem();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (!empty($options['_pSearch'])) {
            if (is_string($options['_pSearch'])) {
                $config['condition']["Hash"] = $this->jsapiCreateDataSourceCondition($options['_pSearch'] . '%', 'like');
                // $config['condition']["Model"] = $this->jsapiCreateDataSourceCondition('%' . $options['search'] . '%', 'like');
                // $config['condition']["SKU"] = $this->jsapiCreateDataSourceCondition('%' . $options['search'] . '%', 'like');
            } elseif (is_array($options['_pSearch'])) {
                foreach ($options['_pSearch'] as $value) {
                    $config['condition']["Hash"] = $this->jsapiCreateDataSourceCondition($value . '%', 'like');
                    // $config['condition']["Model"] = $this->jsapiCreateDataSourceCondition('%' . $value . '%', 'like');
                    // $config['condition']["SKU"] = $this->jsapiCreateDataSourceCondition('%' . $value . '%', 'like');
                }
            }
        }
        return $config;
    }
    public function jsapiGetShopOrderList_Pending () {
        $config = $this->jsapiGetShopOrderList();
        $config['condition']['Status'] = $this->jsapiCreateDataSourceCondition('NEW');
        return $config;
    }
    public function jsapiGetShopOrderList_Todays () {
        $config = $this->jsapiGetShopOrderList();
        $config['condition']['DateCreated'] = $this->jsapiCreateDataSourceCondition(date('Y-m-d'), ">");
        return $config;
    }
    public function jsapiGetShopOrderList_Expired () {
        $config = $this->jsapiGetShopOrderList();
        $config['condition']['Status'] = $this->jsapiCreateDataSourceCondition(array("SHOP_CLOSED", "SHOP_REFUNDED", "CUSTOMER_CANCELED"), "NOT IN");
        $config['condition']['DateCreated'] = $this->jsapiCreateDataSourceCondition(date('Y-m-d', strtotime("-1 week")), "<");
        return $config;
    }
    public function jsapiShopCreateOrder ($data) {
        $data["DateUpdated"] = $this->getDate();
        $data["DateCreated"] = $this->getDate();
        $data["Hash"] = substr(md5(time() . md5(time())), 0, 5);
        // adjust values
        if (is_string($data["DeliveryID"])) {
            $data["DeliveryID"] = null;
        }
        if (is_string($data["Warehouse"])) {
            $data["Warehouse"] = null;
        }
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_orders",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }
    public function jsapiShopCreateOrderBought ($data) {
        $data["DateCreated"] = $this->getDate();
        $data["IsPromo"] = empty($data["IsPromo"]) ? 0 : 1;
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_boughts",
            "action" => "insert",
            "data" => $data,
            "options" => null
        ));
    }
    public function jsapiShopGetOrderBoughts ($orderID) {
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_boughts",
            "condition" => array(
                "OrderID" => $this->jsapiCreateDataSourceCondition($orderID)
            ),
            "fields" => array("ID", "ProductID", "Price", "SellingPrice", "Quantity", "IsPromo", "DateCreated"),
            "offset" => 0,
            "limit" => 0
        ));
    }
    public function jsapiGetShopOrderByHash ($orderHash) {
        $config = $this->jsapiShopGetOrderItem();
        $config['condition'] = array(
            "Hash" => $this->jsapiCreateDataSourceCondition($orderHash)
        );
        $config['options'] = array(
            "expandSingleRecord" => true
        );
        $config['limit'] = 1;
        return $config;
    }
    public function jsapiShopUpdateOrder ($orderID, $data) {
        $data["DateUpdated"] = $this->getDate();
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "update",
            "source" => "shop_orders",
            "condition" => array(
                "ID" => $this->jsapiCreateDataSourceCondition($orderID)
            ),
            "data" => $data,
            "options" => null
        ));
    }
    public function jsapiShopDisableOrder ($OrderID) {
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "shop_orders",
            "action" => "update",
            "condition" => array(
                "ID" => $this->jsapiCreateDataSourceCondition($OrderID)
            ),
            "data" => array(
                "Status" => 'REMOVED',
                "DateUpdated" => $this->getDate()
            ),
            "options" => null
        ));
    }
    // <<<< Shop order













    // >>>> Shop statistics
    public function jsapiShopStat_PopularProducts () {
        return $this->jsapiGetDataSourceConfig(array(
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

    public function jsapiShopStat_NonPopularProducts () {
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_products",
            "fields" => array("ID"),
            "condition" => array(
                "Status" => $this->jsapiCreateDataSourceCondition("ACTIVE"),
                "ID" => $this->jsapiCreateDataSourceCondition("SELECT ProductID AS ID FROM shop_boughts", "NOT IN")
            ),
            "order" => array(
                "field" => "DateCreated",
                "ordering" => "ASC"
            ),
            "limit" => 15,
            "options" => null
        ));
    }

    public function jsapiShopStat_ProductsOverview ($filter = null) {
        $config = $this->jsapiShopGetProductItem();
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
                $config['condition']['CategoryID'] = $this->jsapiCreateDataSourceCondition($filter['_fCategoryID']);
        }
        return $config;
    }

    public function jsapiShopStat_OrdersOverview () {
        $config = $this->jsapiShopGetOrderItem();
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

    public function jsapiShopStat_OrdersIntensityLastMonth ($status, $comparator = null) {
        if (!is_string($comparator))
            $comparator = $this->DEFAULT_COMPARATOR;
        $config = $this->jsapiShopGetOrderItem();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "@Date(DateUpdated) AS CloseDate");
        $config['condition'] = array(
            'Status' => $this->jsapiCreateDataSourceCondition($status, $comparator),
            'DateUpdated' => $this->jsapiCreateDataSourceCondition(date('Y-m-d', strtotime("-1 month")), "<=")
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

    public function jsapiShopStat_ProductsIntensityLastMonth ($status) {
        $config = $this->jsapiShopGetProductItem();
        $config['fields'] = array("@COUNT(*) AS ItemsCount", "@Date(DateUpdated) AS CloseDate");
        $config['condition'] = array(
            'Status' => $this->jsapiCreateDataSourceCondition($status),
            'DateUpdated' => $this->jsapiCreateDataSourceCondition(date('Y-m-d', strtotime("-1 month")), "<=")
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
    public function jsapiShopGetPromoByHash ($hash, $activeOnly) {
        $config = $this->jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "shop_promo",
            "condition" => array(
                "Code" => $this->jsapiCreateDataSourceCondition($hash)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));

        if ($activeOnly) {
            $config['condition']['DateStart'] = $this->jsapiCreateDataSourceCondition($this->getDate(), '<=');
            $config['condition']['DateExpire'] = $this->jsapiCreateDataSourceCondition($this->getDate(), '>=');
        }

        return $config;
    }

    public function jsapiShopGetPromoByID ($promoID = null) {
        $config = $this->jsapiGetDataSourceConfig(array(
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
                "ID" => $this->jsapiCreateDataSourceCondition($promoID)
            );
        return $config;
    }

    public function jsapiShopGetPromoList (array $options = array()) {
        $config = $this->jsapiShopGetPromoByID();
        $config['fields'] = array("ID");
        $config['limit'] = 64;
        $config['options']['expandSingleRecord'] = false;
        if (empty($options['expired'])) {
            $config['condition']['DateExpire'] = $this->jsapiCreateDataSourceCondition($this->getDate(), '>=');
        }
        // if (empty($options['future'])) {
            // $config['condition']['DateStart'] = $this->jsapiCreateDataSourceCondition($this->getDate(), '<=');
        // }
        return $config;
    }

    public function jsapiShopCreatePromo ($data) {
        $data["DateCreated"] = $this->getDate();
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "insert",
            "source" => "shop_promo",
            "data" => $data,
            "options" => null
        ));
    }

    public function jsapiShopUpdatePromo ($promoID, $data) {
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "update",
            "source" => "shop_promo",
            "condition" => array(
                "ID" => $this->jsapiCreateDataSourceCondition($promoID)
            ),
            "data" => $data,
            "options" => null
        ));
    }

    public function jsapiShopExpirePromo ($promoID) {
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "update",
            "source" => "shop_promo",
            "condition" => array(
                "ID" => $this->jsapiCreateDataSourceCondition($promoID)
            ),
            "data" => array(
                "DateExpire" => $this->getDate()
            ),
            "options" => null
        ));
    }
    // Promo area >>>>>








}

?>
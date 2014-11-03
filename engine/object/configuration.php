<?php
namespace engine\object;
use Utils as Utils;

class configuration implements \engine\interface\IConfiguration {

    public $DATE_FORMAT = 'Y-m-d H:i:s';
    public $DEFAULT_COMPARATOR = '=';
    public $DEFAULT_CONCATENATE = '+';

    public function getDate ($strDate = '') {
        if (!empty($strDate)) {
            $time = strtotime($strDate);
            return date($this->DATE_FORMAT, $time);
        }
        return date($this->DATE_FORMAT);
    }

    public function jsapiCreateDataSourceCondition ($value, $comparator = null, $concatenate = null) {
        $condition = array(
            "comparator" => $comparator,
            "value" => $value,
            "concatenate" => $concatenate
        );
        if (!is_string($condition['comparator']))
            $condition['comparator'] = $this->DEFAULT_COMPARATOR;
        if (!is_string($condition['concatenate']))
            $condition['concatenate'] = $this->DEFAULT_CONCATENATE;
        return $condition;
    }

    public function jsapiGetDataSourceConfig($configExtend = null) {
        $configDefault = array(
            "source" => "",
            "action" => "select",
            "procedure" => array(
                "name" => "",
                "parameters" => array()
            ),
            "condition" => array(), // use fieldName => jsapiCreateDataSourceCondition()
            "data" => array(),
            "useFieldPrefix" => true,
            "fields" => array(/*"ID", "CategoryID", "OriginID", "Name", "Model", "SKU", "Description", "DateCreated"*/),
            "offset" => 0,
            "limit" => 0,
            // "output" => "DEFAULT", // see function "to" for available values
            "group" => "", // "ProductID"
            "transformToArray" => array(), // keys which values will be served as array
            "additional" => array(
                // example of join configuration
                // "shop_categories" => array(
                //     "constraint" => array("shop_categories.ID", "=", "shop_products.CategoryID"),
                //     "fields" => array(
                //         "CategoryName" => "Name",
                //         "CategoryEnable" => "Enabled"
                //     )
                // ),
                // "shop_origins" => array(
                //     "constraint" => array("shop_origins.ID", "=", "shop_products.OriginID"],
                //     "fields" => array(
                //         "CategoryName" => "Name",
                //         "CategoryEnable" => "Enabled"
                //     )
                // )
            ),
            "order" => array(
                // "field" => "shop_productPrices.DateCreated",
                // "ordering" => "DESC"
            ),
            "options" => array(
                // This goes by default.
                // You can baypass any filed names to force make value as array of each
                "transformToArray" => array(),
                // required fields:
                // "combineDataByKeys" => array(
                //    "mapKeysToCombine" => array(
                //        'ProductAttributes' => array(
                //            'keys' => 'Attributes',
                //            'values' => 'Values',
                //            'keepOriginal' => true|false (optional) if true then Attributes and Values fields will be removed from this example
                //        )
                //    ),
                // optional:
                //    "doOptimization" => true,
                //    "keysToForceTransformToArray" = array("FieldName")
                // )
                "expandSingleRecord" => false
            )
        );

        return $this->extendConfigs($configDefault, $configExtend, true);
    }

    public function extendConfigs ($configA, $configB = null) {
        return Utils::array_merge_recursive_distinct($configA, $configB);
    }

    public function jsapiUtil_GetTableRecordsCount ($table, $condition = array()) {
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => $table,
            "condition" => $condition,
            "fields" => array("@COUNT(*) AS ItemsCount"),
            "offset" => 0,
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

    public function jsapiUtil_GetTableStatusFieldOptions ($table) {
        return $this->jsapiUtil_GetFieldOptions($table, 'Status');
    }

    public function jsapiUtil_GetFieldOptions ($table, $field) {
        return $this->jsapiGetDataSourceConfig(array(
            "action" => "call",
            "procedure" => array(
                "name" => "getFieldOptions",
                "parameters" => array($table, $field)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

}

?>
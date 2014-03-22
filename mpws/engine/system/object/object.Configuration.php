<?php

class objectConfiguration implements iConfiguration {

    static function jsapiGetDataSourceConfig($configExtend = null) {
        $configDefault = array(
            "source" => "",
            "action" => "select",
            "procedure" => array(
                "name" => "",
                "parameters" => array()
            ),
            "condition" => array(
                "filter" => "", //"shop_products.Status = ? AND shop_products.Enabled = ?",
                "values" => array(/*"ACTIVE", 1*/)
            ),
            "data" => array(
                "fields" => array(/*"DateLastAccess", "IsOnline"*/),
                "values" => array()
            ),
            "useFieldPrefix" => true,
            "fields" => array(/*"ID", "CategoryID", "OriginID", "Name", "Model", "SKU", "Description", "DateCreated"*/),
            "offset" => 0,
            "limit" => 10,
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

        return self::extendConfigs($configDefault, $configExtend, true);
    }

    static function extendConfigs ($configA, $configB = null, $useRecursiveMerge = false) {

        return libraryUtils::array_merge_recursive_distinct($configA, $configB);

//         if (!is_array($configB))
//             return $configA;
// // 
//         $target = array_merge(array(), $configA);

//         // var_dump($configB);
//         foreach ($configB as $key => $value) {

//             if (!isset($target[$key])) {
//                 $target[$key] = $value;
//                 continue;
//             }

//             $classConfigValue = $target[$key];

//             if (is_array($classConfigValue)) {
//                 if ($useRecursiveMerge)
//                     $target[$key] = array_replace_recursive($classConfigValue, is_array($value) ? $value : array($value));
//                 else
//                     $target[$key] = array_merge($classConfigValue, is_array($value) ? $value : array($value));
//             }
//             else {
//                 // echo 'setting value ' . $value . ' by the key ' . $key;
//                 $target[$key] = $value;
//             }
//         }

//         // if (!empty($this->_config['condition']) && $this->_config['condition']['values'])

//         // var_dump($configB);
//         // echo "extendConfig", $this->_config;
//         return $target;
    }

}

?>
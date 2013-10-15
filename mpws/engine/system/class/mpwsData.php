<?php

class mpwsData {
    private $_data;
    private $_config;

    function __construct($data = null, $config = null, $isError = false) {

        $this->_data = array();
        $this->_config = $this->getConfigDefault();

        if (!empty($data))
            $this->setData($data);

        if (!empty($config))
            return $this->setConfig($config);

        if (!empty($isError))
            $this->setDataError($data);
    }
    
    // data
    public function setDataError($errorMessage) {
        $this->_data = array('error' => $errorMessage);
        return $this;
    }
    public function setData($val) {
        if ($val instanceof mpwsData)
            $this->_data = $val->getData();
        else
            $this->_data = $val;
        return $this;
    }

    // configuration
    public function extendConfig($config, $useRecursiveMerge = false) {
        if (!is_array($config))
            return $this;

        foreach ($config as $key => $value) {
            
            if (!isset($this->_config[$key])) {
                $this->_config[$key] = $value;
                continue;
            }

            $classConigValue = $this->_config[$key];

            if (is_array($classConigValue)) {
                if ($useRecursiveMerge)
                    $this->_config[$key] = array_merge_recursive($classConigValue, is_array($value) ? $value : array($value));
                else
                    $this->_config[$key] = array_merge($classConigValue, is_array($value) ? $value : array($value));
            }
            else
                $this->_config[$key] = $value;
        }

        // if (!empty($this->_config['condition']) && $this->_config['condition']['values'])

        // echo "extendConfig", $this->_config;
        return $this;
    }
    public function setConfig($config) {
        $this->_config = $config;
        return $this;
    }

    // getters
    public function getData() { return $this->_data; }
    public function getConfig() { return $this->_config; }
    public function getConfigDefault() {
        // see commented examples how to configure this object before fetch data
        return array(
            "source" => "",
            "condition" => array(
                "filter" => "", //"shop_products.Status = ? AND shop_products.Enabled = ?",
                "values" => array(/*"ACTIVE", 1*/)
            ),
            "fields" => array(/*"ID", "CategoryID", "OriginID", "Name", "Model", "SKU", "Description", "DateCreated"*/),
            "offset" => "0",
            "limit" => "10",
            "output" => "DEFAULT", // see function "to" for available values
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
                "transformToArray" => array()
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
            )
        );
    }

    // database fetch data
    public function fetchData($params = false) {
        $this->extendConfig($params);
        $ctx = contextMPWS::instance();
        // var_dump($this->getConfig());
        // echo 'TROLOLO';
        $_db_dataObj = $ctx->contextCustomer->getDBO()->mpwsFetchData($this->getConfig());
        return $this->setData($_db_dataObj);
    }

    // converters
    public function toJSON() { return libraryUtils::getJSON($this->getData()); }
    public function toDEFAULT() { return $this->getData(); }
    public function toHASH() { return $this->_inner; }
    public function to($outputType) {
        switch ($outputType) {
            case fmtJSON:
                return $this->toJSON();
                return ;
            case fmtDEFAULT:
                return $this->toDEFAULT();
            default:
                throw new Exception("Error Processing Request: mpwsData: at to(" . $outputType . "). Wrong format selected", 1);
        }
    }
}

?>


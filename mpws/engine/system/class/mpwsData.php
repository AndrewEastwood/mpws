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
    public function extendConfig($config) {
        if (!is_array($config))
            return $this;

        $this->_config = array_merge($this->_config, $config);

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
            )
        );
    }

    // database fetch data
    public function fetchData($params) {
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


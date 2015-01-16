<?php

namespace engine\lib;

class database {

    var $config;
    var $dbo;
    var $transactionIsActive = false;
    var $disableTransactions = false;
    public $DATE_FORMAT = 'Y-m-d H:i:s';
    public $DEFAULT_COMPARATOR = '=';
    public $DEFAULT_CONCATENATE = 'AND';

    public function __construct($config = false) {
        $this->config = $config;
        orm::configure($this->config);
        $this->dbo = orm::mpwsInstance();
    }

    public function getDBO () {
        return $this->dbo;
    }

    public function getDBLink () {
        return orm::get_db();
    }

    public function quote ($str) {
        if (!is_string($str))
            return $str;
        $link = orm::get_db();
        return $link->quote($str);
    }

    public function get_last_query () {
        return orm::get_last_query();
    }

    public function getLastErrorCode () {
        return $this->getDBLink()->errorCode();
    }

    public function getLastErrorInfo ($index = null) {
        $arr = $this->getDBLink()->errorInfo();
        if (is_null($index))
            return $arr;
        return $arr[$index];
    }

    public function beginTransaction () {
        if (!$this->isTransactionsAllowed())
            return false;
        if ($this->transactionIsActive)
            return false;
        // echo 3;
        try {
            $this->getDBLink()->beginTransaction();
        } catch (Exception $e) {
            var_dump($e);
        }
        $this->transactionIsActive = true;
        // echo 4;
    }

    public function commit () {
        if (!$this->isTransactionsAllowed())
            return false;
        $this->getDBLink()->commit();
        $this->transactionIsActive = false;
    }

    public function rollback () {
        if (!$this->isTransactionsAllowed())
            return false;
        $this->getDBLink()->rollBack();
        $this->transactionIsActive = false;
    }

    public function disableTransactions () {
        $this->disableTransactions = true;
    }

    public function enableTransactions () {
        $this->disableTransactions = false;
    }

    public function isTransactionsAllowed () {
        return !$this->disableTransactions;
    }

    public function getDataJSON($config) {
        if (is_string($config))
            return json_encode(array("message" => $config));
        return json_encode($this->_fetchData($config));
    }

    public function getLastInsertId () {
        return $this->dbo->mpwsGetLastInsertId();
    }

    public function getData ($config, $skipCustomerID = false) {
        return $this->_fetchData($config);
    }

    private function _getDefaultObject () {
        return array("error" => null, "data" => null);
    }

    private function _fetchData ($config) {
        // $_result = $this->_getDefaultObject();

        // $config = $this->extendConfig($params)->getConfig();

        
        // var_dump($this->getConfig());
        // $_db_dataObj = $ctx->contextCustomer->getDBO()->mpwsFetchData($this->getConfig());

        $this->dbo->mpwsReset();

        $action = $config['action'];
        $source = $config['source'];
        $saveOptions = isset($config['saveOptions']) ? $config['saveOptions'] : array();
        $fieldsToSelectFromDB = $config['fields'] ?: array();

        // prepend ID column
        // if (!in_array("ID", $fieldsToSelectFromDB))
        //     array_unshift($fieldsToSelectFromDB, 'ID');

        if ($config['useFieldPrefix']) {
            $fieldsToSelectFromDBClear = array();
            // just to avoid mysql error: XXXX in field list is ambiguous
            foreach ($fieldsToSelectFromDB as $key => $value) {
                // var_dump($value);
                if ($value[0] === '@')
                    $this->dbo->select_expr(substr($value, 1));
                elseif (!strstr($value, '.'))
                    $fieldsToSelectFromDBClear[$key] = sprintf("%s.%s", $source, $value);
            }
        } else
            $fieldsToSelectFromDBClear = $fieldsToSelectFromDB;

        $this->dbo->mpwsTable($source);

        if (!empty($fieldsToSelectFromDBClear))
            $this->dbo->select_many($fieldsToSelectFromDBClear);

        // var_dump($fieldsToSelectFromDBClear);

        if (!empty($config['additional']))
            foreach ($config['additional'] as $addSource => $addConfig) {
                if (empty($addConfig['fields']))
                    continue;
                $this->dbo->join($addSource, $addConfig['constraint']);

                if (!empty($addConfig['fields'])) {
                    $fieldsToSelect = $addConfig['fields'];
                    $fieldsToSelectClear = array();

                    foreach ($fieldsToSelect as $key => $value) {
                        if ($value[0] === '@')
                            $this->dbo->select_expr(substr($value, 1));
                            // $fieldsToSelect[$key] = substr($value, 1);
                        elseif (!strstr($value, '.'))
                            $fieldsToSelectClear[$key] = sprintf("%s.%s", $addSource, $value);
                        else
                            $fieldsToSelectClear[$key] = $value;
                    }

                    $this->dbo->select_many($fieldsToSelectClear);
                }
                // var_dump($fieldsToSelectClear);
            }

        $_fieldOptionsWorkerFn = function ($context, $fieldName, $fieldOptions) {
            switch (strtolower($fieldOptions['comparator'])) {
                case '>':
                    $context->dbo->where_gt($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case '>=':
                    $context->dbo->where_gte($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case '<':
                    $context->dbo->where_lt($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case '<=':
                    $context->dbo->where_lte($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case 'is null':
                    $context->dbo->where_null($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case 'is not null':
                    $context->dbo->where_not_null($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case '=':
                    $context->dbo->where_equal($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case '!=':
                    $context->dbo->where_not_equal($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case 'like':
                    $context->dbo->where_like($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case 'not like':
                    $context->dbo->where_not_like($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case 'in':
                    // var_dump('using WHERE_IN', $fieldOptions['value']);
                    $context->dbo->where_in($fieldName, is_array($fieldOptions['value']) ? $fieldOptions['value'] : array($fieldOptions['value']));
                    break;
                case 'not in':
                    $context->dbo->where_not_in($fieldName, is_array($fieldOptions['value']) ? $fieldOptions['value'] : array($fieldOptions['value']));
                    break;
                default:
                    var_dump('Unknown condition statement occured');
                    break;
            }
        };

        // condition
        // var_dump($fieldsToSelectFromDBClear);
        if (!empty($config['condition'])) {
            // var_dump($config['condition']);
            // translate condition filter string
            foreach ($config['condition'] as $fieldName => $fieldOptions) {
                if (is_array($fieldOptions) && !isset($fieldOptions['comparator'])) {
                    // var_dump($fieldOptions);
                    foreach ($fieldOptions as $fieldOption)
                        $_fieldOptionsWorkerFn($this, $fieldName, $fieldOption);
                } else {
                    $_fieldOptionsWorkerFn($this, $fieldName, $fieldOptions);
                }
            }
        }

        if (!empty($config['group']))
            $this->dbo->group_by($config['group']);

        if (!empty($config['offset']) && $config['offset'] >= 0)
            $this->dbo->offset($config['offset']);

        if (!empty($config['limit']) && $config['limit'] >= 0)
            $this->dbo->limit($config['limit']);

        if (!empty($config['order'])) {
            if (!empty($config['order']['expr'])) {
                $this->dbo->order_by_expr($config['order']['expr']);
            } elseif (!empty($config['order']['field'])) {
                if (!empty($config['order']['ordering']) && $config['order']['ordering'] === 'DESC')
                    $this->dbo->order_by_desc($config['order']['field']);
                else
                    $this->dbo->order_by_asc($config['order']['field']);
            }
        }

        // echo '>>>>>>>>>>>>>>>>>>>>>>>.dbo:';
        // var_dump($this->dbo);
        // echo '<<<<<<<<<<<<<<<<<<<<<<';
        $dbData = null;

        switch ($action) {
            case 'call':
                $proc = $config['procedure'];
                if (!empty($proc))
                    $dbData = $this->dbo->mpwsProcedureCall($proc['name'], $proc['parameters']);
                break;
            case 'update':
                // var_dump(array_combine($config['data']['fields'], $config['data']['values']));
                $this->dbo->update($config['data']);
                // echo 'libraryDataObject update DB';
                $this->dbo->save($saveOptions);
                break;
            case 'delete':
                $this->dbo->delete_many();
                break;
            case 'insert':
                $this->dbo->create($config['data']);
                $this->dbo->save($saveOptions);
                break;
            case 'select':
            default:
                // fetch data
                $dbData = $this->dbo->find_array();
                break;
        }

        // var_dump($dbData);

        $_opt_expandSingleRecord = false;

        // apply data transformation options
        if (!empty($config['options']))
            foreach ($config['options'] as $key => $_options)
                switch ($key) {
                    case 'expandSingleRecord':
                        if (is_bool($_options))
                            $_opt_expandSingleRecord = $_options;
                        break;
                    case "asDict":
                        $dict = array();
                        $keyForKey = null;
                        $keyForVal = null;
                        if (is_string($_options))
                            $keyForKey = $_options;
                        elseif (is_array($_options)) {
                            $keyForKey = $_options['keys'] ?: null;
                            $keyForVal = $_options['values'] ?: null;
                        }
                        if (!empty($keyForKey))
                            foreach ($dbData as $key => $val) {
                                if ($keyForVal)
                                    $dict[$val[$keyForKey]] = $val[$keyForVal] ?: null;
                                else
                                    $dict[$val[$keyForKey]] = $val;
                            }
                        $dbData = $dict;
                        break;
                    default:
                        # code...
                        break;
                }

        if ($action === 'insert')
            return $this->getLastInsertId();

        //var_dump($dbData);
        // var_dump($config['options']);
        // echo "do expand single record ? " . ($_opt_expandSingleRecord ? 'true' : 'false');
        // echo print_r($config['options'], true) . PHP_EOL;
        // echo 'count($dbData)'. count($dbData) . PHP_EOL;
        // create libraryDataObject object
        $data = null;
        if (count($dbData) === 1) {
            // echo print_r($dbData, true) . PHP_EOL;
            //echo '_opt_expandSingleRecord: ' . ($_opt_expandSingleRecord ? 'Y': 'N') . PHP_EOL;
            if ($_opt_expandSingleRecord && isset($dbData[0]))
                $data = $dbData[0];
            else
                $data = $dbData;
        }
        if (count($dbData) > 1)
            $data = $dbData;

        return $data;
    }

    public function getDate ($strDate = '') {
        if (!empty($strDate)) {
            $time = strtotime($strDate);
            return date($this->DATE_FORMAT, $time);
        }
        return date($this->DATE_FORMAT);
    }

    public function createCondition ($value, $comparator = null, $concatenate = null) {
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

    public function createDBQuery ($queryExtend = null) {
        $queryDefault = array(
            "source" => "",
            "action" => "select",
            "procedure" => array(
                "name" => "",
                "parameters" => array()
            ),
            "condition" => array(), // use fieldName => createCondition()
            "data" => array(),
            "useFieldPrefix" => true,
            "fields" => array(/*"ID", "CategoryID", "OriginID", "Name", "Model", "SKU", "Description", "DateCreated"*/),
            "offset" => 0,
            "limit" => 0,
            "group" => "", // "ProductID"
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
                "expandSingleRecord" => false
            )
        );

        return Utils::array_merge_recursive_distinct ($queryDefault, $queryExtend);
        // return $this->extendConfigs($queryDefault, $queryExtend, true);
    }

    // public function extendConfigs ($configA, $configB = null) {
    //     return Utils::array_merge_recursive_distinct ($configA, $configB);
    // }

    public function jsapiUtil_GetTableRecordsCount ($table, $condition = array()) {
        return $this->createDBQuery(array(
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

    public function getDataList ($dsConfig, array $options = array(), array $callbacks = array()) {
        $limit = $dsConfig['limit'];
        $page = 1;
        $items = array();

        if ($dsConfig['action'] !== "select")
            throw new Exception("ErrorProcessingDataListMethod", 1);

        // grab other fields
        foreach ($options as $key => $value) {
            $matches = array();
            if (preg_match("/^_f(\w+)$/", $key, $matches)) {
                // $matches
                $field = $matches[1];
                // parse value
                $parsedValue = array();
                preg_match("/([0-9A-Za-z%\,_-]+)\:(.*)$/", $value, $parsedValue);
                // var_dump($field);
                // var_dump($value);
                $count = count($parsedValue);
                // var_dump($parsedValue);
                // var_dump($count);
                if ($count === 0)
                    $dsConfig['condition'][$field] = $app->getDB()->createCondition($value);
                elseif ($count === 3) {
                    $value = $parsedValue[1];
                    $comparator = $parsedValue[2];
                    if (strtolower($comparator) === 'in')
                        $value = explode(',', $parsedValue[1]);
                    $dsConfig['condition'][$field] = $app->getDB()->createCondition($value, $comparator);
                }
            }
        }

        // var_dump($dsConfig['condition']);
        // get data total records
        $configCount = $app->getSettings()->data->jsapiUtil_GetTableRecordsCount($dsConfig['source'], $dsConfig['condition']);
        
        $countData = $this->fetch($configCount);
        $count = intval($countData["ItemsCount"]);

        if (!empty($options)) {
            if (isset($options['sort']))
                $dsConfig['order']['field'] = $options['sort'];
            if (isset($options['order']))
                $dsConfig['order']['ordering'] = $options['order'];

            if (isset($options['page']))
                $page = intval($options['page']);
            if (isset($options['limit']))
                $limit = intval($options['limit']);

            if ($count > 0) {
                if ($limit >= 1) {
                    $dsConfig['limit'] = $limit;
                }
                if ($limit === 0) {
                    unset($dsConfig['limit']);
                }
                if ($page >= 1 && $limit >= 1) {
                    if ($page > round($count / $limit + 0.49)) {
                        $page = round($count / $limit + 0.49);
                    }
                    $dsConfig['offset'] = ($page - 1) * $limit;
                } elseif ($page === 0)
                    $page = 1;
            }
        }

        // var_dump($dsConfig);
        // get data
        $items = $this->fetch($dsConfig) ?: array();
        // var_dump($items);

        if (isset($callbacks['parse']) && is_callable($callbacks['parse'])) {
            $parseFn = $callbacks['parse'];
            $items = $parseFn($items) ?: array();
        }

        $rez = array();

        $listInfo = array(
            "page" => $page,
            "limit" => $limit,
            "total_pages" => empty($limit) ? 1 : round($count / $limit + 0.49),
            "total_entries" => $count
        );

        if (isset($dsConfig['order']['field'])) {
            $listInfo["order_by"] = $dsConfig['order']['field'];
        }

        if (isset($dsConfig['order']['ordering'])) {
            $listInfo["order"] = $dsConfig['order']['ordering'];
        }

        $rez["info"] = $listInfo;
        $rez["items"] = $items ?: array();

        return $rez;
    }

}

?>
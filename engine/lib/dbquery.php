<?php

namespace engine\lib;

class dbquery {
    // $queryDefault = array(
    //     "source" => "",
    //     "action" => "select",
    //     "procedure" => array(
    //         "name" => "",
    //         "parameters" => array()
    //     ),
    //     "condition" => array(), // use fieldName => createCondition()
    //     "data" => array(),
    //     "useFieldPrefix" => true,
    //     "fields" => array(/*"ID", "CategoryID", "OriginID", "Name", "Model", "SKU", "Description", "DateCreated"*/),
    //     "offset" => 0,
    //     "limit" => 0,
    //     "group" => "", // "ProductID"
    //     "additional" => array(
    //         // example of join configuration
    //         // "shop_categories" => array(
    //         //     "constraint" => array("shop_categories.ID", "=", "shop_products.CategoryID"),
    //         //     "fields" => array(
    //         //         "CategoryName" => "Name",
    //         //         "CategoryEnable" => "Enabled"
    //         //     )
    //         // ),
    //         // "shop_origins" => array(
    //         //     "constraint" => array("shop_origins.ID", "=", "shop_products.OriginID"],
    //         //     "fields" => array(
    //         //         "CategoryName" => "Name",
    //         //         "CategoryEnable" => "Enabled"
    //         //     )
    //         // )
    //     ),
    //     "order" => array(
    //         // "field" => "shop_productPrices.DateCreated",
    //         // "ordering" => "DESC"
    //     ),
    //     "options" => array(
    //         "expandSingleRecord" => false
    //     )
    // );

    static $queryNameToInstanceMap = array();

    var $name;
    var $source = null;
    var $action = null;
    var $conditions = array();
    var $options = array();
    var $order = array();
    var $fields = array();
    var $data = array();
    var $limit = 100;
    var $offset = 0;
    var $group = null;
    var $having = null;
    var $procedureName = null;
    var $procedureParams = array();
    var $useCustomerID = true;

    function __construct ($queryName, $props = null) {
        if (self::exists($queryName)) {
            throw new Exception("Query '$queryName' already exists", 1);
        }
        $this->name = $queryName;
        // #A >> usually this block runs when the cloneQuery is being invoked
        if (is_array($props)) {
            foreach ($props as $key => $value) {
                if (isset($this->$key)) {
                    $this->$key = $value;
                }
            }
        } // #A <<
        self::$queryNameToInstanceMap[$queryName] = self;
    }

    function __clone () {
        throw new Exception("Use cloneQuery function instead", 1);
    }

    private function packAllProps () {
        $props = get_object_vars($this);
        $props['conditions'] = clone $this->conditions;
        $props['options'] = clone $this->options;
        $props['order'] = clone $this->order;
        $props['fields'] = clone $this->fields;
        $props['data'] = clone $this->data;
        $props['procedureParams'] = clone $this->procedureParams;
        unlink($props['name']);
        return $props;
    }

    public static function exists ($name) {
        return isset(self::$queryNameToInstanceMap[$name]);
    }

    public function cloneQuery ($newQueryName) {
        if (self::exists($newQueryName)) {
            throw new Exception("Query '$newQueryName' already exists. Use ::getQueryByName function", 1);
        }
        if (empty($newQueryName)) {
            throw new Exception("You must provide query name", 1);
        }
        $props = $this->packAllProps();
        $newQueryInstance = new dbquery($newQueryName, $props);
        return $newQueryInstance;
    }

    public static function get ($queryName) {
        return self::$queryNameToInstanceMap[$queryName];
    }

    public function setSource ($src) {
        $this->source = $src;
        return $this;
    }
    public function setAction ($action) {
        $this->action = $action;
        return $this;
    }
    public function addCondition ($field, $value, $comparator = null, $concatenate = null) {
        $this->conditions[$this->setFieldSource($filed)] = $this->createCondition($value, $comparator, $concatenate);
        return $this;
    }
    public function clearAllConditions () {
        $this->conditions = array();
        return $this;
    }
    public function setData ($data) {
        $this->data = $data;
        return $this;
    }
    public function appendData ($data) {
        $this->data += $data;
        return $this;
    }
    public function clearData () {
        $this->data = array();
        return $this;
    }
    // public function useFieldPrefix () {
        
    // }
    public function useCustomerID () {
        $this->useCustomerID = true;
        return $this;
    }
    public function skipCustomerID () {
        $this->useCustomerID = false;
        return $this;
    }
    public function setFields (/* args */) {
        $fileds = func_get_args();
        if (isset($fileds) && count($fileds) == 0 && is_array($fileds[0])) {
            $fileds = func_get_arg(0);
        }
        if (empty($fileds)) {
            throw new Exception("setFields got empty array", 1);
        }
        $this->fields = array();
        foreach ($fields as $fld) {
            $this->fields[] = $this->setFieldSource($fld);
        }
        return $this;
    }
    public function limit ($limit = 100/*, $offset = 0*/) {
        $this->limit = intval($limit);
        // $this->offset = intval($offset);
        return $this;
    }
    public function offset (/*$limit = 100, */$offset = 0) {
        // $this->limit = intval($limit);
        $this->offset = intval($offset);
        return $this;
    }
    public function groupBy ($group) {
        $this->group = $group;
        return $this;
    }
    public function addHaving ($having) {
        $this->having = $having;
        return $this;
    }
    public function addJoin ($src, $constraint, array $fileds) {
        $constraint = explode('=', $constraint);
        array_splice($constraint, 1, 0, array('='));
        $this->join[$src] = array(
            'constraint' = $constraint,
            'fields' = $fields
        );
        return $this;
    }
    public function ordering ($field, $desc = false) {
        if ($field[0] == '-') {
            $desc = true;
            $field = substr($filed, 1);
        }
        if (is_bool($desc)) {
            $desc = $desc ? 'DESC' : 'ASC';
        }
        if (is_string($desc)) {
            $desc = strtoupper($desc);
        }
        $this->order = array(
            'field' => $field,
            'ordering' => $desc
        );
        return $this;
    }
    public function orderingExpr ($expr) {
        $this->order = array(
            "expr" => $expr
        );
        return $this;
    }

    public function willBeSingleRow () {
        $this->limit(1);
        $this->options['expandSingleRecord'] = true;
        $this->options['asList'] = false;
        return $this;
    }

    public function willBeList ($limit = null) {
        $this->options['expandSingleRecord'] = false;
        $this->options['asList'] = true;
        if (!is_null($limit)) {
            $this->limit($limit);
        }
        return $this;
    }

    public function queryCount () {
        $this->options['addCount'] = true;
        return $this;
    }

    public function setPage ($pageNo = 0) {
        $this->offset($this->limit * intval($pageNo));
        return $this;
    }

    public function addParams (array $params = array()) {
        // $keys = array('limit', 'page', 'sort', 'order', '_f([a-zA-Z\._]+)');
        // $options = array();
        // foreach ($params as $queryKey => $queryParam) {
        //     foreach ($keys as $keyToGrab) {
        //         $matches = array();
        //         if (preg_match("/^" . $keyToGrab . "$/", $queryKey, $matches)) {
        //             $options[$queryKey] = $queryParam;
        //         }
        //     }
        // }
        $sort = null;
        $order = null;
        foreach ($params as $key => $value) {
            $matches = array();
            if ($key == 'limit') {
                $this->limit($value);
                continue;
            }
            if ($key == 'page') {
                $this->stePage($value);
                continue;
            }
            if ($key == 'sort') {
                $sort = $value;
                continue;
            }
            if ($key == 'order') {
                $order = $value;
                continue;
            }
            if (preg_match("/^_f([a-zA-Z\._]+)$/", $key, $matches)) {
                // $matches
                // echo $key;
                $field = $matches[1];
                // parse value
                $isArray = strpos($value, ',') > 0;
                // $hasComparator = strpos($value, ':') > 0;
                $parsedValue = array();
                preg_match("/([0-9A-Za-z%\,_-]+)\:(.*)$/", $value, $parsedValue);
                // var_dump($field);
                // var_dump($value);
                $count = count($parsedValue);
                // var_dump($parsedValue);
                // var_dump($count);
                if ($count === 0)
                    $dsConfig['condition'][$field] = $this->createCondition($value);
                elseif ($count > 2) {
                    $value = $parsedValue[1];
                    $comparator = null;
                    if (isset($parsedValue[2]))
                        $comparator = $parsedValue[2];
                    // if (strtolower($comparator) === 'in')
                    if ($isArray)
                        $value = explode(',', $parsedValue[1]);
                    $dsConfig['condition'][$field] = $this->createCondition($value, $comparator);
                }
            }
        }

        if ($sort != null && $order != null) {
            $this->ordering($sort, $order);
        }

        // return $options;
        return $this;
    }

    public function willSelect ($source) {
        $this->action = 'select';
        if (!empty($source)) { $this->setSource($source); }
        return $this;
    }
    public function willUpdate ($source, array $data) {
        $this->action = 'update';
        if (!empty($source)) { $this->setSource($source); }
        if (!empty($data)) {
            $this->setData($data);
        }
        return $this;
    }
    public function willInsert ($source, array $data) {
        $this->action = 'insert';
        if (!empty($source)) { $this->setSource($source); }
        if (!empty($data)) {
            $this->setData($data);
        }
        return $this;
    }
    public function willDelete ($source) {
        $this->action = 'delete';
        if (!empty($source)) { $this->setSource($source); }
        return $this;
    }
    public function willCall ($source, $name, $params = array()) {
        $this->action = 'call';
        $this->procedureName = $name;
        $this->procedureParams = $params;
        if (!empty($source)) { $this->setSource($source); }
        return $this;
    }

    private function createCondition ($value, $comparator = null, $concatenate = null) {
        $condition = array(
            "comparator" => $comparator,
            "value" => $value,
            "concatenate" => $concatenate
        );
        if (!is_string($condition['comparator']))
            $condition['comparator'] = $this->DEFAULT_COMPARATOR;
        if (!is_string($condition['concatenate']))
            $condition['concatenate'] = $this->DEFAULT_CONCATENATE;
        if (is_array($value) && empty($comparator)) {
            $condition['comparator'] = 'IN';
        }
        if (is_string($value) && empty($comparator) && $value[0] == '%' && $value[strlen($value) - 1] == '%') {
            $condition['comparator'] = 'like';
        }
        return $condition;
    }

    private function setFieldSource ($fld, $src = null) {
        if ($src == null && $this->getSource() == null) {
            return $fld;
        }
        if ($fld[0] == '@') { // for expressions
            return $fld;
        }
        $fldParts = explode('.', $fld);
        $fldHasSrc = count($fldParts) == 2;
        $fldSrc = $fldHasSrc ? $fldParts[0] : null;
        $fldName = $fldHasSrc ? $fldParts[1] : $fldParts[0];
        if (!empty($src)) {
            $fldSrc = $src;
        } else {
            $fldSrc = $this->getSource();
        }
        return implode('.', array($fldSrc, $fldName));
    }

    public function query (dbquery $config, $useCustomerID = true) {
        global $app;

        $db = $app->getDB()
        // $customerInfo = $this->getCustomerInfo();
        // var_dump($config);
        if ($useCustomerID) {
            $runtimeCustomerID = $app->getSite()->getRuntimeCustomerID();
            if ($runtimeCustomerID >= 0) {
                $source = $config["source"];
                $key = $source . '.CustomerID';
                // $addCustomerID = false;
                if (isset($config["condition"]["CustomerID"])) {
                    $config["condition"][$key] = $app->getDB()->createCondition($runtimeCustomerID);
                    unset($config["condition"]["CustomerID"]);
                } else if (!isset($config["condition"][$key])) {
                    $config["condition"][$key] = $app->getDB()->createCondition($runtimeCustomerID);
                }
            }
        }
        // $_result = $this->_getDefaultObject();

        // $config = $this->extendConfig($params)->getConfig();

        
        // var_dump($this->getConfig());
        // $_db_dataObj = $ctx->contextCustomer->getDBO()->mpwsFetchData($this->getConfig());

        $db->mpwsReset();

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
                    $db->select_expr(substr($value, 1));
                elseif (!strstr($value, '.'))
                    $fieldsToSelectFromDBClear[$key] = sprintf("%s.%s", $source, $value);
            }
        } else
            $fieldsToSelectFromDBClear = $fieldsToSelectFromDB;

        $db->mpwsTable($source);

        if (!empty($fieldsToSelectFromDBClear))
            $db->select_many($fieldsToSelectFromDBClear);

        // var_dump($fieldsToSelectFromDBClear);

        if (!empty($config['additional']))
            foreach ($config['additional'] as $addSource => $addConfig) {
                if (empty($addConfig['fields']))
                    continue;

                $db->join($addSource, $addConfig['constraint']);

                if (!empty($addConfig['fields'])) {
                    $fieldsToSelect = $addConfig['fields'];
                    $fieldsToSelectClear = array();

                    foreach ($fieldsToSelect as $key => $value) {
                        if ($value[0] === '@')
                            $db->select_expr(substr($value, 1));
                            // $fieldsToSelect[$key] = substr($value, 1);
                        elseif (!strstr($value, '.'))
                            $fieldsToSelectClear[$key] = sprintf("%s.%s", $addSource, $value);
                        else
                            $fieldsToSelectClear[$key] = $value;
                    }

                    $db->select_many($fieldsToSelectClear);
                }
                // var_dump($fieldsToSelectClear);
            }

        $_fieldOptionsWorkerFn = function ($db, $fieldName, $fieldOptions, $type = 'where') {
            // var_dump($type . '_like');
            // var_export($db->where_like);
            switch (strtolower($fieldOptions['comparator'])) {
                case '>':
                    $db->{$type . '_gt'}($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case '>=':
                    $db->{$type . '_gte'}($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case '<':
                    $db->{$type . '_lt'}($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case '<=':
                    $db->{$type . '_lte'}($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case 'is null':
                    $db->{$type . '_null'}($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case 'is not null':
                    $db->{$type . '_not_null'}($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case '=':
                    $db->{$type . '_equal'}($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case '!=':
                    $db->{$type . '_not_equal'}($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case 'like':
                    $db->{$type . '_like'}($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case 'not like':
                    $db->{$type . '_not_like'}($fieldName, $fieldOptions['value'], $fieldOptions['concatenate']);
                    break;
                case 'in':
                    // var_dump('using WHERE_IN', $fieldOptions['value']);
                    $db->{$type . '_in'}($fieldName, is_array($fieldOptions['value']) ? $fieldOptions['value'] : array($fieldOptions['value']));
                    break;
                case 'not in':
                    $db->{$type . '_not_in'}($fieldName, is_array($fieldOptions['value']) ? $fieldOptions['value'] : array($fieldOptions['value']));
                    break;
                default:
                    var_dump('Unknown condition statement occured');
                    break;
            }
        };

        // condition
        // var_dump($fieldsToSelectFromDBClear);
        if (!empty($config['condition'])) {
            if (is_string($config['condition'])) {
                $db->where_raw($config['condition']);
            } else {
                // var_dump($config['condition']);
                // translate condition filter string
                foreach ($config['condition'] as $fieldName => $fieldOptions) {
                    if (is_array($fieldOptions) && !isset($fieldOptions['comparator'])) {
                        // var_dump($fieldOptions);
                        foreach ($fieldOptions as $fieldOption)
                            $_fieldOptionsWorkerFn($db, $fieldName, $fieldOption);
                    } else {
                        $_fieldOptionsWorkerFn($db, $fieldName, $fieldOptions);
                    }
                }
            }
        }

        if (!empty($config['having'])) {
            // translate having filter string
            foreach ($config['having'] as $fieldName => $fieldOptions) {
                if (is_array($fieldOptions) && !isset($fieldOptions['comparator'])) {
                    foreach ($fieldOptions as $fieldOption)
                        $_fieldOptionsWorkerFn($db, $fieldName, $fieldOption, 'having');
                } else {
                    $_fieldOptionsWorkerFn($db, $fieldName, $fieldOptions, 'having');
                }
            }
        }

        if (!empty($config['group']))
            $db->group_by($config['group']);

        if (!empty($config['offset']) && $config['offset'] >= 0)
            $db->offset($config['offset']);

        if (!empty($config['limit']) && $config['limit'] >= 0)
            $db->limit($config['limit']);

        if (!empty($config['order'])) {
            if (!empty($config['order']['expr'])) {
                $db->order_by_expr($config['order']['expr']);
            } elseif (!empty($config['order']['field'])) {
                if (!empty($config['order']['ordering']) && $config['order']['ordering'] === 'DESC')
                    $db->order_by_desc($config['order']['field']);
                else
                    $db->order_by_asc($config['order']['field']);
            }
        }

        // echo '>>>>>>>>>>>>>>>>>>>>>>>.dbo:';
        // var_dump($db);
        // echo '<<<<<<<<<<<<<<<<<<<<<<';
        $dbData = null;

        switch ($action) {
            case 'call':
                $proc = $config['procedure'];
                if (!empty($proc))
                    $dbData = $db->mpwsProcedureCall($proc['name'], $proc['parameters']);
                break;
            case 'update':
                // var_dump(array_combine($config['data']['fields'], $config['data']['values']));
                $db->update($config['data']);
                // echo 'libraryDataObject update DB';
                $db->save($saveOptions);
                break;
            case 'delete':
                $db->delete_many();
                break;
            case 'insert':
                $db->create($config['data']);
                $db->save($saveOptions);
                break;
            case 'select':
            default:
                // fetch data
                $dbData = $db->find_array();
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
            return $db->getLastInsertId();

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

}

?>
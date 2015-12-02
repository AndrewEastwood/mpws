<?php

namespace engine\lib;

use Exception;
use ArrayObject;

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

    var $defaultLimit = 32;

    var $name;
    var $source = null;
    var $action = null;
    var $conditions = array();
    var $options = array();
    var $order = array();
    var $fields = array();
    var $data = array();
    var $limit = 32;
    var $offset = 0;
    var $group = null;
    var $having = null;
    var $procedureName = null;
    var $procedureParams = array();
    var $customerID = null;

    function __construct ($queryName, $props = null) {
        if (!is_string($queryName)) {
            throw new Exception("Query name must be a string", 1);
            return;
        }
        if (dbquery::exists($queryName)) {
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
        dbquery::$queryNameToInstanceMap[$queryName] = $this;
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

    public static function exists ($qName) {
        return isset(dbquery::$queryNameToInstanceMap[$qName]);
    }

    public function cloneQuery ($newQueryName) {
        if (dbquery::exists($newQueryName)) {
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
        return dbquery::$queryNameToInstanceMap[$queryName] ?: null;
    }

    public function setSource ($src) {
        $this->source = $src;
        return $this;
    }
    public function getSource () {
        return $this->source;
    }
    public function setAction ($action) {
        $this->action = $action;
        return $this;
    }
    public function addCondition ($field, $value, $comparator = null, $concatenate = null) {
        $this->conditions[$this->setFieldSource($field)] = $this->createCondition($value, $comparator, $concatenate);
        return $this;
    }
    // public function addRawCondition ($field, $condition) {
    //     $this->conditions[$this->setFieldSource($field)] = $condition;
    //     return $this;
    // }
    public function addConditionFn ($field, $callParams) {
        $this->conditions[$this->setFieldSource($field)] = array('fn' => $callParams);
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
    public function setLimit ($limit = 100/*, $offset = 0*/) {
        $this->limit = intval($limit);
        // $this->offset = intval($offset);
        return $this;
    }
    public function setOffset (/*$limit = 100, */$offset = 0) {
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
    public function addJoin ($src, $constraint, array $fields) {
        $constraint = explode('=', $constraint);
        array_splice($constraint, 1, 0, array('='));
        $this->join[$src] = array(
            'constraint' => $constraint,
            'fields' => $fields
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
            'desc' => $desc
        );
        return $this;
    }
    public function orderingExpr ($expr) {
        $this->order = array(
            "expr" => $expr
        );
        return $this;
    }

    public function querySingleItem () {
        $this->setLimit(1);
        $this->options['expandSingleRecord'] = true;
        $this->options['asDataList'] = false;
        $this->options['asArray'] = false;
        return $this->query();
    }

    public function queryAsArray ($limit = null) {
        $this->options['expandSingleRecord'] = false;
        $this->options['asDataList'] = false;
        $this->options['asArray'] = true;
        if (!is_null($limit)) {
            $this->setLimit($limit);
        }
        return $this->query();
    }
    public function queryAsDataList ($page = null, $limit = null) {
        $this->options['expandSingleRecord'] = false;
        $this->options['asDataList'] = true;
        $this->options['asArray'] = false;
        $this->queryCount();
        if (!is_null($limit)) {
            $this->setLimit($limit);
        } else {
            $this->setLimit($this->defaultLimit);
        }
        if (!is_null($page)) {
            $this->setOffset($this->limit * intval($page));
        } else {
            $this->setOffset(0);
        }
        return $this->query();
    }

    public function queryCount () {
        $this->options['asCount'] = true;
        return $this;
    }

    // public function setPage ($pageNo = 0) {
        // $this->setOffset($this->limit * intval($pageNo));
    //     return $this;
    // }

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
                $this->setLimit($value);
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
    public function willUpdate ($source, array $data = array()) {
        $this->action = 'update';
        if (!empty($source)) { $this->setSource($source); }
        if (!empty($data)) {
            $this->setData($data);
        }
        return $this;
    }
    public function willInsert ($source, array $data = array()) {
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
        $DEFAULT_COMPARATOR = '=';
        $DEFAULT_CONCATENATE = 'AND';

        $condition = array(
            "comparator" => $comparator,
            "value" => $value,
            "concatenate" => $concatenate
        );
        if (!is_string($condition['comparator']))
            $condition['comparator'] = $DEFAULT_COMPARATOR;
        if (!is_string($condition['concatenate']))
            $condition['concatenate'] = $DEFAULT_CONCATENATE;
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

    var $saveOptions = array();
    public function setSaveOption ($k, $v) {
        $this->saveOptions[$k] = $v;
        return $this;
    }
    public function getSaveOption ($k) {
        return $this->saveOptions[$k];
    }
    public function getOptions () {
        return $this->saveOptions;
    }

    public function query () {
        global $app;

        $db = $app->getDB()->getDBO();
        // $customerInfo = $this->getCustomerInfo();
        // var_dump($config);
        // if ($this->useCustomerID) {
        //     $runtimeCustomerID = $app->getSite()->getRuntimeCustomerID();
        //     if ($runtimeCustomerID >= 0) {
        //         $source = $config["source"];
        //         $key = $source . '.CustomerID';
        //         // $addCustomerID = false;
        //         if (isset($config["condition"]["CustomerID"])) {
        //             $config["condition"][$key] = $app->getDB()->createCondition($runtimeCustomerID);
        //             unset($config["condition"]["CustomerID"]);
        //         } else if (!isset($config["condition"][$key])) {
        //             $config["condition"][$key] = $app->getDB()->createCondition($runtimeCustomerID);
        //         }
        //     }
        // }
        // $_result = $this->_getDefaultObject();

        // $config = $this->extendConfig($params)->getConfig();

        
        // var_dump($this->getConfig());
        // $_db_dataObj = $ctx->contextCustomer->getDBO()->mpwsFetchData($this->getConfig());

        $db->mpwsReset();

        // $action = $this->action;
        // $source = $this->source;
        // $saveOptions = isset($config['saveOptions']) ? $config['saveOptions'] : array();
        // $fieldsToSelectFromDB = $this->fields ?: array();

        // prepend ID column
        // if (!in_array("ID", $fieldsToSelectFromDB))
        //     array_unshift($fieldsToSelectFromDB, 'ID');

        // if ($config['useFieldPrefix']) {
        //     $fieldsToSelectFromDBClear = array();
        //     // just to avoid mysql error: XXXX in field list is ambiguous
        //     foreach ($fieldsToSelectFromDB as $key => $value) {
        //         // var_dump($value);
        //         if ($value[0] === '@')
        //             $db->select_expr(substr($value, 1));
        //         elseif (!strstr($value, '.'))
        //             $fieldsToSelectFromDBClear[$key] = sprintf("%s.%s", $source, $value);
        //     }
        // } else
        //     $fieldsToSelectFromDBClear = $fieldsToSelectFromDB;

        // set source
        $db->mpwsTable($this->source);

        // add fields to select
        foreach ($this->fields as $key => $value) {
            if ($value[0] === '@')
                $db->select_expr(substr($value, 1));
            else
                $db->select($value);
        }

        // if (!empty($fieldsToSelectFromDBClear))
        //     $db->select_many($fieldsToSelectFromDBClear);

        // var_dump($fieldsToSelectFromDBClear);

        if (!empty($this->join))
            foreach ($this->join as $joinSource => $joinData) {
                if (empty($joinData['fields']))
                    continue;

                $db->join($joinSource, $joinData['constraint']);

                if (!empty($joinData['fields'])) {
                    $fieldsToSelect = $joinData['fields'];
                    $fieldsToSelectClear = array();

                    foreach ($fieldsToSelect as $key => $value) {
                        if ($value[0] === '@')
                            $db->select_expr(substr($value, 1));
                            // $fieldsToSelect[$key] = substr($value, 1);
                        elseif (!strstr($value, '.'))
                            $fieldsToSelectClear[$key] = sprintf("%s.%s", $joinSource, $value);
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

        foreach ($this->conditions as $fieldName => $condition) {
            if (isset($condition['fn'])) {
                // var_dump($condition);
                $condition = $this->createCondition(call_user_func($condition['fn']));
            }
            $_fieldOptionsWorkerFn($db, $fieldName, $condition);
            // if (is_array($fieldOptions) && !isset($fieldOptions['comparator'])) {
            //     // var_dump($fieldOptions);
            //     foreach ($fieldOptions as $fieldOption)
            //         $_fieldOptionsWorkerFn($db, $fieldName, $fieldOption);
            // } else {
            //     $_fieldOptionsWorkerFn($db, $fieldName, $fieldOptions);
            // }
        }

        // condition
        // var_dump($fieldsToSelectFromDBClear);
        // if (!empty($this->conditions)) {
        //     if (is_string($this->conditions)) {
        //         $db->where_raw($this->conditions);
        //     } else {
        //         // var_dump($this->conditions);
        //         // translate condition filter string
        //         foreach ($this->conditions as $fieldName => $fieldOptions) {



        //             if (is_array($fieldOptions) && !isset($fieldOptions['comparator'])) {
        //                 // var_dump($fieldOptions);


        //                 foreach ($fieldOptions as $fieldOption)
        //                     $_fieldOptionsWorkerFn($db, $fieldName, $fieldOption);
        //             } else {
        //                 $_fieldOptionsWorkerFn($db, $fieldName, $fieldOptions);
        //             }
        //         }
        //     }
        // }

        // if (!empty($this->having)) {
        //     // translate having filter string
        //     foreach ($this->having as $fieldName => $fieldOptions) {
        //         if (is_array($fieldOptions) && !isset($fieldOptions['comparator'])) {
        //             foreach ($fieldOptions as $fieldOption)
        //                 $_fieldOptionsWorkerFn($db, $fieldName, $fieldOption, 'having');
        //         } else {
        //             $_fieldOptionsWorkerFn($db, $fieldName, $fieldOptions, 'having');
        //         }
        //     }
        // }

        if (!empty($this->group))
            $db->group_by($this->group);

        if (!empty($this->offset) && $this->offset >= 0)
            $db->offset($this->offset);

        if (!empty($this->limit) && $this->limit >= 0)
            $db->limit($this->limit);

        if (!empty($this->order)) {
            if (!empty($this->order['expr'])) {
                $db->order_by_expr($this->order['expr']);
            } elseif (!empty($this->order['field'])) {
                if ($this->order['desc'])
                    $db->order_by_desc($this->order['field']);
                else
                    $db->order_by_asc($this->order['field']);
            }
        }

        // echo '>>>>>>>>>>>>>>>>>>>>>>>.dbo:';
        // var_dump($db);
        // echo '<<<<<<<<<<<<<<<<<<<<<<';

        $dbData = null;

        switch ($this->action) {
            case 'call':
                if (!empty($this->procedureName))
                    $dbData = $db->mpwsProcedureCall($this->procedureName, $this->procedureParams);
                break;
            case 'update':
                // var_dump(array_combine($config['data']['fields'], $config['data']['values']));
                $db->update($this->data);
                // echo 'libraryDataObject update DB';
                $db->save($this->saveOptions);
                break;
            case 'delete':
                $db->delete_many();
                break;
            case 'insert':
                $db->create($this->data);
                $db->save($this->saveOptions);
                break;
            case 'select':
            default:
                // fetch data
                $dbData = $db->find_array();
                break;
        }

        // var_dump($dbData);

        $_opt_expandSingleRecord = false;
        $data = null;
        // apply data transformation options
        if (!empty($this->options))
            foreach ($this->options as $key => $_options)
                switch ($key) {
                    case 'expandSingleRecord':
                        if (count($dbData) === 1 && isset($dbData[0])) {
                            $data = $dbData[0];
                        } else {
                            $data = $dbData;
                        }
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
                    case "asDataList":
                        $count = 100;
                        $total_pages = empty($this->limit) ? 1 : round($count / $this->limit + 0.49);
                        $data = array(
                            "items" => $dbData,
                            "page" => round($this->offset / $this->limit),
                            "limit" => $this->limit,
                            "total_pages" => $total_pages,// empty($limit) ? 1 : round($count / $limit + 0.49),
                            "total_entries" => $count,
                            "order_by" => isset($this->order['field']) ? $this->order['field'] : null,
                            "desc" => isset($this->order['desc']) ? $this->order['desc'] : null
                        );
                        // $dict = array();
                        // $keyForKey = null;
                        // $keyForVal = null;
                        // if (is_string($_options))
                        //     $keyForKey = $_options;
                        // elseif (is_array($_options)) {
                        //     $keyForKey = $_options['keys'] ?: null;
                        //     $keyForVal = $_options['values'] ?: null;
                        // }
                        // if (!empty($keyForKey))
                        //     foreach ($dbData as $key => $val) {
                        //         if ($keyForVal)
                        //             $dict[$val[$keyForKey]] = $val[$keyForVal] ?: null;
                        //         else
                        //             $dict[$val[$keyForKey]] = $val;
                        //     }
                        // $dbData = $dict;
                        break;
                    // case "asArray":
                    default:
                        # code...
                        break;
                }

        if ($this->action === 'insert')
            return $db->getLastInsertId();

        //var_dump($dbData);
        // var_dump($config['options']);
        // echo "do expand single record ? " . ($_opt_expandSingleRecord ? 'true' : 'false');
        // echo print_r($config['options'], true) . PHP_EOL;
        // echo 'count($dbData)'. count($dbData) . PHP_EOL;
        // create libraryDataObject object


        return $data;
    }

}

?>
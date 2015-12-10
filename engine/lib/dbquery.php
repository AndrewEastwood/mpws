<?php

namespace engine\lib;

use \engine\lib\data as BaseData;
use Exception;
use ArrayObject;

class dbquery {

    static $queryNameToInstanceMap = array();
    static $DATE_FORMAT = 'Y-m-d H:i:s';

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
    var $filters = array();
    var $result = null;

    function __construct ($queryName, $source, $props = null) {
        if (!is_string($queryName)) {
            throw new Exception("Query name must be a string", 1);
            return;
        }
        if (dbquery::exists($queryName)) {
            throw new Exception("Query '$queryName' already exists", 1);
        }
        $this->name = $queryName;
        $this->source = $source;
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
        $props['saveOptions'] = clone $this->saveOptions;
        unlink($props['result']);
        unlink($props['name']);
        return $props;
    }

    public static function exists ($qName) {
        return isset(dbquery::$queryNameToInstanceMap[$qName]);
    }

    public static function get ($queryName) {
        return dbquery::$queryNameToInstanceMap[$queryName];
    }

    public static function getDate ($strDate = '') {
        if (!empty($strDate)) {
            $time = strtotime($strDate);
            return date(dbquery::$DATE_FORMAT, $time);
        }
        return date(dbquery::$DATE_FORMAT);
    }

    public static function getLike ($value = '') {
        return '%' . $value . '%';
    }

    /**  As of PHP 5.3.0  */
    public static function __callStatic($name, $args)
    {
        return self::get($name);
    }

    public static function setQueryFilter ($filter, $queryNameLookup) {
        foreach (dbquery::$queryNameToInstanceMap as $queryName => &$queryInstance) {
            if (preg_match('/^' . $queryNameLookup . '$/', $queryName)) {
                $queryInstance->setFilter('fetch', $filter);
            }
        }
    }

    public static function setBeforeCallFilter ($filter, $queryNameLookup) {
        foreach (dbquery::$queryNameToInstanceMap as $queryName => &$queryInstance) {
            if (preg_match('/^' . $queryNameLookup . '$/', $queryName)) {
                $queryInstance->setFilter('call', $filter);
            }
        }
    }

    public static function setBeforeSaveFilter ($filter, $queryNameLookup) {
        foreach (dbquery::$queryNameToInstanceMap as $queryName => &$queryInstance) {
            if (preg_match('/^' . $queryNameLookup . '$/', $queryName)) {
                $queryInstance->setFilter('save', $filter);
            }
        }
    }

    public static function setBeforeInsertFilter ($filter, $queryNameLookup) {
        foreach (dbquery::$queryNameToInstanceMap as $queryName => &$queryInstance) {
            if (preg_match('/^' . $queryNameLookup . '$/', $queryName)) {
                $queryInstance->setFilter('insert', $filter);
            }
        }
    }

    public static function setBeforeUpdateFilter ($filter, $queryNameLookup) {
        foreach (dbquery::$queryNameToInstanceMap as $queryName => &$queryInstance) {
            if (preg_match('/^' . $queryNameLookup . '$/', $queryName)) {
                $queryInstance->setFilter('update', $filter);
            }
        }
    }

    public function genFieldStr ($fieldName) {
        if (empty($this->source))
            return $fieldName;
        return $this->source . '.' . $fieldName;
    }
    public function genFieldQueryParamStr ($fieldName) {
        $prefix = '_f';
        if (empty($this->source))
            return $prefix . $fieldName;
        return $prefix . $this->source . '.' . $fieldName;
    }
    public function genDescSortOrderStr ($fieldName) {
        return '-' . $this->asField($fieldName);
    }

    public function cloneQuery ($newQueryName) {
        if (dbquery::exists($newQueryName)) {
            throw new Exception("Query '$newQueryName' already exists. Use ::getQueryByName function", 1);
        }
        if (empty($newQueryName)) {
            throw new Exception("You must provide query name", 1);
        }
        $props = $this->packAllProps();
        $newQueryInstance = new dbquery($newQueryName, $this->source, $props);
        return $newQueryInstance;
    }
    public function getSource () {
        return $this->source;
    }
    public function setAction ($action) {
        $this->action = $action;
        return $this;
    }


    public function setCondition ($field, $value, $comparator = null, $concatenate = null) {
        $this->clearConditions();
        return $this->addCondition($field, $value, $comparator, $concatenate);
    }
    public function setConditionByFlag ($flag, $field, $value, $comparator = null, $concatenate = null) {
        if (!$flag) return $this;
        return $this->setCondition($field, $value, $comparator, $concatenate);
    }
    public function addCondition ($field, $value, $comparator = null, $concatenate = null) {
        $this->conditions[$this->setFieldSource($field)] = $this->createCondition($value, $comparator, $concatenate);
        return $this;
    }
    public function addConditionByFlag ($flag, $field, $value, $comparator = null, $concatenate = null) {
        if (!$flag) return $this;
        return $this->setCondition($field, $value, $comparator, $concatenate);
    }
    public function setConditionFn ($field, $callParams) {
        $this->clearConditions();
        return $this->addConditionFn($field, $callParams);
    }
    public function setConditionFnByFlag ($field, $callParams) {
        if (!$flag) return $this;
        return $this->setConditionFn($field, $callParams);
    }
    public function addConditionFn ($field, $callParams) {
        $this->conditions[$this->setFieldSource($field)] = array('fn' => $callParams);
        return $this;
    }
    public function addConditionFnByFlag ($flag, $field, $callParams) {
        if (!$flag) return $this;
        return $this->addConditionFn($field, $callParams);
    }


    public function clearConditions () {
        $this->conditions = array();
        return $this;
    }
    public function setData (array $data) {
        $this->data = $data;
        return $this;
    }
    public function addData (array $data) {
        $this->data += $data;
        return $this;
    }
    public function addDataItem ($key, $value) {
        $this->data[$key] = $value;
        return $this;
    }
    public function clearData () {
        $this->data = array();
        return $this;
    }
    public function setFields () {
        $this->fields = array();
        return $this->addFields(func_get_args());
    }
    public function setAllFields () {
        $this->fields = array("*");
        return $this;
    }
    public function addFields () {
        $fields = func_get_args();
        if (isset($fields) && count($fields) == 1 && is_array($fields[0])) {
            $fields = func_get_arg(0);
        }
        if (empty($fields)) {
            throw new Exception("setFields got empty array", 1);
        }
        foreach ($fields as $fld) {
            $this->fields[] = $this->setFieldSource($fld);
        }
        return $this;
    }
    public function setLimit ($limit = 100) {
        $this->limit = intval($limit);
        return $this;
    }
    public function setOffset ($offset = 0) {
        $this->offset = intval($offset);
        return $this;
    }
    public function groupBy ($group) {
        $this->group = $group;
        return $this;
    }
    public function dontGroup () {
        $this->group = null;
        return $this;
    }
    public function setHaving ($having) {
        $this->having = $having;
        return $this;
    }
    public function setJoin ($src, $constraint, array $fields) {
        $this->join = array();
        return $this->addJoin($src, $constraint, $fields);
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
    public function addJoinByFlag ($flag, $src, $constraint, array $fields) {
        if (!$flag) return $this;
        $constraint = explode('=', $constraint);
        array_splice($constraint, 1, 0, array('='));
        $this->join[$src] = array(
            'constraint' => $constraint,
            'fields' => $fields
        );
        return $this;
    }
    public function clearJoins () {
        $this->join = array();
    }
    public function ordering ($field, $desc = false) {
        if ($field[0] == '@') {
            return $this->orderingExpr(substr($field, 1));
        }
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

    public function setFilter ($type, &$filter) {
        $this->filters[$type] = $filter;
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
                $this->setLimit($value);
                continue;
            }
            if ($key == 'page') {
                $this->stePage($value);
                continue;
            }
            if ($key == 'sort' || $key == 'sortorder') {
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
                    $this->addCondition($field, $value);// $dsConfig['condition'][$field] = $this->createCondition($value);
                elseif ($count > 2) {
                    $value = $parsedValue[1];
                    $comparator = null;
                    if (isset($parsedValue[2]))
                        $comparator = $parsedValue[2];
                    // if (strtolower($comparator) === 'in')
                    if ($isArray)
                        $value = explode(',', $parsedValue[1]);
                    // $dsConfig['condition'][$field] = $this->createCondition($value, $comparator);
                    $this->addCondition($field, $value, $comparator);
                }
            }
        }

        if ($sort != null) {
            if ($order != null) {
                $this->ordering($sort, $order);
            } else {
                $this->ordering($sort);
            }
        }

        // return $options;
        return $this;
    }

    public function addStandardDateFields () {
        $this->addStandardDateCreatedField();
        $this->addStandardDateUpdatedField();
        return $this;
    }

    public function addStandardDateCreatedField () {
        $this->addDataItem('DateCreated', dbquery::getDate());
        return $this;
    }

    public function addStandardDateUpdatedField () {
        $this->addDataItem('DateUpdated', dbquery::getDate());
        return $this;
    }

    public function addStandardDateNowField ($fldName) {
        $this->addDataItem($fldName, dbquery::getDate());
        return $this;
    }

    public function addStandardDateField ($fldName, $date) {
        $this->addDataItem($fldName, dbquery::getDate($date));
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
        // var_dump($fld);
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

    public function insert (array $data = array(), array $saveOptions = array()) {
        $this->setAction('insert');
        if (!empty($data)) {
            $this->setData($data);
        }
        if (!empty($saveOptions)) {
            $this->setSaveOption($saveOptions);
        }
        return $this->query();
    }
    public function update (array $data = array(), array $saveOptions = array()) {
        $this->setAction('update');
        if (!empty($data)) {
            $this->setData($data);
        }
        if (!empty($saveOptions)) {
            $this->setSaveOption($saveOptions);
        }
        return $this->query();
    }
    public function delete () {
        $this->setAction('delete');
        return $this->query();
    }
    public function call () {
        $this->setAction('call');
        return $this->query();
    }
    public function select () {
        $this->setAction('select');
        return $this->query();
    }

    // select wrappers
    public function selectSingleItem () {
        $this->setLimit(1);
        $dbData = $this->select();
        $data = null;
        if (isset($dbData[0])) {
            $data = $dbData[0];
        }
        return $data;
    }

    public function selectAsArray ($limit = null) {
        if (!is_null($limit)) {
            $this->setLimit($limit);
        }
        return $this->select();
    }

    public function selectAsDict ($dictKey, $dictValue = null, $limit = null) {
        if (!is_null($limit)) {
            $this->setLimit($limit);
        }
        $dbData = $this->select();
        $data = null;
        foreach ($dbData as $key => $val) {
            if ($dictValue) // peak specific value from a object
                $data[$val[$dictKey]] = $val[$dictValue] ?: null;
            else
                $data[$val[$dictKey]] = $val; // use whole item as value
        }
        return $data;
    }
    public function selectAsDataList ($page = null, $limit = null) {
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
        $dbData = $this->select();
        $data = null;
        $count = $this->queryCountForCurrentQueryParameters();
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
        return $data;
    }

    public function selectCount () {
        $this->setFields('COUNT(*) AS ItemsCount');
        $this->setLimit(1);
        $this->setOffset(0);
        $dbData = $this->select();
        $count = null;
        if (isset($dbData[0])) {
            $count = $dbData[0]['ItemsCount'];
        }
        return $count;
    }

    // results
    private function getLastResult ($r) {
        return $this->result;
    }
    private function setLastResult ($r) {
        $this->result = $r;
    }

    // private region
    private function configureOrm () {
        global $app;

        if (empty($this->source)) {
            throw new Exception("Source is empty", 1);
        }

        $db = $app->getDB()->getDBO();
        $db->mpwsReset();
        // set source
        $db->mpwsTable($this->source);

        // add fields to select
        foreach ($this->fields as $key => $value) {
            if ($value[0] === '@')
                $db->select_expr(substr($value, 1));
            else
                $db->select($value);
        }
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

        return $db;
    }
    private function queryCountForCurrentQueryParameters () {
        global $app;
        $db = $app->getDB()->getDBO();
        $db->select_expr('COUNT(*) AS ItemsCount');
        $db->offset(0);
        $db->limit(1);
        $dbData = $db->find_array();
        $count = 0;
        if (isset($dbData[0])) {
            $count = $dbData[0]['ItemsCount'];
        }
        return $count;
    }
    private function query () {
        global $app;

        $db = $this->configureOrm();

        // echo '>>>>>>>>>>>>>>>>>>>>>>>.dbo:';
        // var_dump($db);
        // echo '<<<<<<<<<<<<<<<<<<<<<<';

        $dbData = null;

        switch ($this->action) {
            case 'call':
                if (!empty($this->filters['call'])) {
                    $filter = $this->filters['call'];
                    $filter($this->data);
                }
                $dbData = $db->mpwsProcedureCall($this->source, $this->data);
                // filter fetched data using filter function
                if (!empty($this->filters['query'])) {
                    $filter = $this->filters['query'];
                    foreach ($dbData as $key => &$value) {
                        $filter($value);
                    }
                }
                break;
            case 'update':
                if (!empty($this->filters['update'])) {
                    $filter = $this->filters['update'];
                    $filter($this->data);
                }
                if (!empty($this->filters['save'])) {
                    $filter = $this->filters['save'];
                    $filter($this->data);
                }
                $db->update($this->data);
                $db->save($this->saveOptions);
                break;
            case 'delete':
                $db->delete_many();
                break;
            case 'insert':
                // filter fetched data using filter function
                if (!empty($this->filters['insert'])) {
                    $filter = $this->filters['insert'];
                    $filter($this->data);
                }
                if (!empty($this->filters['save'])) {
                    $filter = $this->filters['save'];
                    $filter($this->data);
                }
                $db->create($this->data);
                $db->save($this->saveOptions);
                $dbData = $db->mpwsGetLastInsertId();
                break;
            case 'select':
            default:
                // fetch data
                $dbData = $db->find_array();
                // filter fetched data using filter function
                if (!empty($this->filters['query'])) {
                    $filter = $this->filters['query'];
                    foreach ($dbData as $key => &$value) {
                        $filter($value);
                    }
                }
                break;
        }

        // var_dump($dbData);

        $this->setLastResult($dbData);

        return $dbData;
    }

}

?>
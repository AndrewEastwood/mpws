<?php

class libraryDataBase {

    var $config;
    var $dbo;
    var $transactionIsActive = false;
    var $disableTransactions = false;

    public function __construct($config = false) {
        $this->config = $config;
        libraryORM::configure($this->config);
        $this->dbo = libraryORM::mpwsInstance();
    }

    public function getDBO () {
        return $this->dbo;
    }

    public function getDBLink () {
        return libraryORM::get_db();
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

    public function getTableStatusFieldOptions($table) {
        $config = objectConfiguration::jsapiUtil_GetTableStatusFieldOptions($table);
        $data = $this->getData($config);
        preg_match('#^enum\((.*?)\)$#ism', $data['Type'], $matches);
        $enum = str_getcsv($matches[1], ",", "'");
        return $enum;
    }

    public function getLastInsertId () {
        return $this->dbo->mpwsGetLastInsertId();
    }

    public function getData ($config) {
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
                    }

                    $this->dbo->select_many($fieldsToSelectClear);
                }
                // var_dump($fieldsToSelectClear);
            }

        $_fieldOptionsWorkerFn = function ($context, $fieldName, $fieldOptions) {
            switch (strtolower($fieldOptions['comparator'])) {
                case '>':
                    $context->dbo->where_gt($fieldName, $fieldOptions['value']);
                    break;
                case '>=':
                    $context->dbo->where_gte($fieldName, $fieldOptions['value']);
                    break;
                case '<':
                    $context->dbo->where_lt($fieldName, $fieldOptions['value']);
                    break;
                case '<=':
                    $context->dbo->where_lte($fieldName, $fieldOptions['value']);
                    break;
                case 'is null':
                    $context->dbo->where_null($fieldName, $fieldOptions['value']);
                    break;
                case 'is not null':
                    $context->dbo->where_not_null($fieldName, $fieldOptions['value']);
                    break;
                case '=':
                    $context->dbo->where_equal($fieldName, $fieldOptions['value']);
                    break;
                case '!=':
                    $context->dbo->where_not_equal($fieldName, $fieldOptions['value']);
                    break;
                case 'like':
                    $context->dbo->where_like($fieldName, $fieldOptions['value']);
                    break;
                case 'not like':
                    $context->dbo->where_not_like($fieldName, $fieldOptions['value']);
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

        if (!empty($config['order']) && !empty($config['order']['field'])) {

            if (!empty($config['order']['ordering']) && $config['order']['ordering'] === 'DESC')
                $this->dbo->order_by_desc($config['order']['field']);
            else
                $this->dbo->order_by_asc($config['order']['field']);
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
                $this->dbo->set($config['data']);
                // echo 'libraryDataObject update DB';
                $this->dbo->save();
                break;
            case 'delete':
                $this->dbo->delete_many();
                break;
            case 'insert':
                $this->dbo->create($config['data']);
                $this->dbo->save();
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
                    case 'transformToArray':
                        // optimize values
                        $dbData = $this->_mpwsOptimizeDataValues($dbData, $_options ?: array());
                        break;
                    case 'combineDataByKeys':
                        // var_dump($dbData);
                        $dbData = $this->_mpwsCombineDataByKeys($dbData, $_options['mapKeysToCombine'], isset($_options['doOptimization']) ?: true, isset($_options['keysToForceTransformToArray']) ? $_options['keysToForceTransformToArray'] : array());
                        break;
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

    public function _mpwsOptimizeDataValues ($dataArray, $keysToForceTransformToArray) {
        $keysToForceTransformToArray = is_array($keysToForceTransformToArray) ? $keysToForceTransformToArray : array();
        // optimize values:
        // 1. values like: 1#EXPLODE#2#EXPLODE#....
        //    will be converted to array [1, 2, n]
        foreach ($dataArray as $key => $value) {
            if (is_array($value))
                $dataArray[$key] = $this->_mpwsOptimizeDataValues($value, $keysToForceTransformToArray);
            else if (strstr($value, EXPLODE) || in_array($key, $keysToForceTransformToArray))
                $dataArray[$key] = explode(EXPLODE, $value);
        }
        return $dataArray;

    }
    public function _mpwsCombineDataByKeys ($dataArray, $mapKeysToCombine, $doOptimization, $keysToForceTransformToArray) {
        // $newArray = array();

        if ($doOptimization)
            $dataArray = $this->_mpwsOptimizeDataValues($dataArray, $keysToForceTransformToArray);

        // var_dump($mapKeysToCombine);
        // var_dump($dataArray);
        // values will be combinet into 
        foreach ($mapKeysToCombine as $destKey => $keyMap) {

            if (!isset($dataArray[$keyMap['keys']]) || !isset($dataArray[$keyMap['values']])) {
                foreach ($dataArray as $key => $value)
                    if (is_array($value))
                        $dataArray[$key] = $this->_mpwsCombineDataByKeys($dataArray[$key], $mapKeysToCombine, $doOptimization, $keysToForceTransformToArray);
                continue;
            }

            $_keys = $dataArray[$keyMap['keys']];
            $_values = $dataArray[$keyMap['values']];

            if (!empty($_keys) && !is_array($_keys))
                $_keys = array($_keys);

            if (!empty($_values) && !is_array($_values))
                $_values = array($_values);

            if (!is_array($_keys) || !is_array($_values))
                continue;

            $_combinedData = array();

            // var_dump($_keys);
            // var_dump($_values);
            for ($_idx = 0, $_len = count($_keys); $_idx < $_len; $_idx++) {
                if (isset($_combinedData[$_keys[$_idx]])) {
                    if (is_array($_combinedData[$_keys[$_idx]]))
                        $_combinedData[$_keys[$_idx]][] = $_values[$_idx];
                    else
                        $_combinedData[$_keys[$_idx]] = array($_combinedData[$_keys[$_idx]], $_values[$_idx]);
                } else
                    $_combinedData[$_keys[$_idx]] = $_values[$_idx];
            }

            // $dataArray[$destKey] = array_combine($_keys, $_values);
            $dataArray[$destKey] = $_combinedData;

            if (isset($keyMap['keepOriginal']))
                continue;

            // remove orignial sources
            unset($dataArray[$keyMap['keys']]);
            unset($dataArray[$keyMap['values']]);

        }
        return $dataArray;
    }
}

?>
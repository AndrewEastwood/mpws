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

        return $this;
    }
    
    // data
    public function setDataError($errorMessage) {
        if ($this->hasData())
            $this->_data['error'] = $errorMessage;
        else
            $this->setData(array('error' => $errorMessage));
        return $this;
    }
    public function setDataStatus($status) {
        if ($this->hasData())
            $this->_data['status'] = $status;
        else
            $this->setData(array('status' => $status));
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
        // echo 'setConfig >>>>>>>>>>>>>>>>>';
        // var_dump($config);
        // echo '<<<<<<<<<<<<<<<<<< setConfig';
        $this->_config = $config;
        return $this;

    }

    // getters
    public function hasData() { return !empty($this->_data); }
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
            "data" => array(
                "fields" => array(/*"DateLastAccess", "IsOnline"*/),
                "values" => array()
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
                "expandSingleRecord" => true
            )
        );
    }

    // configuration helpers
    public function setValuesDbCondition ($values, $mode = MERGE_MODE_REPLACE) {
        if (!is_array($values))
            $values = array($values);

        // echo '<br>setValuesDbCondition >>>>>>>>><br>';
        // var_dump($values);
        // echo '>>>>>>>. mode';
        // var_dump($mode);
        // echo '<br><-------------------------';

        // prepend values
        if ($mode === MERGE_MODE_PREPEND) {
            // echo '... prepending to existedValues';
            $cfg = $this->getConfig();
            $existedValues = $cfg['condition']['values'] ?: array();
            // var_dump($cfg);
            foreach ($values as $value)
                array_unshift($existedValues, $value);
            $values = $existedValues;
        }

        // append values
        if ($mode === MERGE_MODE_APPEND) {
            // echo '... appending to existedValues';
            $cfg = $this->getConfig();
            $existedValues = $cfg['condition']['values'] ?: array();
            // var_dump($cfg);
            // var_dump($existedValues);
            foreach ($values as $value)
                array_push($existedValues, $value);
            $values = $existedValues;
        }

        // var_dump($values);

        // just set values to config
        $this->extendConfig(array(
            "condition" => array(
                "values" => $values
            )
        ));

        // var_dump($this->getConfig());

        return $this;
    }
    public function setValuesDbData ($values, $mode = MERGE_MODE_REPLACE) {
        if (!is_array($values))
            $values = array($values);

        // echo '<br>setValuesDbData >>>>>>>>><br>';
        // var_dump($values);
        // echo '<br><-------------------------';

        // prepend values
        if ($mode === MERGE_MODE_PREPEND) {
            $cfg = $this->getConfig();
            $existedValues = $cfg['data']['values'] ?: array();
            foreach ($values as $value)
                array_unshift($existedValues, $value);
            $values = $existedValues;
        }

        // append values
        if ($mode === MERGE_MODE_APPEND) {
            $cfg = $this->getConfig();
            $existedValues = $cfg['data']['values'] ?: array();
            foreach ($values as $value)
                $existedValues[] =  $value;
            $values = $existedValues;
        }

        // var_dump($values);
        // just set values to config
        $this->extendConfig(array(
            "data" => array(
                "values" => $values
            )
        ));
        return $this;
    }

    // database fetch/push data
    public function process($params = false) {
        // echo 'TROLOLO';
        $ctx = contextMPWS::instance();
        $dbo = $ctx->contextCustomer->getDBO();
        $config = $this->extendConfig($params)->getConfig();

        
        // var_dump($this->getConfig());
        // $_db_dataObj = $ctx->contextCustomer->getDBO()->mpwsFetchData($this->getConfig());

        $dbo->mpwsReset();

        $action = $config['action'];
        $source = $config['source'];
        $fieldsToSelectFromDB = $config['fields'] ?: array();

        // prepend ID column
        // if (!in_array("ID", $fieldsToSelectFromDB))
        //     array_unshift($fieldsToSelectFromDB, 'ID');

        $fieldsToSelectFromDBClear = array();
        // just to avoid mysql error: XXXX in field list is ambiguous
        foreach ($fieldsToSelectFromDB as $key => $value) {
            if ($value[0] === '@')
                $dbo->select_expr(substr($value, 1));
            elseif (!strstr($value, '.'))
                $fieldsToSelectFromDBClear[$key] = sprintf("%s.%s", $source, $value);
        }

        $dbo->mpwsTable($source);

        if (!empty($fieldsToSelectFromDBClear))
            $dbo->select_many($fieldsToSelectFromDBClear);

        if (!empty($config['additional']))
            foreach ($config['additional'] as $addSource => $addConfig) {
                if (empty($addConfig['fields']))
                    continue;
                $dbo->join($addSource, $addConfig['constraint']);

                $fieldsToSelect = $addConfig['fields'];
                $fieldsToSelectClear = array();

                foreach ($fieldsToSelect as $key => $value) {
                    if ($value[0] === '@')
                        $dbo->select_expr(substr($value, 1));
                        // $fieldsToSelect[$key] = substr($value, 1);
                    elseif (!strstr($value, '.'))
                        $fieldsToSelectClear[$key] = sprintf("%s.%s", $addSource, $value);
                }

                $dbo->select_many($fieldsToSelectClear);
            }


        // condition
        // var_dump($config);
        if (!empty($config['condition']['filter'])) {
            // var_dump('LOLOLOL');
            // translate condition filter string
            $values = $config['condition']['values'];

            // var_dump($values);

            // ProductID (LIKE) (?) + Name (=) ?
            $filterElements = explode (' + ', $config['condition']['filter']);
            // $addedCount = 0;
            for ($i = 0, $len = count($filterElements); $i < $len; $i++) {
                $matches = null;
                $returnValue = preg_match('/(.*)(\\s)\\((.*)\\)(\\s)(.*)/', $filterElements[$i], $matches);
                // check for valid condition:
                // array (
                //   0 => 'ProductID (LIKE) ?',
                //   1 => 'ProductID',
                //   2 => ' ',
                //   3 => 'LIKE',
                //   4 => ' ',
                //   5 => '?',
                // )
                // var_dump($matches);
                // var_dump($returnValue);
                // var_dump($values[$i]);
                if (is_array($matches) && count($matches) === 6) {
                    switch (strtolower($matches[3])) {
                        case '>':
                            $dbo->where_gt($matches[1], $values[$i]);
                            break;
                        case '>=':
                            $dbo->where_gte($matches[1], $values[$i]);
                            break;
                        case '<':
                            $dbo->where_lt($matches[1], $values[$i]);
                            break;
                        case '<':
                            $dbo->where_lte($matches[1], $values[$i]);
                            break;
                        case 'is null':
                            $dbo->where_null($matches[1], $values[$i]);
                            break;
                        case 'is not null':
                            $dbo->where_not_null($matches[1], $values[$i]);
                            break;
                        case '=':
                            $dbo->where_equal($matches[1], $values[$i]);
                            break;
                        case '!=':
                            $dbo->where_not_equal($matches[1], $values[$i]);
                            break;
                        case 'like':
                            $dbo->where_like($matches[1], $values[$i]);
                            break;
                        case 'not like':
                            $dbo->where_not_like($matches[1], $values[$i]);
                            break;
                        case 'in':
                            // var_dump('using WHERE_IN', $values[$i]);
                            $dbo->where_in($matches[1], is_array($values[$i]) ? $values[$i] : array($values[$i]));
                            break;
                        case 'not in':
                            $dbo->where_not_in($matches[1], is_array($values[$i]) ? $values[$i] : array($values[$i]));
                            break;
                        default:
                            var_dump('Unknown condition statement occured');
                            break;
                    }
                }
            }
            // $dbo->where_raw($config['condition']['filter'], $values ?: array());
        }

        if (!empty($config['group']))
            $dbo->group_by($config['group']);

        if (!empty($config['offset']))
            $dbo->offset($config['offset']);

        if (!empty($config['limit']))
            $dbo->limit($config['limit']);

        if (!empty($config['order']) && !empty($config['order']['field'])) {

            if (!empty($config['order']['ordering']) && $config['order']['ordering'] === 'DESC')
                $dbo->order_by_desc($config['order']['field']);
            else
                $dbo->order_by_asc($config['order']['field']);
        }

        // echo '>>>>>>>>>>>>>>>>>>>>>>>.dbo:';
        // var_dump($dbo);
        // echo '<<<<<<<<<<<<<<<<<<<<<<';
        switch ($action) {
            case 'update':
                // var_dump(array_combine($config['data']['fields'], $config['data']['values']));
                $dbo->set(array_combine($config['data']['fields'], $config['data']['values']));
                // echo 'mpwsData update DB';
                $dbo->save();
                break;
            case 'delete':
                $dbo->delete_many();
                break;
            case 'insert':
                $dbo->create(array_combine($config['data']['fields'], $config['data']['values']));
                $dbo->save();
                break;
            case 'select':
            default:
                // fetch data
                $dbData = $dbo->find_array();
                break;
        }

        $_opt_expandSingleRecord = false;

        // apply data transformation options
        if (!empty($config['options']))
            foreach ($config['options'] as $key => $value)
                switch ($key) {
                    case 'transformToArray':
                        // optimize values
                        $dbData = $this->mpwsOptimizeDataValues($dbData, $value ?: array());
                        break;
                    case 'combineDataByKeys':
                        // var_dump($dbData);
                        $dbData = $this->mpwsCombineDataByKeys($dbData, $value['mapKeysToCombine'], $value['doOptimization'] ?: true, $value['keysToForceTransformToArray'] ?: array());
                        break;
                    case 'expandSingleRecord':
                        if (is_bool($value))
                            $_opt_expandSingleRecord = $value;
                        break;
                    default:
                        # code...
                        break;
                }

        // var_dump($dbData);
        // echo "do expand single record ? " . ($_opt_expandSingleRecord ? 'true' : 'false');

        // create mpwsData object
        $data = null;
        if (count($dbData) === 1) {
            if ($_opt_expandSingleRecord)
                $data = $dbData[0];
            else
                $data = $dbData;
        }
        if (count($dbData) > 1)
            $data = $dbData;


        $this->setData($data);
        // $mpwsDataObj = new mpwsData($data);

        // if (isset($config['output']))
        //     return $mpwsDataObj->to($config['output']);
        // $dbo->table('shop_products')
        //     ->select('shop_products.ID', 'ID')
        //     ->select('shop_products.Name', 'pName')
        //     ->select('shop_origins.Name', 'oName')
        //     ->select('shop_categories.Name', 'cName')
        //     ->join('shop_origins', array(
        //         'shop_origins.ID', '=', 'shop_products.OriginID'
        //     ))
        //     ->join('shop_categories', array(
        //         'shop_categories.ID', '=', 'shop_products.CategoryID'
        //     ));
        // return $mpwsDataObj;
        return $this;









        // return $this->setData($_db_dataObj);
    }

    public function mpwsOptimizeDataValues ($dataArray, $keysToForceTransformToArray) {
        $keysToForceTransformToArray = is_array($keysToForceTransformToArray) ? $keysToForceTransformToArray : array();
        // optimize values:
        // 1. values like: 1#EXPLODE#2#EXPLODE#....
        //    will be converted to array [1, 2, n]
        foreach ($dataArray as $key => $value) {
            if (is_array($value))
                $dataArray[$key] = $this->mpwsOptimizeDataValues($value, $keysToForceTransformToArray);
            else if (strstr($value, EXPLODE) || in_array($key, $keysToForceTransformToArray))
                $dataArray[$key] = explode(EXPLODE, $value);
        }
        return $dataArray;

    }
    public function mpwsCombineDataByKeys ($dataArray, $mapKeysToCombine, $doOptimization, $keysToForceTransformToArray) {
        // $newArray = array();

        if ($doOptimization)
            $dataArray = $this->mpwsOptimizeDataValues($dataArray, $keysToForceTransformToArray);

        // var_dump($mapKeysToCombine);
        // values will be combinet into 
        foreach ($mapKeysToCombine as $destKey => $keyMap) {

            if (!isset($dataArray[$keyMap['keys']]) || !isset($dataArray[$keyMap['values']])) {
                foreach ($dataArray as $key => $value)
                    if (is_array($value))
                        $dataArray[$key] = $this->mpwsCombineDataByKeys($dataArray[$key], $mapKeysToCombine, $doOptimization, $keysToForceTransformToArray);
                continue;
            }

            $_keys = $dataArray[$keyMap['keys']];
            $_values = $dataArray[$keyMap['values']];

            if (!is_array($_keys) || !is_array($_values))
                continue;

            // var_dump($_keys);
            // var_dump($_values);

            $dataArray[$destKey] = array_combine($_keys, $_values);

            if ($keyMap['keepOriginal'])
                continue;

            // remove orignial sources
            unset($dataArray[$keyMap['keys']]);
            unset($dataArray[$keyMap['values']]);

        }
        return $dataArray;
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


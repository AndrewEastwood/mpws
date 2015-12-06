<?php

namespace engine\lib;

class database {

    var $config;
    var $dbo;
    var $transactionIsActive = false;
    var $lockTransaction = false;

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
        if (!$this->isTransactionLocked())
            return $this;
        if ($this->transactionIsActive)
            return $this;
        // echo 3;
        try {
            $this->getDBLink()->beginTransaction();
        } catch (Exception $e) {
            var_dump($e);
        }
        $this->transactionIsActive = true;
        // echo 4;
        return $this;
    }

    public function commit () {
        if (!$this->isTransactionLocked())
            return false;
        if ($this->transactionIsActive) {
            $this->getDBLink()->commit();
            $this->transactionIsActive = false;
        }
        return $this;
    }

    public function rollback () {
        if (!$this->isTransactionLocked())
            return $this;
        if ($this->transactionIsActive) {
            $this->getDBLink()->rollBack();
            $this->transactionIsActive = false;
        }
        return $this;
    }

    public function lockTransaction () {
        $this->lockTransaction = true;
        return $this;
    }

    public function unlockTransaction () {
        $this->lockTransaction = false;
        return $this;
    }

    public function isTransactionLocked () {
        return !$this->lockTransaction;
        return $this;
    }

    public function xxx_getLastInsertId () {
        return $this->dbo->mpwsGetLastInsertId();
    }

    public function getSqlBooleanValue ($boolval) {
        return $boolval ? 1 : 0;
    }

    public function xxx_createCondition ($value, $comparator = null, $concatenate = null) {
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

    public function xxx_createSortOrder ($fld, $desc = false) {
        return array(
            'field' => $fld,
            'ordering' => $desc ? 'DESC' : 'ASC'
        );
    }

    public function createQuery ($name, $source) {
        return new dbquery($name, $source);
    }

    public function getQuery ($name) {
        return dbquery::get($name);
    }

    public function xxx_createOrGetQuery ($name) {
        if (dbquery::exists($name)) {
            return dbquery::get($name);
        }
        return new dbquery($name);


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
    }

    public function xxx_getTableRecordsCount ($table, $condition = array()) {
        return $this->createOrGetQuery(array(
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

    public function xxxx_getTableRecordsCount ($dbq) {
        $config = $this->createOrGetQuery(array(
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
        $countData = $this->query($configCount, $useCustomerID);
        $count = intval($countData["ItemsCount"]);
        return $count;
    }

    public function xxx_pickDataListParamsFromRequest ($req) {
        $keys = array('limit', 'page', 'sort', 'order', '_f([a-zA-Z\._]+)', '_p([a-zA-Z]+)');
        $options = array();
        foreach ($req->get as $queryKey => $queryParam) {
            foreach ($keys as $keyToGrab) {
                $matches = array();
                if (preg_match("/^" . $keyToGrab . "$/", $queryKey, $matches)) {
                    $options[$queryKey] = $queryParam;
                }
            }
        }
        return $options;
    }
    // public function addQueryConditionsFrom
    public function xxx_queryMatchedIDs (array $dsConfig = array()) {
        if (empty($dsConfig)) {
            throw new Exception("Empty dbConfig in queryMatchedIDs", 1);
        }
        $limit = isset($dsConfig['limit']) ? $dsConfig['limit'] : 0;
        $page = 1;
        $items = array();
        $useCustomerID = true;
        $dsConfig['fields'] = array('ID');
        $options = $dsConfig['options'] ?: array();
        if (isset($options['useCustomerID']) && is_bool($options['useCustomerID'])) {
            $useCustomerID = $options['useCustomerID'];
        }

        if (isset($dsConfig['action']) && $dsConfig['action'] !== "select")
            throw new Exception("ErrorProcessingDataListMethod", 1);

        // grab other fields
        foreach ($options as $key => $value) {
            $matches = array();
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

        // var_dump($dsConfig['condition']);
        // get data total records
        $configCount = $this->getTableRecordsCount($dsConfig['source'], $dsConfig['condition']);

        // add missing fields
        $configCount['fields'] = array_merge($configCount['fields'], $dsConfig['fields']);

        // add additional
        if (isset($dsConfig['having'])) {
            $configCount['having'] = $dsConfig['having'];
        }
        // add additional
        if (isset($dsConfig['additional'])) {
            $configCount['additional'] = $dsConfig['additional'];
        }
        // var_dump($configCount);

        $countData = $this->query($configCount, $useCustomerID);
        $count = intval($countData["ItemsCount"]);

        // if (!empty($options)) {

        //     if (isset($options['sort']) || isset($options['order'])) {
        //         $dsConfig['order'] = array();
        //         if (isset($options['sort'])) {
        //             if (strpos($options['sort'], '.') === false)
        //                 $dsConfig['order']['field'] = $dsConfig['source'] . '.' . $options['sort'];
        //             else
        //                 $dsConfig['order']['field'] = $options['sort'];
        //         }
        //         if (isset($options['order'])) {
        //             $dsConfig['order']['ordering'] = $options['order'];
        //         }
        //     }

        //     if (isset($options['page']))
        //         $page = intval($options['page']);
        //     if (isset($options['limit']))
        //         $limit = intval($options['limit']);

        //     if ($count > 0) {
        //         if ($limit >= 1) {
        //             $dsConfig['limit'] = $limit;
        //         }
        //         if ($limit === 0) {
        //             unset($dsConfig['limit']);
        //         }
        //         if ($page >= 1 && $limit >= 1) {
        //             if ($page > round($count / $limit + 0.49)) {
        //                 $page = round($count / $limit + 0.49);
        //             }
        //             $dsConfig['offset'] = ($page - 1) * $limit;
        //         } elseif ($page === 0)
        //             $page = 1;
        //     }
        // }

        // if (isset($dsConfig['sort']) || isset($dsConfig['order'])) {
        //     $dsConfig['order'] = array();
        //     if (isset($dsConfig['sort'])) {
        //         if (strpos($dsConfig['sort'], '.') === false)
        //             $dsConfig['order']['field'] = $dsConfig['source'] . '.' . $dsConfig['sort'];
        //         else
        //             $dsConfig['order']['field'] = $dsConfig['sort'];
        //     }
        //     if (isset($dsConfig['order'])) {
        //         $dsConfig['order']['ordering'] = $dsConfig['order'];
        //     }
        // }

        // if (isset($dsConfig['page']))
        //     $page = intval($dsConfig['page']);
        // if (isset($dsConfig['limit']))
        //     $limit = intval($dsConfig['limit']);

        // if ($count > 0) {
        //     if ($limit >= 1) {
        //         $dsConfig['limit'] = $limit;
        //     }
        //     if ($limit === 0) {
        //         unset($dsConfig['limit']);
        //     }
        //     if ($page >= 1 && $limit >= 1) {
        //         if ($page > round($count / $limit + 0.49)) {
        //             $page = round($count / $limit + 0.49);
        //         }
        //         $dsConfig['offset'] = ($page - 1) * $limit;
        //     } elseif ($page === 0)
        //         $page = 1;
        // }

        // var_dump($dsConfig);
        // get data
        $items = $this->query($dsConfig, $useCustomerID) ?: array();


        $itemsIDs = array();
        // var_dump($items);

        // if (isset($callbacks['parse']) && is_callable($callbacks['parse'])) {
        //     $parseFn = $callbacks['parse'];
            foreach ($items as $item) {
                if (isset($item['ID'])) {
                    $itemsIDs[] = $item['ID'];
                }
                // $items[$key] = $parseFn($item, $key, $items) ?: array();
            }
        // }

        $rez = array();
        $orderBy = null;
        $order = null;

        // if (isset($dsConfig['order']['field'])) {
        //     $orderBy = $dsConfig['order']['field'];
        // }

        // if (isset($dsConfig['order']['ordering'])) {
        //     $order = $dsConfig['order']['ordering'] === 'ASC';
        // }

        $rez = $this->arrayToDataList($itemsIDs ?: array(),
            $count, $page, $limit, $orderBy, $order);
        // $rez["type"] = "list";
        // $rez["info"] = $listInfo;
        // $rez["items"] = $items ?: array();
        // $rez['ids'] = $itemsIDs;
        return $rez;
    }

    public function xxx_arrayToDataList (array $ids, $count = 0,
        $page = 0, $limit = 0, $orderBy = null, $order = null) {
        
        $total_pages = empty($limit) ? 1 : round($count / $limit + 0.49);
        $listInfo = array(
            "ids" => $ids,
            "page" => $page,
            "limit" => $limit,
            "total_pages" => $total_pages,// empty($limit) ? 1 : round($count / $limit + 0.49),
            "total_entries" => empty($count) ? count($ids) : $count,
            "order_by" => $orderBy,
            "order" => $order
        );

        // $listInfo = !empty($info) ? $info : array(
        //     "page" => 0,
        //     "limit" => 0,
        //     "total_pages" => 1,
        //     "total_entries" => count($items)
        // );
        // $rez["type"] = "list";
        // $rez["info"] = $listInfo;
        // $rez["items"] = array_values($items) ?: array();
        return $listInfo;
    }

    public function query () {}

}

?>
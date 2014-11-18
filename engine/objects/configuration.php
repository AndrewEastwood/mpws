<?php
namespace engine\objects;
use \engine\lib\utils as Utils;

class configuration {

    public $DATE_FORMAT = 'Y-m-d H:i:s';
    public $DEFAULT_COMPARATOR = '=';
    public $DEFAULT_CONCATENATE = 'AND';

    public function getDate ($strDate = '') {
        if (!empty($strDate)) {
            $time = strtotime($strDate);
            return date($this->DATE_FORMAT, $time);
        }
        return date($this->DATE_FORMAT);
    }

    public function jsapiCreateDataSourceCondition ($value, $comparator = null, $concatenate = null) {
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

    public function jsapiGetDataSourceConfig($configExtend = null) {
        $configDefault = array(
            "source" => "",
            "action" => "select",
            "procedure" => array(
                "name" => "",
                "parameters" => array()
            ),
            "condition" => array(), // use fieldName => jsapiCreateDataSourceCondition()
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

        return $this->extendConfigs($configDefault, $configExtend, true);
    }

    public function extendConfigs ($configA, $configB = null) {
        return Utils::array_merge_recursive_distinct($configA, $configB);
    }

    public function jsapiUtil_GetTableRecordsCount ($table, $condition = array()) {
        return $this->jsapiGetDataSourceConfig(array(
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

    // public function jsapiUtil_GetTableStatusFieldOptions ($table) {
    //     return $this->jsapiUtil_GetFieldOptions($table, 'Status');
    // }

    // public function jsapiUtil_GetFieldOptions ($table, $field) {
    //     return $this->jsapiGetDataSourceConfig(array(
    //         "action" => "call",
    //         "procedure" => array(
    //             "name" => "getFieldOptions",
    //             "parameters" => array($table, $field)
    //         ),
    //         "options" => array(
    //             "expandSingleRecord" => true
    //         )
    //     ));
    // }

    public function jsapiAddTask ($data) {
        $data["DateCreated"] = $this->getDate();
        $params = isset($data['Params']) ? $data['Params'] : '';
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "mpws_tasks",
            "action" => "insert",
            "data" => array(
                'CustomerID' => $data['CustomerID'],
                'Group' => $data['Group'],
                'Name' => $data['Name'],
                'Hash' => md5($data['Group'] . $data['Name'] . $params),
                'PrcPath' => isset($data['PrcPath']) ? $data['PrcPath'] : '',
                'PID' => isset($data['PID']) ? $data['PID'] : '',
                'Params' => $params,
                'DateCreated' => $data["DateCreated"]
            ),
            "options" => null
        ));
    }
    public function jsapiGetGroupTasks ($groupName, $active = false, $completed = false, $canceled = false) {
        $config = $this->jsapiGetDataSourceConfig(array(
            "source" => "mpws_tasks",
            "action" => "select",
            'condition' => array(
                'Group' => $this->jsapiCreateDataSourceCondition($groupName)
            ),
            "options" => null
        ));
        if ($active) {
            $config['condition']['IsRunning'] = $this->jsapiCreateDataSourceCondition(1);
        }
        if ($completed) {
            $config['condition']['Complete'] = $this->jsapiCreateDataSourceCondition(1);
        }
        if ($canceled) {
            $config['condition']['ManualCancel'] = $this->jsapiCreateDataSourceCondition(1);
        }
        return $config;
    }
    public function jsapiStopTask ($id) {
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "mpws_tasks",
            "action" => "update",
            'condition' => array(
                'ID' => $this->jsapiCreateDataSourceCondition($id)
            ),
            "data" => array(
                'IsRunning' => 0,
                'Complete' => 0,
                'ManualCancel' => 1
            ),
            "options" => null
        ));
    }
    public function jsapiGetTaskByHash ($hash) {
        $config = $this->jsapiGetDataSourceConfig(array(
            "source" => "mpws_tasks",
            "action" => "select",
            'condition' => array(
                'Hash' => $this->jsapiCreateDataSourceCondition($hash)
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
        return $config;
    }
    public function jsapiDeleteTaskByHash ($hash) {
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "mpws_tasks",
            "action" => "delete",
            'condition' => array(
                'Hash' => $this->jsapiCreateDataSourceCondition($hash)
            ),
            "options" => null
        ));
    }
    public function jsapiGetNextTaskToProcess ($group, $name) {
        return $this->jsapiGetDataSourceConfig(array(
            "source" => "mpws_tasks",
            "action" => "select",
            'condition' => array(
                'Group' => $this->jsapiCreateDataSourceCondition($group),
                'Name' => $this->jsapiCreateDataSourceCondition($name)
            ),
            "order" => array(
                "field" => "DateCreated",
                "ordering" => "ASC"
            ),
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

}

?>
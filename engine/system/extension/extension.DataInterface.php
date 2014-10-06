<?php

class extensionDataInterface extends objectExtension {

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
                    $dsConfig['condition'][$field] = configurationDefaultDataSource::jsapiCreateDataSourceCondition($value);
                elseif ($count === 3) {
                    $value = $parsedValue[1];
                    $comparator = $parsedValue[2];
                    if (strtolower($comparator) === 'in')
                        $value = explode(',', $parsedValue[1]);
                    $dsConfig['condition'][$field] = configurationDefaultDataSource::jsapiCreateDataSourceCondition($value, $comparator);
                }
            }
        }

        // var_dump($dsConfig['condition']);
        // get data total records
        $configCount = configurationDefaultDataSource::jsapiUtil_GetTableRecordsCount($dsConfig['source'], $dsConfig['condition']);
        
        $countData = $this->getCustomer()->fetch($configCount);
        $count = intval($countData["ItemsCount"]);

        if (!empty($options) && is_object($options)) {
            if (isset($options['sort']))
                $dsConfig['order']['field'] = $options['sort'];
            if (isset($options['order']))
                $dsConfig['order']['ordering'] = $options['order'];

            if (isset($options['page']))
                $page = intval($options['page']);
            if (isset($options['limit']))
                $limit = intval($options['limit']);

            if ($count > 0) {
                if ($limit >= 1)
                    $dsConfig['limit'] = $limit;
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
        $items = $this->getCustomer()->fetch($dsConfig) ?: array();
        // var_dump($items);

        if (isset($callbacks['parse']) && is_callable($callbacks['parse'])) {
            $parseFn = $callbacks['parse'];
            $items = $parseFn($items) ?: array();
        }

        $rez = array();

        $listInfo = array(
            "page" => $page,
            "limit" => $limit,
            "total_pages" => round($count / $limit + 0.49),
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
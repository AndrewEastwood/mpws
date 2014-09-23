<?php

class extensionDataInterface extends objectExtension {

    public function getDataList ($dsConfig, $req = null, $callbacks = array()) {
        $limit = $dsConfig['limit'];
        $page = 1;
        $items = array();

        if ($dsConfig['action'] !== "select")
            throw new Exception("ErrorProcessingDataListMethod", 1);

        // grab other fields
        if (is_object($req))
            foreach ($req->get as $key => $value) {
                $matches = array();
                if (preg_match("/^_f(\w+)$/", $key, $matches)) {
                    // $matches
                    $field = $matches[1];
                    // parse value
                    $parsedValue = array();
                    preg_match("/([0-9A-Za-z\,_-]+):(\w+)$/", $value, $parsedValue);
                    // var_dump($field);
                    $count = count($parsedValue);
                    // var_dump($parsedValue[2]);
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

        if (!empty($req) && is_object($req)) {
            if (isset($req->get['sort']))
                $dsConfig['order']['field'] = $req->get['sort'];
            if (isset($req->get['order']))
                $dsConfig['order']['ordering'] = $req->get['order'];

            if (isset($req->get['page']))
                $page = intval($req->get['page']);
            if (isset($req->get['per_page']))
                $limit = intval($req->get['per_page']);

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
            "per_page" => $limit,
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
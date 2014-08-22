<?php

class extensionDataInterface extends objectExtension {

    public function getDataList ($dsConfig, $req = null) {
        $limit = $dsConfig['limit'];
        $page = 1;
        $items = array();

        if ($dsConfig['action'] !== "select")
            throw new Exception("ErrorProcessingDataListMethod", 1);

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
        $items = $this->getCustomer()->fetch($dsConfig);

        return array(
            "items" => $items ?: array(),
            "page" => $page,
            "per_page" => $limit,
            "count" => $count
        );
    }

}

?>
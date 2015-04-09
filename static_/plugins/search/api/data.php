<?php
namespace static_\plugins\search\api;

use \engine\lib\api as API;
use Exception;

class data {

    public function get (&$resp, $req) {
        $apiCustomer = API::getAPI('system:customers');
        $customer = $apiCustomer->getRuntimeCustomer();
        $plugins = $customer['Plugins'];
        foreach ($plugins as $plgName) {
            $plgSearchApi = API::getApi($plgName . ':search');
            if (!empty($plgSearchApi) && isset($req->get['text'])) {
                $resp[$plgName] = $plgSearchApi->search($req->get['text']);
            }
        }
    }

}

?>
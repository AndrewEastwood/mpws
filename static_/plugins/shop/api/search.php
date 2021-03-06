<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\request as Request;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class search {

    public function get (&$resp, $req) {
        if (isset($req->get['text'])) {
            $resp = $this->search($req->get['text'], $req);
        }
    }

    public function search ($text, $req = null) {
        if (empty($text)) {
            return null;
        }
        $searchOptions['_pSearchText'] = implode("%", str_split(str_replace(' ', '', $text)));// str_replace(' ', '%', $text);
        $searchOptions['_fshop_products.Status'] = 'REMOVED:!=';
        if (isset($req->get['limit'])) {
            $searchOptions['limit'] = $req->get['limit'];
        }
        if (isset($req->get['page'])) {
            $searchOptions['page'] = $req->get['page'];
        }
        // var_dump($searchOptions);
        return API::getAPI('shop:products')->getProducts_List($searchOptions);
    }

}

?>
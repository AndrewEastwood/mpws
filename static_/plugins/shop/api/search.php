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
            $resp = $this->search($req->get['text']);
        }
    }

    public function search ($text) {
        if (empty($text)) {
            return null;
        }
        $searchOptions['_pSearchText'] = $text;
        return API::getAPI('shop:products')->getProducts_List($searchOptions);
    }

}

?>
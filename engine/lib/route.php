<?php

namespace engine\lib;

class route {

    function __construct() {
    }

    public function if ($path, $callback) {

    }

    // this is how it might be used in any api
    // public function route ($route, &$resp, $req) {
    //     $route->if('get:/system/customers/', function () {

    //     });
    // }
    // RewriteRule ^api/(.*) /engine/controllers/api.php?route=$1 [QSA,L]

}

?>
<?php
namespace web\plugin\system\api;

class address {

    var $shared = null;

    function __construct() {
        $this->shared = new shared();
    }
}

?>
<?php
namespace web\plugin\system\api;

class account {

    var $shared = null;

    function __construct() {
        $this->shared = new shared();
    }
}

?>
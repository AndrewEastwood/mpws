<?php

namespace engine\lib;

class data {

    var $db;

    function __construct () {
        global $app;
        $this->db = $app->getDB();
    }

}

?>
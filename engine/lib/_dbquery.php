<?php
namespace engine\lib;

use \engine\lib\request as Request;
use \engine\lib\response as Response;
use \engine\lib\utils as Utils;

class dbquery {

    function __construct($table) {
        $this->table = $table;
    }

    public function setData () {

        return $this;
    }

    public function setOrder () {

        return $this;
    }

    public function addJoin () {

        return $this;
    }
    public function removeJoin () {

        return $this;
    }

    public function setLimits ($offset = 0, $limit = 30) {

        return $this;
    }

    public function setFields () {

        return $this;
    }

    public function addCondition () {

        return $this;
    }

    public function removeConditionByFieldName () {

        return $this;
    }

    public function getQuery () {
        $query = array();
        return $query;
    }

    public function exec () {
        global $app;
        return $app->getDB()->query($this->getQuery());
    }

}

?>
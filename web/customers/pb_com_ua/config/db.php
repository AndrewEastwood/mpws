<?php
namespace web\customers\pb_com_ua\config;

class db extends \web\base\atlantis\config\db {

    function __construct () {
        $this->connection['db'] = "mpws_light";
        $this->connection['connection_string'] = "mysql:dbname=mpws_light;host=localhost;charset=utf8";
    }

}

?>
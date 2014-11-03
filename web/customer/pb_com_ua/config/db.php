<?php
namespace web\customer\pb_com_ua\config;

class db extends \web\default\atlantis\config\data {

    function __construct () {
        $this->debug['ви'] = "mpws_light";
        $this->debug['connection_string'] = "mysql:dbname=mpws_light;host=localhost;charset=utf8";
    }

}

?>
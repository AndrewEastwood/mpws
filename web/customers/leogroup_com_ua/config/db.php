<?php
namespace web\customers\leogroup_com_ua\config;

class db extends \web\base\atlantis\config\db {

    function __construct () {
        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            $this->connection['db'] = "mpws_light";
            $this->connection['connection_string'] = "mysql:dbname=mpws_light;host=localhost;charset=utf8";
        } elseif ($_SERVER['SERVER_NAME'] === 'leogroup.com.ua' || $_SERVER['SERVER_NAME'] === 'toolbox.leogroup.com.ua') {
            $this->connection['username'] = "leogroup";
            $this->connection['password'] = "ukx8Y6tz";
            $this->connection['host'] = "localhost";
            $this->connection['db'] = "leogroup";
            $this->connection['connection_string'] = "mysql:dbname=leogroup;host=localhost;charset=utf8";
        }
    }

}

?>
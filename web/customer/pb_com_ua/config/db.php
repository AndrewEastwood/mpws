<?php
namespace web\customer\pb_com_ua\config;

class db extends \web\default\atlantis\config\data {

    function __construct () {
        $this->DEV['USER'] => 'root';
        $this->DEV['PWD'] => '1111';
        $this->DEV['DB'] => 'mpws_light';
        $this->DEV['STRING'] = sprintf("mysql:dbname=%s;host=%s;charset=%s", $this->DEV['DB'], $this->DEV['HOST'], $this->DEV['CHARSET']);
        $this->DEV['DBOini'] = array(
            "connection_string" => $this->DEV['STRING'],
            "id_column" => 'ID',
            "username" => $this->DEV['USER'],
            "password" => $this->DEV['PWD'],
            "driver_options" => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="STRICT_ALL_TABLES"',
                PDO::ATTR_AUTOCOMMIT => false,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            )
        );
    }

}

?>
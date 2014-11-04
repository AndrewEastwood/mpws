<?php
namespace web\base\atlantis\config;

use \engine\object\configuration as baseConfig;
use PDO;

class db extends baseConfig {

    public $debug = array(
        'host' => 'localhost',
        'username' => 'root',
        'password' => '1111',
        'db' => 'mpws_default',
        "id_column" => 'ID',
        'charset' => 'utf8',
        'connection_string' => "mysql:dbname=mpws_default;host=localhost;charset=utf8",
        "driver_options" => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="STRICT_ALL_TABLES"',
            PDO::ATTR_AUTOCOMMIT => false,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
        )
    );

    public $live = array(
        'host' => 'localhost',
        'username' => 'root',
        'password' => '1111',
        'db' => 'mpws_default',
        "id_column" => 'ID',
        'charset' => 'utf8',
        'connection_string' => "mysql:dbname=mpws_default;host=localhost;charset=utf8",
        "driver_options" => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="STRICT_ALL_TABLES"',
            PDO::ATTR_AUTOCOMMIT => false,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
        )
    );

    public function getConnectionParams ($isDebug) {
        if (is_bool($isDebug))
            return $isDebug ? $this->debug : $this->live;
        else if (is_string($isDebug))
            return $this->$isDebug;
    }

}

?>
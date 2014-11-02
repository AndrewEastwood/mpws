<?php

namespace web\default\atlantis\config;

use \engine\object\configuration as baseConfig;

class db extends baseConfig {

    var $DEV = array(
        'HOST' => 'localhost',
        'USER' => 'root',
        'PWD' => '1111',
        'DB' => 'mpws_default',
        'CHARSET' => 'utf8',
        'STRING' => sprintf("mysql:dbname=%s;host=%s", $this->$DEV['DB'], $this->$DEV['HOST']),
        'DBOini' => array()
    );

    var $PROD = array(
        'HOST' => '',
        'USER' => '',
        'PWD' => '',
        'DB' => '',
        'CHARSET' => 'utf8',
        'STRING' => sprintf("mysql:dbname=%s;host=%s", $this->$PROD['DB'], $this->$PROD['HOST']),
        'DBOini' => array()
    );

    public function getConnectionParams () {
        $env = MPWS_ENV;
        return $this->$env;
    }

}

?>
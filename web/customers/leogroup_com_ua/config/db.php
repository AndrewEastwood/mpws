<?php
namespace web\customers\leogroup_com_ua\config;

class db extends \web\base\atlantis\config\db {

    function __construct () {
        call_user_func_array(array($this, 'parent::__construct'), func_get_args());
        $metadata = $this->getCustomer()->getMetaData();
        // if ($_SERVER['SERVER_NAME'] === 'localhost') {
        //     $this->connection['db'] = $metadata['db_name'];
        //     $this->connection['connection_string'] = "mysql:dbname=mpws_light;host=localhost;charset=utf8";
        // } elseif ($this->getApp()->customerName() === 'leogroup_com_ua') {
            $this->connection['username'] = $metadata['db_user'];
            $this->connection['password'] = $metadata['db_pwd'];
            $this->connection['host'] = $metadata['db_host'];
            $this->connection['db'] = $metadata['db_name'];
            $this->connection['connection_string'] = "mysql:dbname=leogroup;host=localhost;charset=utf8";
        // }
    }

}

?>
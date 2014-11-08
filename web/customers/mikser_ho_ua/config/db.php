<?php
namespace web\customers\mikser_ho_ua\config;

class db extends \web\default\atlantis\config\db {

    function __construct () {
        $this->DEV['HOST'] => 'db2.ho.ua';
        $this->DEV['USER'] => 'mikser';
        $this->DEV['PWD'] => 'KL3fsa)(';
        $this->DEV['DB'] => 'mikser';
        $this->DEV['STRING'] = sprintf("mysql:dbname=%s;host=%s;charset=%s", $this->DEV['DB'], $this->DEV['HOST'], parent::$DEV['CHARSET']);
        $this->DEV['DBOini'] = array(
            "connection_string" => $this->DEV['STRING'],
            "id_column" => 'ID',
            "username" => $this->DEV['USER'],
            "password" => $this->DEV['PWD']
        );
    }

}

?>
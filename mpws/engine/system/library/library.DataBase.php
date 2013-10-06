<?php

class libraryDataBase {

    var $_config;

    public function __construct($config = false) {
        $this->_config = $config;
    }

    public function getDBO () {
        libraryORM::configure($this->_config);
        return libraryORM::mpwsInstance();
    }

}

?>
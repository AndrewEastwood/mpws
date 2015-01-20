<?php
namespace web\customers\pb_com_ua\config;

use engine\lib\utils as Utils;

class db extends \web\base\atlantis\config\db {

    function __construct () {
        call_user_func_array(array($this, 'parent::__construct'), func_get_args());
        $metadata = $this->getCustomer()->getMetaData();
        $this->connection['db'] = $metadata['db_name'];
        $this->connection['connection_string'] = "mysql:dbname=mpws_light;host=localhost;charset=utf8";
    }

}

?>
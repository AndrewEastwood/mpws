<?php
namespace static_\plugins\system\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class permissions extends API {

    private $_permissions = array();
    public function getPermissions () {
        return $this->_permissions;
    }
}

?>
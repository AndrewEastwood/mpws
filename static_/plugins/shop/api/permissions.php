<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class permissions {

    private $_permissions = array(
    	// PRODUCTS
    	'EDIT_PRODUCT', 'EDIT_ORIGIN', 'EDIT_CATEGORY',
    	'CREATE_PRODUCT', 'CREATE_ORIGIN', 'CREATE_CATEGORY',
    	// PROMO
    	'EDIT_PROMO', 'CREATE_PROMO',
    	// FEEDS
    	'EXPORT_XML', 'IMPORT_XLS', 'EXPORT_YAML'
    );
    public function getPermissions () {
        return $this->_permissions;
    }
}

?>
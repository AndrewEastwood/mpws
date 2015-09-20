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
    	// PRODUCTS, ORIGINS, CATEGORIES
    	'EDIT_PRODUCT', 'EDIT_ORIGIN', 'EDIT_CATEGORY',
    	'CREATE_PRODUCT', 'CREATE_ORIGIN', 'CREATE_CATEGORY',
        // ORDER
    	'EDIT_ORDER', 'CREATE_ORDER',
        // PROMO
        'EDIT_PROMO', 'CREATE_PROMO',
    	// FEEDS
    	'IMPORT_XLS', 'EXPORT_XML', 'EXPORT_XLS', 'EXPORT_YML', 'EXPORT_JSON',
        // MENU
        'MENU_CONTENT', 'MENU_ORDERS', 'MENU_PROMO', 'MENU_SETTINGS', 'MENU_FEEDS', 'MENU_REPORTS'
    );
    public function getPermissions () {
        return $this->_permissions;
    }
}

?>
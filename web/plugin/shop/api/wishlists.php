<?php
namespace web\plugin\shop\api;

use \engine\object\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use Exception;
use ArrayObject;

class wishlists extends \engine\object\api {

    private $_productsLimit = 10;
    private $_listKey_Wish = 'shop:wishList';
    private $_statuses = array(
        'ACTIVE','LOGISTIC_DELIVERING',
        'CUSTOMER_CANCELED','LOGISTIC_DELIVERED',
        'SHOP_CLOSED','SHOP_REFUNDED','NEW');

    public function getProductsLimit () {
        return $this->_productsLimit;
    }

    public function get (&$resp) {
        $resp = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
    }

    public function post (&$resp, $req) { 
        $resp = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        if (count($resp) >= $this->getProductsLimit()) {
            $resp['error'] = "ProductLimitExceeded";
            return;
        }
        if (isset($req->data['productID'])) {
            $productID = $req->data['productID'];
            if (!isset($resp[$productID])) {
                $product = $this->getAPI()->products->getProductByID($productID, false, false);
                $resp[$productID] = $product;
                $_SESSION[$this->_listKey_Wish] = $resp;
            }
        } else
            $resp['error'] = "MissedParameter_productID";
    }

    public function delete (&$resp, $req) {
        $resp = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        if (isset($req->get['productID'])) {
            $productID = $req->get['productID'];
            if ($productID === "*") {
                $resp = array();
            } elseif (isset($resp[$productID])) {
                unset($resp[$productID]);
            }
            $_SESSION[$this->_listKey_Wish] = $resp;
        }
    }

    private function productIsInWishList ($id) {
        $list = array();
        $this->get($list);
        return isset($list[$id]);
    }

}


?>
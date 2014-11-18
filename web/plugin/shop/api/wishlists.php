<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use Exception;
use ArrayObject;

class wishlists extends \engine\objects\api {

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
        $items = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        $resp = array_values($items);
    }

    public function post (&$resp, $req) { 
        $items = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        if (count($items) >= $this->getProductsLimit()) {
            $resp['error'] = "ProductLimitExceeded";
            return;
        }
        if (isset($req->data['productID'])) {
            $productID = $req->data['productID'];
            if (!isset($items[$productID])) {
                $product = $this->getAPI()->products->getProductByID($productID);
                $items[$productID] = $product;
                $_SESSION[$this->_listKey_Wish] = $items;
            }
            $resp = array_values($items);
        } else
            $resp['error'] = "MissedParameter_productID";
    }

    public function delete (&$resp, $req) {
        $items = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        if (isset($req->get['productID'])) {
            $productID = $req->get['productID'];
            if ($productID === "*") {
                $items = array();
            } elseif (isset($items[$productID])) {
                unset($items[$productID]);
            }
            $_SESSION[$this->_listKey_Wish] = $items;
            $resp = array_values($items);
        }
    }

    public function productIsInWishList ($id) {
        $items = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        return isset($items[$id]);
    }

}


?>
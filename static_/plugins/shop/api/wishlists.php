<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class wishlists extends API {

    private $_productsLimit = 10;
    private $_listKey_Wish = 'shop:wishList';
    private $_statuses = array(
        'ACTIVE','LOGISTIC_DELIVERING',
        'CUSTOMER_CANCELED','LOGISTIC_DELIVERED',
        'SHOP_CLOSED','SHOP_REFUNDED','NEW');

    public function getProductsLimit () {
        return $this->_productsLimit;
    }

    public function get ($req, $resp) {
        $items = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        $resp->setResponse(array_values($items));
    }

    public function post ($req, $resp) {
        $items = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        if (count($items) >= $this->getProductsLimit()) {
            $resp->setError('ProductLimitExceeded');
            return;
        }
        if (isset($req->data['productID'])) {
            $productID = $req->data['productID'];
            if (!isset($items[$productID])) {
                $product = $this->data->fetchSingleProductByID($productID);
                $items[$productID] = $product;
                $_SESSION[$this->_listKey_Wish] = $items;
            }
            $resp->setResponse(array_values($items));
        } else
            $resp->setError('MissedParameter_productID');
    }

    public function delete ($req, $resp) {
        $items = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        if (isset($req->get['productID'])) {
            $productID = $req->get['productID'];
            if ($productID === "*") {
                $items = array();
            } elseif (isset($items[$productID])) {
                unset($items[$productID]);
            }
            $_SESSION[$this->_listKey_Wish] = $items;
            $resp->setResponse(array_values($items));
        }
    }

    public function productIsInWishList ($id) {
        $items = isset($_SESSION[$this->_listKey_Wish]) ? $_SESSION[$this->_listKey_Wish] : array();
        return isset($items[$id]);
    }

}


?>
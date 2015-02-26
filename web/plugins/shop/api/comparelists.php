<?php
namespace web\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class comparelists {

    private $_productsLimit = 10;
    private $_listKey_Compare = 'shop:listCompare';

    public function getProductsLimit () {
        return $this->_productsLimit;
    }

    public function get (&$resp) {
        $items = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        $resp = array_values($items);
    }

    public function post (&$resp, $req) {
        $items = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        if (count($items) >= $this->getProductsLimit()) {
            $items['error'] = "ProductLimitExceeded";
            return;
        }
        if (isset($req->data['productID'])) {
            $productID = $req->data['productID'];
            if (!isset($items[$productID])) {
                $product = API::getAPI('shop:products')->getProductByID($productID);
                $items[$productID] = $product;
                $_SESSION[$this->_listKey_Compare] = $items;
            }
            $resp = array_values($items);
        } else
            $resp['error'] = "MissedParameter_productID";
    }

    public function delete (&$resp, $req) {
        $items = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        if (isset($req->get['productID'])) {
            $productID = $req->get['productID'];
            if ($productID === "*") {
                $items = array();
            } elseif (isset($items[$productID])) {
                unset($items[$productID]);
            }
            $_SESSION[$this->_listKey_Compare] = $items;
            $resp = array_values($items);
        }
    }

    public function productIsInCompareList ($id) {
        $items = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        return isset($items[$id]);
    }
}


?>
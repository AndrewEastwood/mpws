<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class comparelists extends API {

    private $_productsLimit = 10;
    private $_listKey_Compare = 'shop:listCompare';

    public function getProductsLimit () {
        return $this->_productsLimit;
    }

    public function get (&$resp) {
        $items = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        $resp->setResponse(array_values($items));
    }

    public function post ($req, $resp) {
        $items = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        if (count($items) >= $this->getProductsLimit()) {
            $items['error'] = "ProductLimitExceeded";
            return;
        }
        if (isset($req->data['productID'])) {
            $productID = $req->data['productID'];
            if (!isset($items[$productID])) {
                $product = $this->data->fetchSingleProductByID($productID);
                $items[$productID] = $product;
                $_SESSION[$this->_listKey_Compare] = $items;
            }
            $resp->setResponse(array_values($items));
        } else
            $resp->setError('MissedParameter_productID');
    }

    public function delete ($req, $resp) {
        $items = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        if (isset($req->get['productID'])) {
            $productID = $req->get['productID'];
            if ($productID === "*") {
                $items = array();
            } elseif (isset($items[$productID])) {
                unset($items[$productID]);
            }
            $_SESSION[$this->_listKey_Compare] = $items;
            $resp->setResponse(array_values($items));
        }
    }

    public function productIsInCompareList ($id) {
        $items = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        return isset($items[$id]);
    }
}


?>
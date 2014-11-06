<?php
namespace web\plugin\shop\api;

use \engine\object\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use Exception;
use ArrayObject;

class delivery extends \engine\object\api {

    private $_productsLimit = 10;
    private $_listKey_Compare = 'shop:listCompare';

    public function getProductsLimit () {
        return $this->_productsLimit;
    }

    public function get (&$resp) {
        $resp = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        // $resp['limit'] = 10;
    }

    public function post (&$resp, $req) {
        $resp = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        if (count($resp) >= $this->getProductsLimit()) {
            $resp['error'] = "ProductLimitExceeded";
            return;
        }
        if (isset($req->data['productID'])) {
            $productID = $req->data['productID'];
            if (!isset($resp[$productID])) {
                $product = $this->getAPI()->products->getProductByID($productID, false, false);
                $resp[$productID] = $product;
                $_SESSION[$this->_listKey_Compare] = $resp;
            }
        }
    }

    public function delete (&$resp, $req) {
        $resp = isset($_SESSION[$this->_listKey_Compare]) ? $_SESSION[$this->_listKey_Compare] : array();
        if (isset($req->get['productID'])) {
            $productID = $req->get['productID'];
            if ($productID === "*") {
                $resp = array();
            } elseif (isset($resp[$productID])) {
                unset($resp[$productID]);
            }
            $_SESSION[$this->_listKey_Compare] = $resp;
        }
    }

    public function productIsInCompareList ($id) {
        $list = array();
        $this->get($list);
        return isset($list[$id]);
    }
}


?>
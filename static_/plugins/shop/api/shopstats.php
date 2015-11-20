<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class shopstats {

    public function get (&$resp, $req) {
        global $app;
        $self = $this;
        $sources = array();

        // $sources['orders_new'] = function ($req) use ($self) {
        //     return $self->getOrders_ListPending($req);
        // };
        $sources['orders_list_pending'] = function ($req) use ($self, $app) {
            $options = $app->getDB()->getDataListParamsFromRequest($req);
            return API::getAPI('shop:orders')->getOrders_ListPending($options);
        };
        $sources['orders_list_todays'] = function ($req) use ($self, $app) {
            $options = $app->getDB()->getDataListParamsFromRequest($req);
            return API::getAPI('shop:orders')->getOrders_ListTodays($options);
        };
        $sources['orders_list_expired'] = function ($req) use ($self, $app) {
            $options = $app->getDB()->getDataListParamsFromRequest($req);
            return API::getAPI('shop:orders')->getOrders_ListExpired($options);
        };
        $sources['orders_intensity_last_month'] = function ($req) use ($self) {
            $res = array();
            $res['OPEN'] = API::getAPI('shop:orders')->getStats_OrdersIntensityAliveLastMonth();
            $res['CLOSED'] = API::getAPI('shop:orders')->getStats_OrdersIntensityClosedLastMonth();
            return $res;
        };
        $sources['overview_orders'] = function () use ($self) {
            return API::getAPI('shop:orders')->getStats_OrdersOverview();
        };
        $sources['overview_products'] = function () use ($self) {
            return API::getAPI('shop:products')->getStats_ProductsOverview();
        };
        $sources['products_list_popular'] = function () use ($self) {
            $res = array();
            $res['items'] = API::getAPI('shop:products')->getProductsArray_TopPopular();
            return $res;
        };
        $sources['products_list_non_popular'] = function () use ($self) {
            $res = array();
            $res['items'] = API::getAPI('shop:products')->getProductsArray_TopNonPopular();
            return $res;
        };
        $sources['products_intensity_last_month'] = function () use ($self) {
            $res = array();
            $res['ACTIVE'] = API::getAPI('shop:products')->getStats_ProductsIntensityActiveLastMonth();
            $res['PREORDER'] = API::getAPI('shop:products')->getStats_ProductsIntensityPreorderLastMonth();
            $res['DISCOUNT'] = API::getAPI('shop:products')->getStats_ProductsIntensityDiscountLastMonth();
            return $res;
        };

        $type = false;
        if (!empty($req->get['type'])) {
            $type = $req->get['type'];
        }

        if (isset($sources[$type]))
            $resp = $sources[$type]($req);
        else
            $resp['error'] = 'WrongType';
    }
}

?>
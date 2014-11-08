<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use Exception;
use ArrayObject;

class shopstats extends \engine\objects\api {

    public function get (&$resp, $req) {

        $self = $this;
        $sources = array();
        // $sources['orders_new'] = function ($req) use ($self) {
        //     return $self->getOrders_ListPending($req);
        // };
        $sources['orders_list_pending'] = function ($req) use ($self) {
            return $self->getAPI()->orders->getOrders_ListPending($req->get);
        };
        $sources['orders_list_todays'] = function ($req) use ($self) {
            return $self->getAPI()->orders->getOrders_ListTodays($req->get);
        };
        $sources['orders_list_expired'] = function ($req) use ($self) {
            return $self->getAPI()->orders->getOrders_ListExpired($req->get);
        };
        $sources['orders_intensity_last_month'] = function ($req) use ($self) {
            $res = array();
            $res['OPEN'] = $self->getAPI()->orders->getStats_OrdersIntensityLastMonth('SHOP_CLOSED', '!=');
            $res['CLOSED'] = $self->getAPI()->orders->getStats_OrdersIntensityLastMonth('SHOP_CLOSED');
            return $res;
        };
        $sources['overview_orders'] = function () use ($self) {
            return $self->getAPI()->orders->getStats_OrdersOverview();
        };
        $sources['overview_products'] = function () use ($self) {
            return $self->getAPI()->products->getStats_ProductsOverview();
        };
        $sources['products_list_popular'] = function () use ($self) {
            $res = array();
            $res['items'] = $self->getAPI()->products->getProducts_TopPopular();
            return $res;
        };
        $sources['products_list_non_popular'] = function () use ($self) {
            $res = array();
            $res['items'] = $self->getAPI()->products->getProducts_TopNonPopular();
            return $res;
        };
        $sources['products_intensity_last_month'] = function () use ($self) {
            $res = array();
            $res['ACTIVE'] = $self->getAPI()->products->getStats_ProductsIntensityLastMonth('ACTIVE');
            $res['PREORDER'] = $self->getAPI()->products->getStats_ProductsIntensityLastMonth('PREORDER');
            $res['DISCOUNT'] = $self->getAPI()->products->getStats_ProductsIntensityLastMonth('DISCOUNT');
            return $res;
        };

        $type = false;
        if (!empty($req->get['type']))
            $type = $req->get['type'];

        if (isset($sources[$type]))
            $resp = $sources[$type]($req);
        else
            $resp['error'] = 'WrongType';
    }
}


?>
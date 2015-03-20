define([
    'jquery',
    'underscore',
    'backbone',
    'cachejs',
    'auth',
    'plugins/shop/site/js/model/order',
    'plugins/shop/site/js/view/listProductLatest',
    'plugins/shop/site/js/view/categoryNavigation'
    'plugins/shop/common/js/model/setting'
], function ($, _, Backbone, Cache, Auth, ModelSiteOrder,
     ViewProductsLatest, ViewCatNav, PluginSettings) {

    var PluginJS = function () {

        var order = new ModelSiteOrder({
            ID: "temp"
        });
        var settings = new PluginSettings();

        // why it's here?
        order.url = APP.getApiLink({
            source: 'shop',
            fn: 'orders'
        });

        var $dfdSettings = settings.fetch();

        order.fetch();

        this.getOffers = getOffers;
        this.getLatestProducts = getLatestProducts;
        this.getCategoryTree = getCategoryTree;

        function getOffers () {

        }

        function getLatestProducts () {
            var v = new ViewProductsLatest();
            v.collection.fetch({
                reset: true
            });
            return v;
        }

        function getFeaturedProducts () {

        }

        function getOnSaleProducts () {

        }

        function getLastViewedProducts () {

        }

        function getCategoryTree () {
            var v = new ViewCatNav();
            v.model.fetch({
                reset: true
            });
            return v;
        }

        function getSubCategoriesByCategoryID (id) {

        }

        function getCatalogProducts (categoryid, pageid) {

        }

        function getProductByID (id) {

        }

        function getWishList () {

        }

        function getCompareList () {

        }

        function getCartList () {

        }

        function checkout () {

        }

        function trackOrder (id) {

        }
    };





    return PluginJS;
});
define([
    'jquery',
    'underscore',
    'backbone',
    'cachejs',
    'auth',
    'plugins/shop/site/js/model/order',
    'plugins/shop/site/js/view/siteMenu',
    'plugins/shop/site/js/view/siteWidgets',
    'plugins/shop/common/js/model/setting'
], function ($, _, Backbone, Cache, Auth, SiteOrder, SiteMenu, SiteWidgets, SiteSettings) {

    var PluginJS = function () {

        var order = new SiteOrder({
            ID: "temp"
        });
        var settings = new SiteSettings();

        // why it's here?
        order.url = APP.getApiLink({
            source: 'shop',
            fn: 'orders'
        });

        var $dfdSettings = settings.fetch();

        order.fetch();



        this.getOffers = getOffers;

        function getOffers () {

        }

        function getLatestProducts () {

        }

        function getFeaturedProducts () {

        }

        function getOnSaleProducts () {

        }

        function getLastViewedProducts () {

        }

        function getCategoryTree () {

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
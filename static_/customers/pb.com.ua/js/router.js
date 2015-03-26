define([
    'jquery',
    'underscore',
    './view/breadcrumb'
], function ($, _) {

    // var _customerOptions = {};


    // function CustomerClass () {}

    // CustomerClass.prototype.renderProxy = function () {
    //     // console.log('customer renderProxy');
    //     // console.log(arguments);
    //     return true;
    // }
    var shopRoutes = {
        // '!/': 'home',
        '!/catalog/:category': 'shopCatalogCategory',
        '!/catalog/:category/:page': 'shopCatalogCategoryPage',
        '!/catalog/': 'shopCatalog',
        '!/product/:product': 'shopProduct',
        '!/cart': 'shopCart',
        '!/wishlist': 'shopWishlist',
        '!/compare': 'shopCompare',
        '!/tracking/(:id)': 'shopTracking'
        // "!/shop/profile/orders": "shop_profile_orders"
    };

    var Router = Backbone.Router.extend({

        name: 'pb.com.ua',

        settings: {
            title: APP.config.TITLE,
            logoImageUrl: APP.config.URL_PUBLIC_LOGO
        },

        routes: _.extend.apply(_, [
            {
                '!/home': 'home'
            },
            shopRoutes
        ]),

        plugins: {},

        initialize: function () {

            var that = this;

            this.on('app:ready', function () {

                that.plugins.shop = APP.initPlugin('shop', {
                    urls: _(shopRoutes).invert()
                });

                $('ul.js-mainnav').append(this.plugins.shop.menuItemCart().$el);
                $('ul.js-mainnav').append(this.plugins.shop.menuItemPopupInfoPayment().$el);
                $('ul.js-mainnav').append(this.plugins.shop.menuItemPopupInfoWarranty().$el);
                $('ul.js-mainnav').append(this.plugins.shop.menuItemPopupInfoShipping().$el);
            });

            APP.Sandbox.eventSubscribe('global:page:index', function () {
                that.home();
            });

            // configure titles and brand images
            $('head title').text(this.settings.title);
            $('#site-logo-ID').attr({
                src: this.settings.logoImageUrl,
                title: this.settings.title,
                itemprop: 'logo'
            });
            $('.navbar-brand').removeClass('hide');
            // var breadcrumb = new Breadcrumb();
            // breadcrumb.render();

            // add banner image
            var $banner = $('<div>').addClass('banner-decor');
            $('.MPWSBannerHeaderTop').append($banner);
        },
        shopCart: function () {

        },
        home: function () {
            // debugger
        }

    });

    return Router;


    // CustomerClass.prototype.setBreadcrumb = function (options) {
        // breadcrumb.render(options);
        // APP.Sandbox.eventNotify('global:content:render', {
        //     name: 'CommonBreadcrumbTop',
        //     el: breadcrumb.$el.clone()
        // });
        // APP.Sandbox.eventNotify('global:content:render', {
        //     name: 'CommonBreadcrumbBottom',
        //     el: breadcrumb.$el.clone()
        // });
    // }

    // return CustomerClass;

});
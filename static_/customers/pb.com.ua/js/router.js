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

    APP.configurePlugins({
        shop: {
            urls: _(shopRoutes).invert()
        }
    });

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

        getPlugin: function (name) {
            return this.plugins[name] || null;
        },

        setPlugin: function (plugin) {
            if (!plugin || !plugin.name)
                throw 'wrong plugin object. plugin is ' + (typeof plugin);
            this.plugins[plugin.name] = plugin;
        },

        initialize: function () {

            var that = this;

            this.on('app:ready', function () {

                that.setPlugin(APP.getPlugin('shop'));

                // menu items
                $('.mpws-js-menu-cart').html(that.plugins.shop.menuItemCart().$el);
                $('.mpws-js-menu-payment').html(that.plugins.shop.menuItemPopupInfoPayment().$el);
                $('.mpws-js-menu-warranty').html(that.plugins.shop.menuItemPopupInfoWarranty().$el);
                $('.mpws-js-menu-shipping').html(that.plugins.shop.menuItemPopupInfoShipping().$el);
                $('.mpws-js-menu-compare').html(that.plugins.shop.menuItemCompareList().$el);
                $('.mpws-js-menu-wishlist').html(that.plugins.shop.menuItemWishList().$el);

                // widgets
                $('.mpws-js-shop-addresses').html(that.plugins.shop.widgetAddresses().$el);
                $('.mpws-js-cart-embedded').html(that.plugins.shop.widgetCartButton().$el);
                $('.mpws-js-top-nav-right').html($('<li>').addClass('dropdown').html(that.plugins.shop.widgetExchangeRates().$el));
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
            $('.mpws-js-catalog-tree').html(this.plugins.shop.widgetCatalogBar().$el);
            $('.mpws-js-main-section').html(this.plugins.shop.latestProducts().$el);
        },
        shopProduct: function (id) {
            // debugger
            $('.mpws-js-main-section').html(this.plugins.shop.shopProduct(id).$el);
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
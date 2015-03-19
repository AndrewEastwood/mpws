define([
    'jquery',
    'underscore',
    'backbone',
    'plugins/shop/site/plugin',
    'echo',
    './view/breadcrumb',
], function ($, _, Backbone, plgShop, echo) {

    var _customerOptions = {};

    _customerOptions.site = {
        title: APP.config.TITLE,
        logoImageUrl: APP.config.URL_PUBLIC_LOGO
    };

    // configure titles and brand images
    $('head title').text(_customerOptions.site.title);
    $('#site-logo-ID').attr({
        src: _customerOptions.site.logoImageUrl,
        title: _customerOptions.site.title,
        itemprop: 'logo'
    });
    $('.navbar-brand').removeClass('hide');
    // var breadcrumb = new Breadcrumb();
    // breadcrumb.render();

    // add banner image
    var $banner = $('<div>').addClass('banner-decor');
    $('.MPWSBannerHeaderTop').append($banner);


    var routes = {
        "!/shop": "home",
        "!/shop/catalog/:category": "shop_catalog_category",
        "!/shop/catalog/:category/:page": "shop_catalog_category_page",
        "!/shop/catalog/": "shop_catalog",
        "!/shop/product/:product": "shop_product",
        "!/shop/cart": "shop_cart",
        "!/shop/wishlist": "shop_wishlist",
        "!/shop/compare": "shop_compare",
        "!/shop/tracking/(:id)": "shop_tracking"
        // "!/shop/profile/orders": "shop_profile_orders"
    };



    var Router = Backbone.Router.extend({
        routes: routes,

        urls: _(routes).invert(),

    });

    function CustomerClass () {}

    CustomerClass.prototype.setBreadcrumb = function (options) {
        // breadcrumb.render(options);
        // APP.Sandbox.eventNotify('global:content:render', {
        //     name: 'CommonBreadcrumbTop',
        //     el: breadcrumb.$el.clone()
        // });
        // APP.Sandbox.eventNotify('global:content:render', {
        //     name: 'CommonBreadcrumbBottom',
        //     el: breadcrumb.$el.clone()
        // });
    }

    echo.init({
        offset: 100,
        throttle: 250,
        unload: false,
        callback: function (element, op) {
            console.log(element, 'has been', op + 'ed')
        }
    });

    return CustomerClass;

});
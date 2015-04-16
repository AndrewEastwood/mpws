define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'echo',
    'bootstrap-dialog',
    // page templates
    // 'text!./../hbs/breadcrumb.hbs',
    // 'text!./../hbs/homeFrame.hbs',
    // 'text!./../hbs/productsTab.hbs',
    // 'text!./../hbs/viewedProducts.hbs',
    // 'text!./../hbs/page404.hbs',
    // 'text!./../hbs/catalogBrowser.hbs',
    // 'text!./../hbs/categoriesRibbon.hbs',
    // 'text!./../hbs/productComparisons.hbs',
    // 'text!./../hbs/productWishlist.hbs',
    // 'text!./../hbs/search.hbs',
    // 'owl.carousel',
    'bootstrap',
    'icheck',
    'jquery.sliphover'
], function ($, _, Backbone, Handlebars, echo, BootstrapDialog


 ) {

    var shopRoutes = {
        // '!/': 'home',
        '!/catalog/:category': 'shopCatalogCategory',
        '!/catalog/:category/:page': 'shopCatalogCategoryPage',
        '!/catalog/': 'home', //catalog
        '!/product/:product': 'shopProduct',
        '!/cart': 'shopCart',
        '!/wishlist': 'shopWishlist',
        '!/compare': 'shopCompare',
        '!/tracking/(:id)': 'shopTracking',
        '!/search/:text': 'shopSearch'
        // "!/shop/profile/orders": "shop_profile_orders"
    };

    APP.configurePlugins({
        shop: {
            urls: _(shopRoutes).invert()//,
            // productShortClassNames: 'no-margin product-item-holder hover'
        }
    });

    function onAppReady () {
        var $container = $('#container'),
            defaultOptions = {
                itemSelector : '.element',
                filter: '.element',
                sortBy: 'original-order',
                sortAscending: true,
                layoutMode: 'masonry'
            };
        $container.sliphover({
            target: '.slip',
            caption: 'alt'
        });
        var iso = new Isotope($container.get(0) , defaultOptions);
    }


    var Router = Backbone.Router.extend({

        name: 'demo.com.ua',
        
        routes: _.extend.apply(_, [
            {
                '': 'home',
                '!': 'home',
                '!/': 'home',
                ':whatever': 'page404'
            },
            shopRoutes
        ]),

        plugins: {},

        views: {},

        initialize: function () {
            var that = this;
            this.on('app:ready', function () {
                onAppReady();
                // menu items
                $('.mpws-js-menu-cart').html(that.plugins.shop.menuItemCart().$el);
                $('.mpws-js-menu-payment').html(that.plugins.shop.menuItemPopupInfoPayment().$el);
                $('.mpws-js-menu-warranty').html(that.plugins.shop.menuItemPopupInfoWarranty().$el);
                $('.mpws-js-menu-shipping').html(that.plugins.shop.menuItemPopupInfoShipping().$el);
                $('.mpws-js-menu-compare').html(that.plugins.shop.menuItemCompareList().$el);
                $('.mpws-js-menu-wishlist').html(that.plugins.shop.menuItemWishList().$el);


                var optionsCategoryMenu = {design: {className: 'nav navbar-nav'}};
                that.views.categoryHomeMenu = that.plugins.shop.catalogNavigator(optionsCategoryMenu);


                $('.mpws-js-catalog-tree').html(that.views.categoryHomeMenu.render().$el);
            });
        },

        home: function () {},

        page404: function () {}

    });

    return Router;



});
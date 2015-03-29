define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'echo',
    // page templates
    'text!./../hbs/breadcrumb.hbs',
    'text!./../hbs/leftNavAndBanner.hbs',
    'text!./../hbs/productsTab.hbs',
    'text!./../hbs/recentlyViewed.hbs',
    'text!./../hbs/footer.hbs',
    'text!./../hbs/productComparisons.hbs',
    'owl.carousel',
], function ($, _, Backbone, Handlebars, echo,
     tplBreadcrumb,
     tplLeftNavAndBanner,
     tplProductsTab,
     tplRecentlyViewed,
     tplFooter,
     tplProductComparisons) {

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
            urls: _(shopRoutes).invert(),
            productShortClassNames: 'no-margin product-item-holder hover'
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
                '': 'home',
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
        home: function () {
            var featuredProducts = this.plugins.shop.featuredProducts(),
                newProducts = this.plugins.shop.newProducts(),
                topProducts = this.plugins.shop.topProducts();

            featuredProducts.on('shop:rendered', initEchoJS);
            newProducts.on('shop:rendered', initEchoJS);
            topProducts.on('shop:rendered', initEchoJS);

            // set top category list with banner
            var $levtNavAndBanner = $(Handlebars.compile(tplLeftNavAndBanner)());
            $levtNavAndBanner.find('.mpws-js-catalog-tree').html(this.plugins.shop.widgetCatalogBar().$el);
            $('.mpws-js-main-section').html($levtNavAndBanner);
            // append products tab
            var $tplProductsTab = $(Handlebars.compile(tplProductsTab)());
            $tplProductsTab.find('.mpws-js-shop-products-featured').html(featuredProducts.$el);
            $tplProductsTab.find('.mpws-js-shop-products-new').html(newProducts.$el);
            $tplProductsTab.find('.mpws-js-shop-products-top').html(topProducts.$el);
            $('.mpws-js-main-section').append($tplProductsTab);
            // recently viewed
            var $tplRecentlyViewed = $(Handlebars.compile(tplRecentlyViewed)());
            // $tplProductsTab.find('.mpws-js-products-featured').html(this.plugins.shop.featuredProducts().$el);
            $('.mpws-js-main-section').append($tplRecentlyViewed);
            var $owlEl = $('#owl-recently-viewed', $tplRecentlyViewed);
            $owlEl.owlCarousel({
                stopOnHover: true,
                rewindNav: false,
                items: 6,
                pagination: false,
                loop: true,
                dots: false,
                itemsTablet: [768,3]
            });

            $(".slider-next", $tplRecentlyViewed).click(function () {
                $owlEl.trigger('next.owl.carousel', [1500]);
            });
            
            $(".slider-prev", $tplRecentlyViewed).click(function () {
                $owlEl.trigger('prev.owl.carousel', [1500]);
            });

            this.addCommonFooter();
        },
        shopCart: function () {
            $('.mpws-js-main-section').html(this.plugins.shop.cart().$el);

            this.addCommonFooter();
        },
        shopProduct: function (id) {
            var breadcrumb = Handlebars.compile(tplBreadcrumb)();
            $('.mpws-js-main-section').html(breadcrumb);
            $('.mpws-js-main-section').append(this.plugins.shop.product(id).$el);

            this.addCommonFooter();
        },
        shopWishlist: function () {
            $('.mpws-js-main-section').html(this.plugins.shop.wishlist().$el);

            this.addCommonFooter();
        },
        shopCompare: function () {
            var breadcrumb = $(Handlebars.compile(tplBreadcrumb)());
            $('.mpws-js-main-section').html(breadcrumb);
            var pageContent = $(Handlebars.compile(tplProductComparisons)());
            $('.mpws-js-main-section').append(pageContent);
            $('.mpws-js-main-section').find('.mpws-js-product-comparisons').html(this.plugins.shop.compare().$el);

            this.addCommonFooter();
        },


        addCommonFooter: function () {
            // adding footer
            var $tplFooter = $(Handlebars.compile(tplFooter)()),
                productOptions = {limit: 5, design: {asList: true, style: 'minimal', wrap: '<div class="row"></div>'}};
            $tplFooter.find('.mpws-js-shop-new-products-minimal-list').html(this.plugins.shop.newProducts(productOptions).$el);
            $tplFooter.find('.mpws-js-shop-onsale-products-minimal-list').html(this.plugins.shop.onSaleProducts(productOptions).$el);
            $tplFooter.find('.mpws-js-shop-top-products-minimal-list').html(this.plugins.shop.topProducts(productOptions).$el);
            $('.mpws-js-main-footer').html($tplFooter);
        }

    });


    function initEchoJS () {
        echo.init({
            offset: 100,
            throttle: 250,
            callback: function(element, op) {
                if(op === 'load') {
                    element.classList.add('loaded');
                } else {
                    element.classList.remove('loaded');
                }
            }
        });
    }

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
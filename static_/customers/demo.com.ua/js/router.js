define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'echo',
    'bootstrap-dialog',
    'isotope',
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
    'owl.carousel',
    'bootstrap',
    'icheck',
    'jquery.sliphover',
    'jquery.bridget'
], function ($, _, Backbone, Handlebars, echo, BootstrapDialog, Isotope

 ) {

    $.bridget('isotope', Isotope);

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
            urls: _(shopRoutes).invert()
        }
    });

    function filterLayoutElements (filter) {
        // debugger
        var $container = $('#container'),
            defaultOptions = {
                itemSelector : '.element',
                filter: filter,
                sortBy: 'original-order',
                sortAscending: true,
                layoutMode: 'masonry'
            };
        $container.sliphover({
            target: '.slip',
            caption: 'alt'
        });
        $container.isotope(defaultOptions);
    }


    var Router = Backbone.Router.extend({

        name: 'demo.com.ua',
        
        routes: _.extend.apply(_, [
            {
                '': 'home',
                '!': 'home',
                '!/': 'home',
                '!/contacts': 'contacts',
                '!/info': 'info',
                ':whatever': 'page404'
            },
            shopRoutes
        ]),

        plugins: {},

        views: {},

        address: {},

        initialize: function () {
            var that = this;
            this.on('app:ready', function () {
                filterLayoutElements('.home');
                // menu items
                $('.mpws-js-menu-cart').html(that.plugins.shop.menuItemCart().$el);
                $('.mpws-js-menu-payment').html(that.plugins.shop.menuItemPopupInfoPayment().$el);
                $('.mpws-js-menu-warranty').html(that.plugins.shop.menuItemPopupInfoWarranty().$el);
                $('.mpws-js-menu-shipping').html(that.plugins.shop.menuItemPopupInfoShipping().$el);
                $('.mpws-js-menu-compare').html(that.plugins.shop.menuItemCompareList().$el);
                $('.mpws-js-menu-wishlist').html(that.plugins.shop.menuItemWishList().$el);


                var optionsCategoryMenu = {design: {className: 'nav navbar-nav'}};
                that.views.categoryHomeMenu = that.plugins.shop.catalogNavigator(optionsCategoryMenu);

                that.address = that.plugins.shop.widgetAddresses();

                // info data
                $('.mpws-js-info-shipping').html(that.address.$infoShipping);
                $('.mpws-js-info-payment').html(that.address.$infoPayment);
                $('.mpws-js-info-warranty').html(that.address.$infoWarranty);
                $('.mpws-js-info-contacts').html(that.address.render().$el);

                // footer
                $('.mpws-js-link-social-twitter').html(that.address.$linkTwitter);
                $('.mpws-js-link-social-facebook').html(that.address.$linkFacebook);
                $('.mpws-js-link-social-googleplus').html(that.address.$linkGooglePlus);

                $('.mpws-js-addressline-footer').html(that.address.$addressLine);
                $('.mpws-js-copyright').html(that.address.$copy);

                // widgets
                $('.mpws-js-catalog-tree').html(that.views.categoryHomeMenu.render().$el);
                $('.mpws-js-widget-cart').html(that.plugins.shop.cart().$el);
            });
        },


        home: function () {
            filterLayoutElements('.home');
        },

        page404: function () {},

        info: function () {
            // var iso = new Isotope($('#container').get(0) , {filter: '.info'});
            filterLayoutElements('.info');
            // debugger
        },

        contacts: function () {
            filterLayoutElements('.contacts');
        },

        shopCart: function () {
            filterLayoutElements('.shop-cart');
        },

        shopCatalogCategory: function (category) {
            filterLayoutElements('.shop-catalog');
            this.shopCatalogCategoryPage(category);
        },
        shopCatalogCategoryPage: function (category, pageNo) {
            filterLayoutElements('.shop-catalog');

            var catalogFilterView = this.plugins.shop.catalogFilterPanel(category, pageNo),
                catalogBrowseView = this.plugins.shop.catalogBrowseContent();

            catalogFilterView.on('render:complete', function () {
                var $filterCheckBoxes = $('.mpws-js-category-filter .list-group-item input[type="checkbox"]');
                $filterCheckBoxes.iCheck({
                    checkboxClass: 'icheckbox_minimal-aero shop-filter-checkbox',
                    radioClass: 'iradio_minimal-red'
                }).on('ifChanged', function (event) { $(event.target).trigger('change'); });
                initEchoJS();
                APP.setPageAttributes(catalogFilterView.getPageAttributes());
            });

            $('.mpws-js-category-filter').html(catalogFilterView.$el);
            $('.mpws-js-catalog-products').addClass('product-grid-holder').html(catalogBrowseView.$el);
        },
        shopProduct: function (id) {
            filterLayoutElements('.shop-product');
            var that = this,
                productView = this.plugins.shop.product(id);
            productView.on('render:complete', function () {
                initEchoJS();
                APP.setPageAttributes(productView.getPageAttributes());
            });
            $('.mpws-js-product').html(productView.$el);
        },
    });



    function initEchoJS () {
        echo.init({
            offset: 100,
            throttle: 250,
            callback: function(element, op) {
                console.log(op)
                if(op === 'load') {
                    element.classList.add('loaded');
                } else {
                    element.classList.remove('loaded');
                }
            }
        });
    }
    return Router;



});
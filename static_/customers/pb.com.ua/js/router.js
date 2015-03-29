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
    'text!./../hbs/viewedProducts.hbs',
    'text!./../hbs/page404.hbs',
    'text!./../hbs/categoriesTopNav.hbs',
    'text!./../hbs/productComparisons.hbs',
    'owl.carousel',
], function ($, _, Backbone, Handlebars, echo,
     tplBreadcrumb,
     tplLeftNavAndBanner,
     tplProductsTab,
     tplViewedProducts,
     tplPage404,
     tplCategoriesTopNav,
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
                '!': 'home',
                '!/': 'home',
                ':whatever': 'page404'
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
            var productOptions = {design: {className: 'col-sm-4 col-md-3 no-margin product-item-holder hover', pageSize: 4}},
                categoryOptions = {design: {className: 'nav'}},
                featuredProducts = this.plugins.shop.featuredProducts(productOptions),
                newProducts = this.plugins.shop.newProducts(productOptions),
                topProducts = this.plugins.shop.topProducts(productOptions),
                viewedProducts = this.plugins.shop.viewedProducts({design: {className: 'no-margin carousel-item product-item-holder size-small hover'}}),
                categoryTopLevelList = this.plugins.shop.categoryList({design: {style: 'toplevel', className: 'dropdown-menu'}}),
                categoryMenu = this.plugins.shop.categoryList(categoryOptions);


            var $tplLeftNavAndBanner = $(Handlebars.compile(tplLeftNavAndBanner)());
            var $tplProductsTab = $(Handlebars.compile(tplProductsTab)());
            var $tplViewedProducts = $(Handlebars.compile(tplViewedProducts)());

            // show more buttons selectors
            var $btnShowMoreFeatured = $tplProductsTab.find('#mpws-js-shop-tab-products-featured .btn-loadmore');
            var $btnShowMoreNew = $tplProductsTab.find('#mpws-js-shop-tab-products-new .btn-loadmore');
            var $btnShowMoreTop = $tplProductsTab.find('#mpws-js-shop-tab-products-top .btn-loadmore');
            function hideShowMoreButton ($btn) {
                return function () {
                    $($btn).addClass('hidden');
                }
            }
            function displayShowMoreButton ($btn) {
                return function () {
                    $($btn).removeClass('hidden');
                }
            }

            // display show more buttons
            displayShowMoreButton($btnShowMoreFeatured);
            displayShowMoreButton($btnShowMoreNew);
            displayShowMoreButton($btnShowMoreTop);

            // init image loading
            featuredProducts.on('shop:rendered', initEchoJS);
            newProducts.on('shop:rendered', initEchoJS);
            topProducts.on('shop:rendered', initEchoJS);

            // hide showmore button when all products are visible
            featuredProducts.on('shop:allvisible', hideShowMoreButton($btnShowMoreFeatured));
            newProducts.on('shop:allvisible', hideShowMoreButton($btnShowMoreNew));
            topProducts.on('shop:allvisible', hideShowMoreButton($btnShowMoreTop));

            // hide showmore button when no products
            featuredProducts.on('shop:emptylist', hideShowMoreButton($btnShowMoreFeatured));
            newProducts.on('shop:emptylist', hideShowMoreButton($btnShowMoreNew));
            topProducts.on('shop:emptylist', hideShowMoreButton($btnShowMoreTop));

            viewedProducts.on('shop:rendered', function () {
                $tplViewedProducts.removeClass('hidden');
                // recently viewed
                $tplViewedProducts.find('.mpws-js-shop-viewed-products').html(viewedProducts.$el.children());
                initEchoJS();
                // init some js
                var $owlEl = $('#owl-recently-viewed', $tplViewedProducts);
                $owlEl.owlCarousel({
                    stopOnHover: true,
                    rewindNav: false,
                    items: 6,
                    pagination: false,
                    loop: true,
                    dots: false,
                    itemsTablet: [768,3]
                });

                $(".slider-next", $tplViewedProducts).click(function () {
                    $owlEl.trigger('next.owl.carousel', [1500]);
                });
                
                $(".slider-prev", $tplViewedProducts).click(function () {
                    $owlEl.trigger('prev.owl.carousel', [1500]);
                });
            });
            viewedProducts.on('shop:emptylist', function () {
                $tplViewedProducts.addClass('hidden');
            });

            // cleanup unnecessary components
            $('.mpws-js-shop-categories-topnav').empty();

            // set top category list with banner
            $tplLeftNavAndBanner.find('.mpws-js-catalog-tree').html(categoryMenu.render().$el);
            // append products tab
            $tplProductsTab.find('.mpws-js-shop-products-featured').html(featuredProducts.$el);
            $tplProductsTab.find('.mpws-js-shop-products-new').html(newProducts.$el);
            $tplProductsTab.find('.mpws-js-shop-products-top').html(topProducts.$el);
            $tplProductsTab.on('click', '#mpws-js-shop-tab-products-featured .btn-loadmore', featuredProducts.revealNextPage);
            $tplProductsTab.on('click', '#mpws-js-shop-tab-products-new .btn-loadmore', newProducts.revealNextPage);
            $tplProductsTab.on('click', '#mpws-js-shop-tab-products-top .btn-loadmore', topProducts.revealNextPage);
            // searchbox categories
            $('header li.mpws-js-shop-categories-toplist').append(categoryTopLevelList.render().$el);

            $('.mpws-js-main-section').html($tplLeftNavAndBanner);
            $('.mpws-js-main-section').append($tplProductsTab);
            $('.mpws-js-main-section').append($tplViewedProducts);

            this.updateFooter();
        },
        shopCart: function () {
            $('.mpws-js-main-section').html(this.plugins.shop.cart().$el);

            this.updateFooter();
        },
        shopProduct: function (id) {
            var breadcrumb = Handlebars.compile(tplBreadcrumb)();
            $('.mpws-js-main-section').html(breadcrumb);
            $('.mpws-js-main-section').append(this.plugins.shop.product(id).$el);

            this.updateFooter();
        },
        shopWishlist: function () {
            $('.mpws-js-main-section').html(this.plugins.shop.wishlist().$el);

            this.updateFooter();
        },
        shopCompare: function () {
            var breadcrumb = $(Handlebars.compile(tplBreadcrumb)());
            $('.mpws-js-main-section').html(breadcrumb);
            var pageContent = $(Handlebars.compile(tplProductComparisons)());
            $('.mpws-js-main-section').append(pageContent);
            $('.mpws-js-main-section').find('.mpws-js-product-comparisons').html(this.plugins.shop.compare().$el);

            this.updateFooter();
        },


        updateFooter: function () {
            // adding footer
            var $tplFooter = $('footer.mpws-js-main-footer'),
                productOptions = {limit: 5, design: {asList: true, style: 'minimal', wrap: '<div class="row"></div>'}},
                newProducts = this.plugins.shop.newProducts(productOptions),
                onSaleProducts = this.plugins.shop.onSaleProducts(productOptions),
                topProducts = this.plugins.shop.topProducts(productOptions),
                categoryTopLevelList = this.plugins.shop.categoryList({design: {style: 'toplevel'}});

            $tplFooter.find('.mpws-js-shop-new-products-minimal-list').html(newProducts.$el);
            $tplFooter.find('.mpws-js-shop-onsale-products-minimal-list').html(onSaleProducts.$el);
            $tplFooter.find('.mpws-js-shop-top-products-minimal-list').html(topProducts.$el);
            $tplFooter.find('.mpws-js-shop-categories-toplist').html(categoryTopLevelList.$el);
        },

        page404: function () {
            var $tplPage404 = $(Handlebars.compile(tplPage404)()),
                $tplCategoriesTopNav = $(Handlebars.compile(tplCategoriesTopNav)()),
                categoryOptions = {design: {className: 'nav navbar-nav'}},
                categoryMenu = this.plugins.shop.categoryList(categoryOptions);

            $tplCategoriesTopNav.find('.mpws-js-catalog-tree').html(categoryMenu.render().$el);

            $('.mpws-js-shop-categories-topnav').html($tplCategoriesTopNav);
            $('.mpws-js-main-section').html($tplPage404);

            this.updateFooter();
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
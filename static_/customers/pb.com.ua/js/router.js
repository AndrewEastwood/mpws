define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'echo',
    // page templates
    'text!./../hbs/breadcrumb.hbs',
    'text!./../hbs/homeFrame.hbs',
    'text!./../hbs/productsTab.hbs',
    'text!./../hbs/viewedProducts.hbs',
    'text!./../hbs/page404.hbs',
    'text!./../hbs/categoriesRibbon.hbs',
    'text!./../hbs/productComparisons.hbs',
    'owl.carousel',
], function ($, _, Backbone, Handlebars, echo,
     tplBreadcrumb,
     tplHomeFrame,
     tplProductsTab,
     tplViewedProducts,
     tplPage404,
     tplCategoriesRibbon,
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


    var templatesCompiled = {
        homeFrame: $(Handlebars.compile(tplHomeFrame)()),
        categoriesRibbon: $(Handlebars.compile(tplCategoriesRibbon)()),
        breadcrumb: $(Handlebars.compile(tplBreadcrumb)()),
        viewedProducts: $(Handlebars.compile(tplViewedProducts)()),
        productComparisons: $(Handlebars.compile(tplProductComparisons)()),
        page404: $(Handlebars.compile(tplPage404)())
    };

    function getTemplate (key) {
        return function () {
            return templatesCompiled[key] && templatesCompiled[key].clone();
        }
    }

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

        elements: {},

        templates: {
            homeFrame: getTemplate('homeFrame'),
            categoriesRibbon: getTemplate('categoriesRibbon'),
            breadcrumb: getTemplate('breadcrumb'),
            viewedProducts: getTemplate('viewedProducts'),
            productComparisons: getTemplate('productComparisons'),
            page404: getTemplate('page404'),
        },

        views: {},

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

                // common elements


                // setup ribbon nav and breadcrumb
                var $tplBreadcrumb = that.templates.breadcrumb(),
                    $tplCategoriesRibbon = that.templates.categoriesRibbon(),
                    optionsCategoryMenu = {design: {className: 'nav navbar-nav'}},
                    optionsTopLevelList = {design: {style: 'toplevel', className: 'dropdown-menu'}};
                that.views.categoryMenu = that.plugins.shop.categoryList(optionsCategoryMenu),
                that.views.categoryTopLevelList = that.plugins.shop.categoryList(optionsTopLevelList);
                $('.mpws-js-breadcrumb').html($tplBreadcrumb);
                $('.mpws-js-shop-categories-ribbon').html($tplCategoriesRibbon);
                $tplCategoriesRibbon.find('.mpws-js-catalog-tree').html(that.views.categoryMenu.render().$el.clone());
                $tplCategoriesRibbon.find('.mpws-js-shop-categories-toplist').html(that.views.categoryTopLevelList.render().$el.clone());


                    // categoryListOptions = {design: {className: 'nav'}},
                    // categoryMenu = that.plugins.shop.categoryList(categoryListOptions)
                    // categoryTopLevelList = that.plugins.shop.categoryList({design: {style: 'toplevel', className: 'dropdown-menu'}})
                // set top category list with banner
                
                // setup home fame
                var $tplHomeFrame = that.templates.homeFrame();
                $tplHomeFrame.find('.mpws-js-catalog-tree').html(that.views.categoryMenu.render().$el.clone());
                $('.mpws-js-main-home-frame').html($tplHomeFrame);

                // update searchbox categories
                $('header li.mpws-js-shop-categories-toplist').append(that.views.categoryTopLevelList.render().$el.clone());

                // ;
                // setup viewed products
                var $tplViewedProducts = that.templates.viewedProducts(),
                    $owlEl = $('.mpws-js-shop-viewed-products', $tplViewedProducts),
                    optionsViewedProducts = {design: {className: 'no-margin item carousel-item product-item-holder size-small hover'}};
                that.views.viewedProducts = that.plugins.shop.viewedProducts(optionsViewedProducts);
                that.views.viewedProducts.on('shop:rendered', function () {
                    console.log('viewed rendering');
                    $tplViewedProducts.removeClass('hidden');
                    // recently viewed
                    $owlEl.html(that.views.viewedProducts.$el.children());
                    // init some js
                    initEchoJS();
                    $owlEl.owlCarousel({
                        stopOnHover: true,
                        rewindNav: false,
                        items: 6,
                        pagination: false,
                        loop: true,
                        dots: false,
                        itemsTablet: [768,3],
                        responsive:{
                            0:{
                                items:1
                            },
                            600:{
                                items:3
                            },
                            1000:{
                                items:5
                            }
                        }
                    });

                    $(".slider-next", $tplViewedProducts).click(function () {
                        $owlEl.trigger('next.owl.carousel', [1500]);
                    });
                    
                    $(".slider-prev", $tplViewedProducts).click(function () {
                        $owlEl.trigger('prev.owl.carousel', [1500]);
                    });
                });
                that.views.viewedProducts.on('shop:emptylist', function () {
                    $tplViewedProducts.addClass('hidden');
                });
                $('.mpws-js-shop-viewed-products').html($tplViewedProducts);
                // $('.owl-carousel', $tplViewedProducts).owlCarousel({
                //     loop:true,
                //     margin:10,
                //     nav:true,
                //     responsive:{
                //         0:{
                //             items:1
                //         },
                //         600:{
                //             items:3
                //         },
                //         1000:{
                //             items:5
                //         }
                //     }
                // })

            });

            // APP.Sandbox.eventSubscribe('global:page:index', function () {
            //     that.home();
            // });

            // configure titles and brand images
            // $('head title').text(this.settings.title);
            // $('#site-logo-ID').attr({
            //     src: this.settings.logoImageUrl,
            //     title: this.settings.title,
            //     itemprop: 'logo'
            // });
            // $('.navbar-brand').removeClass('hide');

        },
        refreshViewedProducts: function () {
            this.views.viewedProducts.collection.fetch({reset: true});
        },
        home: function () {
            this.toggleCategoryRibbonAndBreadcrumb(false);
            this.toggleHomeFrame(true);
            this.refreshViewedProducts();
            this.updateFooter();



            var $tplProductsTab = $(Handlebars.compile(tplProductsTab)()),
                productOptions = {design: {className: 'col-sm-4 col-md-3 no-margin product-item-holder hover', pageSize: 4}},
                featuredProducts = this.plugins.shop.featuredProducts(productOptions),
                newProducts = this.plugins.shop.newProducts(productOptions),
                topProducts = this.plugins.shop.topProducts(productOptions);

            // show more buttons selectors
            var $btnShowMoreFeatured = $tplProductsTab.find('#mpws-js-shop-tab-products-featured .btn-loadmore'),
                $btnShowMoreNew = $tplProductsTab.find('#mpws-js-shop-tab-products-new .btn-loadmore'),
                $btnShowMoreTop = $tplProductsTab.find('#mpws-js-shop-tab-products-top .btn-loadmore');
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

            // append products tab
            $tplProductsTab.find('.mpws-js-shop-products-featured').html(featuredProducts.$el);
            $tplProductsTab.find('.mpws-js-shop-products-new').html(newProducts.$el);
            $tplProductsTab.find('.mpws-js-shop-products-top').html(topProducts.$el);
            $tplProductsTab.on('click', '#mpws-js-shop-tab-products-featured .btn-loadmore', featuredProducts.revealNextPage);
            $tplProductsTab.on('click', '#mpws-js-shop-tab-products-new .btn-loadmore', newProducts.revealNextPage);
            $tplProductsTab.on('click', '#mpws-js-shop-tab-products-top .btn-loadmore', topProducts.revealNextPage);

            $('.mpws-js-main-section').html($tplProductsTab);
        },
        shopCart: function () {
            this.toggleCategoryRibbonAndBreadcrumb(true);
            this.toggleHomeFrame(false);
            this.refreshViewedProducts();
            this.updateFooter();

            $('.mpws-js-main-section').html(this.plugins.shop.cart().$el);

        },
        shopProduct: function (id) {
            this.toggleCategoryRibbonAndBreadcrumb(true);
            this.toggleHomeFrame(false);
            this.refreshViewedProducts();
            this.updateFooter();

            $('.mpws-js-main-section').html(this.plugins.shop.product(id).$el);

        },
        shopWishlist: function () {
            this.toggleCategoryRibbonAndBreadcrumb(true);
            this.toggleHomeFrame(false);
            this.refreshViewedProducts();
            this.updateFooter();

            $('.mpws-js-main-section').html(this.plugins.shop.wishlist().$el);

        },
        shopCompare: function () {
            this.toggleCategoryRibbonAndBreadcrumb(true);
            this.toggleHomeFrame(false);
            this.refreshViewedProducts();
            this.updateFooter();

            $('.mpws-js-main-section').html(this.templates.productComparisons());
            $('.mpws-js-main-section').find('.mpws-js-product-comparisons').html(this.plugins.shop.compare().$el);

        },
        page404: function () {
            this.toggleCategoryRibbonAndBreadcrumb(true);
            this.toggleHomeFrame(false);
            this.refreshViewedProducts();
            this.updateFooter();

            //     $tplCategoriesRibbon = $(Handlebars.compile(tplCategoriesRibbon)()),
            //     categoryOptions = {design: {className: 'nav navbar-nav'}},
            //     categoryMenu = this.plugins.shop.categoryList(categoryOptions);
            // $tplCategoriesRibbon.find('.mpws-js-catalog-tree').html(categoryMenu.render().$el);

            // $('.mpws-js-shop-categories-topnav').html($tplCategoriesRibbon);
            $('.mpws-js-main-section').html(this.templates.page404());

        },

        // utils

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
        toggleCategoryRibbonAndBreadcrumb: function (show) {
            $('.mpws-js-breadcrumb').toggleClass('hidden', !show);
            $('.mpws-js-shop-categories-ribbon').toggleClass('hidden', !show);
        },
        toggleHomeFrame: function (show) {
            $('.mpws-js-main-home-frame').toggleClass('hidden', !show);
        },

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
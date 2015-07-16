define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'echo',
    'auth',
    'bootstrap-dialog',
    // page templates
    'text!./../hbs/breadcrumb.hbs',
    'text!./../hbs/homeFrame.hbs',
    'text!./../hbs/productsTab.hbs',
    'text!./../hbs/viewedProducts.hbs',
    'text!./../hbs/page404.hbs',
    'text!./../hbs/catalogBrowser.hbs',
    'text!./../hbs/categoriesRibbon.hbs',
    'text!./../hbs/productComparisons.hbs',
    'text!./../hbs/productWishlist.hbs',
    'text!./../hbs/search.hbs',
    'owl.carousel',
    'bootstrap',
    'icheck'
], function ($, _, Backbone, Handlebars, echo, Auth, BootstrapDialog,
     tplBreadcrumb,
     tplHomeFrame,
     tplProductsTab,
     tplViewedProducts,
     tplPage404,
     tplCatalogBrowser,
     tplCategoriesRibbon,
     tplProductComparisons,
     tplProductWishlist,
     tplSearch) {

        BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_DEFAULT] = 'Повідомлення';
        BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_INFO] = 'Повідомлення';
        BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_PRIMARY] = 'Повідомлення';
        BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_SUCCESS] = 'Успішно';
        BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_WARNING] = 'Увага';
        BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_DANGER] = 'Помилка';
        BootstrapDialog.DEFAULT_TEXTS['OK'] = 'Добре';
        BootstrapDialog.DEFAULT_TEXTS['CANCEL'] = 'Скасувати';
        BootstrapDialog.DEFAULT_TEXTS['CONFIRM'] = 'Підтвердити';

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

    var systemUrls = {
        '!/signin': 'signin',
        '!/account': 'account'
    };

    APP.configurePlugins({
        shop: {
            urls: _(shopRoutes).invert()//,
            // productShortClassNames: 'no-margin product-item-holder hover'
        },
        system: {
            urls: _(systemUrls).invert(),
            states: {
                onGuestRoute: '!/',
                onRegisteredRoute: '!/account'
            }
        }
    });

    var templatesCompiled = {
        homeFrame: $(Handlebars.compile(tplHomeFrame)()),
        categoriesRibbon: $(Handlebars.compile(tplCategoriesRibbon)()),
        breadcrumb: $(Handlebars.compile(tplBreadcrumb)()),
        viewedProducts: $(Handlebars.compile(tplViewedProducts)()),
        productComparisons: $(Handlebars.compile(tplProductComparisons)()),
        productsTab: $(Handlebars.compile(tplProductsTab)()),
        productWishlist: $(Handlebars.compile(tplProductWishlist)()),
        page404: $(Handlebars.compile(tplPage404)()),
        catalogBrowser: $(Handlebars.compile(tplCatalogBrowser)()),
        search: $(Handlebars.compile(tplSearch)()),
    };

    function getTemplate (key) {
        return function () {
            return templatesCompiled[key] && templatesCompiled[key].clone();
        }
    }

    var Router = Backbone.Router.extend({

        name: 'pb.com.ua',

        // settings: {
        //     title: APP.config.TITLE,
        //     logoImageUrl: APP.config.URL_PUBLIC_LOGO
        // },

        routes: _.extend.apply(_, [
            {
                '': 'home',
                '!': 'home',
                '!/': 'home',
                ':whatever': 'page404'
            },
            shopRoutes,
            systemUrls
        ]),

        plugins: {},

        elements: {},

        templates: {
            homeFrame: getTemplate('homeFrame'),
            categoriesRibbon: getTemplate('categoriesRibbon'),
            breadcrumb: getTemplate('breadcrumb'),
            viewedProducts: getTemplate('viewedProducts'),
            productsTab: getTemplate('productsTab'),
            productComparisons: getTemplate('productComparisons'),
            productWishlist: getTemplate('productWishlist'),
            page404: getTemplate('page404'),
            catalogBrowser: getTemplate('catalogBrowser'),
            search: getTemplate('search'),
        },

        views: {},


        signin: function () {
            // check if user is authenticated
            // if (Auth.verifyStatus()) {
            //     Backbone.history.navigate('!/', true);
            //     return;
            // }
            var auth = this.plugins.system.authorize();
            if (auth) {
                this.toggleCategoryRibbonAndBreadcrumb(true);
                this.toggleHomeFrame(false);
                $('section.mpws-js-main-section').html(auth.$el);
            }
        },

        account: function () {
            // check if user is authenticated
            // if (!Auth.verifyStatusAndThen()) {
            //     Backbone.history.navigate('!/', true);
            //     return;
            // }
            var user = this.plugins.system.userPanel();
            if (user) {
                this.toggleCategoryRibbonAndBreadcrumb(true);
                this.toggleHomeFrame(false);
                $('section.mpws-js-main-section').html(user.$el);
            }
        },

        initialize: function () {

            var that = this;

            $('img.mpws-js-logo').attr('src', APP.config.URL_PUBLIC_LOGO);

            this.on('app:ready', function () {
                Auth.getStatus();

                // Auth.on('registered', function () {
                //     Backbone.history.navigate(that.plugins.system.urls.account, true);
                // });

                // Auth.on('guest', function () {
                //     Backbone.history.navigate('!/', true);
                // });
                // that.setPlugin(APP.getPlugin('shop'));

                // menu items
                $('.mpws-js-menu-cart').html(that.plugins.shop.menuItemCart().$el);
                $('.mpws-js-menu-payment').html(that.plugins.shop.menuItemPopupInfoPayment().$el);
                $('.mpws-js-menu-warranty').html(that.plugins.shop.menuItemPopupInfoWarranty().$el);
                $('.mpws-js-menu-shipping').html(that.plugins.shop.menuItemPopupInfoShipping().$el);
                $('.mpws-js-menu-compare').html(that.plugins.shop.menuItemCompareList().$el);
                $('.mpws-js-menu-wishlist').html(that.plugins.shop.menuItemWishList().$el);

                // widgets
                $('.mpws-js-shop-phones').html(that.plugins.shop.widgetPhonesList().$el);
                $('.mpws-js-shop-addresses').html(that.plugins.shop.widgetAddresses().$el);
                $('.mpws-js-cart-embedded').html(that.plugins.shop.widgetCartButton().$el);
                $('.mpws-js-top-nav-right').html([
                    $('<li>').addClass('dropdown').html(that.plugins.shop.widgetExchangeRates().$el),
                    $('<li>').addClass('dropdown').html(that.plugins.system.menu().$el)
                ]);

                // common elements

                // setup ribbon nav and breadcrumb
                var $tplBreadcrumb = that.templates.breadcrumb(),
                    $tplCategoriesRibbon = that.templates.categoriesRibbon(),
                    optionsCategoryMenu = {design: {className: 'nav navbar-nav'}},
                    optionsSubItemChildItems = {design: {style: 'sub', className: 'dropdown-menu'}};

                that.views.categoryHomeMenu = that.plugins.shop.catalogNavigator(optionsCategoryMenu);
                that.views.categoryRibbonMenu = that.plugins.shop.catalogNavigator(optionsCategoryMenu);
                that.views.categorySearchTopLevelList = that.plugins.shop.catalogNavigator(optionsSubItemChildItems);
                that.views.categoryBreadcrumbTopLevelList = that.plugins.shop.catalogNavigator(optionsSubItemChildItems);

                $tplBreadcrumb.find('li.mpws-js-shop-categories-toplist').append(that.views.categoryBreadcrumbTopLevelList.render().$el);
                $tplCategoriesRibbon.find('.mpws-js-catalog-tree').html(that.views.categoryRibbonMenu.render().$el);
                
                $('.mpws-js-breadcrumb').html($tplBreadcrumb);
                $('.mpws-js-shop-categories-ribbon').html($tplCategoriesRibbon);
                    // categoryListOptions = {design: {className: 'nav'}},
                    // categoryMenu = that.plugins.shop.catalogNavigator(categoryListOptions)
                    // categoryTopLevelList = that.plugins.shop.catalogNavigator({design: {style: 'sub', className: 'dropdown-menu'}})
                // set top category list with banner
                // setup home frame: category navbar and hot offers carousel
                var $tplHomeFrame = that.templates.homeFrame(),
                    hotOffers = this.plugins.shop.hotOffers({design: {style: 'offerbanner'}});
                $tplHomeFrame.find('.mpws-js-catalog-tree').html(that.views.categoryHomeMenu.render().$el);
                $tplHomeFrame.find('.mpws-js-shop-offers-carousel').html(hotOffers.$el);
                hotOffers.$el.addClass('owl-carousel owl-inner-nav owl-ui-sm owl-theme');
                $('.mpws-js-main-home-frame').html($tplHomeFrame);

                hotOffers.on('shop:rendered', function () {
                    hotOffers.$el.owlCarousel({
                        loop: true,
                        autoplay: true,
                        autoplayTimeout: 5000,
                        autoplayHoverPause: true,
                        items: 1,
                        animateOut: 'slideOutLeft',
                        animateIn: 'slideInRight',
                        stagePadding:-30,
                        smartSpeed:450
                    });
                });

                // update searchbox categories
                $('header li.mpws-js-shop-categories-toplist').append(that.views.categorySearchTopLevelList.$el);

                $('body').on('click', 'header a.search-button', function () {
                    Backbone.history.navigate('!/search/' + $('header input.search-field').val(), true);
                    return false;
                });

                $('body').on('keypress', 'header input.search-field', function (event) {
                    if (event.which === 13) {
                        Backbone.history.navigate('!/search/' + $('header input.search-field').val(), true);
                        return false;
                    }
                });

                // setup recently viewed products
                if (true) {
                    var $tplViewedProducts = that.templates.viewedProducts(),
                        optionsViewedProducts = {design: {className: 'no-margin item carousel-item product-item-holder size-small hover'}};
                    that.views.viewedProducts = that.plugins.shop.viewedProducts(optionsViewedProducts);

                    $('div.mpws-js-shop-viewed-products', $tplViewedProducts).html(that.views.viewedProducts.$el);
                    $('section.mpws-js-shop-viewed-products').html($tplViewedProducts);
                    that.views.viewedProducts.$el.addClass('owl-carousel');

                    // $owlEl.html($tplViewedProducts);
                    var owl = that.views.viewedProducts.$el.data('owlCarousel');
                    that.views.viewedProducts.on('shop:rendered', function () {
                    // return;
                    // console.log('viewed rendering');
                    // $tplViewedProducts.removeClass('hidden');
                    // debugger
                    // $owlEl.html(that.views.viewedProducts.$el);
                    // console.log('items are added');
                    // init some js
                    _.delay(function () {
                        initEchoJS();
                        // debugger
                        // owl.update();
                        // owl.destroy();
                        that.views.viewedProducts.$el.owlCarousel({
                            stopOnHover: true,
                            rewindNav: false,
                            items: 6,
                            pagination: false,
                            loop: false,
                            itemsTablet: [768, 3],
                            responsive:{
                                0:{
                                    items:1
                                },
                                600:{
                                    items:3
                                },
                                1000:{
                                    items:6
                                }
                            }
                        });
                        // $owlEl.trigger('destroy.owl.carousel');
                    }, 1000);
                    });
                    // that.views.viewedProducts.on('shop:emptylist', function () {
                    //     $tplViewedProducts.addClass('hidden');
                    // });
                    $(".slider-next", $tplViewedProducts).click(function () {
                        that.views.viewedProducts.$el.trigger('next.owl.carousel', [1500]);
                    });
                    $(".slider-prev", $tplViewedProducts).click(function () {
                        that.views.viewedProducts.$el.trigger('prev.owl.carousel', [1500]);
                    });
                }
            });
        },
        // routes
        home: function () {
            this.toggleCategoryRibbonAndBreadcrumb(false);
            this.toggleHomeFrame(true);
            this.refreshViewedProducts();
            this.updateFooter();

            var that = this,
                $tplProductsTab = this.templates.productsTab(),
                productOptions = {design: {className: 'col-sm-4 col-md-3 no-margin product-item-holder hover', pageSize: 4}},
                featuredProducts = this.plugins.shop.featuredProducts(productOptions),
                newProducts = this.plugins.shop.newProducts(productOptions),
                topProducts = this.plugins.shop.topProducts(productOptions),
                onSaleProducts = this.plugins.shop.onSaleProducts(productOptions);

            // show more buttons selectors
            var $btnShowMoreFeatured = $tplProductsTab.find('#mpws-js-shop-tab-products-featured .btn-loadmore'),
                $btnShowMoreNew = $tplProductsTab.find('#mpws-js-shop-tab-products-new .btn-loadmore'),
                $btnShowMoreTop = $tplProductsTab.find('#mpws-js-shop-tab-products-top .btn-loadmore'),
                $btnShowMoreOnSale = $tplProductsTab.find('#mpws-js-shop-tab-products-onsale .btn-loadmore');

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
            displayShowMoreButton($btnShowMoreOnSale);

            // init image loading
            featuredProducts.on('shop:rendered', initEchoJS);
            newProducts.on('shop:rendered', initEchoJS);
            topProducts.on('shop:rendered', initEchoJS);
            onSaleProducts.on('shop:rendered', initEchoJS);

            // hide showmore button when all products are visible
            featuredProducts.on('shop:allvisible', hideShowMoreButton($btnShowMoreFeatured));
            newProducts.on('shop:allvisible', hideShowMoreButton($btnShowMoreNew));
            topProducts.on('shop:allvisible', hideShowMoreButton($btnShowMoreTop));
            onSaleProducts.on('shop:allvisible', hideShowMoreButton($btnShowMoreOnSale));

            // hide showmore button when no products
            featuredProducts.on('shop:emptylist', hideShowMoreButton($btnShowMoreFeatured));
            newProducts.on('shop:emptylist', hideShowMoreButton($btnShowMoreNew));
            topProducts.on('shop:emptylist', hideShowMoreButton($btnShowMoreTop));
            onSaleProducts.on('shop:emptylist', hideShowMoreButton($btnShowMoreOnSale));

            // append products tab
            $tplProductsTab.find('.mpws-js-shop-products-featured').html(featuredProducts.$el);
            $tplProductsTab.find('.mpws-js-shop-products-new').html(newProducts.$el);
            $tplProductsTab.find('.mpws-js-shop-products-top').html(topProducts.$el);
            $tplProductsTab.find('.mpws-js-shop-products-onsale').html(onSaleProducts.$el);
            $tplProductsTab.on('click', '#mpws-js-shop-tab-products-featured .btn-loadmore', featuredProducts.revealNextPage);
            $tplProductsTab.on('click', '#mpws-js-shop-tab-products-new .btn-loadmore', newProducts.revealNextPage);
            $tplProductsTab.on('click', '#mpws-js-shop-tab-products-top .btn-loadmore', topProducts.revealNextPage);
            $tplProductsTab.on('click', '#mpws-js-shop-tab-products-onsale .btn-loadmore', onSaleProducts.revealNextPage);

            $('section.mpws-js-main-section').html($tplProductsTab);
        },
        shopCatalogCategory: function (category) {
            this.shopCatalogCategoryPage(category);
        },
        shopCatalogCategoryPage: function (category, pageNo) {
            this.toggleCategoryRibbonAndBreadcrumb(true);
            this.toggleHomeFrame(false);
            this.refreshViewedProducts();
            this.updateFooter();
            // console.log('category = ' + category);
            var that = this,
                $tplCatalogBrowser = this.templates.catalogBrowser(),
                optionsFeaturedProducts = {limit: 5, _pCategoryExternalKey: category, design: {asList: true, style: 'minimal2', listItemClassName: 'sidebar-product-list-item'}},
                optionsCatalogProducts = {design: {className: 'col-xs-12 col-sm-4 no-margin product-item-holder hover'}},
                featuredProducts = this.plugins.shop.featuredProducts(optionsFeaturedProducts);

            var catalogFilterView = this.plugins.shop.catalogFilterPanel(category, pageNo, {
                filter_viewItemsOnPage: 21
            });
            var catalogBrowseView = this.plugins.shop.catalogBrowseContent(optionsCatalogProducts);

            $tplCatalogBrowser.find('.mpws-js-category-filter').html(catalogFilterView.$el);
            $tplCatalogBrowser.find('.mpws-ja-catalog-products').addClass('product-grid-holder').html(catalogBrowseView.$el);
            $tplCatalogBrowser.find('.mpws-js-category-featured-products').html(featuredProducts.render().$el);

            catalogFilterView.on('render:complete', function () {
                var $filterCheckBoxes = $tplCatalogBrowser.find('.mpws-js-category-filter .list-group-item input[type="checkbox"]');
                $filterCheckBoxes.iCheck({
                    checkboxClass: 'icheckbox_minimal-red shop-filter-checkbox',
                    radioClass: 'iradio_minimal-red'
                }).on('ifChanged', function (event) { $(event.target).trigger('change'); });
                // update breadcrumb
                var brItems = [],
                    productLocationPath = catalogFilterView.getPathInCatalog();
                _(productLocationPath).each(function (locItem) {
                    var pathCategorySubList = that.plugins.shop.catalogNavigator({design: {style: 'sub', parentID: locItem.ID}}),
                        subList = pathCategorySubList.hasSubCategories(locItem.ID) && pathCategorySubList.render().$el;
                    brItems.push([locItem.Name, that.plugins.shop.getCatalogUrl(locItem.ExternalKey), subList]);
                });
                // brItems.push([catalogFilterView.getDisplayName(), catalogFilterView.getCatalogUrl()]);
                that.updateBreadcrumb(brItems);
                initEchoJS();

                APP.setPageAttributes(catalogFilterView.getPageAttributes());
            });

            $tplCatalogBrowser.find('.mpws-js-catalog-infolink-payment').html(this.plugins.shop.menuItemPopupInfoPayment().$el);
            $tplCatalogBrowser.find('.mpws-js-catalog-infolink-warranty').html(this.plugins.shop.menuItemPopupInfoWarranty().$el);
            $tplCatalogBrowser.find('.mpws-js-catalog-infolink-shipping').html(this.plugins.shop.menuItemPopupInfoShipping().$el);

            $('section.mpws-js-main-section').html($tplCatalogBrowser);
        },
        shopSearch: function (text) {
            this.updateBreadcrumb('Пошук: ' + text);
            this.toggleHomeFrame(false);
            this.refreshViewedProducts();
            this.updateFooter();
            this.toggleCategoryRibbonAndBreadcrumb(true);

            var that = this,
                $tplSearch = this.templates.search(),
                optionsSearchProducts = {text: text, design: {className: 'col-xs-12 col-sm-4 no-margin product-item-holder hover'}},
                searchProducts = this.plugins.shop.searchProducts(optionsSearchProducts),
                optionsFeaturedProducts = {limit: 10, design: {asList: true, style: 'minimal2', listItemClassName: 'sidebar-product-list-item'}},
                featuredProducts = this.plugins.shop.featuredProducts(optionsFeaturedProducts);
            // $('section.mpws-js-main-section').html($('<div>').addClass('grid-list-products').html(searchProducts.$el));
            $tplSearch.find('.mpws-ja-catalog-products').addClass('product-grid-holder').html(searchProducts.$el);

            $tplSearch.find('.mpws-js-category-featured-products').html(featuredProducts.render().$el);
            $tplSearch.find('.mpws-js-catalog-infolink-payment').html(this.plugins.shop.menuItemPopupInfoPayment().$el);
            $tplSearch.find('.mpws-js-catalog-infolink-warranty').html(this.plugins.shop.menuItemPopupInfoWarranty().$el);
            $tplSearch.find('.mpws-js-catalog-infolink-shipping').html(this.plugins.shop.menuItemPopupInfoShipping().$el);
            $('section.mpws-js-main-section').html($tplSearch);
            $('header input.search-field').val(text);
        },
        // todo:
        // show last 3 products only
        // attach promo section
        shopCart: function () {
            this.updateBreadcrumb('Кошик');
            this.toggleCategoryRibbonAndBreadcrumb(true);
            this.toggleHomeFrame(false);
            this.refreshViewedProducts();
            this.updateFooter();

            var view = this.plugins.shop.cart();
            view.$el.addClass('container');

            $('section.mpws-js-main-section').html(view.$el);
            initEchoJS();

        },
        // todo:
        // add tabs for extra info
        // add magnifier
        // promo section
        // relative products
        // product families
        // enable wish and compare list
        // quick order (by name and phone only)
        // recommended items
        shopProduct: function (id) {
            var that = this;
            this.toggleCategoryRibbonAndBreadcrumb(true);
            this.toggleHomeFrame(false);
            this.refreshViewedProducts();
            this.updateFooter();

            var productView = this.plugins.shop.product(id);

            productView.on('render:complete', function () {
                var brItems = [],
                    productLocationPath = productView.getPathInCatalog();
                _(productLocationPath).each(function (locItem) {
                    var pathCategorySubList = that.plugins.shop.catalogNavigator({design: {style: 'sub', parentID: locItem.ID}}),
                        subList = pathCategorySubList.hasSubCategories(locItem.ID) && pathCategorySubList.render().$el;
                    brItems.push([locItem.Name, locItem.url, subList]);
                });
                brItems.push([productView.getDisplayName(), productView.getProductUrl()]);
                that.updateBreadcrumb(brItems);
                initEchoJS();
                APP.setPageAttributes(productView.getPageAttributes());
            });

            $('section.mpws-js-main-section').html(productView.$el);

        },

        // under construction
        // todo: implement events
        shopWishlist: function () {
            this.updateBreadcrumb('Мій список');
            this.toggleCategoryRibbonAndBreadcrumb(true);
            this.toggleHomeFrame(false);
            this.refreshViewedProducts();
            this.updateFooter();

            var viewWishList = this.plugins.shop.wishlist();

            $('section.mpws-js-main-section').html(this.templates.productWishlist());
            // $('section.mpws-js-main-section').html(this.plugins.shop.wishlist().$el);
            $('section.mpws-js-main-section').find('.mpws-js-products-wishlist').html(viewWishList.$el);

            viewWishList.on('shop:rendered', function () {
                initEchoJS();
            });
        },
        // under construction
        // todo: implement events
        shopCompare: function () {
            this.updateBreadcrumb('Порівняння');
            this.toggleCategoryRibbonAndBreadcrumb(true);
            this.toggleHomeFrame(false);
            this.refreshViewedProducts();
            this.updateFooter();

            var viewWishList = this.plugins.shop.compare();

            $('section.mpws-js-main-section').html(this.templates.productComparisons());
            $('section.mpws-js-main-section').find('.mpws-js-product-comparisons').html(viewWishList.$el);

            viewWishList.on('shop:rendered', function () {
                initEchoJS();
            });

        },
        page404: function () {
            this.toggleCategoryRibbonAndBreadcrumb(true);
            this.toggleHomeFrame(false);
            this.refreshViewedProducts();
            this.updateFooter();
            $('section.mpws-js-main-section').html(this.templates.page404());
        },

        // utils
        refreshViewedProducts: function () {
            // this.views.viewedProducts.collection.fetch({reset: true});
        },
        updateBreadcrumb: function (items) {
            $('.mpws-js-breadcrumb ul.mpws-js-breadcrumb-list > li:not(.locked)').remove();
            if (_.isString(items)) {
                items = [[items, null]];
            }
            _(items).each(function (item) {
                if (!item || !item[0]) {
                    return;
                }
                var text = item[0] || null,
                    url = item[1] || null,
                    $bcItem = $('<li>')
                        .addClass('breadcrumb-item'),
                    $bcLink = $('<a>').attr('href', url || 'javascript://').text(text);
                $bcItem.html($bcLink);
                if (!!item[2]) {
                    var $subMenu = $(item[2]);
                    if ($subMenu.is('ul') && $subMenu.children().length) {
                        $subMenu.addClass('dropdown-menu');
                        $bcItem.append($subMenu);
                        $bcLink.attr({
                            'class': 'dropdown-toggle',
                            'data-toggle': 'dropdown',
                            'aria-expanded': 'true'
                        });
                        $bcItem.addClass('dropdown');
                    }
                }
                $('.mpws-js-breadcrumb ul.mpws-js-breadcrumb-list').append($bcItem);
            });
            $('.mpws-js-breadcrumb ul.mpws-js-breadcrumb-list > li:last').addClass('current');
        },
        updateFooter: function () {
            // adding footer
            var $tplFooter = $('footer.mpws-js-main-footer'),
                productOptions = {limit: 5, design: {asList: true, style: 'minimal', wrap: '<div class="row"></div>'}},
                newProducts = this.plugins.shop.newProducts(productOptions),
                onSaleProducts = this.plugins.shop.onSaleProducts(productOptions),
                topProducts = this.plugins.shop.topProducts(productOptions),
                categoryTopLevelList = this.plugins.shop.catalogNavigator({design: {style: 'sub'}});

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
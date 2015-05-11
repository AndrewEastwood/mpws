define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'echo',
    'bootstrap-dialog',
    'isotope',
    // page templates
    'text!./../hbs/breadcrumb.hbs',
    // plugins
    'owl.carousel',
    'bootstrap',
    'icheck',
    'jquery.sliphover',
    'jquery.bridget'
], function ($, _, Backbone, Handlebars,
     echo, BootstrapDialog, Isotope, tplBreadcrumb) {

    $.bridget('isotope', Isotope);

    var shopRoutes = {
        '!/catalog/:category': 'shopCatalogCategory',
        '!/catalog/:category/:page': 'shopCatalogCategoryPage',
        '!/catalog/': 'home', //catalog
        '!/product/:product': 'shopProduct',
        '!/cart': 'shopCart',
        '!/wishlist': 'shopWishlist',
        '!/compare': 'shopCompare',
        '!/tracking/(:id)': 'shopTracking',
        '!/search/:text': 'shopSearch'
    };

    APP.configurePlugins({
        shop: {
            urls: _(shopRoutes).invert()
        }
    });

    var templatesCompiled = {
        breadcrumb: $(Handlebars.compile(tplBreadcrumb)()),
    };

    function getTemplate (key) {
        return function () {
            return templatesCompiled[key] && templatesCompiled[key].clone();
        }
    }

    function filterLayoutElements (filter) {
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

        name: 'demo.leogroup.com.ua',
        
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

        templates: {
            breadcrumb: getTemplate('breadcrumb')
        },

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

                // setup breadcrumbs
                var $tplBreadcrumbCat = that.templates.breadcrumb(),
                    $tplBreadcrumbProd = that.templates.breadcrumb(),
                    optionsSubItemChildItems = {design: {style: 'sub', className: 'dropdown-menu'}};
                that.views.categoryBreadcrumbTopLevelListCat = that.plugins.shop.catalogNavigator(optionsSubItemChildItems);
                that.views.categoryBreadcrumbTopLevelListProd = that.plugins.shop.catalogNavigator(optionsSubItemChildItems);
                $tplBreadcrumbCat.find('li.mpws-js-shop-categories-toplist').append(that.views.categoryBreadcrumbTopLevelListCat.render().$el);
                $tplBreadcrumbProd.find('li.mpws-js-shop-categories-toplist').append(that.views.categoryBreadcrumbTopLevelListProd.render().$el);
                $('.mpws-js-breadcrumb-category').html($tplBreadcrumbCat);
                $('.mpws-js-breadcrumb-product').html($tplBreadcrumbProd);

                // hot offers
                var hotOffers = this.plugins.shop.hotOffers({design: {style: 'offerbanner'}});
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
                    filterLayoutElements('.home');
                });
                $('.mpws-js-shop-offers-carousel').html(hotOffers.$el);

                // category menu
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
            $('.mpws-js-shop-filter-panel-wrapper').addClass('hidden');
        },

        page404: function () {},

        info: function () {
            // var iso = new Isotope($('#container').get(0) , {filter: '.info'});
            filterLayoutElements('.info');
        },

        contacts: function () {
            filterLayoutElements('.contacts');
        },

        shopCart: function () {
            $('.mpws-js-shop-filter-panel-wrapper').addClass('hidden');
            filterLayoutElements('.shop-cart');
        },

        shopCatalogCategory: function (category) {
            this.shopCatalogCategoryPage(category);
        },

        shopCatalogCategoryPage: function (category, pageNo) {
            // console.log('shopCatalogCategoryPage');
            $('.mpws-js-shop-filter-panel-wrapper').removeClass('hidden');
            filterLayoutElements('.shop-catalog');

            var that = this,
                catalogFilterView = this.plugins.shop.catalogFilterPanel(category, pageNo),
                catalogBrowseView = this.plugins.shop.catalogBrowseContent();

            catalogFilterView.on('render:complete', function () {
                initEchoJS();
                APP.setPageAttributes(catalogFilterView.getPageAttributes());
                // filterLayoutElements('.shop-catalog');
                // update breadcrumb
                var brItems = [],
                    productLocationPath = catalogFilterView.getPathInCatalog();
                _(productLocationPath).each(function (locItem) {
                    var pathCategorySubList = that.plugins.shop.catalogNavigator({design: {style: 'sub', parentID: locItem.ID}}),
                        subList = pathCategorySubList.hasSubCategories(locItem.ID) && pathCategorySubList.render().$el;
                    brItems.push([locItem.Name, that.plugins.shop.getCatalogUrl(locItem.ExternalKey), subList]);
                });
                that.updateBreadcrumb(brItems, 'category');
            });

            $('.mpws-js-category-filter').html(catalogFilterView.$el);
            $('.mpws-js-catalog-products').html(catalogBrowseView.$el);
        },
        shopProduct: function (id) {
            // console.log('shopProduct');
            $('.mpws-js-shop-filter-panel-wrapper').addClass('hidden');
            filterLayoutElements('.shop-product');
            var that = this,
                productView = this.plugins.shop.product(id);

            productView.on('render:complete', function () {
                filterLayoutElements('.shop-product');
                initEchoJS();
                APP.setPageAttributes(productView.getPageAttributes());
                // update breadcrumb
                var brItems = [],
                    productLocationPath = productView.getPathInCatalog();
                _(productLocationPath).each(function (locItem) {
                    var pathCategorySubList = that.plugins.shop.catalogNavigator({design: {style: 'sub', parentID: locItem.ID}}),
                        subList = pathCategorySubList.hasSubCategories(locItem.ID) && pathCategorySubList.render().$el;
                    brItems.push([locItem.Name, locItem.url, subList]);
                });
                brItems.push([productView.getDisplayName(), productView.getProductUrl()]);
                that.updateBreadcrumb(brItems, 'product');
            });

            $('.mpws-js-product').html(productView.$el);
        },
        updateBreadcrumb: function (items, name) {
            $('.mpws-js-breadcrumb-' + name + ' ul.mpws-js-breadcrumb-list > li:not(.locked)').remove();
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
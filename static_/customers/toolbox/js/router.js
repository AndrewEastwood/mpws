define([
    'jquery',
    'underscore',
    'auth',
    'cachejs',
    'bootstrap'
], function ($, _, Auth, Cache) {


    var shopRoutes = {
        "!/shop/content": "contentList",
        "!/shop/content/:status": "contentListByStatus",
        "!/shop/product/new": "productCreate",
        "!/shop/product/edit/:id": "productEdit",
        "!/shop/orders": "ordersList",
        "!/shop/orders/:status": "ordersListByStatus",
        "!/shop/order/edit/:id": "orderEdit",
        "!/shop/order/print/:id": "orderPrint",
        "!/shop/order/email_tracking/:id": "orderEmailTracking",
        "!/shop/order/email_reciept/:id": "orderEmailReceipt",
        "!/shop/origin/new": "originCreate",
        "!/shop/origin/edit/:id": "originEdit",
        "!/shop/category/new": "categoryCreate",
        "!/shop/category/edit/:id": "categoryEdit",
        "!/shop/reports": "reports",
        "!/shop/settings": "shopSettings",
        "!/shop/promo": "promo",
        "!/shop/promo/edit/:id": "promoEdit",
        "!/shop/promo/new": "promoCreate",
        "!/shop/feeds": "feeds"
    };

    var systemUrls = {
        "!/system/customers": "customersList",
        "!/system/customers/:status": "customersListByStatus",
        "!/system/customer/edit/:id": "customerEdit",
        "!/system/customer/new": "customerCreate",
        "!/system/migrations": "migrations",
        "!/system/users": "usersList",
        "!/system/users/:status": "usersListByStatus",
        "!/system/user/edit/:id": "userEdit",
        "!/system/user/new": "userCreate"
    };

    APP.configurePlugins({
        shop: {
            urls: _(shopRoutes).invert()
        },
        system: {
            urls: _(systemUrls).invert()
        }
    });

    var Router = Backbone.Router.extend({

        name: 'toolbox',

        settings: {
            title: APP.config.TITLE,
            logoImageUrl: APP.config.URL_PUBLIC_LOGO
        },

        routes: _.extend.apply(_, [
            {
                '': 'home',
                '!': 'home',
                '!/': 'home',
                '!/signin': 'signin',
                '!/signout': 'signout'
            },
            shopRoutes
        ]),

        plugins: {},

        elements: {},

        views: {},

        before: function (route, params) {
            if (!Auth.getUserID()) {
                this.toggleMenu(false);
                this.toggleWidgets(false);
                if (!/!\/signin/.test(Backbone.history.getHash())) {
                    // Backbone.history.fragment = false;
                    // window.location.href = '/#!/signin';
                    Backbone.history.navigate('!/signin', true);
                }
                return false;
            } else {
                this.toggleMenu(true);
                this.toggleWidgets(true);
                if (/!\/signin/.test(Backbone.history.getHash())) {
                    Backbone.history.navigate('!/', true);
                }
            }
        },

        signin: function () {
            var signin = this.plugins.system.signin();
            $('section.mpws-js-main-section').html(signin.$el);
        },

        signout: function () {
            var that = this;
            Auth.signout(function () {
                that.toggleMenu(false);
                that.toggleWidgets(false);
                Backbone.history.navigate('!/signin', true);
            });
        },

        initialize: function () {

            var that = this;

            this.on('app:ready', function () {


                var brandTitle = APP.config.TITLE.split(''),
                    blinkingCharPos = _.random(0, Math.ceil(brandTitle.length / 2)),
                    blinkingCharPos2 = _.random(Math.ceil(brandTitle.length / 2), brandTitle.length - 1);
                brandTitle[blinkingCharPos] = $('<span>').addClass('anim-neonblink').text(brandTitle[blinkingCharPos]).get(0).outerHTML,
                brandTitle[blinkingCharPos2] = $('<span>').addClass('anim-neonblink2').text(brandTitle[blinkingCharPos2]).get(0).outerHTML;
                    // debugger
                // return CustomerClass;
                $('head title').text(APP.config.TITLE);
                $('a.navbar-brand').attr('href', '/#!/').html(brandTitle.join(''));
                $('a.mpjs-opensite').attr('href', APP.config.URL_PUBLIC_HOMEPAGE).html(APP.config.URL_PUBLIC_HOMEPAGE);
                // $('#navbar [name="TopMenuLeft"]').empty().append(renderMenuPlaceholdersFn());



                // menu items
                // $('.mpws-js-menu-cart').html(that.plugins.shop.menuItemCart().$el);
                // $('.mpws-js-menu-payment').html(that.plugins.shop.menuItemPopupInfoPayment().$el);
                // $('.mpws-js-menu-warranty').html(that.plugins.shop.menuItemPopupInfoWarranty().$el);
                // $('.mpws-js-menu-shipping').html(that.plugins.shop.menuItemPopupInfoShipping().$el);
                // $('.mpws-js-menu-compare').html(that.plugins.shop.menuItemCompareList().$el);
                // $('.mpws-js-menu-wishlist').html(that.plugins.shop.menuItemWishList().$el);

                // // widgets
                // $('.mpws-js-shop-addresses').html(that.plugins.shop.widgetAddresses().$el);
                // $('.mpws-js-cart-embedded').html(that.plugins.shop.widgetCartButton().$el);
                // $('.mpws-js-top-nav-right').html($('<li>').addClass('dropdown').html(that.plugins.shop.widgetExchangeRates().$el));
            });

            this.on('route', function (routeFn) {
                that.toggleMenu(true);
                that.toggleWidgets(true);
                if (_.isFunction(that[routeFn])) {
                    return;
                }
                var contentItems = [];
                _(that.plugins).each(function (plg) {
                    if (_.isFunction(plg[routeFn])) {
                        var view = plg[routeFn]();
                        contentItems.push(view.render().$el);
                    }
                });
                $('section.mpws-js-main-section').empty().append(contentItems);
            });

            Auth.on('registered', function () {
                Backbone.history.navigate('!/', true);
            });
        },
        // routes
        home: function () {
            this.toggleMenu(true);
            this.toggleWidgets(true);
            // debugger
            $('section.mpws-js-main-section').empty();
            _(this.plugins).each(function (plg) {
                $('section.mpws-js-main-section').append(plg.dashboard().$el);
            });
            // $('section.mpws-js-main-section').html($tplProductsTab);
        },
        page404: function () {
            this.toggleMenu(true);
            $('section.mpws-js-main-section').html($('<h1/>').text('NOT FOUND'));
        },
        // utils
        toggleWidgets: function (show) {
            if (show) {
                var viewUserButton = this.plugins.system.widgetUserButton();
                $('.mpws-js-top-menu-right').html(viewUserButton.render().$el);
            } else {
                $('.mpws-js-top-menu-right').empty();
            }
        },
        toggleMenu: function (show) {
            $('mpws-js-top-menu-container').toggleClass('hidden', !show);
            $('.mpws-js-top-menu-left').empty();
            if (show) {
                _(this.plugins).each(function (plg) {
                    $('.mpws-js-top-menu-left').append(plg.menu({tagName: 'li'}).$el);
                });
            }
        },
        // updateBreadcrumb: function (items) {
        //     $('.mpws-js-breadcrumb ul.mpws-js-breadcrumb-list > li:not(.locked)').remove();
        //     if (_.isString(items)) {
        //         items = [[items, null]];
        //     }
        //     _(items).each(function (item) {
        //         if (!item || !item[0]) {
        //             return;
        //         }
        //         var text = item[0] || null,
        //             url = item[1] || null,
        //             $bcItem = $('<li>')
        //             .addClass('breadcrumb-item'),
        //             $bcLink = $('<a>').attr('href', url || 'javascript://').text(text);
        //         $bcItem.html($bcLink);
        //         if (item[2]) {
        //             var $subMenu = $(item[2]);
        //             if ($subMenu.is('ul')) {
        //                 $subMenu.addClass('dropdown-menu');
        //                 $bcItem.append($subMenu);
        //                 $bcLink.attr({
        //                     'class': 'dropdown-toggle',
        //                     'data-toggle': 'dropdown',
        //                     'aria-expanded': 'true'
        //                 });
        //                 $bcItem.addClass('dropdown');
        //             }
        //         }
        //         $('.mpws-js-breadcrumb ul.mpws-js-breadcrumb-list').append($bcItem);
        //     });
        //     $('.mpws-js-breadcrumb ul.mpws-js-breadcrumb-list > li:last').addClass('current');
        // },
        // updateFooter: function () {
        //     // adding footer
        //     var $tplFooter = $('footer.mpws-js-main-footer'),
        //         productOptions = {limit: 5, design: {asList: true, style: 'minimal', wrap: '<div class="row"></div>'}},
        //         newProducts = this.plugins.shop.newProducts(productOptions),
        //         onSaleProducts = this.plugins.shop.onSaleProducts(productOptions),
        //         topProducts = this.plugins.shop.topProducts(productOptions),
        //         categoryTopLevelList = this.plugins.shop.catalogNavigator({design: {style: 'sub'}});

        //     $tplFooter.find('.mpws-js-shop-new-products-minimal-list').html(newProducts.$el);
        //     $tplFooter.find('.mpws-js-shop-onsale-products-minimal-list').html(onSaleProducts.$el);
        //     $tplFooter.find('.mpws-js-shop-top-products-minimal-list').html(topProducts.$el);
        //     $tplFooter.find('.mpws-js-shop-categories-toplist').html(categoryTopLevelList.$el);
        // },
        // toggleCategoryRibbonAndBreadcrumb: function (show) {
        //     $('.mpws-js-breadcrumb').toggleClass('hidden', !show);
        //     $('.mpws-js-shop-categories-ribbon').toggleClass('hidden', !show);
        // },
        // toggleHomeFrame: function (show) {
        //     $('.mpws-js-main-home-frame').toggleClass('hidden', !show);
        // }
    });


    // function initEchoJS () {
    //     echo.init({
    //         offset: 100,
    //         throttle: 250,
    //         callback: function(element, op) {
    //             console.log(op)
    //             if(op === 'load') {
    //                 element.classList.add('loaded');
    //             } else {
    //                 element.classList.remove('loaded');
    //             }
    //         }
    //     });
    // }

    return Router;















    // APP.dfd.customerReady = new $.Deferred();

    // if (!APP.hasPlugin('system')) {
    //     throw 'System plugin is unavailable';
    // }

    // var _ifNotAuthorizedNavigateToSignin = function () {
    //     if (!Auth.getUserID()) {
    //         // APP.xhrAbortAll();
    //         if (!/!\/signin/.test(Backbone.history.getHash())) {
    //             Backbone.history.fragment = false;
    //             window.location.href = '/#!/signin';
    //             // Backbone.history.navigate('signin', true);
    //             // window.location.reload();
    //         }
    //     }
    // }

    // var renderMenuPlaceholdersFn = function () {
    //     // create containers for the rest plugins
    //     var menus = [], $menuItem;
    //     _(APP.config.PLUGINS).each(function (pluginName) {
    //         $menuItem = $('<li>').attr({
    //             name: 'MenuForPlugin_' + pluginName,
    //             id: 'menu-' + pluginName + '-ID',
    //             'class': 'dropdown menu-' + pluginName,
    //             rel: 'menu'
    //         });
    //         menus.push($menuItem);
    //     });
    //     return menus;
    // };

    // var renderDashboardPlaceholdersFn = function () {
    //     // create containers for the rest plugins
    //     var blocks = [], $blockItem;
    //     _(APP.config.PLUGINS).each(function (pluginName) {
    //         $blockItem = $('<div>').attr({
    //             name: 'DashboardForPlugin_' + pluginName,
    //             id: 'dashboard-container-' + pluginName + '-ID',
    //             'class': 'well dashboard-container dashboard-container-' + pluginName,
    //             rel: 'menu'
    //         });
    //         $blockItem.html(animSpinnerFB);
    //         blocks.push($blockItem);
    //     });
    //     return blocks;
    // }

    // Auth.on('signout:ok', function () {
    //     // debugger
    //     _ifNotAuthorizedNavigateToSignin();
    // });

    // // when user opens signin page and it's already signed in we redirect 
    // // user to home page
    // Auth.on('signin:ok', function () {
    //     // debugger
    //     Backbone.history.navigate(Cache.getFromLocalStorage('location') || '', true);
    // });

    // Auth.on('registered', function () {
    //     // debugger
    //     Backbone.history.navigate(Cache.getFromLocalStorage('location') || '', true);
    // });

    // Auth.on('guest', function () {
    //     // debugger
    //     _ifNotAuthorizedNavigateToSignin();
    // });

    // // verify user with every route
    // APP.Sandbox.eventSubscribe('global:route', function () {
    //     _ifNotAuthorizedNavigateToSignin();
    // });

    // APP.Sandbox.eventSubscribe('global:auth:status:active', function (data) {
    // });

    // APP.Sandbox.eventSubscribe('global:auth:status:inactive', function () {
    // });

    // APP.Sandbox.eventSubscribe('global:page:index', function () {
    //     // debugger
    //     APP.Sandbox.eventNotify('global:content:render', {
    //         name: 'CommonBodyCenter',
    //         el: $('<div>').addClass('dashboard').html(renderDashboardPlaceholdersFn())
    //     });
    // });

    // function CustomerClass () {}

    // CustomerClass.waitPlugins = true;


    // Auth.getStatus();

});
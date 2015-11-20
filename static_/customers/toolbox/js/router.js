define([
    'jquery',
    'underscore',
    'auth',
    'cachejs',
    'toastr',
    'imageInsert',
    'bootstrap-dialog',
    'bootstrap'
], function ($, _, Auth, Cache, toastr, imageInsert, BootstrapDialog) {

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

    var systemRoutes = {
        "!/system/customers": "customersList",
        "!/system/customers/:status": "customersListByStatus",
        "!/system/customer/edit/:id": "customerEdit",
        "!/system/customer/new": "customerCreate",
        "!/system/migrations": "migrations",
        "!/system/users": "usersList",
        "!/system/users/:status": "usersListByStatus",
        "!/system/user/edit/:id": "userEdit",
        "!/system/user/new": "userCreate",
        "!/system/email": "emailList",
        "!/system/email/new": "emailCreate",
        "!/system/email/edit/:id": "emailEdit"
    };

    APP.configurePlugins({
        shop: {
            urls: _(shopRoutes).invert()
        },
        system: {
            urls: _(systemRoutes).invert()
        }
    });

    var Router = Backbone.Router.extend({

        name: 'toolbox',

        routes: _.extend.apply(_, [
            {
                '': 'home',
                '!': 'home',
                '!/': 'home',
                '!/home': 'home',
                '!/signin': 'signin',
                // '!/signout': 'signout'
            },
            shopRoutes,
            systemRoutes,
            {
                ':whatever': 'home'
            }
        ]),

        plugins: {},

        elements: {},

        views: {},

        signin: function () {
            this.toggleMenu(false);
            this.toggleWidgets(false);
            var signin = this.plugins.system.signinForm();
            $('section.mpws-js-main-section').html(signin.render().$el);
        },

        getCurrentRouteName: function () {
            return this.routes[Backbone.history.getFragment()];
        },

        initialize: function () {

            var that = this,
                routesWhenUnauthorizedOnly = ['signin'];

            Auth.on('signin:fail', function () {
                toastr.error('Помилка авторизації');
            });

            Auth.on('registered', function () {
                that.toggleMenu(true);
                that.toggleWidgets(true);

            });

            Auth.on('guest', function () {
                var currRouteName = that.getCurrentRouteName();
                that.toggleMenu(false);
                that.toggleWidgets(false);
                if (_(routesWhenUnauthorizedOnly).indexOf(currRouteName) === -1) {
                    Backbone.history.navigate('!/signin', true);
                    return;
                }
            });

            this.on('app:ready', function () {
                // setup site animated title
                $('a.navbar-brand').attr('href', '/#!/').empty();
                var brandTitle = APP.config.TITLE.split(''),
                    blinkingCharPos = _.random(0, Math.ceil(brandTitle.length / 2)),
                    blinkingCharPos2 = _.random(Math.ceil(brandTitle.length / 2), brandTitle.length - 1);
                for (var i = 0, len = brandTitle.length; i < len; i++) {
                    brandTitle[i] = $('<span>').text(brandTitle[i]);
                    $('a.navbar-brand').append(brandTitle[i]);
                }
                brandTitle[blinkingCharPos].addClass('anim-neonblink');
                brandTitle[blinkingCharPos2].addClass('anim-neonblink2');
                $('head title').text(APP.config.TITLE);
                $('a.mpjs-opensite')
                    .attr('href', APP.config.URL_PUBLIC_HOMEPAGE)
                    .html(APP.config.URL_PUBLIC_HOMEPAGE);
                Auth.getStatus();
            });

            this.on('route', function (routeFn, params) {
                Auth.verifyStatusAndThen()
                    .ifRegistered(function () {
                        if (_(routesWhenUnauthorizedOnly).indexOf(routeFn) > -1) {
                            Backbone.history.navigate('!/', true);
                            return;
                        }
                        var contentItems = [];
                        _(that.plugins).each(function (plg) {
                            if (_.isFunction(plg[routeFn])) {
                                var view = plg[routeFn].apply(plg, params);
                                if (view && view.render) {
                                    contentItems.push(view.render().$el);
                                }
                            }
                        });
                        if (contentItems.length) {
                            $('section.mpws-js-main-section').empty().append(contentItems);
                        } else {
                            Backbone.history.navigate('!/', true);
                        }
                    })
                    .ifNotRegistered(function () {
                        // debugger
                        that.toggleMenu(false);
                        that.toggleWidgets(false);
                        if (_(routesWhenUnauthorizedOnly).indexOf(routeFn) === -1) {
                            Backbone.history.navigate('!/signin', true);
                            return;
                        }
                    });
            });

            // Setting up defaults
            // ==================================
            BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_DEFAULT] = 'Повідомлення';
            BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_INFO] = 'Повідомлення';
            BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_PRIMARY] = 'Повідомлення';
            BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_SUCCESS] = 'Успішно';
            BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_WARNING] = 'Увага';
            BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_DANGER] = 'Помилка';
            BootstrapDialog.DEFAULT_TEXTS['OK'] = 'Добре';
            BootstrapDialog.DEFAULT_TEXTS['CANCEL'] = 'Скасувати';
            BootstrapDialog.DEFAULT_TEXTS['CONFIRM'] = 'Підтвердити';
            ImageInsert.setDefaults({
                FLD_URL: 'Адреса',
                FLD_TITLE: 'Заголовок',
                FLD_WIDTH: 'Ширина',
                FLD_HEIGHT: 'Висота',
                FLD_PH_URL: 'вставте адресу зображення',
                DLG_TITLE: 'Вставка зображення'
            });
        },
        // routes
        home: function () {
            // debugger
            this.toggleMenu(true);
            this.toggleWidgets(true);
            $('section.mpws-js-main-section').empty();
            _(this.plugins).each(function (plg) {
                $('section.mpws-js-main-section').append(plg.dashboard().$el);
            });
        },
        page404: function () {
            this.toggleMenu(true);
            $('section.mpws-js-main-section').html($('<h1/>').text('NOT FOUND'));
        },
        // utils
        toggleWidgets: function (show) {
            if (show) {
                var viewUserButton = this.plugins.system.widgetUserButton();
                if (viewUserButton) {
                    $('.mpws-js-top-menu-right').html(viewUserButton.render().$el);
                }
                var customerSwitcher = this.plugins.system.widgetCustomerSwitcher();
                if (customerSwitcher) {
                    $('.mpws-js-top-menu-right').prepend(customerSwitcher.render().$el);
                }
            } else {
                $('.mpws-js-top-menu-right').empty();
            }
        },
        toggleMenu: function (show) {
            $('.mpws-js-top-menu-container').toggleClass('hidden', !show);
            $('.mpws-js-top-menu-left').empty();
            if (show) {
                _(this.plugins).each(function (plg) {
                    $('.mpws-js-top-menu-left').append(plg.menu({tagName: 'li'}).$el);
                });
            }
        }
    });

    return Router;
});
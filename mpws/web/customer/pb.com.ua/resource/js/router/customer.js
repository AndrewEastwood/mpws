APP.Modules.register("router/customer", [], [

    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/mpws.page',
    'plugin/shop/router/shop',
    'lib/mpws.ui',

], function (app, Sandbox, $, _, Backbone, mpwsPage, pluginShopRouter){

    // app.log('TROLOLOL!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');

    // var mpwsPageLib = new mpwsPage();
    var Controller = Backbone.Router.extend({
        routes: {
            "": "site_home",
            "site/login": "site_login",
            "site/logout": "site_logout",
            "site/payment": "site_payment",
            "site/shipping": "site_shipping",
            "site/help": "site_help",
            "site/search/:value": "site_search",
            "user/account": "user_account",
            // "shop/catalog": "shop_catalog",
            // "shop/product/:id": "shop_product",
            // "shop/cart": "shop_cart",
            "*whatever": "site_error"
        },

        // we display startup products
        site_home: function (route, name, callback) {
            // app.log(true, 'pluginShopRouterLib', pluginShopRouterLib);
            // app.log(true, 'site_home page with arguments', route, name, callback);
            // _pageHome();
            // pluginShopRouterLib.shopHome();
        },
        // display error page
        site_error: function () {
        },
        site_login: function () {

        },
        site_logout: function () {

        },
        site_payment: function () {

        },
        site_shipping: function () {

        },
        site_help: function () {

        },
        site_search: function (searchText) {
            // _pageProduct(searchText);
            pluginShopRouterLib.pageProductItem(searchText);
        },
        // accounting
        user_account: function () {
            // account page:
            // general information
            // profile settings
            // website settings
            // orders
        },

    });

    // start native site page monitoring
    var controller = new Controller(); // Создаём контроллер

    // init all available plugins here
    var pluginShopRouterLib = new pluginShopRouter({
        breadcrumb: {
            el: $('.breadcrumb-placeholder'),
            placement: mpwsPage.PLACEMENT.APPEND
        },
        menu: {
            el: $('header .navbar-nav-main'),
            placement: mpwsPage.PLACEMENT.APPEND
        },
        productListOverview: {
            el: mpwsPage.getPageBody(),
            placement: mpwsPage.PLACEMENT.REPLACE
        },
        productEntryViewStandalone: {
            el: mpwsPage.getPageBody(),
            placement: mpwsPage.PLACEMENT.REPLACE
        },
        shoppingCartStandalone: {
            el: mpwsPage.getPageBody(),
            placement: mpwsPage.PLACEMENT.REPLACE
        },
        shoppingCartEmbedded: {
            el: $('header .navbar-nav-plugins'),
            placement: mpwsPage.PLACEMENT.APPEND
        },
        shoppingCartCheckout: {
            el: mpwsPage.getPageBody(),
            placement: mpwsPage.PLACEMENT.REPLACE
        },
        productsByCategory: {
            el: mpwsPage.getPageBody(),
            placement: mpwsPage.PLACEMENT.REPLACE
        }
    });

    Backbone.history.start();  // Запускаем HTML5 History push

});
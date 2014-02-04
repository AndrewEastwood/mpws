// APP.Modules.register("router/customer", [], [

//     'lib/jquery',
//     'lib/underscore',
//     'lib/backbone',
//     'lib/mpws.page',
//     'plugin/shop/router/shopPublic',
//     // 'lib/mpws.ui',

// ], function (app, Sandbox, $, _, Backbone, mpwsPage, pluginShopRouter){

define("customer/js/site", [

    'cmn_jquery',
    'default/js/site',
    // 'default/js/lib/underscore',
    // 'default/js/lib/backbone',
    // 'default/js/lib/mpws.page',
    // 'plugin/shop/js/site'
    'default/js/plugin/css!customer/css/theme.css'

], function ($, Site/*, _, Backbone, mpwsPage, pluginShopRouter*/) {

    var _customerOptions = {};

    _customerOptions.placeholders = {
        header: $('.MPWSPageHeader'),
        body: $('.MPWSPageBody'),
        footer: $('.MPWSPageFooter'),
        breadcrumb: $('.MPWSBreadcrumb'),
        menu: $('.MPWSPageHeader .MPWSBlockCenter'),
        productListOverview: $('.MPWSPageBody .MPWSBlockCenter'),
        productEntryStandalone: $('.MPWSPageBody .MPWSBlockCenter'),
        shoppingCartStandalone: $('.MPWSPageBody .MPWSBlockCenter'),
        shoppingCartEmbedded: $('.MPWSWidgetsTop'),
        shoppingCartCheckout: $('.MPWSPageBody .MPWSBlockCenter'),
        productListCatalog: $('.MPWSPageBody .MPWSBlockCenter')
    };

    _customerOptions.views = {
        menu: {
            el: _customerOptions.placeholders.menu
        },
        breadcrumb: {
            el: _customerOptions.placeholders.breadcrumb,
            template: 'default/js/plugin/hbs!customer/hbs/breadcrumb'
        }
    }

    var siteObj = new Site(_customerOptions);

    // this object will be passed into all enabled plugins
    // to inject additional components into page layout
    return siteObj;

    // app.log('TROLOLOL!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');

    // // var mpwsPageLib = new mpwsPage();
    // var Controller = Backbone.Router.extend({
    //     routes: {
    //         "": "site_home",
    //         "site/login": "site_login",
    //         "site/logout": "site_logout",
    //         "site/payment": "site_payment",
    //         "site/shipping": "site_shipping",
    //         "site/help": "site_help",
    //         "site/search/:value": "site_search",
    //         "user/account": "user_account",
    //         // "shop/catalog": "shop_catalog",
    //         // "shop/product/:id": "shop_product",
    //         // "shop/cart": "shop_cart",
    //         "*whatever": "site_error"
    //     },

    //     // we display startup products
    //     site_home: function (route, name, callback) {
    //         // app.log(true, 'pluginShopRouterLib', pluginShopRouterLib);
    //         // app.log(true, 'site_home page with arguments', route, name, callback);
    //         // _pageHome();
    //         // pluginShopRouterLib.shopHome();
    //     },
    //     // display error page
    //     site_error: function () {
    //     },
    //     site_login: function () {

    //     },
    //     site_logout: function () {

    //     },
    //     site_payment: function () {

    //     },
    //     site_shipping: function () {

    //     },
    //     site_help: function () {

    //     },
    //     site_search: function (searchText) {
    //         // _pageProduct(searchText);
    //         pluginShopRouterLib.pageProductItem(searchText);
    //     },
    //     // accounting
    //     user_account: function () {
    //         // account page:
    //         // general information
    //         // profile settings
    //         // website settings
    //         // orders
    //     },

    // });

    // // start native site page monitoring
    // var controller = new Controller(); // Создаём контроллер

    // // init all available plugins here
    // var pluginShopRouterLib = new pluginShopRouter({
    //     breadcrumb: $('.breadcrumb-placeholder'),
    //     catalogStructureMenu: $('header .navbar-nav-main'),
    //     productListOverview: mpwsPage.getPageBody(),
    //     productEntryStandalone: mpwsPage.getPageBody(),
    //     shoppingCartStandalone: mpwsPage.getPageBody(),
    //     shoppingCartEmbedded: $('header .navbar-nav-plugins'),
    //     shoppingCartCheckout: mpwsPage.getPageBody(),
    //     productListCatalog: mpwsPage.getPageBody()
    // });

    // Backbone.history.start();  // Запускаем HTML5 History push

});
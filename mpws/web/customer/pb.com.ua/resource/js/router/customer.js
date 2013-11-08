APP.Modules.register("router/customer", [], [

    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/mpws.page',
    'plugin/shop/router/shop',
    'lib/bootstrap',

], function (app, Sandbox, $, _, Backbone, mpwsPage, pluginShopRouter){

    // app.log('TROLOLOL!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');

    var mpwsPageLib = new mpwsPage();
    var pluginShopRouterLib = new pluginShopRouter({
        placeholders: {
            menu: {
                target: $('.mainNav .navbar .nav'),
                placement: mpwsPage.PLACEMENT.APPEND
            },
            productsLatest: {
                target: $('.MPWSPageBody'),
                placement: mpwsPage.PLACEMENT.REPLACE
            }
        }
    });

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
            app.log(true, 'pluginShopRouterLib', pluginShopRouterLib);
            app.log(true, 'site_home page with arguments', route, name, callback);
            // _pageHome();
            pluginShopRouterLib.shopHome();
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

    // // page handlers
    // function _pageHome () {
    //     pluginShopRouterLib.getProductListLatest();
    //     mpwsPageLib.pageName('home');
    // }

    // function _pageShop () {
    // }

    // function _pageProduct (productId) {
    //     pluginShopRouterLib.getProductItemByID(productId);
    // }


    // $('#button1id').on('click', function(){
    //     _pageHome();
    // })

    $('#buttonSearch').on('click', function(){
        controller.navigate('site/search/' + $('#inputSearch').val(), {trigger: true });
    })

    $('#buttonAttr').on('click', function(){
        // controller.navigate('site/search/' + $('#inputSearch').val(), {trigger: true });
        pluginShopRouterLib._test_getProductAttributes($('#inputSearch').val().split(','));

    })
    $('#buttonPriceArch').on('click', function(){
        // controller.navigate('site/search/' + $('#inputSearch').val(), {trigger: true });
        pluginShopRouterLib._test_getProductPriceArchive($('#inputSearch').val().split(','));

    })

    // start native site page monitoring
    var controller = new Controller(); // Создаём контроллер

    pluginShopRouterLib.init();
    // start additional routers
    // // start shop page monitoring
    // pluginShopRouterLib.start(false);

    Backbone.history.start();  // Запускаем HTML5 History push    

});
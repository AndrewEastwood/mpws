APP.Modules.register("router/customer", [], [

    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/mpws.page',
    'plugin/shop/view/render',
    'lib/bootstrap',

], function (app, Sandbox, $, _, Backbone, mpwsPage, pluginShopRender){

    // app.log('TROLOLOL!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');

    var mpwsPageLib = new mpwsPage();
    var pluginShopLib = new pluginShopRender();

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
            "shop/catalog": "shop_catalog",
            "shop/product/:id": "shop_product",
            "shop/cart": "shop_cart",
            "*whatever": "site_error"
        },

        // we display startup products
        site_home: function (route, name, callback) {
            app.log(true, 'site_home page with arguments', route, name, callback);
            _pageHome();
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
            _pageProduct(searchText);
        },
        // display catalog
        shop_catalog: function (route, name, callback) {
            _pageShop(route, name, callback);
        },
        // display category
        shop_category: function () {
            // here we handle different stuff:
            // sorting by [name, date, price, popularity, etc]
            // pagination
            // 
        },
        shop_cart: function () {
            mpwsPageLib.pageName('cart');
            mpwsPageLib.getPageBody('fdfsdfdsf', true);
        },
        // display particular product
        shop_product: function (productId, name, callback) {
            _pageProduct(productId, name, callback);
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

    // page handlers
    function _pageHome () {
        mpwsPageLib.pageName('home');
        pluginShopLib.getProductListLatest();
    }

    function _pageShop () {
    }

    function _pageProduct (productId) {
        mpwsPageLib.pageName('product');
        pluginShopLib.getProductItemByID(productId);
    }


    $('#button1id').on('click', function(){
        _pageHome();
    })

    $('#buttonSearch').on('click', function(){
        controller.navigate('site/search/' + $('#inputSearch').val(), {trigger: true });
    })

    var controller = new Controller(); // Создаём контроллер

    Backbone.history.start();  // Запускаем HTML5 History push    

});
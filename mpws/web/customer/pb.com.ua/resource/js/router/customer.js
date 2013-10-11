APP.Modules.register("router/customer", [], [

    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/mpws.page',
    'plugin/shop/lib/products',

], function (app, Sandbox, $, _, Backbone, mpwsPage, pluginShopProducts){

    app.log('TROLOLOL!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');

    var mpwsPageLib = new mpwsPage();
    var pluginShopProductsLib = new pluginShopProducts();
    var $dataHolder = $('#data-holder-ID');
    var $dataView = $('#data-ID');

    var Controller = Backbone.Router.extend({
        routes: {
            "": "site_home",
            "site/login": "site_login",
            "site/logout": "site_logout",
            "user/account": "user_account",
            "shop/catalog": "shop_catalog",
            "shop/product/:id": "shop_product",
            "shop/cart": "shop_product",
            "*whatever": "site_error"
        },

        // we display startup products
        site_home: function (route, name, callback) {
            _pageHome(route, name, callback);
        },
        // display error page
        site_error: function (  ) {
            $dataHolder.text(mpwsPageLib.getPageError());
        },
        site_login: function () {

        },
        site_logout: function () {

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
        app.log(arguments);
        $dataHolder.text('HOME');
        $dataView.html('');
    }

    function _pageShop () {
        app.log(arguments);
        $dataHolder.text('SHOP');
        $dataView.html('');
    }

    function _pageProduct (productId) {
        app.log(arguments);
        $dataHolder.text('PRODUCT');
        pluginShopProductsLib.getProductByID(productId, function (product) {
            var _productJSON = JSON.parse(product);

            $dataView.html($('<pre>').text(JSON.stringify(_productJSON, null, 4)));
        });
    }

    var controller = new Controller(); // Создаём контроллер

    Backbone.history.start();  // Запускаем HTML5 History push    

});
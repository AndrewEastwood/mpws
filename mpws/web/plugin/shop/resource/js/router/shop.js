APP.Modules.register("plugin/shop/router/shop", [], [
    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/mpws.api',
    'lib/mpws.page',
    'plugin/shop/view/render',
], function (app, Sandbox, $, _, Backbone, mpwsAPI, mpwsPage, pluginShopRender) {

    // start site routing
    var pluginShopRenderObj = null;

	function Router (options) {
		pluginShopRenderObj = new pluginShopRender(options);
	}

    Router.prototype.shopHome = function() {
    	app.log(true, 'Router.prototype.shopHome');
        pluginShopRenderObj.pageShopHome();
    };

    Router.prototype.shopProductsLatest = function(route, name, callback) {
    	app.log(true, 'Router.prototype.shopProductsLatest');
        pluginShopRenderObj.pageShopProductListLatest(route, name, callback);
    };

    Router.prototype.shopCatalog = function(route, name, callback) {
        pluginShopRenderObj.pageShopCatalog(route, name, callback);
    };

    Router.prototype.shopCatalogByCategory = function(route, name, callback) {
        pluginShopRenderObj.pageShopCatalogByCategory(route, name, callback);
    };

    Router.prototype.shopCatalogByCategoryAndBrand = function(route, name, callback) {
        pluginShopRenderObj.pageShopCatalogByCategoryAndBrand(route, name, callback);
    };
    
    Router.prototype.shopCart = function(route, name, callback) {
        pluginShopRenderObj.pageShopCart();
    };

    Router.prototype.shopProductItem = function(productId, name, callback) {
        app.log(true, 'shop_product')
        pluginShopRenderObj.pageShopProductItemByID(productId);
    };

    Router.prototype.init = function(productId, name, callback) {
		app.log(true, 'plugin/shop/router/shop constructor');

		// if (this.Controller)
		// 	return;
		var self = this;

		app.log(true, 'plugin/shop/router/shop creating new instance');
	    var _Controller = Backbone.Router.extend({
	        routes: {
	            "shop": "shop_home",
	            "shop/latest": "shop_latest",
	            "shop/catalog": "shop_catalog",
	            "shop/catalog/:category": "shop_category",
	            "shop/catalog/:category/:brand": "shop_category_brand",
	            "shop/product/:product": "shop_product",
	            "shop/cart": "shop_cart"
	        },
	        // shop default page
	        shop_home: function () {
	            // pluginShopRenderObj.pageShopHome();
	            self.shopHome();
	        },
	        // display shop latest stuff
	        shop_latest: function (route, name, callback) {
	            // pluginShopRenderObj.pageShopProductListLatest(route, name, callback);
	            self.shopProductsLatest(route, name, callback);
	        },
	        // display catalog
	        shop_catalog: function (route, name, callback) {
	            // pluginShopRenderObj.pageShopCatalog(route, name, callback);
	            self.shopCatalog(route, name, callback);
	        },
	        // display category
	        shop_category: function () {
	            // here we handle different stuff:
	            // sorting by [name, date, price, popularity, etc]
	            // pagination
	            // 
	            // pluginShopRenderObj.pageShopCatalogByCategory(route, name, callback);
	            self.shopCatalogByCategory(route, name, callback);
	        },
	        // display category
	        shop_category_brand: function () {
	            // here we handle different stuff:
	            // sorting by [name, date, price, popularity, etc]
	            // pagination
	            // 
	            // pluginShopRenderObj.pageShopCatalogByCategoryAndBrand(route, name, callback);
	            self.shopCatalogByCategoryAndBrand(route, name, callback);
	        },
	        shop_cart: function () {
	            // pluginShopRenderObj.pageShopCart();
	            self.shopCart();
	        },
	        // display particular product
	        shop_product: function (productId, name, callback) {
	            // app.log(true, 'shop_product')
	            // pluginShopRenderObj.pageShopProductItemByID(productId);
	            self.shopProductItem(productId, name, callback);
	        },

	    });

		// this.start = function () {
			/*return*/ new _Controller();
		// }

		// app.log(true, 'Router.controller', this.Controller);

	}

	return Router;

});
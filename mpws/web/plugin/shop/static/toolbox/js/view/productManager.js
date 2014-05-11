define("plugin/shop/toolbox/js/view/productManager", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'default/js/lib/cache',
    /* items */
    'plugin/shop/toolbox/js/view/popupProduct',
    'plugin/shop/toolbox/js/view/popupCategory',
    'plugin/shop/toolbox/js/view/popupOrigin',
    /* lists */
    'plugin/shop/toolbox/js/view/listProducts',
    'plugin/shop/toolbox/js/view/listCategories',
    'plugin/shop/toolbox/js/view/listOrigins',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/productManager',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
], function (Sandbox, MView, Cache, PopupProduct, PopupCategory, PopupOrigin, ListProducts, ListCategories, ListOrigins, tpl, lang) {

    var listProducts = new ListProducts();
    var listCategories = new ListCategories();
    var listOrigins = new ListOrigins();

    Sandbox.eventSubscribe('plugin:shop:product:add', function(data){
        var popupProduct = new PopupProduct();
        popupProduct.fetchAndRender();
    });
    Sandbox.eventSubscribe('plugin:shop:product:edit', function(data){
        var popupProduct = new PopupProduct();
        popupProduct.fetchAndRender();
    });
    Sandbox.eventSubscribe('plugin:shop:category:add', function(data){
        var popupCategory = new PopupCategory();
        popupCategory.fetchAndRender();
    });
    Sandbox.eventSubscribe('plugin:shop:category:edit', function(data){
        var popupCategory = new PopupCategory();
        popupCategory.fetchAndRender();
    });
    Sandbox.eventSubscribe('plugin:shop:origin:add', function(data){
        var popupOrigin = new PopupOrigin();
        popupOrigin.fetchAndRender();
    });
    Sandbox.eventSubscribe('plugin:shop:origin:edit', function(data){
        var popupOrigin = new PopupOrigin();
        popupOrigin.fetchAndRender();
    });

    var ProductManager = MView.extend({
        // tagName: 'div',
        // className: 'shop-order-entry',
        template: tpl,
        lang: lang,
        initialize: function () {
            MView.prototype.initialize.call(this);
            var self = this;
            this.on('mview:renderComplete', function () {
                Cache.withObject('ListProducts', function (cachedView) {
                    listProducts.render();
                    self.$('.shop-products').html(listProducts.$el);
                    return listProducts;
                });
                Cache.withObject('ListCategories', function (cachedView) {
                    listCategories.render();
                    self.$('.shop-categories').html(listCategories.$el);
                    return listCategories;
                });
                Cache.withObject('ListOrigins', function (cachedView) {
                    listOrigins.render();
                    self.$('.shop-origins').html(listOrigins.$el);
                    return listOrigins;
                });
            });
        }
    });

    return ProductManager;

});
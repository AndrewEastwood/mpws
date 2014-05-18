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
    'plugin/shop/toolbox/js/view/categoriesTree',
    'plugin/shop/toolbox/js/view/listOrigins',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/productManager',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
], function (Sandbox, MView, Cache, PopupProduct, PopupCategory, PopupOrigin, ListProducts, CategoriesTree, ListOrigins, tpl, lang) {

    var listProducts = new ListProducts();
    var categoriesTree = new CategoriesTree();
    var listOrigins = new ListOrigins();

    Sandbox.eventSubscribe('plugin:shop:product:add', function(data){
        var popupProduct = new PopupProduct();
        popupProduct.fetchAndRender();
    });

    Sandbox.eventSubscribe('plugin:shop:product:edit', function(data){
        var popupProduct = new PopupProduct();
        popupProduct.fetchAndRender({
            productID: data.oid
        });
    });

    Sandbox.eventSubscribe('plugin:shop:origin:add', function(data){
        var popupOrigin = new PopupOrigin({
            _fn: 'statuses'
        });
        popupOrigin.fetchAndRender();
    });

    Sandbox.eventSubscribe('plugin:shop:origin:edit', function(data){
        // debugger;
        var popupOrigin = new PopupOrigin({
            originID: data.oid,
            _fn: "getItem"
        });
        popupOrigin.fetchAndRender();
    });

    var ProductManager = MView.extend({
        // tagName: 'div',
        className: 'shop-product-manager',
        template: tpl,
        lang: lang,
        initialize: function () {
            MView.prototype.initialize.call(this);
            var self = this;
            this.on('mview:renderComplete', function () {
                // debugger;
                Cache.withObject('ListProducts', function (cachedView) {
                    listProducts.render();
                    self.$('.shop-products').html(listProducts.$el);
                    return listProducts;
                });
                Cache.withObject('CategoriesTree', function (cachedView) {
                    categoriesTree.fetchAndRender();
                    self.$('.shop-categories').html(categoriesTree.$el);
                    return categoriesTree;
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
define("plugin/shop/site/js/view/productsCompare", [
    'default/js/lib/backbone',
    'plugin/shop/site/js/model/productsCompare',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/productsCompare',
    "default/js/lib/jquery.cookie"
], function (Backbone, ModelProductsCompareInstance, Utils, tpl) {

    var ProductsCompare = Backbone.View.extend({
        model: ModelProductsCompareInstance,
        className: 'row shop-products-compare',
        id: 'shop-products-compare-ID',
        template: tpl,
        initialize: function() {
            MView.prototype.initialize.call(this);
            this.listenTo(this.model, "change", this.render);
        }
    });

    return ProductsCompare;

});
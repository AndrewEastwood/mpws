define("plugin/shop/js/view/productsCompare", [
    'default/js/view/mView',
    'plugin/shop/js/model/productsCompare',
    'default/js/plugin/hbs!plugin/shop/hbs/productsCompare',
    "default/js/lib/jquery.cookie"
], function (MView, ModelProductsCompareInstance, tpl) {

    var ProductsCompare = MView.extend({
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
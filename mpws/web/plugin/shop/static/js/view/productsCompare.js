define("plugin/shop/js/view/productsCompare", [
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/productsCompare',
    "default/js/lib/jquery.cookie"
], function (MView, tpl) {

    var ProductsCompare = MView.extend({
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
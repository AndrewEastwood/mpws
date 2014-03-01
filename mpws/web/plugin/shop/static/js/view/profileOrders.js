define("plugin/shop/js/view/profileOrders", [
    'default/js/view/mView',
    'plugin/shop/js/model/profileOrders',
    'default/js/plugin/hbs!plugin/shop/hbs/profileOrders',
    "default/js/lib/jquery.cookie"
], function (MView, ModelProfileOrders, tpl) {

    var ProfileOrders = MView.extend({
        className: 'row shop-products-compare',
        id: 'shop-products-compare-ID',
        model: new ModelProfileOrders(),
        template: tpl,
        initialize: function() {
            MView.prototype.initialize.call(this);
            this.listenTo(this.model, "change", this.render);
        }
    });

    return ProfileOrders;

});
define("plugin/shop/js/view/toolboxOrders", [
    'default/js/view/mView',
    'plugin/shop/js/model/toolboxOrders',
    'default/js/plugin/hbs!plugin/shop/hbs/toolboxOrders',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/nls/shop',
    "default/js/lib/jquery.cookie",
], function (MView, ModelProfileOrders, tpl, lang) {

    var ProfileOrders = MView.extend({
        className: 'row shop-toolbox-orders',
        id: 'shop-toolbox-orders-ID',
        model: new ModelProfileOrders(),
        template: tpl,
        lang: lang,
        // initialize: function() {
        //     MView.prototype.initialize.call(this);
        //     this.listenTo(this.model, "change", this.render);
        // }
    });

    return ProfileOrders;

});
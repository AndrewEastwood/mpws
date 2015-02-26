define("plugin/shop/site/js/view/profileOrders", [
    'default/js/view/mView',
    'plugin/shop/site/js/model/profileOrders',
    'default/js/plugin/hbs!plugin/shop/site/hbs/profileOrders',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation',
    "default/js/lib/jquery.cookie",
], function (MView, ModelProfileOrders, tpl, lang) {

    var ProfileOrders = MView.extend({
        className: 'row shop-profile-orders',
        id: 'shop-profile-orders-ID',
        model: new ModelProfileOrders(),
        template: tpl,
        lang: lang,
        initialize: function() {
            MView.prototype.initialize.call(this);
            this.listenTo(this.model, "change", this.render);
        }
    });

    return ProfileOrders;

});
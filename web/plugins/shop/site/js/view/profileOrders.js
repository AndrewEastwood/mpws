define([
    'base/js/view/mView',
    'plugins/shop/site/js/model/profileOrders',
    'hbs!plugins/shop/site/hbs/profileOrders',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
    'jquery.cookie',
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
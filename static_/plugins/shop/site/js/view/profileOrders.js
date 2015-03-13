define([
    'handlebars',
    'base/js/view/mView',
    'plugins/shop/site/js/model/profileOrders',
    'text!plugins/shop/site/hbs/profileOrders.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
    'jquery.cookie',
], function (Handlebars, MView, ModelProfileOrders, tpl, lang) {

    var ProfileOrders = MView.extend({
        className: 'row shop-profile-orders',
        id: 'shop-profile-orders-ID',
        model: new ModelProfileOrders(),
        template: Handlebars.compile(tpl), // check
        lang: lang,
        initialize: function() {
            MView.prototype.initialize.call(this);
            this.listenTo(this.model, "change", this.render);
        }
    });

    return ProfileOrders;

});
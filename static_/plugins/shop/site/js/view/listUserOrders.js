define([
    'backbone',
    'handlebars',
    'plugins/shop/site/js/collection/listProductWish',
    'utils',
    'text!plugins/shop/site/hbs/profileOrders.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
    'jquery.cookie'
], function (Backbone, Handlebars, ColListUserOrders, Utils, tpl, lang) {

    var ListProductWish = Backbone.View.extend({
        collection: new ColListUserOrders(),
        className: 'shop-user-orders',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        initialize: function () {
            this.listenTo(this.collection, 'reset', this.render);
            this.listenTo(this.collection, 'sync', this.render);
            Backbone.on('changed:plugin-shop-currency', this.switchCurrency);
        },
        render: function() {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        },
        switchCurrency: function (visibleCurrencyName) {
            this.$('.shop-price-value').addClass('hidden');
            this.$('.shop-price-value.' + visibleCurrencyName).removeClass('hidden');
        }
    });

    return ListProductWish;

});
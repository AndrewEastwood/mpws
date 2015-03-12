define([
    'backbone',
    'plugins/shop/site/js/collection/listProductWish',
    'utils',
    'hbs!plugins/shop/site/hbs/listProductWish',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
    'jquery.cookie'
], function (Backbone, wishCollectionInstance, Utils, tpl, lang) {

    var ListProductWish = Backbone.View.extend({
        collection: wishCollectionInstance,
        className: 'row shop-wishlist-standalone clearfix',
        id: 'shop-cart-wishlist-ID',
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
            this.$('.moneyValue').addClass('hidden');
            this.$('.moneyValue.' + visibleCurrencyName).removeClass('hidden');
        }
    });

    return ListProductWish;

});
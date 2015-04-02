define([
    'backbone',
    'handlebars',
    'plugins/shop/site/js/collection/listProductWish',
    'utils',
    'text!plugins/shop/site/hbs/listProductWish.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
    'jquery.cookie'
], function (Backbone, Handlebars, CollWishList, Utils, tpl, lang) {

    var ListProductWish = Backbone.View.extend({
        collection: CollWishList.getInstance(),
        className: 'shop-productlist-wish',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        initialize: function () {
            this.listenTo(this.collection, 'reset', this.render);
            this.listenTo(this.collection, 'sync', this.render);
            Backbone.on('changed:plugin-shop-currency', this.switchCurrency);
        },
        render: function() {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            this.trigger('shop:rendered');
            return this;
        },
        switchCurrency: function (visibleCurrencyName) {
            this.$('.shop-price-value').addClass('hidden');
            this.$('.shop-price-value.' + visibleCurrencyName).removeClass('hidden');
        }
    });

    return ListProductWish;

});
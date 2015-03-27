define([
    'underscore',
    'backbone',
    'handlebars',
    'utils',
    'handlebars',
    'text!plugins/shop/site/hbs/productItemShort.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation'
], function (_, Backbone, Handlebars, Utils, Handlebars, tpl, lang) {

    // Handlebars.registerDynamicHelper('shopProductTitle', function (data, opts) {
    //     return opts.fn(data._origin.Name + ' ' + data.Model);
    // });

    var ProductItemShort = Backbone.View.extend({
        className: 'shop-product-item shop-product-item-short col-xs-12 col-sm-6 col-md-3 col-lg-3',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        initialize: function () {
            _.bindAll(this, 'refresh', 'switchCurrency');
            APP.Sandbox.eventSubscribe('plugin:shop:list_wish:changed', this.refresh);
            APP.Sandbox.eventSubscribe('plugin:shop:list_compare:changed', this.refresh);
            APP.Sandbox.eventSubscribe('plugin:shop:order:changed', this.refresh);
            Backbone.on('changed:plugin-shop-currency', this.switchCurrency);
            if (this.model)
                this.listenTo(this.model, 'change', this.render);
        },
        refresh: function (data) {
            if (this.model) {
                if (data && data.id && (data.id === this.model.id || data.id === "*")) {
                    this.model.fetch();
                }
            }
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            // shop pulse animation for cart button badge
            if (this.model.hasChanged('_viewExtras') && this.model.previous('_viewExtras') && this.model.get('_viewExtras').InCartCount !== this.model.previous('_viewExtras').InCartCount)
                this.$('.btn.withNotificationBadge .badge').addClass("pulse").delay(1000).queue(function(){
                    $(this).removeClass("pulse").dequeue();
                });
            this.$('[data-toggle="tooltip"]').tooltip();
            this.$('.product-availability .fa').popover({trigger: 'hover'});
            return this;
        },
        switchCurrency: function (visibleCurrencyName) {
            this.$('.moneyValue').addClass('hidden');
            this.$('.moneyValue.' + visibleCurrencyName).removeClass('hidden');
        }
    });

    return ProductItemShort;

});
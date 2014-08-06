define("plugin/shop/site/js/view/productItemShort", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/productItemShort',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation'
], function (Sandbox, _, Backbone, Utils, tpl, lang) {

    var ProductItemShort = Backbone.View.extend({
        className: 'shop-product-item shop-product-item-short col-xs-12 col-sm-6 col-md-3 col-lg-3',
        template: tpl,
        lang: lang,
        initialize: function () {
            _.bindAll(this, 'refresh');
            Sandbox.eventSubscribe('plugin:shop:list_wish:changed', this.refresh);
            Sandbox.eventSubscribe('plugin:shop:list_compare:changed', this.refresh);
            Sandbox.eventSubscribe('plugin:shop:order:product:changed', this.refresh);
            if (this.model)
                this.listenTo(this.model, 'change', this.render);
        },
        refresh: function (productID) {
            debugger;
            if (this.model) {
                if (productID && (productID === this.model.id || productID === "*")) {
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
            return this;
        }
    });

    return ProductItemShort;

});
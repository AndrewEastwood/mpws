define([
    'backbone',
    'handlebars',
    'plugins/shop/site/js/collection/listUserOrders',
    'utils',
    'bootstrap-dialog',
    'text!plugins/shop/site/hbs/listUserOrders.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
    'jquery.cookie'
], function (Backbone, Handlebars, ColListUserOrders, Utils, BootstrapDialog, tpl, lang) {

    var ListProductWish = Backbone.View.extend({
        collection: new ColListUserOrders(),
        className: 'bootstrap-dialog type-primary size-normal plugin-shop-user-orders',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        initialize: function () {
            this.listenTo(this.collection, 'reset', this.render);
            this.listenTo(this.collection, 'sync', this.render);
            Backbone.on('changed:plugin-shop-currency', this.switchCurrency);
        },
        render: function() {
            var $dialog = new BootstrapDialog({
                closable: false,
                title: 'Мої Замовлення',
                message: $(this.template(Utils.getHBSTemplateData(this))),
                buttons: false
            });
            // $dialog.open();
            $dialog.realize();
            $dialog.updateMessage();
            $dialog.updateClosable();

            this.$el.html($dialog.getModalContent());
            return this;
        },
        switchCurrency: function (visibleCurrencyName) {
            this.$('.shop-price-value').addClass('hidden');
            this.$('.shop-price-value.' + visibleCurrencyName).removeClass('hidden');
        }
    });

    return ListProductWish;

});
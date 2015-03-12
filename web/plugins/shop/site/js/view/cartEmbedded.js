define([
    'underscore',
    'backbone',
    'utils',
    'bootstrap-dialog',
    // 'plugins/shop/site/js/collection/listProductCart',
    'hbs!plugins/shop/site/hbs/cartEmbedded',
    /* lang */
    'i18n!plugins/shop/site/nls/translation'
], function (_, Backbone, Utils, BootstrapDialog, tpl, lang) {

    var CartEmbedded = Backbone.View.extend({
        className: 'btn-group shop-cart-embedded',
        id: 'shop-cart-embedded-ID',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            'click .open-shopping-cart-embedded': 'openShoppingCartModal'
        },
        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
            Backbone.on('changed:plugin-shop-currency', this.switchCurrency);
        },
        render: function () {
            var tplData = Utils.getHBSTemplateData(this),
                items = _(tplData.data.items).toArray();
            this.$el.html(this.template(tplData));
        },
        openShoppingCartModal: function () {
            BootstrapDialog.show({
                draggable: false,
                type: BootstrapDialog.TYPE_WARNING,
                cssClass: 'shop-cart-embedded-modal',
                title: function () {
                    var $title = $('<span>');
                    $title.append('<i class="fa fa-shopping-cart fa-fw"></i>');
                    $title.append('Ваш кошик');

                    return $title;
                },
                message: this.$('.shopping-cart-embedded').clone().removeClass('hidden'),
                buttons: [{
                    label: 'Переглянути всі позиції',
                    cssClass: 'btn btn-success',
                    action: function (dialog) {
                        window.location.hash = APP.instances.shop.urls.shop_cart;
                        dialog.close();
                    }
                }]
            });
        },
        switchCurrency: function (visibleCurrencyName) {
            this.$('.moneyValue').addClass('hidden');
            this.$('.moneyValue.' + visibleCurrencyName).removeClass('hidden');
        }
    });

    return CartEmbedded;

});
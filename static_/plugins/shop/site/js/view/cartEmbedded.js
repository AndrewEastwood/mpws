define([
    'underscore',
    'backbone',
    'handlebars',
    'plugins/shop/site/js/model/order',
    'bootstrap-dialog',
    'utils',
    'bootstrap-dialog',
    'text!plugins/shop/site/hbs/cartEmbedded.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
    'echo'
], function (_, Backbone, Handlebars, ModelOrder, BootstrapDialog, Utils, BootstrapDialog, tpl, lang, echo) {

    var CartEmbedded = Backbone.View.extend({
        className: 'shop-cart-embedded',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            'click .shop-cart-product-remove': 'removeProduct'
            // 'click .open-shopping-cart-embedded': 'openShoppingCartModal',
        },
        initialize: function () {
            this.model = ModelOrder.getInstance();
            _.bindAll(this, 'removeProduct');
            this.listenTo(this.model, 'change', this.render);
            Backbone.on('changed:plugin-shop-currency', this.switchCurrency);
        },
        render: function () {
            var tplData = Utils.getHBSTemplateData(this),
                items = _(tplData.data.items).toArray();
            this.$el.html(this.template(tplData));
            this.delegateEvents();
            echo.init({
                offset: 100,
                throttle: 250,
                callback: function(element, op) {
                    console.log(op)
                    if(op === 'load') {
                        element.classList.add('loaded');
                    } else {
                        element.classList.remove('loaded');
                    }
                }
            });
            return this;
        },
        removeProduct: function (event) {
            var that = this,
                $target = $(event.target).parents('a'),
                productID = $target.data('id');
            BootstrapDialog.confirm('Видалити цей товар?', function (result) {
                if (result) {
                    that.model.removeProduct(productID);
                }
            });
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
                        window.location.hash = APP.instances.shop.urls.shopCart;
                        dialog.close();
                    }
                }]
            });
        },
        switchCurrency: function (visibleCurrencyName) {
            this.$('.shop-price-value').addClass('hidden');
            this.$('.shop-price-value.' + visibleCurrencyName).removeClass('hidden');
        }
    });

    return CartEmbedded;

});
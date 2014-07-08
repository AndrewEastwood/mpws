define('plugin/shop/site/js/model/order', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'plugin/shop/common/js/lib/utils',
    'default/js/lib/bootstrap-alert',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation',
], function (Sandbox, Backbone, _, ShopUtils, BSAlert, lang) {

    // debugger;
    var model = Backbone.Model.extend({
        idAttribute: "ID",
        defaults: {
            items: {},
            info: {},
            promo: {},
            account: {}
        },
        initialize: function () {
            var self = this;
            _.bindAll(this, 'productAdd', 'productRemove', 'productRemoveAll');
            Sandbox.eventSubscribe('plugin:shop:order:add', this.productAdd);
            Sandbox.eventSubscribe('plugin:shop:order:remove', this.productRemove);
            Sandbox.eventSubscribe('plugin:shop:order:clear', this.productRemoveAll);
        },
        parse: function (data) {
            // debugger;
            return {
                account: data.account || {},
                info: data.info || {},
                promo: data.promo || {},
                items: _(data.items).reduce(function(target, productData){
                    target[productData.ID] = ShopUtils.adjustProductItem(productData);
                    return target;
                }, {})
            };
            // debugger;
            // this.shoppingCart.reset(_(data.items).map(function(item){ return item; }), {parse: true});
            // return ShopUtils.adjustProductItem(data);
        },
        getProductCount: function () {
            return Object.getOwnPropertyNames(this.get('items') || {}).length;
        },
        getProductByID: function (productID) {
            return this.get('items')[productID] || null;
        },
        setProductQuantity:  function (event, productID, quantity) {
            var self = this;
            this.sync("patch", this, {
                attrs: {
                    productID: productID,
                    Quantity: parseInt(quantity, 10),
                },
                parse: true,
                success: function (response) {
                    self.set(self.parse(response));
                    BSAlert.warning(lang.list_cart_alert_updated);
                    Sandbox.eventNotify('plugin:shop:order:changed', event);
                }
            });
        },
        productAdd: function (event) {
            // debugger;
            var product = this.getProductByID(event.id);
            if (product)
                this.setProductQuantity(event, event.id, product.Quantity + 1);
            else
                this.setProductQuantity(event, event.id, 1);
        },
        productRemove: function (event) {
            var product = this.getProductByID(event.id);
            if (product)
                this.setProductQuantity(event, event.id, 0);
        },
        productRemoveAll: function (event) {
            this.setProductQuantity(event, event.id, 0);
        },
        applyPromo: function (promo) {
            var self = this;
            this.sync("patch", this, {
                attrs: {
                    promo: promo
                },
                parse: true,
                success: function (response) {
                    self.set(self.parse(response));
                    if (!!promo)
                        BSAlert.success(lang.list_cart_alert_promoAdded);
                    else
                        BSAlert.danger(lang.list_cart_alert_promoRemoved);
                    Sandbox.eventNotify('plugin:shop:order:changed', event);
                }
            });
        }
    });

    // order = new model();
    return model;

});
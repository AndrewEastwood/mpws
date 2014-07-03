define('plugin/shop/site/js/model/order', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'plugin/shop/site/js/model/product',
    'plugin/shop/common/js/lib/utils',
    'default/js/lib/bootstrap-alert',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation',
], function (Sandbox, Backbone, _, ModelProduct, ShopUtils, BSAlert, lang) {

    // debugger;
    var model = Backbone.Model.extend({
        idAttribute: "ID",
        defaults: {
            items: {},
            info: {},
            account: {}
        },
        initialize: function () {
            var self = this;
            _.bindAll(this, 'productAdd', 'productRemove', 'productRemoveAll');
            Sandbox.eventSubscribe('plugin:shop:order:add', this.productAdd);
            Sandbox.eventSubscribe('plugin:shop:order:remove', this.productRemove);
            Sandbox.eventSubscribe('plugin:shop:order:clear', this.productRemoveAll);
            // Sandbox.eventSubscribe('plugin:shop:list_cart:changed', function(data) {
            //     // Sandbox.eventNotify('plugin:shop:order:changed', self);
            //     debugger;
            //     self.trigger('change');
            // });
            // this.on('change', function () {
            //     debugger;
            // });
        },
        parse: function (data) {
            // debugger;
            return {
                account: data.account || {},
                info: data.info || {},
                items: _(data.items).reduce(function(target, productData){
                    var _product = new ModelProduct(productData);
                    target[_product.id] = _product.toJSON();
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
            var product = this.get('items')[event.id];
            if (product)
                this.setProductQuantity(event, event.id, product.Quantity + 1);
            else
                this.setProductQuantity(event, event.id, 1);
        },
        productRemove: function (event) {
            var product = this.get('items')[event.id];
            if (product)
                this.setProductQuantity(event, event.id, 0);
        },
        productRemoveAll: function (event) {
            this.setProductQuantity(event, event.id, 0);
        }
    });

    // order = new model();
    return model;

});
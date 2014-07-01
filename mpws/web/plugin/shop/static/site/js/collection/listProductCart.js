define('plugin/shop/site/js/collection/listProductCart', [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/site/js/model/product',
    'plugin/shop/common/js/lib/utils',
    'default/js/lib/bootstrap-alert',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation',
], function (Sandbox, _, Backbone, ModelProduct, ShopUtils, BSAlert, lang) {

    var ListProductCart = Backbone.Collection.extend({
        model: ModelProduct,
        extras: {},
        url: function (options) {
            return APP.getApiLink(_.extend({
                source: 'shop',
                fn: 'cart'}, options));
        },
        initialize: function () {
            listProductCart = this;
            _.bindAll(this, '_productDecrease', '_productIncrease', '_productAdd', '_productRemove', '_productClear');
            Sandbox.eventSubscribe('plugin:shop:list_cart:add', this._productAdd);
            Sandbox.eventSubscribe('plugin:shop:list_cart:decrease', this._productDecrease);
            Sandbox.eventSubscribe('plugin:shop:list_cart:increase', this._productIncrease);
            Sandbox.eventSubscribe('plugin:shop:list_cart:remove', this._productRemove);
            Sandbox.eventSubscribe('plugin:shop:list_cart:clear', this._productClear);
        },
        _productDecrease:  function (data) {
            var self = this;
            if (data && data.id) {
                var model = this.get(data.id);
                if (model) {
                    model.save({Quantity: model.get('Quantity') - 1}, {
                        url: this.url({productID: data.id}),
                        success: function (collection, resp) {
                            self.reset(self.parse(resp), {parse: true});
                            Sandbox.eventNotify('plugin:shop:list_cart:changed', data);
                            BSAlert.warning(lang.list_cart_alert_updated);
                        }
                    });
                }
            }
        },
        _productIncrease:  function (data) {
            var self = this;
            if (data && data.id) {
                var model = this.get(data.id);
                if (model) {
                    model.save({Quantity: model.get('Quantity') + 1},{
                        url: this.url({productID: data.id}),
                        success: function (collection, resp) {
                            self.reset(self.parse(resp), {parse: true});
                            Sandbox.eventNotify('plugin:shop:list_cart:changed', data);
                            BSAlert.warning(lang.list_cart_alert_updated);
                        }
                    });
                }
            }
        },
        _productAdd: function (data) {
            var self = this;
            if (data && data.id) {
                this.create({productID: data.id}, {
                    url: this.url(),
                    success: function (model, resp) {
                        self.reset(self.parse(resp), {parse: true});
                        Sandbox.eventNotify('plugin:shop:list_cart:changed', data);
                        BSAlert.success(lang.list_cart_alert_add);
                    }
                });
            }
        },
        _productRemove: function (data) {
            var self = this;
            if (data && data.id) {
                var model = this.get(data.id);
                if (model) {
                    model.destroy({
                        url: this.url({productID: data.id}),
                        success: function (collection, resp) {
                            self.reset(self.parse(resp), {parse: true});
                            Sandbox.eventNotify('plugin:shop:list_cart:changed', data);
                            BSAlert.warning(lang.list_cart_alert_remove);
                        }
                    });
                }
            }
        },
        _productClear: function (data) {
            var self = this;
            this.sync("delete", this, {
                url: this.url({productID: "*"}),
                success: function (collection, resp) {
                    self.reset(self.parse(resp), {parse: true});
                    Sandbox.eventNotify('plugin:shop:list_cart:changed', data);
                    BSAlert.danger(lang.list_cart_alert_clear);
                }
            });
        },
        parse: function (data) {
            // var self = this;
            if (data.info && data.items) {
                this.extras.info = data.info;
                return _(data.items).map(function(item){ return item; });
            }
            return data;
            // debugger;
        }
    });

    return new ListProductCart();
});
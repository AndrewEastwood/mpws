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
        url: function (options) {
            return APP.getApiLink(_.extend({
                source: 'shop',
                fn: 'cart'}, options));
        },
        initialize: function () {
            listProductCart = this;
            _.bindAll(this, 'decrease', 'increase', 'add', 'remove', 'clear');
            Sandbox.eventSubscribe('plugin:shop:list_cart:add', this.add);
            Sandbox.eventSubscribe('plugin:shop:list_cart:decrease', this.decrease);
            Sandbox.eventSubscribe('plugin:shop:list_cart:increase', this.increase);
            Sandbox.eventSubscribe('plugin:shop:list_cart:remove', this.remove);
            Sandbox.eventSubscribe('plugin:shop:list_cart:clear', this.clear);
        },
        decrease:  function (data) {
            var self = this;
            if (data && data.id) {
                var model = this.get(data.id);
                if (model) {
                    model.save({
                        url: this.url({productID: data.id, decrease: 1}),
                        success: function (collection, resp) {
                            self.reset(self.parse(resp));
                            Sandbox.eventNotify('plugin:shop:list_cart:changed', data);
                            BSAlert.warning(lang.list_cart_alert_updated);
                        }
                    });
                }
            }
        },
        increase:  function (data) {
            var self = this;
            if (data && data.id) {
                var model = this.get(data.id);
                if (model) {
                    model.save({
                        url: this.url({productID: data.id}),
                        success: function (collection, resp) {
                            self.reset(self.parse(resp));
                            Sandbox.eventNotify('plugin:shop:list_cart:changed', data);
                            BSAlert.warning(lang.list_cart_alert_updated);
                        }
                    });
                }
            }
        },
        add: function (data) {
            var self = this;
            if (data && data.id) {
                this.create({productID: data.id}, {
                    url: this.url(),
                    success: function (model, resp) {
                        self.reset(self.parse(resp));
                        Sandbox.eventNotify('plugin:shop:list_cart:changed', data);
                        BSAlert.success(lang.list_cart_alert_add);
                    }
                });
            }
        },
        remove: function (data) {
            var self = this;
            if (data && data.id) {
                var model = this.get(data.id);
                if (model) {
                    model.destroy({
                        url: this.url({productID: data.id}),
                        success: function (collection, resp) {
                            self.reset(self.parse(resp));
                            Sandbox.eventNotify('plugin:shop:list_cart:changed', data);
                            BSAlert.warning(lang.list_cart_alert_remove);
                        }
                    });
                }
            }
        },
        clear: function (data) {
            var self = this;
            this.sync("delete", this, {
                url: this.url({productID: "*"}),
                success: function (collection, resp) {
                    self.reset(self.parse(resp));
                    Sandbox.eventNotify('plugin:shop:list_cart:changed', data);
                    BSAlert.danger(lang.list_cart_alert_clear);
                }
            });
        },
        parse: function (data) {
            // var self = this;
            // debugger;
            this.info = data.info;
            return _(data.items).map(function(item){ return item; });
        }
    });

    return new ListProductCart();
});
define('plugin/shop/site/js/collection/listProductWish', [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/site/js/model/product',
    'plugin/shop/common/js/lib/utils',
    'default/js/lib/bootstrap-alert',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation',
], function (Sandbox, _, Backbone, ModelProduct, ShopUtils, BSAlert, lang) {

    var ListProductWish = Backbone.Collection.extend({
        model: ModelProduct,
        url: function (options) {
            options = options || {};
            return APP.getApiLink(_.extend({}, options, {
                source: 'shop',
                fn: 'wishlists'}));
        },
        initialize: function () {
            // listProductWish = this;
            _.bindAll(this, 'removeProductByID', 'addNew', 'removeAll');
            Sandbox.eventSubscribe('plugin:shop:list_wish:add', this.addNew);
            Sandbox.eventSubscribe('plugin:shop:list_wish:remove', this.removeProductByID);
            Sandbox.eventSubscribe('plugin:shop:list_wish:clear', this.removeAll);
        },
        removeProductByID:  function (data) {
            // debugger;
            var self = this;
            if (data && data.id) {
                var model = this.get(data.id);
                if (model) {
                    model.destroy({
                        url: this.url({productID: data.id}),
                        success: function (collection, resp) {
                            self.reset(self.parse(resp));
                            Sandbox.eventNotify('plugin:shop:list_wish:changed', data);
                            BSAlert.warning(lang.list_wish_alert_remove);
                        }
                    });
                }
            }
        },
        addNew: function (data) {
            // debugger;
            var self = this;
            if (data && data.id && !this.get(data.id)) {
                this.create({productID: data.id}, {
                    url: this.url(),
                    success: function (model, resp) {
                        self.reset(self.parse(resp));
                        Sandbox.eventNotify('plugin:shop:list_wish:changed', data);
                        BSAlert.success(lang.list_compare_alert_add);
                    }
                });
            }
        },
        removeAll: function (data) {
            var self = this;
            this.sync("delete", this, {
                url: this.url({productID: "*"}),
                success: function (collection, resp) {
                    self.reset(self.parse(resp));
                    Sandbox.eventNotify('plugin:shop:list_wish:changed', data);
                    BSAlert.danger(lang.list_compare_alert_clear);
                }
            });
        }
    });

    return new ListProductWish();
});
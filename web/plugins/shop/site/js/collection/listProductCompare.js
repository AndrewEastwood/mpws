define([
    'underscore',
    'backbone',
    'plugins/shop/site/js/model/product',
    'bootstrap-alert',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
], function (_, Backbone, ModelProduct, BSAlert, lang) {

    var ListProductCompare = Backbone.Collection.extend({
        model: ModelProduct,
        url: function (options) {
            options = options || {};
            return APP.getApiLink(_.extend({}, options, {
                source: 'shop',
                fn: 'comparelists'}));
        },
        initialize: function () {
            listProductCompare = this;
            _.bindAll(this, 'removeProductByID', 'addNew', 'removeAll');
            APP.Sandbox.eventSubscribe('plugin:shop:list_compare:add', this.addNew);
            APP.Sandbox.eventSubscribe('plugin:shop:list_compare:remove', this.removeProductByID);
            APP.Sandbox.eventSubscribe('plugin:shop:list_compare:clear', this.removeAll);
        },
        removeProductByID:  function (data) {
            var self = this;
            if (data && data.id) {
                var model = this.get(data.id);
                if (model) {
                    model.destroy({
                        url: this.url({productID: data.id}),
                        success: function (model, resp) {
                            self.reset(resp);
                            APP.Sandbox.eventNotify('plugin:shop:list_compare:changed', data);
                            BSAlert.warning(lang.list_wish_alert_remove);
                        }
                    });
                }
            }
        },
        addNew: function (data) {
            var self = this;
            if (data && data.id && !this.get(data.id)) {
                this.create({productID: data.id}, {
                    url: this.url(),
                    success: function (model, resp) {
                        self.reset(resp);
                        if (resp.error) {
                            BSAlert.danger(lang['list_compare_alert_' + resp.error]);
                        } else {
                            BSAlert.success(lang.list_compare_alert_add);
                        }
                        APP.Sandbox.eventNotify('plugin:shop:list_compare:changed', data);
                    }
                });
            }
        },
        removeAll: function (data) {
            var self = this;
            this.sync("delete", this, {
                url: this.url({productID: "*"}),
                success: function (resp) {
                    self.reset(resp);
                    APP.Sandbox.eventNotify('plugin:shop:list_compare:changed', data);
                    BSAlert.danger(lang.list_wish_alert_clear);
                }
            });
        }
    });

    return new ListProductCompare();
});
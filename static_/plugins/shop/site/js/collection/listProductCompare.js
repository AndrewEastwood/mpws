define([
    'underscore',
    'backbone',
    'plugins/shop/site/js/model/product',
    'bootstrap-alert',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
], function (_, Backbone, ModelProduct, BSAlert, lang) {

    var instance = null;

    var ListProductCompare = Backbone.Collection.extend({
        model: ModelProduct,
        url: function (options) {
            options = options || {};
            return APP.getApiLink(_.extend({}, options, {
                source: 'shop',
                fn: 'comparelists'}));
        },
        // initialize: function () {
        //     listProductCompare = this;
        //     _.bindAll(this, 'removeProduct', 'addProduct', 'removeAll');
        //     // APP.Sandbox.eventSubscribe('plugin:shop:list_compare:add', this.addNew);
        //     // APP.Sandbox.eventSubscribe('plugin:shop:list_compare:remove', this.removeProductByID);
        //     // APP.Sandbox.eventSubscribe('plugin:shop:list_compare:clear', this.removeAll);
        // },
        removeProduct:  function (productID) {
            var self = this,
                model = this.get(productID);
            if (model) {
                model.destroy({
                    url: this.url({productID: productID}),
                    // success: function (model, resp) {
                    //     self.reset(resp);
                    //     // APP.Sandbox.eventNotify('plugin:shop:list_compare:changed', data);
                    //     // BSAlert.warning(lang.list_wish_alert_remove);
                    //     // Backbone.trigger('changed:plugin-shop-comparelist', self.length);
                    // }
                });
            }
        },
        addProduct: function (productID) {
            var self = this,
                model = this.get(productID);
            if (!model) {
                this.create({productID: productID}, {
                    url: this.url(),
                    // success: function (model, resp) {
                    //     self.reset(resp);
                    //     if (resp.error) {
                    //     //     BSAlert.danger(lang['list_compare_alert_' + resp.error]);
                    //     } else {
                    //     //     BSAlert.success(lang.list_compare_alert_add);
                    //         // APP.Sandbox.eventNotify('plugin:shop:list_compare:changed', data);
                    //     }
                    //     // Backbone.trigger('changed:plugin-shop-comparelist', self.length);
                    // }
                });
            }
        },
        removeAll: function () {
            var self = this;
            this.sync("delete", this, {
                url: this.url({productID: "*"}),
                // success: function (resp) {
                //     self.reset(resp);
                //     // APP.Sandbox.eventNotify('plugin:shop:list_compare:changed', data);
                //     // BSAlert.danger(lang.list_wish_alert_clear);
                //     // Backbone.trigger('changed:plugin-shop-comparelist', self.length);
                // }
            });
        }
    }, {
        getInstance: function (options) {
            if (instance) {
                return instance;
            } else {
                instance = new ListProductCompare(options);
                return instance;
            }
        }
    });

    return ListProductCompare;
});
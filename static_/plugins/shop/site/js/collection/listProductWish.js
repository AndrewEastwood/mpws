define([
    'underscore',
    'backbone',
    'plugins/shop/site/js/model/product',
    'bootstrap-alert',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
], function (_, Backbone, ModelProduct, BSAlert, lang) {

    var instance = null;

    var ListProductWish = Backbone.Collection.extend({
        model: ModelProduct,
        url: function (options) {
            return APP.getApiLink('shop', 'wishlists', options);
        },
        // initialize: function () {
        //     // listProductWish = this;
        //     _.bindAll(this, 'removeProductByID', 'addNew', 'removeAll');
        //     APP.Sandbox.eventSubscribe('plugin:shop:list_wish:add', this.addNew);
        //     APP.Sandbox.eventSubscribe('plugin:shop:list_wish:remove', this.removeProductByID);
        //     APP.Sandbox.eventSubscribe('plugin:shop:list_wish:clear', this.removeAll);
        // },
        removeProduct:  function (productID) {
            var self = this,
                model = this.get(productID);
            if (model) {
                model.destroy({
                    url: this.url({productID: productID}),
                    // success: function (collection, resp) {
                    //     self.reset(resp);
                    //     // APP.Sandbox.eventNotify('plugin:shop:list_wish:changed', data);
                    //     // BSAlert.warning(lang.list_wish_alert_remove);
                    //     // Backbone.trigger('changed:plugin-shop-wishlist', self.length);
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
                    //     // APP.Sandbox.eventNotify('plugin:shop:list_wish:changed', data);
                    //     // BSAlert.success(lang.list_compare_alert_add);
                    //     // Backbone.trigger('changed:plugin-shop-wishlist', self.length);
                    // }
                });
            }
        },
        removeAll: function () {
            var self = this;
            this.sync("delete", this, {
                url: this.url({productID: "*"}),
                // success: function (resp, status) {
                //     self.reset(resp);
                //     // APP.Sandbox.eventNotify('plugin:shop:list_wish:changed', data);
                //     // BSAlert.danger(lang.list_compare_alert_clear);
                //     // Backbone.trigger('changed:plugin-shop-wishlist', self.length);
                // }
            });
        }
    }, {
        getInstance: function (options) {
            if (instance) {
                return instance;
            } else {
                instance = new ListProductWish(options);
                return instance;
            }
        }
    });

    return ListProductWish;
});
define([
    'backbone',
    'underscore',
    'bootstrap-alert',
    'bootstrap-dialog',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
], function (Backbone, _, BSAlert, BootstrapDialog, lang) {

    var instance = null;
    // debugger;
    var Order = Backbone.Model.extend({
        idAttribute: 'ID',
        url: APP.getApiLink('shop', 'orders'),
        defaults: {
            items: {},
            info: {},
            promo: {},
            account: {}
        },
        // initialize: function () {
        //     var self = this;
        //     // _.bindAll(this, 'productAdd', 'productRemove', 'productRemoveAll');
        //     // APP.Sandbox.eventSubscribe('plugin:shop:order:add', this.productAdd);
        //     // APP.Sandbox.eventSubscribe('plugin:shop:order:remove', this.productRemove);
        //     // APP.Sandbox.eventSubscribe('plugin:shop:order:clear', this.productRemoveAll);
        //     // APP.Sandbox.eventSubscribe('global:route', $.proxy(function () {
        //     //     if (self.isSaved.call(self)) {
        //     //         self.clear({silent: true});
        //     //         self.fetch();
        //     //     }
        //     // }, this));
        // },
        isSaved: function () {
            return this.get('Hash') && this.get('success');
        },
        // parse: function (data) {
        //     if (data && data.items)
        //         data.items = _(data.items).reduce(function(target, productData){
        //             target[productData.ID] = ShopUtils.adjustProductItem(productData);
        //             return target;
        //         }, {});
        //     return data;
        // },
        getProductCount: function () {
            return _(this.get('items') || {}).keys().length;
        },
        getProductByID: function (productID) {
            return this.get('items')[productID] || null;
        },
        setProduct:  function (productID, quantity) {
            var self = this,
                product = this.getProductByID(productID),
                isNew = !!!product,
                existentQ = !isNew && product._orderQuantity || 0,
                quantity = quantity + existentQ;
            this.sync("patch", this, {
                attrs: {
                    productID: productID,
                    _orderQuantity: parseInt(quantity, 10),
                },
                parse: true,
                success: function (response) {
                    self.set(self.parse(response));
                    if (quantity === 0) {
                        self.trigger('product:removed');
                    } else {
                        self.trigger('product:' + (isNew ? 'added' : 'updated'));
                    }
                    // if (isNew) {
                    //     BSAlert.success(lang.list_cart_alert_add);
                    // } else {
                    //     BSAlert.warning(lang.list_cart_alert_updated);
                    // }
                    // APP.Sandbox.eventNotify('plugin:shop:order:changed', event);
                }
            });
        },
        // addProduct: function (id) {
        //     // debugger;
        //     var product = this.getProductByID(id);
        //     if (product) {
        //         this.setProductQuantity(id, product._orderQuantity + 1);
        //     } else {
        //         this.setProductQuantity(id, 1);
        //     }
        // },
        removeProduct: function (id) {
            var self = this;
            BootstrapDialog.confirm('Видалити цей товар?', function (result) {
                if (result) {
                    var product = self.getProductByID(id);
                    if (product) {
                        self.setProductQuantity(id, 0);
                    }
                }
            });
        },
        clearAll: function (id) {
            var self = this;
            BootstrapDialog.confirm('Видалити всі товари з кошика?', function (result) {
                if (result) {
                    self.setProductQuantity(id, 0);
                }
            });
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
                    if (!!promo) {
                        if (self.get('promo').Code)
                            self.trigger('promo:applied');
                            // BSAlert.success(lang.list_cart_alert_promoAdded);
                        else
                            self.trigger('promo:invalid');
                            // BSAlert.danger(lang.list_cart_alert_promoRejected);
                    } else {
                        self.trigger('promo:cancelled');
                        // BSAlert.danger(lang.list_cart_alert_promoRemoved);
                    }
                    // APP.Sandbox.eventNotify('plugin:shop:order:changed');
                }
            });
        },
        saveOrder: function (formData) {
            var self = this;
            // debugger;
            // return;
            this.set('form', formData, {silent: true});
            this.sync("create", this, {
                success: function (response) {
                    // debugger;
                    self.set(self.parse(response));
                    self.trigger('change');
                    // APP.Sandbox.eventNotify('plugin:shop:order:changed');
                }
            });
        }
    }, {
        getInstance: function (options) {
            if (instance) {
                return instance;
            } else {
                instance = new Order(options);
                return instance;
            }
        }
    });

    // order = new Order();
    return Order;

});
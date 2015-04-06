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
        initialize: function () {
            var that = this;
            this.dfdState = new $.Deferred(),
            this.on('sync', function () {
                that.dfdState.resolve();
            });
        },
        isSaved: function () {
            return this.get('Hash') && this.get('success');
        },
        getProductCount: function () {
            return _(this.get('items') || {}).keys().length;
        },
        getProductQunatity: function (productID) {
            var product = this.getProductByID(productID);
            return product && product._orderQuantity || 0;
        },
        getProductByID: function (productID) {
            return this.get('items')[productID] || null;
        },
        setProduct:  function (productID, quantity, skipUpdate) {
            var that = this,
                product = this.getProductByID(productID),
                isNew = !!!product,
                existentQ = !isNew && product._orderQuantity || 0,
                quantity = !!skipUpdate ? quantity : quantity + existentQ;
            this.save({
                    productID: productID,
                    _orderQuantity: parseInt(quantity, 10),
                }, {
                    patch: true,
                    success: function (response) {
                        that.trigger('change');
                        if (quantity === 0) {
                            that.trigger('product:removed');
                        } else {
                            that.trigger('product:quantity:updated', quantity);
                            if (isNew) {
                                that.trigger('product:added', quantity);
                            }
                        }
                    }
                }
            );
        },
        removeProduct: function (productID) {
            var product = this.getProductByID(productID);
            if (!product) {
                return;
            }
            this.setProduct(productID, 0, true);
        },
        clearAll: function () {
            if (!this.getProductCount()) {
                return;
            }
            this.setProduct('*', 0, true);
        },
        applyPromo: function (promo) {
            var that = this;
            this.sync("patch", this, {
                attrs: {
                    promo: promo
                },
                parse: true,
                success: function (response) {
                    that.set(that.parse(response));
                    if (!!promo) {
                        if (that.get('promo').Code)
                            that.trigger('promo:applied');
                        else
                            that.trigger('promo:invalid');
                    } else {
                        that.trigger('promo:cancelled');
                    }
                }
            });
        },
        saveOrder: function (formData) {
            var that = this;
            // debugger;
            this.set('form', formData, {silent: true});
            return this.sync("create", this, {
                success: function (response) {
                    that.set(that.parse(response));
                    if (that.isSaved()) {
                        that.trigger('saved');
                        that.clear();
                        that.set({ID: 'temp'});
                        this.fetch({silent: true})
                    }
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

    return Order;
});
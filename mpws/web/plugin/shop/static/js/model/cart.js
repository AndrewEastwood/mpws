define("plugin/shop/js/model/cart", [
    'default/js/lib/sandbox',
    'default/js/model/mModel',
    'plugin/shop/js/lib/utils'
], function (Sandbox, MModel, ShopUtils) {

    var Model = MModel.getNewModel();

    var Cart = Model.extend({
        // Consider how to inject this
        // -=-=-=-=-=-=-=-=-=-=-=-=
        // globalEvents: {
        //     'shop:cart:add': 'productAdd'
        // },
        initialize: function () {
            Model.prototype.initialize.call(this);

            var _self = this;
            // debugger;
            this.updateUrlOptions({
                source: 'shop',
                fn: 'shop_cart',
                action: 'INFO'
            });

            Sandbox.eventSubscribe('shop:cart:add', function (data) {
                // debugger;
                if (data && data.id)
                    _self.productAdd(data.id, 1);
            });
            Sandbox.eventSubscribe('shop:cart:sub', function (data) {
                // debugger;
                if (data && data.id)
                    _self.productAdd(data.id, -1);
            });
            Sandbox.eventSubscribe('shop:cart:remove', function (data) {
                // debugger;
                if (data && data.id)
                    _self.productRemove(data.id);
            });
            Sandbox.eventSubscribe('shop:cart:clear', function () {
                // debugger;
                _self.clearAll();
            });

            this.on('change', function () {
                _self.resetUrlOptions();
                Sandbox.eventNotify('shop:cart:info', _self.toJSON());
            });

        },
        parse: function (data) {
            // debugger;
            var products = ShopUtils.adjustProductEntry(data && data.shop);
            return {
                user: data.shop.user || {},
                info: data.shop.info,
                products: _(products).map(function(item){ return item; })
            };
        },
        getInfo: function () {
            this.updateUrlOptions({
                action: 'INFO'
            });
            this.fetch();
        },
        clearAll: function () {
            this.updateUrlOptions({
                action: 'CLEAR'
            });
            this.fetch();
        },
        productAdd: function (productID, productQuantity) {
            this.updateUrlOptions({
                action: 'ADD',
                productID: productID,
                productQuantity: productQuantity
            });
            this.fetch();
        },
        productRemove: function (productID) {
            this.updateUrlOptions({
                action: 'REMOVE',
                productID: productID
            });
            this.fetch();
        },
        checkout: function (userData) {
            this.updateUrlOptions({
                action: 'SAVE'
            });

            $.post(this.url, userData, function(){
                debugger;
            });
        }
    });

    return Cart;

});
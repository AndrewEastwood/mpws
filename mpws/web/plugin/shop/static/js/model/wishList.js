define("plugin/shop/js/model/wishList", [
    'default/js/lib/sandbox',
    'default/js/model/mModel',
    'plugin/shop/js/lib/utils'
], function (Sandbox, MModel, ShopUtils) {

    var Cart = MModel.extend({
        // Consider how to inject this
        // -=-=-=-=-=-=-=-=-=-=-=-=
        // globalEvents: {
        //     'shop:cart:add': 'productAdd'
        // },
        initialize: function () {
            var _self = this;
            // debugger;
            this.updateUrlOptions({
                source: 'shop',
                fn: 'shop_wishlist',
                cartAction: 'INFO'
            });
            MModel.prototype.initialize.call(this);

            Sandbox.eventSubscribe('shop:wishlist:add', function (data) {
                // debugger;
                if (data && data.id)
                    _self.productAdd(data.id);
            });
            Sandbox.eventSubscribe('shop:wishlist:remove', function (data) {
                // debugger;
                if (data && data.id)
                    _self.productRemove(data.id);
            });
            Sandbox.eventSubscribe('shop:wishlist:clear', function () {
                // debugger;
                _self.clearAll();
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
                cartAction: 'INFO'
            });
            this.fetch();
        },
        clearAll: function () {
            this.updateUrlOptions({
                cartAction: 'CLEAR'
            });
            this.fetch();
        },
        productAdd: function (productID) {
            this.updateUrlOptions({
                cartAction: 'ADD',
                productID: productID
            });
            this.fetch();
        },
        productRemove: function (productID) {
            this.updateUrlOptions({
                cartAction: 'REMOVE',
                productID: productID
            });
            this.fetch();
        }
    });

    return Cart;

});
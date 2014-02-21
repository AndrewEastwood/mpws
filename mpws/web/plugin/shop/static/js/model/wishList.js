define("plugin/shop/js/model/wishList", [
    'default/js/lib/sandbox',
    'default/js/model/mModel',
    'plugin/shop/js/lib/utils'
], function (Sandbox, MModel, ShopUtils) {

    var WishList = MModel.extend({
        // Consider how to inject this
        // -=-=-=-=-=-=-=-=-=-=-=-=
        // globalEvents: {
        //     'shop:WishList:add': 'productAdd'
        // },
        initialize: function () {
            // MModel.prototype.initialize.call(this);

            var _self = this;
            // debugger;
            this.updateUrlOptions({
                source: 'shop',
                fn: 'shop_wishlist',
                action: 'INFO'
            });
            // MModel.prototype.initialize.call(this);

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

            this.on('change', function () {
                Sandbox.eventNotify('shop:wishlist:info', _self.toJSON());
            });

            this.fetch();
        },
        parse: function (data) {
            // debugger;
            var products = ShopUtils.adjustProductEntry(data && data.shop);
            return {
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
        productAdd: function (productID) {
            this.updateUrlOptions({
                action: 'ADD',
                productID: productID
            });
            this.fetch();
        },
        productRemove: function (productID) {
            this.updateUrlOptions({
                action: 'REMOVE',
                productID: productID
            });
            this.fetch();
        }
    });

    return WishList;

});
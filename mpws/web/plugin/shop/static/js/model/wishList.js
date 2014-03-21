define("plugin/shop/js/model/wishList", [
    'default/js/lib/sandbox',
    'default/js/model/mModel',
    'plugin/shop/js/lib/utils'
], function (Sandbox, MModel, ShopUtils) {

    var Model = MModel.getNew();

    var WishList = Model.extend({
        // Consider how to inject this
        // -=-=-=-=-=-=-=-=-=-=-=-=
        // globalEvents: {
        //     'plugin:shop:WishList:add': 'productAdd'
        // },
        source: 'shop',
        fn: 'shop_wishlist',
        initialize: function () {
            // MModel.prototype.initialize.call(this);

            var _self = this;
            // debugger;
            // MModel.prototype.initialize.call(this);

            Sandbox.eventSubscribe('plugin:shop:wishlist:add', function (data) {
                // debugger;
                if (data && data.id)
                    _self.productAdd(data.id);
            });
            Sandbox.eventSubscribe('plugin:shop:wishlist:remove', function (data) {
                // debugger;
                if (data && data.id)
                    _self.productRemove(data.id);
            });
            Sandbox.eventSubscribe('plugin:shop:wishlist:clear', function () {
                // debugger;
                _self.clearAll();
            });

            this.on('change', function () {
                Sandbox.eventNotify('plugin:shop:wishlist:info', _self.toJSON());
            });

            // debugger;
            // this.getInfo();
        },
        parse: function (data) {
            // debugger;
            var products = ShopUtils.adjustProductEntry(data && data.shop);
            return {
                products: _(products).map(function(item){ return item; })
            };
        },
        getInfo: function () {
            this.updateUrl({
                action: 'INFO'
            });
            this.fetch();
        },
        clearAll: function () {
            this.updateUrl({
                action: 'CLEAR'
            });
            this.fetch();
        },
        productAdd: function (productID) {
            this.updateUrl({
                action: 'ADD',
                productID: productID
            });
            this.fetch();
        },
        productRemove: function (productID) {
            this.updateUrl({
                action: 'REMOVE',
                productID: productID
            });
            this.fetch();
        }
    });

    return new WishList();

});
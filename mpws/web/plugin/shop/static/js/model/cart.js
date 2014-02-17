define("plugin/shop/js/model/cart", [
    'default/js/lib/sandbox',
    'default/js/model/mModel',
    'plugin/shop/js/lib/utils'
], function (Sandbox, MModel, ShopUtils) {

    var Cart = MModel.extend({
        initialize: function () {
            var _self = this;
            // debugger;
            this.updateUrlOptions({
                source: 'shop',
                fn: 'shop_cart',
                cartAction: 'INFO'
            });
            MModel.prototype.initialize.call(this);

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
        },
        parse: function (data) {
            // debugger;
            var products = ShopUtils.adjustProductEntry(data && data.shop);
            // this.setExtras('info', data.shop.info);
            return {
                info: data.shop.info,
                products: _(products).map(function(item){ return item; })
            };
            // return _(products).map(function(item){ return item; });
            // return Utils.getTreeByJson(data && data.shop && data.shop, 'ID', 'ParentID');
            // return data;
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
        productAdd: function (productID, productQuantity) {
            this.updateUrlOptions({
                cartAction: 'SET',
                productID: productID,
                productQuantity: productQuantity
            });
            this.fetch();
        },
        productRemove: function (productID) {
            this.updateUrlOptions({
                cartAction: 'REMOVE',
                productID: productID
            });
            this.fetch();
        },
        checkout: function () {
        }
    });

    return Cart;

});
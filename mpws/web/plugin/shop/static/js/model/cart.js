define("plugin/shop/js/model/cart", [
    'default/js/model/mModel',
    'plugin/shop/js/lib/utils'
], function (MModel, ShopUtils) {

    var Cart = MModel.extend({
        initialize: function () {
            // debugger;
            this.updateUrlOptions({
                source: 'shop',
                fn: 'shop_cart',
                cartAction: 'INFO'
            });
            MModel.prototype.initialize.call(this);
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
        checkout: function () {
        }
    });

    return Cart;

});
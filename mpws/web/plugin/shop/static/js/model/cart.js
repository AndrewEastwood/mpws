define("plugin/shop/js/model/cart", [
    'default/js/lib/sandbox',
    'default/js/model/mModel',
    'plugin/shop/js/lib/utils'
], function (Sandbox, MModel, ShopUtils) {

    var Model = MModel.getNew();

    var Cart = Model.extend({
        // Consider how to inject this
        // -=-=-=-=-=-=-=-=-=-=-=-=
        // globalEvents: {
        //     'shop:cart:add': 'productAdd'
        // },
        source: 'shop',
        fn: 'shop_cart',
        initialize: function () {
            Model.prototype.initialize.call(this);

            var _self = this;
            // debugger;
            this.updateUrl({
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
                // _self.resetUrlOptions();
                Sandbox.eventNotify('shop:cart:info', _self.toJSON());
            });

        },
        parse: function (data) {
            // debugger;
            var products = ShopUtils.adjustProductEntry(data && data.shop);
            return {
                user: data.shop.user || {},
                info: data.shop.info,
                status: data.shop.status || {},
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
        productAdd: function (productID, productQuantity) {
            this.updateUrl({
                action: 'ADD',
                productID: productID,
                productQuantity: productQuantity
            });
            this.fetch();
        },
        productRemove: function (productID) {
            this.updateUrl({
                action: 'REMOVE',
                productID: productID
            });
            this.fetch();
        },
        checkout: function (userData) {
            this.updateUrl({
                action: 'SAVE'
            });
            // this.save({user: userData});
            var self = this;
            $.post(this.url, {user: userData}, function(data){
                debugger;
                // if (data)
                var _data = self.parse(data);

                _(_data).each(function(val, key){
                    self.set(key, val, {silent: true});
                });

                self.trigger('change');

            });
        }
    });

    return Cart;

});
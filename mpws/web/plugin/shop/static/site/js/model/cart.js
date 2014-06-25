define("plugin/shop/site/js/model/cart", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/shop/common/js/lib/utils'
], function (Sandbox, Backbone, ShopUtils) {

    // var Model = MModel.getNew();

    var Cart = Backbone.Model.extend({
        // Consider how to inject this
        // -=-=-=-=-=-=-=-=-=-=-=-=
        // globalEvents: {
        //     'plugin:shop:list_cart:add': 'productAdd'
        // },
        url: function () {
            // debugger;
            return APP.getApiLink({
                source: 'shop',
                fn: 'cart'
            })
        },
        initialize: function () {
            Backbone.Model.prototype.initialize.call(this);

            var _self = this;
            // debugger;
            // this.updateUrl({
            //     action: 'INFO'
            // });

            Sandbox.eventSubscribe('plugin:shop:list_cart:add', function (data) {
                // debugger;
                if (data && data.id)
                    _self.productAdd(data.id, 1);
            });
            Sandbox.eventSubscribe('plugin:shop:list_cart:sub', function (data) {
                // debugger;
                if (data && data.id)
                    _self.productAdd(data.id, -1);
            });
            Sandbox.eventSubscribe('plugin:shop:list_cart:remove', function (data) {
                // debugger;
                if (data && data.id)
                    _self.productRemove(data.id);
            });
            Sandbox.eventSubscribe('plugin:shop:list_cart:clear', function () {
                // debugger;
                _self.clearAll();
            });

            this.on('change', function () {
                // _self.resetUrlOptions();
                Sandbox.eventNotify('plugin:shop:list_cart:info', _self.toJSON());
            });

        },
        parse: function (data) {
            // debugger;
            // var _data = this.extractModelDataFromResponse(data);
            var products = ShopUtils.adjustProductItem(data.items);
            return {
                error: data.error,
                user: data.user || {},
                info: data.info,
                status: data.status || {},
                products: _(products).map(function(item){ return item; })
            };
        },
        getInfo: function () {
            // this.updateUrl();
            this.fetch({
                action: 'INFO'
            });
        },
        clearAll: function () {
            // this.updateUrl({
            //     action: 'CLEAR'
            // });
            this.fetch({
                action: 'CLEAR'
            });
        },
        productAdd: function (productID, productQuantity) {
            // this.updateUrl({
            //     action: 'ADD',
            //     productID: productID,
            //     productQuantity: productQuantity
            // });
            this.fetch({
                action: 'ADD',
                productID: productID,
                productQuantity: productQuantity
            });
        },
        productRemove: function (productID) {
            // this.updateUrl({
            //     action: 'REMOVE',
            //     productID: productID
            // });
            this.fetch({
                action: 'REMOVE',
                productID: productID
            });
        },
        checkout: function (userData) {
            debugger;
            this.updateUrl({
                action: 'SAVE'
            });
            // this.save({user: userData});
            var self = this;
            $.post(this.url, {user: userData}, function(data){
                // debugger;
                // if (data)
                var _data = self.parse(data);

                _(_data).each(function(val, key){
                    self.set(key, val, {silent: true});
                });

                self.trigger('change');
            });
        }
    });

    return new Cart();

});
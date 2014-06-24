define('plugin/shop/site/js/collection/listProductWish', [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/common/js/lib/utils'
], function (Sandbox, _, Backbone, ShopUtils) {

    var ListProductWish = Backbone.Collection.extend({
        url: APP.getApiLink({
            source: 'shop',
            fn: 'wish'
        }),
        initialize: function () {
            var self = this;
            listProductWish = this;
            Sandbox.eventSubscribe('plugin:shop:wishlist:add', function (data) {
                // debugger;
                // if (data && data.id)
                //     _self.productAdd(data.id);
                if (data && data.id)
                    self.create({
                        productID: data.id
                    });
            });
            Sandbox.eventSubscribe('plugin:shop:wishlist:remove', function (data) {
                debugger;
                // if (data && data.id)
                //     _self.productRemove(data.id);
            });
            Sandbox.eventSubscribe('plugin:shop:wishlist:clear', function () {
                debugger;
                // _self.clearAll();

            });
            this.on('sync', function(data){
                debugger;

                this.parse(data);
            }, this);
        },
        parse: function (data) {
            debugger;
            var products = ShopUtils.adjustProductItems(data && data.items);
            return _(products).map(function(item){ return item; });
        }
    });

    return new ListProductWish();
});
define('plugin/shop/site/js/collection/listProductLatest', [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/common/js/lib/utils'
], function (_, Backbone, ShopUtils) {
    // debugger;

    // var Collection = MCollection.getNew();

    var ListProductLatest = Backbone.Collection.extend({
        url: APP.getApiLink({
            source: 'shop',
            fn: 'products',
            type: 'latest'
        }),
        // model: ProductItemBase,
        // initialize: function () {
        //     Collection.prototype.initialize.call(this);
        //     // debugger;
        //     this.updateUrl();
        // },
        parse: function (data) {
            // debugger;
            var products = ShopUtils.adjustProductItems(data && data.items);
            return _(products).map(function(item){ return item; });
        }
    });

    return ListProductLatest;
});
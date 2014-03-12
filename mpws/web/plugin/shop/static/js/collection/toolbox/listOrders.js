define('plugin/shop/js/collection/toolbox/listOrders', [
    'customer/js/site',
    'default/js/lib/underscore',
    // 'default/js/collection/mCollection',,
    'default/js/lib/backbone-pageable',
    // 'plugin/shop/js/model/toolboxOrderItem',
    // 'default/js/lib/url',
    // 'plugin/shop/js/lib/utils'
], function (Site, _, PageableCollection) {
    // debugger;

    // var Collection = MCollection.getNew();

    // var ListOrders = Collection.extend({
    //     source: 'shop',
    //     fn: 'shop_manage_orders',
    //     model: ToolboxOrderItem,
    //     initialize: function () {
    //         Collection.prototype.initialize.call(this);
    //         // debugger;
    //         this.updateUrl();
    //     },
    //     parse: function (data) {
    //         var _data = this.extractModelDataFromRespce(data);
    //         return _data.orders;
    //         // var products = ShopUtils.adjustProductEntry(data && data.shop);
    //         // return _(products).map(function(item){ return item; });
    //     }
    // });

    // return ListOrders;
    // debugger;

    var ListOrders = PageableCollection.extend({

        url: Site.getApiLink('shop', 'shop_orders_list'),

        // Initial pagination states
        state: {
            pageSize: 2,
            // sortKey: "updated",
            order: 1
        },

        // You can remap the query parameters from `state` keys from
        // the default to those your server supports
        queryParams: {
            totalPages: null,
            totalRecords: null,
            sortKey: "sort",
            // q: "state:closed repo:jashkenas/backbone"
        },

        parseState: function (resp, queryParams, state, options) {
            return {
                totalRecords: parseInt(resp.shop.total_count, 10)
            };
        },

        parseRecords: function (resp, options) {
            return resp.shop.orders;
        }

    });

    return ListOrders;

});l
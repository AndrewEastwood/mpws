define('plugin/shop/toolbox/js/collection/listProducts', [
    'default/js/lib/sandbox',
    'customer/toolbox/js/router',
    'default/js/lib/underscore',
    'default/js/lib/backbone-pageable',
], function (Sandbox, AppRouter, _, PageableCollection) {

    var ListProducts = PageableCollection.extend({

        url: AppRouter.getApiLink('shop', 'shop_managed_products'),

        // Initial pagination states
        state: {
            pageSize: 5,
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
            var state = {
                totalRecords: parseInt(resp && resp.shop && resp.shop.total_count || 0, 10)
            };
            Sandbox.eventNotify('plugin:shop:productList:parseState', {collection: this, state: state});
            return state;
        },

        parseRecords: function (resp, options) {
            var products = resp.shop.products;

            for (var row in products)
                products[row].Price = parseFloat(products[row].Price, 10);

            return products;
        }

    });

    return ListProducts;

});
define('plugin/shop/js/collection/toolbox/listProducts', [
    'customer/js/site',
    'default/js/lib/underscore',
    'default/js/lib/backbone-pageable',
], function (Site, _, PageableCollection) {

    var ListProducts = PageableCollection.extend({

        url: Site.getApiLink('shop', 'shop_managed_products'),

        // Initial pagination states
        state: {
            pageSize: 25,
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
            return resp.shop.products;
        }

    });

    return ListProducts;

});
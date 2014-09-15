define('plugin/shop/toolbox/js/collection/statsProductsNonPopular', [
    'plugin/shop/toolbox/js/collection/basicProducts'
], function (ListProducts) {

    var StatsListProductsTodays = ListProducts.extend({
        initialize: function () {
            ListProducts.prototype.initialize.apply(this);
            this.url = APP.getApiLink({
                source: 'shop',
                fn: 'stats',
                type: 'products_list_non_popular'
            });
        },
        mode: "client",
        parseState: function (resp) {
            var state = {
                totalRecords: parseInt(resp && resp.items && resp.items.length || 0, 10),
                currentPage: 1
            };
            return state;
        }
    });

    return StatsListProductsTodays;
});
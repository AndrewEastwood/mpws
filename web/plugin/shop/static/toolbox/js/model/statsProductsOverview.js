define('plugin/shop/toolbox/js/model/statsProductsOverview', [
    'plugin/shop/toolbox/js/model/basicStats'
], function (ModelBasicStats) {

    return ModelBasicStats.extend({
        type: 'overview_products'
    });

});
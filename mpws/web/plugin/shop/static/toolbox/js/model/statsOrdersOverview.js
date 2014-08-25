define('plugin/shop/toolbox/js/model/statsOrdersOverview', [
    'plugin/shop/toolbox/js/model/basicStats'
], function (ModelBasicStats) {

    return ModelBasicStats.extend({
        type: 'overview_orders'
    });

});
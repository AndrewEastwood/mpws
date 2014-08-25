define('plugin/shop/toolbox/js/model/statsOrdersIntensityLastMonth', [
    'plugin/shop/toolbox/js/model/basicStats'
], function (ModelBasicStats) {

    return ModelBasicStats.extend({
        type: 'orders_intensity_last_month'
    });

});
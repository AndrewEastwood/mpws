define('plugin/shop/toolbox/js/model/statsOrdersIntensityLastMonth', [
    'default/js/lib/backbone'
], function (Backbone) {

    var Stats = Backbone.Model.extend({
        initialize: function (type) {
            this.url = APP.getApiLink({
                source: 'shop',
                fn: 'stats',
                type: 'orders_intensity_last_month'
            });
        }
    });

    return Stats;
});
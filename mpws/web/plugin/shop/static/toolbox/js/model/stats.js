define('plugin/shop/toolbox/js/model/stats', [
    'default/js/lib/backbone',
    'plugin/shop/common/js/lib/utils'
], function (Backbone, Utils) {

    var Stats = Backbone.Model.extend({
        url: APP.getApiLink({
            source: 'shop',
            fn: 'statistic'
        })
    });

    return Stats;

});
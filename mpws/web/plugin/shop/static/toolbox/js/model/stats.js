define('plugin/shop/toolbox/js/model/stats', [
    'default/js/lib/backbone'
], function (Backbone) {

    var Stats = Backbone.Model.extend({
        url: APP.getApiLink({
            source: 'shop',
            fn: 'overview'
        })
    });

    return Stats;

});
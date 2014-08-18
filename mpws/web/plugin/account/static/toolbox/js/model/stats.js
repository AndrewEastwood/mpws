define('plugin/account/toolbox/js/model/stats', [
    'default/js/lib/backbone'
], function (Backbone) {

    var Stats = Backbone.Model.extend({
        url: APP.getApiLink({
            source: 'account',
            fn: 'overview'
        })
    });

    return Stats;

});
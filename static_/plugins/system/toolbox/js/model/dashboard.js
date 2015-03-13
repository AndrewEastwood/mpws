define([
    'backbone'
], function (Backbone) {

    var Dashboard = Backbone.Model.extend({
        url: APP.getApiLink({
            source: 'system',
            fn: 'stats',
            type: 'overview'
        })
    });

    return Dashboard;

});
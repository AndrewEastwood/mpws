define([
    'backbone'
], function (Backbone) {

    var Dashboard = Backbone.Model.extend({
        url: APP.getApiLink('system','stats',{
            type: 'overview'
        })
    });

    return Dashboard;

});
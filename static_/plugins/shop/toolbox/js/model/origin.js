define([
    'backbone'
], function (Backbone) {

    var Origin = Backbone.Model.extend({
        idAttribute: 'ID',
        urlRoot: APP.getApiLink('shop', 'origins')
    });

    return Origin;
});
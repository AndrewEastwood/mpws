define([
    'backbone'
], function (Backbone) {

    var Order = Backbone.Model.extend({
        idAttribute: 'ID',
        urlRoot: APP.getApiLink('shop', 'orders')
    });

    return Order;
});
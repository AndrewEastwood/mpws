define([
    'backbone'
], function (Backbone) {

    var DeliveryAgency = Backbone.Model.extend({
        idAttribute: 'ID',
        urlRoot: APP.getApiLink('shop', 'delivery')
    });

    return DeliveryAgency;
});
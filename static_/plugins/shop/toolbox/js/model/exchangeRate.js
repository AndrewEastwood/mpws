define([
    'backbone'
], function (Backbone) {

    var ExchangeRate = Backbone.Model.extend({
        idAttribute: "ID",
        urlRoot: APP.getApiLink('shop', 'exchangerates')
    });

    return ExchangeRate;
});
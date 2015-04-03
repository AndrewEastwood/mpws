define([
    'backbone'
], function (Backbone) {

    var Promo = Backbone.Model.extend({
        idAttribute: "ID",
        urlRoot: APP.getApiLink('shop', 'promos')
    });

    return Promo;

});
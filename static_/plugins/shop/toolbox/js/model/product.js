define([
    'backbone'
], function (Backbone) {

    var Product = Backbone.Model.extend({
        idAttribute: "ID",
        urlRoot: APP.getApiLink('shop', 'products')
    });

    return Product;

});
define([
    'backbone'
], function (Backbone) {

    var Category = Backbone.Model.extend({
        idAttribute: 'ID',
        urlRoot: APP.getApiLink('shop', 'categories')
    });

    return Category;

});
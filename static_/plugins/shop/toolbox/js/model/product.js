define([
    'backbone'
], function (Backbone) {

    var Product = Backbone.Model.extend({
        idAttribute: "ID",
        urlRoot: APP.getApiLink('shop', 'products'),
        setCategoryID: function (categoryID) {
            return this.save({
                CategoryID: categoryID
            });
        }
    });

    return Product;

});
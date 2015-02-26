define([
    'backbone'
], function (Backbone) {

    var CatalogStructure = Backbone.Model.extend({
        url: function () {
            return APP.getApiLink({
                source: 'shop',
                fn: 'categories',
                tree: true
            })
        }
    });

    return CatalogStructure;

});
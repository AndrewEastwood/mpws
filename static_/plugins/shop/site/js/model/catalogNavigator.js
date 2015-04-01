define([
    'backbone'
], function (Backbone) {

    var instance = null;

    var CatalogNavigator = Backbone.Model.extend({
        url: function () {
            return APP.getApiLink({
                source: 'shop',
                fn: 'categories',
                tree: true
            })
        },
        findCategoryItem: function (categoryID) {
            return deepFind(categoryID, this.toJSON());
        }
    }, {
        getInstance: function (options) {
            if (instance) {
                return instance;
            } else {
                instance = new CatalogNavigator(options);
                return instance;
            }
        }
    });

    function deepFind (key, data) {
        key = (key).toString();
        for (var k in data) {
            if (k === key) {
                return data[k];
            } else {
                return deepFind(key, data[k]);
            }
        }
    }

    return CatalogNavigator;

});
define("plugin/shop/site/js/model/menuCatalog", [
    'default/js/lib/backbone',
    'default/js/lib/utils'
], function (Backbone, Utils) {

    var CatalogStructure = Backbone.Model.extend({
        url: function () {
            return APP.getApiLink({
                source: 'shop',
                fn: 'catalog',
                type: 'tree'
            })
        },
        parse: function (data) {
            return data['tree'];
        }
    });

    return CatalogStructure;

});
define("plugin/shop/js/model/menuCatalog", [
    'default/js/model/mModel',
    'default/js/lib/utils'
], function (MModel, Utils) {

    var CatalogStructure = MModel.extend({
        initialize: function () {
            // debugger;
            this.updateUrlOptions({
                source: 'shop',
                fn: 'shop_catalog_structure'
            });
        },
        parse: function (data) {
            return Utils.getTreeByJson(data && data.shop && data.shop.categories, 'ID', 'ParentID');
        }
    });

    return CatalogStructure;

});
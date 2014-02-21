define("plugin/shop/js/model/menuCatalog", [
    'default/js/model/mModel',
    'default/js/lib/utils'
], function (MModel, Utils) {

    // debugger;
    var CatalogStructure = MModel.extend({
        initialize: function () {
            // MModel.prototype.sinitialize.call(this);
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
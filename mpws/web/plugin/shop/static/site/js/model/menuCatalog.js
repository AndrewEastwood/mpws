define("plugin/shop/site/js/model/menuCatalog", [
    'default/js/model/mModel',
    'default/js/lib/utils'
], function (MModel, Utils) {

    var Model = MModel.getNew();
    // debugger;
    var CatalogStructure = Model.extend({
        source: 'shop',
        fn: 'shop_catalog_structure',
        parse: function (data) {
            return Utils.getTreeByJson(data && data.shop && data.shop.categories, 'ID', 'ParentID');
        }
    });

    return CatalogStructure;

});
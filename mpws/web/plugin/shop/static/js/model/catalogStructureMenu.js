define("plugin/shop/js/model/catalogStructureMenu", [
    'default/js/model/mModel',
    'default/js/lib/utils'
], function (MModel) {

    var CatalogStructureMenu = MModel.extend({
        initialize: function () {
            // debugger;
            this.updateUrlOptions({
                source: 'shop',
                fn: 'shop_catalog_structure'
            });
            // MModel.prototype.initialize.call(this);
            // MModel.prototype.initialize.call(this);
        },
        parse: function (data) {
            debugger;
            return Utils.getTreeByJson(data, 'ID', 'ParentID');
        }
    });

    return CatalogStructureMenu;

});
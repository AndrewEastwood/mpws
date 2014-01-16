define("plugin/shop/js/model/catalogStructureMenu", [
    'default/js/lib/underscore',
    'default/js/model/mmodel',
    'default/js/lib/utils'
], function (_, MModel, Utils) {

    var CatalogStructureMenu = MModel.extend({

        _options: {
            realm: 'plugin',

            caller: 'shop',

            fn: 'shop_catalog_structure'
        },

        initialize: function (options) {
            MModel.prototype.initialize.call(this, _.extend({}, this._options, options));
            // app.log(true, 'CatalogStructureMenu model initialize', this);
        },

        parse: function (data) {
            return Utils.getTreeByJson(data, 'ID', 'ParentID');
        }

    });

    return CatalogStructureMenu;

});
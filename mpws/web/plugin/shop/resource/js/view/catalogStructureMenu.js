APP.Modules.register("plugin/shop/view/catalogStructureMenu", [], [
    'lib/jquery',
    'lib/underscore',
    'view/mview',
    'plugin/shop/model/catalogStructureMenu',
], function (app, Sandbox, $, _, MView, modelCatalogStructureMenu) {

    var CatalogStructureMenu = MView.extend({

        name: "catalogStructureMenu",

        model: new modelCatalogStructureMenu(),

        template: 'plugin.shop.component.catalogStructureMenu@hbs',

        initialize: function (options) {
            // extend parent
            MView.prototype.initialize.call(this, options);
        }
    });

    return CatalogStructureMenu;

});
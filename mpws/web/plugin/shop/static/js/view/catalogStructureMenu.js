define("plugin/shop/js/view/catalogStructureMenu", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/view/mview',
    'plugin/shop/js/model/catalogStructureMenu',
    /* ui components */
    'default/js/lib/bootstrap'
], function ($, _, MView, modelCatalogStructureMenu) {

    var CatalogStructureMenu = MView.extend({

        name: "catalogStructureMenu",

        model: new modelCatalogStructureMenu(),

        template: 'plugin/shop/hbs/component/catalogStructureMenu.hbs',

        initialize: function (options) {
            // extend parent
            MView.prototype.initialize.call(this, options);
        }
    });

    return CatalogStructureMenu;

});
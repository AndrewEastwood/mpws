define("plugin/shop/js/view/catalogStructureMenu", [
    'default/js/view/mView',
    'plugin/shop/js/model/catalogStructureMenu',
    'default/js/plugin/hbs!plugin/shop/hbs/catalogStructureMenu'
], function (MView, modelCatalogStructureMenu, tpl) {

    var CatalogStructureMenu = MView.extend({
        tagName: 'li',
        className: 'dropdown shop-dropdown-catalog',
        id: 'shop-dropdown-catalog-ID',
        model: new modelCatalogStructureMenu(),
        template: tpl
    });

    return CatalogStructureMenu;

});
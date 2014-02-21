define("plugin/shop/js/view/menuCatalog", [
    'default/js/view/mView',
    'plugin/shop/js/model/menuCatalog',
    'default/js/plugin/hbs!plugin/shop/hbs/menuCatalog'
], function (MView, modelCatalogStructureMenu, tpl) {

    var MenuCatalog = MView.extend({
        tagName: 'li',
        className: 'dropdown shop-dropdown-catalog',
        id: 'shop-dropdown-catalog-ID',
        model: new modelCatalogStructureMenu(),
        template: tpl
    });

    // debugger;

    return MenuCatalog;

});
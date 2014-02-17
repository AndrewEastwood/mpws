define("plugin/shop/js/view/menuSite", [
    'customer/js/site',
    'plugin/shop/js/view/menuCatalog',
    'plugin/shop/js/view/menuCart',
], function (Site, MenuCatalog, MenuCart) {

    // inject shop menu (category menu)
    var menuCatalog = new MenuCatalog();
    menuCatalog.fetchAndRender();

    // inject shop menu (category menu)
    var menuCart = new MenuCart();
    menuCart.render();

    return {
        render: function () {
            Site.addMenuItem(menuCatalog.$el);
            Site.addMenuItem(menuCart.$el);
        }
    }

});
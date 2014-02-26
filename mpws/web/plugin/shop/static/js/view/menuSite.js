define("plugin/shop/js/view/menuSite", [
    'customer/js/site',
    'plugin/shop/js/view/menuCatalog',
    'plugin/shop/js/view/menuCart',
    'plugin/shop/js/view/menuWishList',
    'plugin/shop/js/view/menuCompare',
], function (Site, MenuCatalog, MenuCart, MenuWishList, MenuCompare) {

    // inject shop menu (category menu)
    var menuCatalog = new MenuCatalog();
    menuCatalog.fetchAndRender();

    // inject shop menu (category menu)
    var menuCart = new MenuCart();
    menuCart.render();

    // inject shop menu (category menu)
    var menuWishList = new MenuWishList();
    menuWishList.render();

    // inject shop menu (category menu)
    var menuCompare = new MenuCompare();
    menuCompare.render();

    return {
        render: function () {
            Site.addMenuItemLeft(menuCatalog.$el);
            Site.addMenuItemLeft(menuCart.$el);
            Site.addMenuItemLeft(menuWishList.$el);
            Site.addMenuItemLeft(menuCompare.$el);
        }
    }

});
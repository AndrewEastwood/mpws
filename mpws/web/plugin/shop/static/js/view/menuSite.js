define("plugin/shop/js/view/menuSite", [
    'customer/js/site',
    'plugin/shop/js/view/menuCatalog',
    'plugin/shop/js/view/menuCart',
    'plugin/shop/js/view/menuWishList',
], function (Site, MenuCatalog, MenuCart, MenuWishList) {

    // inject shop menu (category menu)
    var menuCatalog = new MenuCatalog();
    menuCatalog.fetchAndRender();

    // inject shop menu (category menu)
    var menuCart = new MenuCart();
    menuCart.render();

    // inject shop menu (category menu)
    var menuWishList = new MenuWishList();
    menuWishList.render();

    return {
        render: function () {
            Site.addMenuItem(menuCatalog.$el);
            Site.addMenuItem(menuCart.$el);
            Site.addMenuItem(menuWishList.$el);
        }
    }

});
define("plugin/shop/js/view/siteMenu", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'plugin/shop/js/view/menuCatalog',
    'plugin/shop/js/view/menuCart',
    'plugin/shop/js/view/menuWishList',
    'plugin/shop/js/view/menuCompare',
    'plugin/shop/js/view/menuProfileOrders',
], function (Sandbox, Site, MenuCatalog, MenuCart, MenuWishList, MenuCompare, MenuProfileOrders) {

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

    var menuProfileOrders = new MenuProfileOrders();
    menuProfileOrders.render();

    // Sandbox.eventSubscribe('view:AccountMenu', function (view) {
    //     if (!view.model.has('profile'))
    //         return;

    //     view.addMenuItem(menuProfileOrders.$el);
    // });

    Sandbox.eventSubscribe('view:AccountProfile', function (view) {
        // if (!view.model.has('profile'))
        //     return;
        view.addModuleMenuItem(menuProfileOrders.$el.find('a').clone());
    });

    // debugger;
    Sandbox.eventSubscribe('global:loader:complete', function () {
        // placeholders.common.menu
        Sandbox.eventNotify('global:content:render', [
            {
                name: 'CommonMenuLeft',
                el: menuCatalog.$el,
                append: true
            },
            {
                name: 'CommonMenuLeft',
                el: menuCart.$el,
                append: true
            },
            {
                name: 'CommonMenuLeft',
                el: menuWishList.$el,
                append: true
            },
            {
                name: 'CommonMenuLeft',
                el: menuCompare.$el,
                append: true
            }
        ]);
    });

});
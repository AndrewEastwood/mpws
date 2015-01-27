define("plugin/shop/site/js/view/siteMenu", [
    'default/js/lib/sandbox',
    'plugin/shop/site/js/view/menuCatalog',
    'plugin/shop/site/js/view/menuCart',
    'plugin/shop/site/js/view/menuWishList',
    'plugin/shop/site/js/view/menuCompare',
    'plugin/shop/site/js/view/menuPayment',
    'plugin/shop/site/js/view/menuWarranty',
    'plugin/shop/site/js/view/menuShipping'
    // 'plugin/shop/site/js/view/menuProfileOrders',
], function (Sandbox, MenuCatalog, MenuCart, MenuWishList, MenuCompare,
    MenuPayment, MenuWarranty, MenuShipping, MenuProfileOrders) {

    return function (models) {

        // inject shop menu (category menu)
        // var menuCatalog = new MenuCatalog();
        // menuCatalog.model.fetch();

        // inject shop menu (category menu)
        var menuCart = new MenuCart({
            model: models.order
        });

        // inject shop menu (category menu)
        var menuWishList = new MenuWishList();
        menuWishList.collection.fetch();

        // inject shop menu (category menu)
        var menuCompare = new MenuCompare();
        menuCompare.collection.fetch();

        // inject shop menu (category menu)
        var menuPayment = new MenuPayment();
        menuPayment.render();

        // inject shop menu (category menu)
        var menuWarranty = new MenuWarranty();
        menuWarranty.render();

        // inject shop menu (category menu)
        var menuShipping = new MenuShipping();
        menuShipping.render();

        // inject into account page shop menu items
        // var menuProfileOrders = new MenuProfileOrders();
        // menuProfileOrders.render();

        // Sandbox.eventSubscribe('view:AccountProfile', function (view) {
        //     view.addModuleMenuItem(menuProfileOrders.$el.find('a').clone());
        // });

        // debugger;
        Sandbox.eventSubscribe('global:loader:complete', function () {
            // placeholders.common.menu
            Sandbox.eventNotify('global:content:render', [
                // {
                //     name: 'CommonMenuLeft',
                //     el: menuCatalog.$el,
                //     append: true
                // },
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
                },
                {
                    name: 'CommonMenuLeft',
                    el: menuPayment.$el,
                    append: true
                },
                {
                    name: 'CommonMenuLeft',
                    el: menuWarranty.$el,
                    append: true
                },
                {
                    name: 'CommonMenuLeft',
                    el: menuShipping.$el,
                    append: true
                }
            ]);
        });
    }

});
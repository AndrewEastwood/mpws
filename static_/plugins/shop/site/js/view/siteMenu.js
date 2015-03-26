define([
    'plugins/shop/site/js/view/menuCatalog',
    'plugins/shop/site/js/view/menuCart',
    'plugins/shop/site/js/view/menuWishList',
    'plugins/shop/site/js/view/menuCompare',
    'plugins/shop/site/js/view/menuPayment',
    'plugins/shop/site/js/view/menuWarranty',
    'plugins/shop/site/js/view/menuShipping'
    // 'plugins/shop/site/js/view/menuProfileOrders',
], function (MenuCatalog, MenuCart, MenuWishList, MenuCompare,
    MenuPayment, MenuWarranty, MenuShipping, MenuProfileOrders) {

    function Menu (models) {

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

        // APP.injectHtml('ShopMenuItemCart', menuCart.el);
        // APP.injectHtml('ShopMenuItemWishList', menuWishList.el);
        // APP.injectHtml('ShopMenuItemCompareList', menuCompare.el);
        // APP.injectHtml('ShopMenuItemPopupInfoPayment', menuPayment.el);
        // APP.injectHtml('ShopMenuItemPopupInfoWarranty', menuWarranty.el);
        // APP.injectHtml('ShopMenuItemPopupInfoShipping', menuShipping.el);

        return {
            cart: menuCart.$el,
            wishList: menuWishList.$el,
            compareList: menuCompare.$el,
            popupInfoPayment: menuPayment.$el,
            popupInfoWarranty: menuWarranty.$el,
            popupInfoShipping: menuShipping.$el
        }
    }

    return Menu;

});
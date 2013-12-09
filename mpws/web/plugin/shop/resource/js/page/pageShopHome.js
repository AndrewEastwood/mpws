APP.Modules.register("plugin/shop/page/pageShopHome", [], [
    'lib/underscore',
    'view/mpage',
    'plugin/shop/view/catalogStructureMenu',
    'plugin/shop/view/breadcrumb',
    'plugin/shop/view/productListOverview'
], function (app, Sandbox, _, MPage, catalogStructureMenu, breadcrumb, productListOverview) {


    var PageShopHome = MPage.extend({

        name: 'shop-home',

        initialize: function (options) {
            // extend parent
            MPage.prototype.initialize.call(this, options);

            this.viewItems.catalogStructureMenu = new catalogStructureMenu(_(options).has('catalogStructureMenu') ? options.catalogStructureMenu : null);
            this.viewItems.breadcrumb = new breadcrumb(_(options).has('breadcrumb') ? options.breadcrumb : null);
            this.viewItems.productListOverview = new productListOverview(_(options).has('productListOverview') ? options.productListOverview : null);
        },

        render: function () {
            // show menu items
            this.viewItems.catalogStructureMenu.render();
            // app.log(true, 'PageShopHome rendering: productListOverview');
            this.viewItems.productListOverview.render();
            // just render default breadcrumb
            this.viewItems.breadcrumb.render();
            // app.log(true, 'PageShopHome rendering: breadcrumb');
        }

    });

    return PageShopHome;

});
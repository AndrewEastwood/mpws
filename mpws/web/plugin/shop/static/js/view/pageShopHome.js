define("plugin/shop/js/view/pageShopHome", [
    'default/js/lib/underscore',
    'default/js/view/mview',
    'default/js/lib/storage',
    'plugin/shop/js/view/catalogStructureMenu',
    'plugin/shop/js/view/breadcrumb',
    'plugin/shop/js/view/productListOverview'
], function (_, MView, Storage, catalogStructureMenu, breadcrumb, productListOverview) {

    var PageShopHome = MView.extend({

        name: 'shop-home',

        initialize: function (options) {
            // extend parent
            MView.prototype.initialize.call(this, options);

            Storage.add('catalogStructureMenu', new catalogStructureMenu(_(options).has('catalogStructureMenu') ? options.catalogStructureMenu : null));
            Storage.add('breadcrumb', new breadcrumb(_(options).has('breadcrumb') ? options.breadcrumb : null));
            Storage.add('productListOverview', new productListOverview(_(options).has('productListOverview') ? options.productListOverview : null));

            // this.viewItems.catalogStructureMenu = new catalogStructureMenu(_(options).has('catalogStructureMenu') ? options.catalogStructureMenu : null);
            // this.viewItems.breadcrumb = new breadcrumb(_(options).has('breadcrumb') ? options.breadcrumb : null);
            // this.viewItems.productListOverview = new productListOverview(_(options).has('productListOverview') ? options.productListOverview : null);
        },

        render: function () {
            // show menu items
            Storage.get('catalogStructureMenu').render();
            // app.log(true, 'PageShopHome rendering: productListOverview');
            Storage.get('productListOverview').render();
            // just render default breadcrumb
            Storage.get('breadcrumb').render();
            // app.log(true, 'PageShopHome rendering: breadcrumb');
        }

    });

    return PageShopHome;

});
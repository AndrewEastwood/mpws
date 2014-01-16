define("plugin/shop/js/view/pageProductEntryStandalone", [
    'default/js/lib/underscore',
    'default/js/view/mview',
    'plugin/shop/js/view/catalogStructureMenu',
    'plugin/shop/js/view/breadcrumb',
    'plugin/shop/js/view/productEntryStandalone'
], function (app, Sandbox, _, MView, catalogStructureMenu, breadcrumb, productEntryStandalone) {

    var PageProductEntryStandalone = MView.extend({

        name: 'shop-product-standalone',

        initialize: function (options) {

            // extend parent
            MView.prototype.initialize.call(this, options);

            this.viewItems.catalogStructureMenu = new catalogStructureMenu(_(options).has('catalogStructureMenu') ? options.catalogStructureMenu : null);
            this.viewItems.breadcrumb = new breadcrumb(_(options).has('breadcrumb') ? options.breadcrumb : null);
            this.viewItems.productEntryStandalone = new productEntryStandalone(_(options).has('productEntryStandalone') ? options.productEntryStandalone : null);

        },

        render: function (productId) {

            // show menu items
            this.viewItems.catalogStructureMenu.render();

            app.log(true, 'PageProductEntryStandalone rendering productEntryStandalone');

            this.viewItems.productEntryStandalone.renderProductByID(productId);
            // just render breadcrumb
            app.log(true, 'PageProductEntryStandalone rendering breadcrumb');
            this.viewItems.breadcrumb.renderLocation(productId);
        }

    });

    return PageProductEntryStandalone;

});
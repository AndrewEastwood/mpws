APP.Modules.register("plugin/shop/page/pageProductEntryStandalone", [], [
    'lib/underscore',
    'view/mpage',
    'plugin/shop/view/catalogStructureMenu',
    'plugin/shop/view/breadcrumb',
    'plugin/shop/view/productEntryStandalone'
], function (app, Sandbox, _, MPage, catalogStructureMenu, breadcrumb, productEntryStandalone) {

    var PageProductEntryStandalone = MPage.extend({

        name: 'shop-product-standalone',

        initialize: function (options) {

            // extend parent
            MPage.prototype.initialize.call(this, options);

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
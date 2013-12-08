APP.Modules.register("plugin/shop/view/pageProductEntryStandalone", [], [
    'lib/jquery',
    'lib/underscore',
    // 'lib/backbone',
    // 'lib/mpws.api',
    'view/mpage',
    // 'lib/mpws.page',
    // 'plugin/shop/lib/driver',
    // 'plugin/shop/view/productListOverview',
    'plugin/shop/view/breadcrumb',
    'plugin/shop/view/productEntryStandalone'
    // ui elements
    // 'lib/fuelux.wizard'
], function (app, Sandbox, $, _, MPage, viewBreadcrumb, productEntryStandalone) {


    var PageProductEntryStandalone = MPage.extend({

        name: 'shop-product-standalone',

        initialize: function (options) {

            // extend parent
            MPage.prototype.initialize.call(this, options);

            this.viewItems.breadcrumb = new viewBreadcrumb(_(options).has('breadcrumb') ? options.breadcrumb : null);
            this.viewItems.productEntryStandalone = new productEntryStandalone(_(options).has('productEntryStandalone') ? options.productEntryStandalone : null);

        },

        render: function (productId) {

            app.log(true, 'PageProductEntryStandalone rendering productEntryStandalone');

            this.viewItems.productEntryStandalone.renderProductByID(productId);
            // just render breadcrumb
            app.log(true, 'PageProductEntryStandalone rendering breadcrumb');
            this.viewItems.breadcrumb.renderLocation(productId);
        }

    });

    return PageProductEntryStandalone;

});
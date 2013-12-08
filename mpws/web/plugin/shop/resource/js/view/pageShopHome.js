APP.Modules.register("plugin/shop/view/pageShopHome", [], [
    'lib/jquery',
    'lib/underscore',
    // 'lib/backbone',
    // 'lib/mpws.api',
    'view/mpage',
    // 'lib/mpws.page',
    // 'plugin/shop/lib/driver',
    // 'plugin/shop/view/productListOverview',
    'plugin/shop/view/breadcrumb',
    'plugin/shop/view/productListOverview'
    // ui elements
    // 'lib/fuelux.wizard'
], function (app, Sandbox, $, _, MPage, viewBreadcrumb, productListOverview) {


    var PageShopHome = MPage.extend({

        name: 'shop-home',

        initialize: function (options) {

            // extend parent
            MPage.prototype.initialize.call(this, options);

            this.viewItems.breadcrumb = new viewBreadcrumb(_(options).has('breadcrumb') ? options.breadcrumb : null);
            this.viewItems.productListOverview = new productListOverview(_(options).has('productListOverview') ? options.productListOverview : null);

        },

        render: function () {

            app.log(true, 'PageShopHome rendering: productListOverview');

            this.viewItems.productListOverview.render();
            // just render default breadcrumb
            app.log(true, 'PageShopHome rendering: breadcrumb');
            this.viewItems.breadcrumb.render();

        }

    });

    return PageShopHome;

});
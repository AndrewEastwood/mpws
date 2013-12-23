APP.Modules.register("plugin/shop/page/pageShopCatalog", [], [
    'lib/underscore',
    'page/mpage',
    'plugin/shop/view/catalogStructureMenu',
    'plugin/shop/view/breadcrumb',
    'plugin/shop/view/productListCatalog'
], function (app, Sandbox, _, MPage, catalogStructureMenu, breadcrumb, productListCatalog) {


    var PageShopCatalog = MPage.extend({

        name: 'shop-catalog',

        initialize: function (options) {
            // extend parent
            MPage.prototype.initialize.call(this, options);

            this.viewItems.catalogStructureMenu = new catalogStructureMenu(_(options).has('catalogStructureMenu') ? options.catalogStructureMenu : null);
            this.viewItems.breadcrumb = new breadcrumb(_(options).has('breadcrumb') ? options.breadcrumb : null);
            this.viewItems.productListCatalog = new productListCatalog(_(options).has('productListCatalog') ? options.productListCatalog : null);
        },

        render: function (categoryId) {

            // show menu items
            this.viewItems.catalogStructureMenu.render();

            if (categoryId) {
                // filter products by category and brand
                
                // app.log(true, 'PageShopCatalog rendering: productListCatalog, categoryId=', categoryId);
                this.viewItems.productListCatalog.applyFiltering({
                    categoryId: categoryId
                });

                // render breadcrumb by categoryId
                this.viewItems.breadcrumb.renderLocation(null, categoryId);
            } else {
                // show shop catalog
                // app.log(true, 'PageShopCatalog rendering: productListCatalog');
                this.viewItems.productListCatalog.render();
                // just render default breadcrumb
                this.viewItems.breadcrumb.render();
            }

        }

    });

    return PageShopCatalog;

});
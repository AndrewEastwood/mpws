define("default/js/site", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    // views + models
    'default/js/view/menu',
    'default/js/view/breadcrumb',
    // pages
    // 'plugin/shop/js/view/pageShopHome',
    // 'plugin/shop/js/view/pageShopCatalog',
    // 'plugin/shop/js/view/pageProductEntryStandalone'
], function ($, _, Backbone, Cache, Menu, Breadcrumb) {

    var Site = function (options) {

        var _views = {};

        // init common views
        if (options.views.menu)
            _views.menu = new Menu(options.views.menu);

        if (options.views.breadcrumb)
            _views.breadcrumb = new Breadcrumb(options.views.breadcrumb);

        return {

            options: options,

            views: _views,

            start: function () {

                // debugger;

                // _(_views).invoke('render');
                _views.menu.render();

            },

            showBreadcrumbLocation: function (options) {
                
                _views.breadcrumb.render(options);
            },

            addMenuItem: function (item) {
                _views.menu.addMenuItem(item);
            }
        }

    }


    return Site;
});
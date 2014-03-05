define("customer/js/site", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/site',
    // 'default/js/lib/underscore',
    // 'default/js/lib/backbone',
    // 'default/js/lib/mpws.page',
    // 'plugin/shop/js/site'
    // views + models
    'default/js/view/menu',
    'default/js/view/breadcrumb',
    'default/js/plugin/css!customer/css/theme.css'

], function (Sandbox, $, _, SiteBase, Menu, Breadcrumb) {

    var _customerOptions = {};

    _customerOptions.site = {
        title: 'Test and demo site',
        logoImageUrl: app.config.URL_STATIC_CUSTOMER + '/img/mikserLogo.png',
        showSearch: true
    };

    _customerOptions.placeholders = {
        common: {
            header: $('.MPWSPageHeader'),
            headerLeft: $('.MPWSPageHeader .MPWSBlockLeft'),
            headerCenter: $('.MPWSPageHeader .MPWSBlockCenter'),
            headerRight: $('.MPWSPageHeader .MPWSBlockRight'),
            body: $('.MPWSPageBody'),
            bodyLeft: $('.MPWSPageBody .MPWSBlockLeft'),
            bodyCenter: $('.MPWSPageBody .MPWSBlockCenter'),
            bodyRight: $('.MPWSPageBody .MPWSBlockRight'),
            footer: $('.MPWSPageFooter'),
            footerLeft: $('.MPWSPageFooter .MPWSBlockLeft'),
            footerCenter: $('.MPWSPageFooter .MPWSBlockCenter'),
            footerRight: $('.MPWSPageFooter .MPWSBlockRight'),
            breadcrumb: $('.MPWSBreadcrumb'),
            menu: $('.MPWSPageHeader .MPWSBlockCenter'),
            widgetsTop: $('.MPWSWidgetsTop'),
            widgetsBottom: $('.MPWSWidgetsBottom')
        },
        shop: {
            productListOverview: $('.MPWSPageBody .MPWSBlockCenter'),
            productListCatalog: $('.MPWSPageBody .MPWSBlockCenter'),
            productItemStandalone: $('.MPWSPageBody .MPWSBlockCenter'),
            productCompare: $('.MPWSPageBody .MPWSBlockCenter'),
            shoppingCartStandalone: $('.MPWSPageBody .MPWSBlockCenter'),
            shoppingWishListStandalone: $('.MPWSPageBody .MPWSBlockCenter'),
            widgetShoppingCartEmbedded: $('.MPWSWidgetsTop'),
            widgetOrderStatusButton: $('.MPWSWidgetsTop'),
            ordertrackingStandalone: $('.MPWSPageBody .MPWSBlockCenter')
        },
        account: {
            widgetButtonAccount: $('.MPWSWidgetsTop'),
            pageProfileCreate: $('.MPWSPageBody .MPWSBlockCenter'),
            pageProfile: $('.MPWSPageBody .MPWSBlockCenter'),
            pageProfileOverview: $('.MPWSPageBody .MPWSBlockCenter'),
            pageProfileEdit: $('.MPWSPageBody .MPWSBlockCenter'),
            pageProfilePassword: $('.MPWSPageBody .MPWSBlockCenter'),
            pageProfileDelete: $('.MPWSPageBody .MPWSBlockCenter'),
        }
    };

    var site = new SiteBase(_customerOptions);

    Sandbox.eventSubscribe('global:loader:complete', function (options) {
        $('head title').text(_customerOptions.site.title);

        // init site views
        var _views = {};
        _views.menu = new Menu({
            el: _customerOptions.placeholders.common.menu
        });
        _views.menu.render();

        _views.breadcrumb = new Breadcrumb({
            el: _customerOptions.placeholders.common.breadcrumb,
            template: 'default/js/plugin/hbs!customer/hbs/breadcrumb'
        });

        Sandbox.eventSubscribe('site:breadcrumb:show', function (options) {
            _views.breadcrumb.fetchAndRender(options);
        });

        Sandbox.eventSubscribe('site:menu:inject', function (options) {
            // debugger;
            if (_.isArray(options))
                _(options).each(function (option){
                    _views.menu.addMenuItem(option.item, !!option.posRight);
                });
            else if (options.item)
                _views.menu.addMenuItem(options.item, !!options.posRight);
        });

    });



    // this object will be passed into all enabled plugins
    // to inject additional components into page layout
    return site;

});
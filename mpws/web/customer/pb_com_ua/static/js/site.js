define("customer/js/site", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/site',
    'default/js/view/breadcrumb',
    'default/js/plugin/css!customer/css/theme.css'

], function (Sandbox, $, _, SiteBase, Breadcrumb) {

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
            menuLeft: $('.MPWSPageHeader .MPWSBlockCenter .navbar-nav-main-left'),
            menuRight: $('.MPWSPageHeader .MPWSBlockCenter .navbar-nav-main-right'),
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

        // configure titles and brand images
        $('head title').text(_customerOptions.site.title);
        $('#site-logo-ID').attr({
            src: _customerOptions.site.logoImageUrl,
            title: _customerOptions.site.title
        });
        $('.navbar-brand').removeClass('hide');

        // init site views
        var _views = {};

        _views.breadcrumb = new Breadcrumb({
            el: _customerOptions.placeholders.common.breadcrumb,
            template: 'default/js/plugin/hbs!customer/hbs/breadcrumb'
        });

        Sandbox.eventSubscribe('site:breadcrumb:show', function (options) {
            _views.breadcrumb.fetchAndRender(options);
        });
    });

    // this object will be passed into all enabled plugins
    // to inject additional components into page layout
    return site;

});
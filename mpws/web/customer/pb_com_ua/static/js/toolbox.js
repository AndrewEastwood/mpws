define("customer/js/site", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/site',
    'default/js/view/breadcrumb',
    'default/js/plugin/css!customer/css/toolboxTheme.css'
], function (Sandbox, $, SiteBase, Breadcrumb) {

    var _customerOptions = {};

    _customerOptions.site = {
        title: 'Toolbox',
        logoImageUrl: app.config.URL_STATIC_DEFAULT + '/img/mpwsLogo.gif'
    };

    _customerOptions.placeholders = {
        CommonHeader: $('.MPWSPageHeader'),
        CommonHeaderLeft: $('.MPWSPageHeader .MPWSBlockLeft'),
        CommonHeaderCenter: $('.MPWSPageHeader .MPWSBlockCenter'),
        CommonHeaderRight: $('.MPWSPageHeader .MPWSBlockRight'),
        CommonBody: $('.MPWSPageBody'),
        CommonBodyLeft: $('.MPWSPageBody .MPWSBlockLeft'),
        CommonBodyCenter: $('.MPWSPageBody .MPWSBlockCenter'),
        CommonBodyRight: $('.MPWSPageBody .MPWSBlockRight'),
        CommonFooter: $('.MPWSPageFooter'),
        CommonFooterLeft: $('.MPWSPageFooter .MPWSBlockLeft'),
        CommonFooterCenter: $('.MPWSPageFooter .MPWSBlockCenter'),
        CommonFooterRight: $('.MPWSPageFooter .MPWSBlockRight'),
        CommonBreadcrumb: $('.MPWSBreadcrumb'),
        CommonMenuLeft: $('.MPWSPageHeader .MPWSBlockCenter .navbar-nav-main-left'),
        CommonMenuRight: $('.MPWSPageHeader .MPWSBlockCenter .navbar-nav-main-right'),
        CommonWidgetsTop: $('.MPWSWidgetsTop'),
        CommonWidgetsBottom: $('.MPWSWidgetsBottom'),
        CommmonToolboxMenu: $('#toolbox-menu-ID'),
        CommmonToolboxPage: $('#toolbox-page-ID'),
        /* plugins  */
        /* = plugin shop */
        ShopListOrders: $('#toolbox-page-ID'),
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
            el: _customerOptions.placeholders.CommonBreadcrumb,
            template: 'default/js/plugin/hbs!customer/hbs/toolbox/breadcrumb'
        });

        Sandbox.eventSubscribe('site:breadcrumb:show', function (options) {
            _views.breadcrumb.fetchAndRender(options);
        });
    });

    // this object will be passed into all enabled plugins
    // to inject additional components into page layout
    return site;

});
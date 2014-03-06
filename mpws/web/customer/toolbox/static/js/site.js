define("customer/js/site", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/site',
    'default/js/view/breadcrumb',
    'default/js/plugin/css!customer/css/theme.css'

], function (Sandbox, $, SiteBase, Breadcrumb) {

    var _customerOptions = {};

    _customerOptions.site = {
        title: 'Toolbox',
        logoImageUrl: app.config.URL_STATIC_DEFAULT + '/img/logo.gif'
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
            menuPlugin: $('.MPWSPageBody .MPWSBlockCenter #toolbox-menu-ID'),
            widgetsTop: $('.MPWSWidgetsTop'),
            widgetsBottom: $('.MPWSWidgetsBottom')
        }
    };

    _customerOptions.views = {
        menu: {
            el: _customerOptions.placeholders.common.menu
        },
        breadcrumb: {
            el: _customerOptions.placeholders.common.breadcrumb,
            template: 'default/js/plugin/hbs!customer/hbs/breadcrumb'
        }
    }

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
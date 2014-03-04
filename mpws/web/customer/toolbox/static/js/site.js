define("customer/js/site", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/site',
    'default/js/lib/backbone',
    'default/js/plugin/css!customer/css/theme.css'

], function (Sandbox, $, SiteBase, Backbone) {

    var _customerOptions = {};

    _customerOptions.site = {
        title: 'Workbench',
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
            menu: $('.MPWSPageHeader .MPWSBlockCenter'),
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

    // this object will be passed into all enabled plugins
    // to inject additional components into page layout
    return site;

});
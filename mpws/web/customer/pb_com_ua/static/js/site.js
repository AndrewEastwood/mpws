// APP.Modules.register("router/customer", [], [

//     'lib/jquery',
//     'lib/underscore',
//     'lib/backbone',
//     'lib/mpws.page',
//     'plugin/shop/router/shopPublic',
//     // 'lib/mpws.ui',

// ], function (app, Sandbox, $, _, Backbone, mpwsPage, pluginShopRouter){

define("customer/js/site", [

    'cmn_jquery',
    'default/js/site',
    // 'default/js/lib/underscore',
    // 'default/js/lib/backbone',
    // 'default/js/lib/mpws.page',
    // 'plugin/shop/js/site'
    'default/js/plugin/css!customer/css/theme.css'

], function ($, SiteBase/*, _, Backbone, mpwsPage, pluginShopRouter*/) {

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
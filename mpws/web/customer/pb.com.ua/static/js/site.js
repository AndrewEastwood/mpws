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

], function ($, Site/*, _, Backbone, mpwsPage, pluginShopRouter*/) {

    var _customerOptions = {};

    _customerOptions.placeholders = {
        header: $('.MPWSPageHeader'),
        body: $('.MPWSPageBody'),
        footer: $('.MPWSPageFooter'),
        breadcrumb: $('.MPWSBreadcrumb'),
        menu: $('.MPWSPageHeader .MPWSBlockCenter'),
        productListOverview: $('.MPWSPageBody .MPWSBlockCenter'),
        productEntryStandalone: $('.MPWSPageBody .MPWSBlockCenter'),
        shoppingCartStandalone: $('.MPWSPageBody .MPWSBlockCenter'),
        shoppingCartEmbedded: $('.MPWSWidgetsTop'),
        shoppingCartCheckout: $('.MPWSPageBody .MPWSBlockCenter'),
        productListCatalog: $('.MPWSPageBody .MPWSBlockCenter')
    };

    _customerOptions.views = {
        menu: {
            el: _customerOptions.placeholders.menu
        },
        breadcrumb: {
            el: _customerOptions.placeholders.breadcrumb,
            template: 'default/js/plugin/hbs!customer/hbs/breadcrumb'
        }
    }

    var siteObj = new Site(_customerOptions);

    // this object will be passed into all enabled plugins
    // to inject additional components into page layout
    return siteObj;

});
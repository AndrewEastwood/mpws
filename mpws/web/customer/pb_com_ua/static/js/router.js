define("customer/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/view/breadcrumb',
    'default/js/plugin/css!customer/css/theme.css'
], function (Sandbox, $, _, Breadcrumb) {

    var _customerOptions = {};

    _customerOptions.site = {
        title: 'Test and demo site',
        logoImageUrl: APP.config.URL_STATIC_CUSTOMER + '/img/mikserLogo.png'
    };

    // _customerOptions.placeholders = {
    //     CommonHeader: $('.MPWSPageHeader'),
    //     CommonHeaderLeft: $('.MPWSPageHeader .MPWSBlockLeft'),
    //     CommonHeaderCenter: $('.MPWSPageHeader .MPWSBlockCenter'),
    //     CommonHeaderRight: $('.MPWSPageHeader .MPWSBlockRight'),
    //     CommonBody: $('.MPWSPageBody'),
    //     CommonBodyLeft: $('.MPWSPageBody .MPWSBlockLeft'),
    //     CommonBodyCenter: $('.MPWSPageBody .MPWSBlockCenter'),
    //     CommonBodyRight: $('.MPWSPageBody .MPWSBlockRight'),
    //     CommonFooter: $('.MPWSPageFooter'),
    //     CommonFooterLeft: $('.MPWSPageFooter .MPWSBlockLeft'),
    //     CommonFooterCenter: $('.MPWSPageFooter .MPWSBlockCenter'),
    //     CommonFooterRight: $('.MPWSPageFooter .MPWSBlockRight'),
    //     CommonBreadcrumb: $('.MPWSBreadcrumb'),
    //     CommonMenuLeft: $('.MPWSPageHeader .MPWSBlockCenter .navbar-nav-main-left'),
    //     CommonMenuRight: $('.MPWSPageHeader .MPWSBlockCenter .navbar-nav-main-right'),
    //     CommonWidgetsTop: $('.MPWSWidgetsTop'),
    //     CommonWidgetsBottom: $('.MPWSWidgetsBottom'),
    //     /* plugins  */
    //     /* = plugin shop */
    //     ShopOffers: $('.MPWSPageBody .MPWSBlockCenter'),
    //     ShopListProductLatest: $('.MPWSPageBody .MPWSBlockCenter'),
    //     ShopListProductCatalog: $('.MPWSPageBody .MPWSBlockCenter'),
    //     ShopProductItemStandalone: $('.MPWSPageBody .MPWSBlockCenter'),
    //     ShopProductCompare: $('.MPWSPageBody .MPWSBlockCenter'),
    //     ShopCartStandalone: $('.MPWSPageBody .MPWSBlockCenter'),
    //     ShopWishList: $('.MPWSPageBody .MPWSBlockCenter'),
    //     ShopWidgetShoppingCartEmbedded: $('.MPWSWidgetsTop'),
    //     ShopWidgetOrderStatusButton: $('.MPWSWidgetsTop'),
    //     ShopOrdertrackingStandalone: $('.MPWSPageBody .MPWSBlockCenter'),
    //     /* = plugin account */
    //     AccountWidgetButtonAccount: $('.MPWSWidgetsTop'),
    //     AccountProfileCreate: $('.MPWSPageBody .MPWSBlockCenter'),
    //     AccountProfile: $('.MPWSPageBody .MPWSBlockCenter'),
    //     AccountProfileOverview: $('.MPWSPageBody .MPWSBlockCenter'),
    //     AccountProfileEdit: $('.MPWSPageBody .MPWSBlockCenter'),
    //     AccountProfilePassword: $('.MPWSPageBody .MPWSBlockCenter'),
    //     AccountProfileDelete: $('.MPWSPageBody .MPWSBlockCenter'),
    // };

    // var site = new App(_customerOptions);

    // Sandbox.eventSubscribe('global:loader:complete', function (options) {

        // configure titles and brand images
        $('head title').text(_customerOptions.site.title);
        $('#site-logo-ID').attr({
            src: _customerOptions.site.logoImageUrl,
            title: _customerOptions.site.title
        });
        $('.navbar-brand').removeClass('hide');
        var breadcrumb = new Breadcrumb({
            template: 'default/js/plugin/hbs!customer/hbs/breadcrumb'
        });
        breadcrumb.render();
    // });

    // Sandbox.eventSubscribe('global:session:expired', function (error) {
    //     // Backbone.history.navigate('signin', true);

    //     if (error === "InvalidPublicTokenKey") {
    //         document.location.reload();
    //         throw "Session timeout";
    //     }

    //     throw "Unknown error";
    // });

    // this object will be passed into all enabled plugins
    // to inject additional components into page layout
    // return site;

});
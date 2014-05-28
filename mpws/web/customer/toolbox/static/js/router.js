define("customer/js/router", [
    'default/js/lib/sandbox',
    'customer/js/view/menu',
], function (Sandbox, Menu) {


    Sandbox.eventSubscribe('global:loader:complete', function (options) {
        Sandbox.eventNotify('global:page:setTitle', 'Toolbox');
        // get initial account status
    });

    Sandbox.eventSubscribe('customer:toolbox:page:show', function (options) {
        Sandbox.eventNotify('global:content:render', _.extend({}, options, {
            name: 'CommonBodyCenter',
        }));
    });

    Sandbox.eventSubscribe('customer:toolbox:menu:display', function (options) {
        // debugger;
        Sandbox.eventNotify('global:content:render', _.extend({}, options, {
            name: options.name || 'PluginToolboxMenuList',
        }));
        // sort nodes
        // debugger;
        // $('#toolbox-menu-ID').tsort({place:'top'});
        Sandbox.eventNotify('global:menu:set-active');
    });

    var menu = new Menu();
    menu.render();

    // Sandbox.eventSubscribe('customer:toolbox:render', function (options) {
    //     debugger;
    // });

    // return Router;
    // control all plugins here here 



    // var _customerOptions = {};

    // _customerOptions.site = {
    //     title: 'Toolbox',
    //     logoImageUrl: APP.config.URL_STATIC_DEFAULT + '/img/mpwsLogo.gif'
    // };

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
    //     CommmonToolboxMenu: $('#toolbox-menu-ID'),
    //     CommmonToolboxPage: $('#toolbox-page-top-ID'),
    //     /* plugins  */
    //     /* = plugin toolbox */
    //     Bridge: $('.MPWSPageBody .MPWSBlockCenter'),
    //     SignIn: $('.MPWSPageBody .MPWSBlockCenter'),
    //     SignOut: $('.MPWSPageBody .MPWSBlockCenter'),
    //     // /* = plugin account */
    //     // AccountLogin: $('#toolbox-page-center-ID'),
    //     // /* = plugin shop */
    //     // ShopListOrders: $('#toolbox-page-center-ID'),
    //     // ShopFilteringListOrders: $('#toolbox-page-top-ID'),
    //     // ShopListProducts: $('#toolbox-page-center-ID'),
    // };

    // var site = new App(_customerOptions);

    // debugger;

    // Sandbox.eventSubscribe('global:loader:complete', function (options) {

    //     // configure titles and brand images
    //     $('head title').text(_customerOptions.site.title);
    //     // $('#site-logo-ID').attr({
    //     //     src: _customerOptions.site.logoImageUrl,
    //     //     title: _customerOptions.site.title
    //     // });
    //     // $('.navbar-brand').removeClass('hide');

    //     // init site views
    //     var _views = {};

    //     _views.breadcrumb = new Breadcrumb({
    //         el: _customerOptions.placeholders.CommonBreadcrumb,
    //         template: 'default/js/plugin/hbs!customer/hbs/breadcrumb'
    //     });

    //     // Sandbox.eventSubscribe('plugin:toolbox:status:received', function (status) {
    //     //     // debugger;
    //     //     // Sandbox.eventNotify('global:breadcrumb:show');
    //     //     if (!status && Backbone.history.fragment !== "signin") {
    //     //         Backbone.history.navigate('signin', true);
    //     //         return;
    //     //     }

    //     Sandbox.eventSubscribe('global:breadcrumb:show', function (options) {
    //         if (!_.isEmpty(options))
    //             _views.breadcrumb.fetchAndRender(options);
    //     });
    //     // });

    //     // debugger
    //     // Sandbox.eventNotify('plugin:toolbox:status');

    // });

    // this object will be passed into all enabled plugins
    // to inject additional components into page layout
    // return site;

});
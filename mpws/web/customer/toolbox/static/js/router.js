define("customer/js/router", [
    'default/js/lib/sandbox',
    'customer/js/view/menu',
    'default/js/lib/auth',
], function (Sandbox, Menu, Auth) {

    // with every route we get user status
    Sandbox.eventSubscribe('global:route', function () {
        Auth.getStatus();
    });

    // when page is loaded first time
    Sandbox.eventSubscribe('global:loader:complete', function (options) {
        Sandbox.eventNotify('global:page:setTitle', 'Toolbox');
    });

    // show menu when user is active
    Sandbox.eventSubscribe('global:auth:status:active', function (options) {
        // debugger;
        var menu = new Menu();
        menu.render();
    });

});
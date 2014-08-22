define("plugin/dashboard/toolbox/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/auth',
    'default/js/lib/backbone'
], function (Sandbox, $, Auth, Backbone) {

    Sandbox.eventSubscribe('global:page:index', function () {
        if (!Auth.getAccountID())
            return;
        require(['plugin/dashboard/toolbox/js/view/dashboard', ''], function (ViewDashboard) {
            var dashboard = new ViewDashboard();
            dashboard.render();
            Sandbox.eventNotify('global:content:render', {
                name: 'CommonBodyCenter',
                el: dashboard.$el
            });
            Sandbox.eventNotify('plugin:dashboard:ready', dashboard);
        });
    });

    Sandbox.eventSubscribe('global:auth:status:active', function (data) {
        require(['plugin/dashboard/toolbox/js/view/menu'], function (ViewMenu) {
            var menu = new ViewMenu();
            menu.render();
            Sandbox.eventNotify('customer:menu:set', {
                name: 'MenuLeft',
                el: menu.$el,
                prepend: true
            });
            if (Backbone.history.getFragment() === '')
                Sandbox.eventNotify('global:menu:set-active', '.menu-dashboard-dashboard');
        });
    });
});
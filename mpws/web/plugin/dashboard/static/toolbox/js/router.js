define("plugin/dashboard/toolbox/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/auth',
    'default/js/lib/backbone'
], function (Sandbox, $, Auth, Backbone) {

    Sandbox.eventSubscribe('global:page:index', function (data) {
        if (!Auth.getAccountID())
            return;
        require(['plugin/dashboard/toolbox/js/view/dashboard'], function (ViewDashboard) {
            var dashboard = new ViewDashboard();
            dashboard.render();
            Sandbox.eventNotify('global:content:render', {
                name: 'CommonBodyCenter',
                el: dashboard.$el
            });
            Sandbox.eventNotify('plugin:dashboard:ready', dashboard);
        });
    });

});
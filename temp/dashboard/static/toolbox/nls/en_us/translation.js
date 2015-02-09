define("plugin/dashboard/toolbox/nls/en_us/translation", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'website/nls/en_us/plugin_dashboard'
], function (_, CustomerPluginDashboard) {
    return _.extend({}, {
        // menu
        pluginMenuTitle: 'System Overview',
        pluginMenu_Dashboard: 'Dashboard'
    }, CustomerPluginDashboard);
});
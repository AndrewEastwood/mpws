define("plugin/dashboard/toolbox/nls/ua_uk/translation", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'website/nls/ua_uk/plugin_dashboard'
], function (_, CustomerPluginDashboard) {
    return _.extend({}, {
        // menu
        pluginMenuTitle: 'Огляд системи',
        pluginMenu_Dashboard: 'Статистика'
    }, CustomerPluginDashboard);
});
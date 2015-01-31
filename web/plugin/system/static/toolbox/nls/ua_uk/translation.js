define("plugin/system/toolbox/nls/ua_uk/translation", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'customer/nls/ua_uk/plugin_system'
], function (_, CustomerPluginShop) {
    return _.extend({}, {

        // menu
        menu: {
            title: 'Система',
            customers: 'Сайти'
        }


    }, CustomerPluginShop);
});
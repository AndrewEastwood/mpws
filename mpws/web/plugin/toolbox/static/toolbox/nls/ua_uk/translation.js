define("plugin/toolbox/toolbox/nls/ua_uk/translation", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'website/nls/ua_uk/plugin_toolbox'
], function (_, CustomerPluginToolbox) {
    return _.extend({}, {
        pluginLogoText: "Toolbox",
        // siginin page
        form_signin_title: 'Вхід в систему адміністрування',
        form_signin_field_login: 'Логін',
        form_signin_field_password: 'Пароль',
        form_signin_button_submit: 'Увійти',
        form_signin_error: "Помилка авторизації",
        // menu items
        pluginMenuTitle: 'Панель керування',
        pluginMenu_Dashboard: 'Дошка',
        pluginMenu_Notifications: 'Сповіщення',
        pluginMenu_Profile: 'Профіль',
        pluginMenu_Administrators: 'Адміни',
        pluginMenu_Version: 'Версія',
        pluginMenu_Logout: 'Вийти',
    }, CustomerPluginToolbox);
});
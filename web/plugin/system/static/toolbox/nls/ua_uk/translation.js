define("plugin/system/toolbox/nls/ua_uk/translation", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'customer/nls/ua_uk/plugin_system'
], function (_, CustomerPluginAccount) {
    return _.extend({}, {
        menu: {
            title: 'Система',
            customers: 'Сайти',
            migrations: 'Маграції БД',
            users: 'Користувачі'
        },
        form: {
            signin: {
                title: 'Вхід у систему',
                generalError: 'НЕвірний логін або пароль',
                fieldLogin: 'Лоігн',
                fieldPassword: 'Пароль',
                buttonSubmit: 'Вхід'
            }
        }
    }, CustomerPluginAccount);
});
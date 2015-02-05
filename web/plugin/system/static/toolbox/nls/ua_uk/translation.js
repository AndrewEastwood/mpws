define("plugin/system/toolbox/nls/ua_uk/translation", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'customer/nls/ua_uk/plugin_system'
], function (_, CustomerPluginAccount) {
    return _.extend({}, {
        menu: {
            title: 'Система',
            dashboard: 'Огляд системи',
            customers: 'Сайти',
            migrations: 'Маграції БД',
            users: 'Користувачі'
        },
        form: {
            signin: {
                title: 'Вхід у систему',
                generalError: 'Невірний логін або пароль',
                fieldLogin: 'Лоігн',
                fieldPassword: 'Пароль',
                buttonSubmit: 'Вхід'
            }
        },
        managers: {
            customers: {
                title: 'Сайти',
                noData: 'Нема даних'
            },
            users: {
                title: 'Користувачі'
            }
        },
        lists: {
            customers: {
                columnID: '#',
                columnName: 'Назва',
                columnStatus: 'Статус',
                columnDateCreated: 'Дата Створення',
                columnDateUpdated: 'Дата Оновлення',
            }
        },
        customer: {
            statuses: {
                ACTIVE: 'Активний',
                REMOVED: 'Видалений'
            }
        }
    }, CustomerPluginAccount);
});
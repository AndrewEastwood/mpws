define("plugins/system/toolbox/nls/ua_uk/translation", {
    menu: {
        title: 'Система',
        dashboard: 'Огляд системи',
        customers: 'Сайти',
        customerEdit: 'Редагувати',
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
            title: 'Користувачі',
            noData: 'Нема даних'
        }
    },
    lists: {
        customers: {
            columnID: '#',
            columnName: 'Назва',
            columnStatus: 'Статус',
            columnLang: 'Мова',
            columnLocale: 'Локалізація',
            columnDateCreated: 'Дата Створення',
            columnDateUpdated: 'Дата Оновлення'
        },
        users: {
            columnID: '#',
            columnIsOnline: 'Онлайн',
            columnFullName: 'Імя',
            columnEMail: 'Мейл',
            columnPhone: 'Тел.',
            columnValidationString: 'Код',
            columnStatus: 'Стату',
            columnDateLastAccess: 'Ост.Доступ',
            columnDateCreated: 'Дата Створення',
            columnDateUpdated: 'Дата Оновлення'
        }
    },
    customer: {
        statuses: {
            ACTIVE: 'Активний',
            REMOVED: 'Видалений'
        }
    },
    user: {
        statuses: {
            ACTIVE: 'Активний',
            REMOVED: 'Видалений',
            TEMP: 'Тимчасовий'
        }
    },
    editors: {
        customer: {
            titleForNew: 'Створення сайту',
            titleForExistent: 'Редагування сайту',
            errors: {
                title: ''
            },
            labelHostName: 'Хост',
            labelHomePage: 'Домашня сторінка',
            labelTitle: 'Заголовок',
            labelAdminTitle: 'Зголовок адміністрування',
            labelLogo: 'Логотип',
            labelLang: 'Мова',
            labelLocale: 'Локалізація',
            labelProtocol: 'Протокол',
            labelSnapshotURL: 'URL СЕО (Seo4Ajax)',
            labelSitemapURL: 'URL сайтмапи',
            buttonSave: 'Зберегти',
            buttonClose: 'Назад',
            messageSuccess: 'Успішно',
            messageError: 'Помилка',
            plugins: {
                title: 'Додатки'
            }
        },
        user: {
            titleForNew: 'Створення користувача',
            titleForExistent: 'Редагування користувача',
            errors: {
                title: ''
            },
            alerts: {
                success: 'Успішно'
            },
            labelIsOnline: '- on-line індикатор',
            labelFirstName: 'Імя',
            labelLastName: '',
            labelEMail: 'Ел.Адреса',
            labelPhone: 'Телеофн',
            labelValidationString: 'Код активації',
            labelPassword: 'Пароль',
            labelPasswordVerify: 'Підтвердження',
            labelСurrentPassword: 'Поточний пароль',
            labelp_CanAdmin: 'Адміністрування',
            labelp_CanCreate: 'Створення записів',
            labelp_CanEdit: 'Редагування записів',
            labelp_CanUpload: 'Завантаження файлів',
            labelp_CanViewReports: 'Перегляд звітів',
            labelp_CanAddUsers: 'Створення користувачів',
            labelp_CanMaintain: 'Сервіс',
            buttonSave: 'Зберегти',
            buttonClose: 'Назад',
            messageSuccess: 'Успішно',
            messageError: 'Помилка',
            permissions: {
                title: 'Права доступу'
            }
        }
    }
});
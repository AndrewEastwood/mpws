define({
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
                success: 'Успішно',
                successAddrRemove: 'Адресу видалено',
                error: 'Помилка'
            },
            popups: {
                password: {
                    title: 'Згенерований пароль'
                }
            },
            labelIsOnline: '- on-line індикатор',
            labelFirstName: 'Імя',
            labelLastName: '',
            labelEMail: 'Ел.Адреса',
            labelPhone: 'Телеофн',
            labelValidationString: 'Код активації',
            labelPassword: 'Пароль',
            labelConfirmPassword: 'Підтвердження',
            labelp_CanAdmin: 'Адміністрування',
            labelp_CanCreate: 'Створення записів',
            labelp_CanEdit: 'Редагування записів',
            labelp_CanUpload: 'Завантаження файлів',
            labelp_CanViewReports: 'Перегляд звітів',
            labelp_CanAddUsers: 'Створення користувачів',
            labelp_CanMaintain: 'Сервіс',
            labelAddress: 'Адреса',
            labelPOBox: 'Поштовий індекс',
            labelCountry: 'Країна',
            labelCity: 'Місто',
            phFirstName: 'імя',
            phLastName: 'прізвище',
            phPhone: '(000) 000-00-00',
            phEMail: 'some@mail.ua',
            phPassword: 'Пароль',
            phConfirmPassword: 'Підтвердження',

    profile_page_password_title: "Зміна паролю",
    profile_page_password_field_Password: "Пароль",
    profile_page_password_field_Password_placeholder: "Пароль",
    profile_page_password_field_ConfirmPassword: "Підтвердження",
    profile_page_password_field_ConfirmPassword_placeholder: "Підтвердження",
    profile_page_password_button_generate: "Згенерувати",
    profile_page_password_button_generate_cancel: "Скасувати",
    profile_page_password_button_save: "Зберегти зміни",
    profile_page_password_message_saveSuccess: "Пароль успішно оновлено",
    profile_page_password_message_saveError: "Помилка під час оновлення паролю",
    profile_page_password_popup_title: "Згенерований пароль",
    /* profile page:edit */
    profile_page_edit_title: "Редагування профілю",
    profile_page_edit_field_firstName: "Імя",
    profile_page_edit_field_firstName_placeholder: "Введіть імя",
    profile_page_edit_field_lastName: "Прізвище",
    profile_page_edit_field_lastName_placeholder: "Введіть прізвище",
    profile_page_edit_field_email: "Ел.адреса",
    profile_page_edit_field_email_placeholder: "Введіть ел.адресу",
    profile_page_edit_field_phone: "Телефон",
    profile_page_edit_field_phone_placeholder: "Введіть телефон",
    profile_page_edit_button_save: "Зберегти зміни",
    profile_page_edit_message_saveSuccess: "Зміни успішно збережені",
    profile_page_edit_error_requiredField: "Поле є обовязковим для заповнення",
    profile_page_edit_error_commonError: "Помилка під час оновлення профілю",
    profile_page_edit_label_emptyValue: "Немає значення",
    profile_page_edit_error_atField_FirstName: "Помилка в полі 'Імя'",
    profile_page_edit_error_FirstNameLengthIsLowerThan_2: "Довжина імені повинна бути більше ніж 2 символи",
    profile_page_edit_error_FirstNameIsNoString: "Ім'я не може бути числом",
    profile_page_edit_error_atField_LastName: "Помилка в полі 'Прізвище'",
    profile_page_edit_error_LastNameLengthIsLowerThan_2: "Довжина прізвища повинна бути більше ніж 2 символи",
    profile_page_edit_error_LastNameIsNoString: "Прізвище не може бути числом",
    profile_page_edit_error_atField_Phone: "Помилка в полі 'Телефон'",
    profile_page_edit_error_PhoneIsNotPhone: "Вказаний телефон є неправильний",
    'profile_page_edit_error_FormatMustBe__(###)_###-##-##': "Формат запису телефона повинен бути: (ххх) ххх-хх-хх",
    profile_page_edit_error_atField_Password: "Помилка в полі 'Пароль'",
    profile_page_edit_error_PasswordDoesNotContainAnyUpperCase: "Потрібно ввести хоча б одну велику літеру",
    profile_page_edit_error_PasswordDoesNotContainAnyLowerCase: "Потрібно ввести хоча б одну маленьку літеру",
    profile_page_edit_error_PasswordDoesNotContainAnySpecial: "Потрібно ввести хоча б один спец-символ: ! @ # $ % *",
    profile_page_edit_error_PasswordDoesNotContainAnyNumber: "Потрібно ввести хоча б одину цифру",
    profile_page_edit_error_PasswordLengthIsLowerThan_8: "Мінімальна довжина паролю 8 символів",
    profile_page_edit_error_atField_ConfirmPassword: "Пароль підтвердження:",
    profile_page_edit_error_atField_EMail: "Помилка в полі 'електронна адреса'",
    profile_page_edit_error_EMailIsNotEmail: "Введено не електронну адресу",
    profile_page_edit_error_ConfirmPasswordIsNotEqualTo_Password: 'Пароль підтвердження не співпадає з основним паролем',
    /* profile addresses */
    profile_page_editAddress_destroySuccess: "Адресу успішно видалено",
    profile_page_editAddress_saveSuccess: "Зміни успішно збережені",
    profile_page_editAddress_error_commonError: "Помилка збереження адреси",
    profile_page_editAddress_error_MissedParameter_id: "Помилка видалення адреси",
    profile_page_edit_error_atField_City: "Помилка в полі Місто",
    profile_page_edit_error_CityLengthIsLowerThan_2: 'Мінімальна довжина 2 символи',
    profile_page_edit_error_CityIsNoString: 'Немає літер в назві міста',
    profile_page_edit_error_atField_Country: "Помилка в полі Країна",
    profile_page_edit_error_CountryLengthIsLowerThan_2: 'Мінімальна довжина 2 символи',
    profile_page_edit_error_CountryIsNoString: 'Немає літер в назві країни',
    profile_page_edit_error_atField_POBox: "Помилка в полі Поштовий індекс",
    profile_page_edit_error_POBoxLengthIsLowerThan_2: 'Мінімальна довжина 2 символи',
    profile_page_edit_error_POBoxIsEmpty: 'Порожнє значення',
    profile_page_edit_error_atField_Address: "Помилка в полі Адреса",
    profile_page_edit_error_AddressLengthIsLowerThan_2: 'Мінімальна довжина 2 символи',
    profile_page_edit_error_AddressIsNoString: 'Немає літер в адресі',
    profile_page_edit_error_AddressIsEmpty: 'Порожнє значення',

            buttonGeneratePasswordCancel: 'Скасувати',
            buttonAddAddress: 'Додати адресу',
            buttonGeneratePassword: 'Згенерувати',
            buttonSaveAddress: 'Зберегти',
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
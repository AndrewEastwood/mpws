define("plugin/account/site/nls/ua_uk/translation", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'website/nls/ua_uk/plugin_account'
], function (_, CustomerPluginAccount) {
    return _.extend({}, {
        /* form register */
        form_register_title: "Реєстрація",
        form_register_field_FirstName: "Імя",
        form_register_field_LastName: "Прізвище",
        form_register_field_EMail: "Ел. адреса",
        form_register_field_Password: "Пароль",
        form_register_field_ConfirmPassword: "Підтвердження паролю",
        form_register_message_bottom: "",
        form_register_message_success_title: "Новий профіль успішно створнений!",
        form_register_message_success_line1: "На Вашу ел. скриньку надійде повідомлення з підтвредженням Вашї реєстрації.",
        form_register_message_success_line2: "Для успішного завершення реєстрації Вам необхідно виконати інструкції в отриманому листі",
        form_register_button_create: "Сворити профіль",
        form_register_error_FirstName_Empty: 'Імя є порожнє',
        form_register_error_LastName_Empty: 'Прізвище є порожнє',
        form_register_error_ConfirmPassword_WrongConfirmPassword: 'Паролі не співпадають',
        /* form signin */
        form_signin_menu_title: "Увійти",
        form_signin_field_Email: "Ел.адреса",
        form_signin_field_Password: "Пароль",
        form_signin_field_RememberMe: "Запамятати мене",
        form_signin_message_bottom: "",
        form_signin_button_signin: "Увійти",
        form_signin_error: 'Невірний логін або пароль',
        /* profile menu short */
        profile_menu_title: "",
        profile_menu_welcome: "Вітаємо: ",
        profile_menu_lastAccessDate: "Останній вхід: ",
        profile_menu_profile: "Профіль",
        profile_menu_logout: "Вийти",
        /* profile pages */
        profile_page_menu_profile: "Профіль",
        profile_page_menu_profile_overview: "Огляд",
        profile_page_menu_profile_password: "Зміна паролю",
        profile_page_menu_profile_edit: "Змінити профіль",
        profile_page_menu_profile_addresses: "Мої адреси",
        profile_page_menu_profile_delete: "Видалити профіль",
        profile_page_menu_plugins: "Компоненти",
        /* profile page:overview */
        profile_page_overview_title: "Огляд",
        profile_page_overview_section_addresses_title: "Мої адреси",
        /* profile page:settings */
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
        /* profile page:delete */
        profile_page_delete_title: "Видалення профілю",
        /* profile page:addresses */
        profile_page_addresses_title: "Мої адреси",
        profile_page_addresses_Address: "Адреса",
        profile_page_addresses_POBox: "Поштовий індекс",
        profile_page_addresses_Country: "Країна",
        profile_page_addresses_City: "Місто",
        profile_page_addresses_entry_new: "Введіть адресу",
        profile_page_addresses_label_emptyValue: "Немає значення",
        profile_page_addresses_message_saveSuccess: "Зміни успішно збережені",
        profile_page_addresses_message_saveError: "Помилка під час збереження адреси",
        profile_page_addresses_button_addAddress: "Додати адресу",
        profile_page_addresses_button_saveAddress: "Зберегти"
    }, CustomerPluginAccount);
});
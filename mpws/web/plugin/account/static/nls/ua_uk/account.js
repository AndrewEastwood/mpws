define("plugin/account/nls/ua_uk/account", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'default/js/plugin/i18n!customer/nls/account'
], function(_, CustomerAccount) {
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
        profile_menu_settings: "Налаштування",
        profile_menu_logout: "Вийти",
    }, CustomerAccount);
});
define("plugin/account/nls/ua_uk/account", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'default/js/plugin/i18n!customer/nls/account'
], function(_, CustomerAccount) {
    return _.extend({}, {
        form_register_title: "Реєстрація",
        form_register_field_FirstName: "Імя",
        form_register_field_LastName: "Прізвище",
        form_register_field_EMail: "Ел. адреса",
        form_register_field_Password: "Пароль",
        form_register_field_ConfirmPassword: "Підтвердження паролю",
        form_register_message_bottom: "",
        form_register_button_create: "Сворити профіль",
        register_error_FirstName_Empty: 'Імя є порожнє',
        register_error_LastName_Empty: 'Прізвище є порожнє',
        register_error_ConfirmPassword_WrongConfirmPassword: 'Паролі не співпадають'
    }, CustomerAccount);
});
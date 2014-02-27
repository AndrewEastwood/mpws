define("plugin/account/nls/en_us/account", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'default/js/plugin/i18n!customer/nls/account'
], function(_, CustomerAccount) {
    return _.extend({}, {
        register_title: "Реєстрація",
        register_error_firstname_Empty: 'User name is empty'
    }, CustomerAccount);
});
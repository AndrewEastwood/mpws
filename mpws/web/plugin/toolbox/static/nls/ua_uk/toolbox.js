define("plugin/toolbox/nls/ua_uk/toolbox", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'customer/nls/ua_uk/toolbox_toolbox'
], function (_, CustomerShop) {
    return _.extend({}, {
        // siginin page
        form_signin_title: 'Вхід в систему адміністрування',
        form_signin_field_login: 'Логін',
        form_signin_field_password: 'Пароль',
        form_signin_button_submit: 'Увійти',
    }, CustomerShop);
});
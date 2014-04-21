define("plugin/account/nls/en_us/site", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'customer/nls/en_us/account_site'
], function(_, CustomerAccount) {
    return _.extend({}, {
        form_register_title: 'Registration',
        form_register_field_FirstName: 'First name',
        form_register_field_LastName: 'Last name',
        form_register_field_EMail: 'Email',
        form_register_field_Password: 'Password',
        form_register_field_ConfirmPassword: 'Confirmation password does not match with original',
        form_register_message_bottom: 'By clicking Create my account, you agree to our Terms and that you have read our Data Use Policy, including our Cookie Use.',
        form_register_button_create: 'Create',
        register_error_FirstName_Empty: 'First name is empty',
        register_error_LastName_Empty: 'Last name is empty',
        register_error_Email_EMpty: 'Email address is empty',
        register_error_ConfirmPassword_WrongConfirmPassword: ''
    }, CustomerAccount);
});
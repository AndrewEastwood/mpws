define("plugin/account/toolbox/nls/ua_uk/translation", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'website/toolbox/nls/ua_uk/plugin_account'
], function (_, CustomerAccount) {
    return _.extend({}, {
    }, CustomerAccount);
});
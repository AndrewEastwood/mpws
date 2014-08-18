define("plugin/dashboard/toolbox/js/view/dashboard", [
    'default/js/lib/backbone'
    // 'default/js/lib/utils',
    // /* template */
    // 'default/js/plugin/hbs!plugin/shop/toolbox/hbs/stats',
    // /* lang */
    // 'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Backbone/*, Utils, Auth, tpl, lang*/) {

    return Backbone.View.extend({
        attributes: {
            id: 'dashboard-ID'
        },
        className: 'plugin-dashboard',
        // model: new (),
        // lang: lang,
        // template: tpl
    });

});
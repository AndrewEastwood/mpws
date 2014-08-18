define("plugin/dashboard/toolbox/js/view/dashboard", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone'
    // 'default/js/lib/utils',
    // /* template */
    // 'default/js/plugin/hbs!plugin/shop/toolbox/hbs/stats',
    // /* lang */
    // 'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function ($, _, Backbone/*, Utils, Auth, tpl, lang*/) {

    return Backbone.View.extend({
        attributes: {
            id: 'dashboard-ID'
        },
        className: 'plugin-dashboard',
        // model: new (),
        // lang: lang,
        // template: tpl
        render: function () {
            var self = this;
            var pluginsItems = APP.config.PLUGINS;

            this.$el.empty();
            // debugger;
            _(pluginsItems).each(function (pluginName) {
                self.$el.append($('<div/>').attr({
                    name: 'DashboardForPlugin_' + pluginName
                }));
            });

            return this;
        }
    });

});
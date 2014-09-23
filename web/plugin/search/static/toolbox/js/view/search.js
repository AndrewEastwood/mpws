define("plugin/search/toolbox/js/view/search", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone'
], function ($, _, Backbone/*, Utils, Auth, tpl, lang*/) {

    return Backbone.View.extend({
        attributes: {
            id: 'search-ID'
        },
        className: 'plugin-search',
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
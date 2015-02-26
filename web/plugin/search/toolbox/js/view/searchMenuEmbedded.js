define("plugin/search/toolbox/js/view/searchMenuEmbedded", [
    'jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/search/toolbox/hbs/searchMenuEmbedded',
    /* lang */
    'default/js/plugin/i18n!plugin/search/toolbox/nls/translation',
], function ($, _, Backbone, Utils, tpl, lang) {

    return Backbone.View.extend({
        tagName: 'li',
        className: 'sidebar-search',
        lang: lang,
        template: tpl,
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
        }
    });

});
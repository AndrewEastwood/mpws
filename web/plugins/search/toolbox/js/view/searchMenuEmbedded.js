define([
    'jquery',
    'underscore',
    'backbone',
    'utils',
    'hbs!plugins/search/toolbox/hbs/searchMenuEmbedded',
    /* lang */
    'i18n!plugins/search/toolbox/nls/translation',
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
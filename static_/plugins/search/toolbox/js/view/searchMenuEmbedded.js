define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'utils',
    'text!plugins/search/toolbox/hbs/searchMenuEmbedded.hbs',
    /* lang */
    'i18n!plugins/search/toolbox/nls/translation',
], function ($, _, Backbone, Handlebars, Utils, tpl, lang) {

    return Backbone.View.extend({
        tagName: 'li',
        className: 'sidebar-search',
        lang: lang,
        template: Handlebars.compile(tpl), // check
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
        }
    });

});
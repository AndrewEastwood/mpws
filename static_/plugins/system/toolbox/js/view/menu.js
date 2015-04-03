define([
    'backbone',
    'handlebars',
    'utils',
    'text!plugins/system/toolbox/hbs/menu.hbs',
    /* lang */
    'i18n!plugins/system/toolbox/nls/translation'
], function (Backbone, Handlebars, Utils, tpl, lang) {

    var Menu = Backbone.View.extend({
        lang: lang,
        template: Handlebars.compile(tpl), // check
        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            // this.$el = $(this.$el.html());
            return this;
        }
    });

    return Menu;
});
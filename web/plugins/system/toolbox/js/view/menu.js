define([
    'backbone',
    'utils',
    'hbs!plugins/system/toolbox/hbs/menu',
    /* lang */
    'i18n!plugins/system/toolbox/nls/translation'
], function (Backbone, Utils, tpl, lang) {

    var Menu = Backbone.View.extend({
        lang: lang,
        template: Handlebars.compile(tpl), // check
        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$el = $(this.$el.html());
            return this;
        }
    });

    return Menu;
});
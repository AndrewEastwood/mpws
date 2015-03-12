define([
    'sandbox',
    'backbone',
    'auth',
    'utils',
    'hbs!plugins/system/toolbox/hbs/buttonUser',
    /* lang */
    'i18n!plugins/system/toolbox/nls/translation'
], function (Sandbox, Backbone, Auth, Utils, tpl, lang) {

    var ButtonUser = Backbone.View.extend({
        tagName: 'li',
        className: 'dropdown plugin-system-user-button',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            if (this.model.isEmpty())
                this.remove();
            else
                this.$el.html(tpl(Utils.getHBSTemplateData(this)));
        }
    });

    return ButtonUser;

});
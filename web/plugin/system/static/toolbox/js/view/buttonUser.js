define("plugin/system/toolbox/js/view/buttonUser", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/auth',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/system/toolbox/hbs/buttonUser',
    /* lang */
    'default/js/plugin/i18n!plugin/system/toolbox/nls/translation'
], function (Sandbox, Backbone, Auth, Utils, tpl, lang) {

    var SignIn = Backbone.View.extend({
        tagName: 'li',
        className: 'dropdown plugin-system-user-button',
        template: tpl,
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

    return SignIn;

});
define("plugin/account/toolbox/js/view/buttonAccount", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/auth',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/account/toolbox/hbs/buttonAccount',
    /* lang */
    'default/js/plugin/i18n!plugin/account/toolbox/nls/translation'
], function (Sandbox, Backbone, Auth, Utils, tpl, lang) {

    var SignIn = Backbone.View.extend({
        tagName: 'li',
        className: 'dropdown plugin-account-button',
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
define("plugin/account/site/js/view/accountSummary", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/account/site/hbs/accountSummary',
    /* lang */
    'default/js/plugin/i18n!plugin/account/site/nls/translation'
], function (Sandbox, Backbone, Utils, tpl, lang) {

    var AccountSummary = Backbone.View.extend({
        // tagName: 'li',
        // className: 'col-sm-9 col-md-9',
        template: tpl,
        lang: lang,
        initialize: function () {
            if (this.model)
                this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return AccountSummary;

});
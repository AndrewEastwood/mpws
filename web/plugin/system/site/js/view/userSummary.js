define("plugin/system/site/js/view/userSummary", [
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/system/site/hbs/userSummary',
    /* lang */
    'default/js/plugin/i18n!plugin/system/site/nls/translation'
], function (Backbone, Utils, tpl, lang) {

    var UserSummary = Backbone.View.extend({
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

    return UserSummary;

});
define([
    'backbone',
    'handlebars',
    'utils',
    'text!plugins/system/site/hbs/userSummary.hbs',
    /* lang */
    'i18n!plugins/system/site/nls/translation'
], function (Backbone, Handlebars, Utils, tpl, lang) {

    var UserSummary = Backbone.View.extend({
        // tagName: 'li',
        // className: 'col-sm-9 col-md-9',
        template: Handlebars.compile(tpl), // check
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
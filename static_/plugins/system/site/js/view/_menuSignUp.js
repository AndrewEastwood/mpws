define([
    'backbone',
    'handlebars',
    'utils',
    'text!plugins/system/site/hbs/menuSignUp.hbs'
], function (Backbone, Handlebars, Utils, tpl) {

    var MenuSignUp = Backbone.View.extend({
        tagName: 'li',
        template: Handlebars.compile(tpl), // check
        initialize: function () {
            this.listenTo(this.model, "change", this.render);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return MenuSignUp;

});
define([
    'sandbox',
    'backbone',
    'utils',
    'hbs!plugins/system/site/hbs/menuSignUp'
], function (Sandbox, Backbone, Utils, tpl) {

    var MenuSignUp = Backbone.View.extend({
        tagName: 'li',
        template: tpl,
        initialize: function () {
            this.listenTo(this.model, "change", this.render);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
        }
    });

    return MenuSignUp;

});
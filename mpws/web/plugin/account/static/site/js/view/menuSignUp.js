define("plugin/account/site/js/view/menuSignUp", [
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/account/site/hbs/menuSignUp'
], function (Backbone, Utils, tpl) {

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
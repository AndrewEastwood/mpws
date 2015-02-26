define("customer/js/view/breadcrumb", [
    'jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    /* template */
    'default/js/plugin/hbs!customer/hbs/breadcrumb',
    /* ui components */
    'default/js/lib/bootstrap'
], function ($, _, Backbone, Utils, tpl) {

    var Breadcrumb = Backbone.View.extend({
        template: tpl,
        render: function (options) {
            this.options = options;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return Breadcrumb;
});
define([
    'jquery',
    'underscore',
    'backbone',
    'utils',
    /* template */
    'hbs!customers/pb.com.ua/hbs/breadcrumb'
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
define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'utils',
    /* template */
    'text!customers/pb.com.ua/hbs/breadcrumb.hbs'
], function ($, _, Backbone, Handlebars, Utils, tpl) {

    var Breadcrumb = Backbone.View.extend({
        template: Handlebars.compile(tpl),
        render: function (options) {
            this.options = options;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return Breadcrumb;
});
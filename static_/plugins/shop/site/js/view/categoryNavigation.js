define([
    'backbone',
    'handlebars',
    'utils',
    'text!plugins/shop/site/hbs/categoryNavigation.hbs'
], function (Backbone, Handlebars, Utils, tpl) {

    var MenuCatalog = Backbone.View.extend({
        className: 'row shop-catalog-navigation',
        template: Handlebars.compile(tpl), // check
        initialize: function () {
            this.model.on('change', this.render, this);
        },
        render: function() {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return MenuCatalog;

});
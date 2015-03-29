define([
    'backbone',
    'handlebars',
    'utils',
    'text!plugins/shop/site/hbs/menuCatalogBar.hbs'
], function (Backbone, Handlebars, Utils, tpl) {

    var MenuCatalogBar = Backbone.View.extend({
        tagName: 'ul',
        className: 'nav shop-catalog-bar',
        template: Handlebars.compile(tpl), // check
        initialize: function () {
            this.model.on('change', this.render, this);
        },
        render: function() {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return MenuCatalogBar;

});
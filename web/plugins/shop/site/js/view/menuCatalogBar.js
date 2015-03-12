define([
    'backbone',
    'handlebars',
    'plugins/shop/site/js/model/menuCatalog',
    'utils',
    'text!plugins/shop/site/hbs/menuCatalogBar.hbs'
], function (Backbone, Handlebars, modelCatalogStructureMenu, Utils, tpl) {

    var MenuCatalogBar = Backbone.View.extend({
        // tagName: 'ul',
        className: 'navbar yamm navbar-default navbar-fixed-top shop-catalog-bar',
        template: Handlebars.compile(tpl), // check
        initialize: function () {
            this.model = new modelCatalogStructureMenu();
            this.model.on('change', this.render, this);
        },
        render: function() {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return MenuCatalogBar;

});
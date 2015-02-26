define([
    'backbone',
    'plugins/shop/site/js/model/menuCatalog',
    'utils',
    'hbs!plugins/shop/site/hbs/menuCatalogBar'
], function (Backbone, modelCatalogStructureMenu, Utils, tpl) {

    var MenuCatalogBar = Backbone.View.extend({
        // tagName: 'ul',
        className: 'navbar yamm navbar-default navbar-fixed-top shop-catalog-bar',
        template: tpl,
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
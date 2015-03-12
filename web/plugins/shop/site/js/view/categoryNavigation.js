define([
    'backbone',
    'plugins/shop/site/js/model/menuCatalog',
    'utils',
    'hbs!plugins/shop/site/hbs/categoryNavigation'
], function (Backbone, modelCatalogStructureMenu, Utils, tpl) {

    var MenuCatalog = Backbone.View.extend({
        className: 'row shop-catalog-navigation',
        template: Handlebars.compile(tpl), // check
        model: new modelCatalogStructureMenu(),
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
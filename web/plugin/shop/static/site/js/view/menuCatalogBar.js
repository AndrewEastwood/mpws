define("plugin/shop/site/js/view/menuCatalogBar", [
    'default/js/lib/backbone',
    'plugin/shop/site/js/model/menuCatalog',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/menuCatalogBar'
], function (Backbone, modelCatalogStructureMenu, Utils, tpl) {

    var MenuCatalogBar = Backbone.View.extend({
        className: 'shop-catelog-bar',
        template: tpl,
        model: new modelCatalogStructureMenu(),
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
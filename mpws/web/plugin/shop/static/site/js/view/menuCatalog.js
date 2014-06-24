define("plugin/shop/site/js/view/menuCatalog", [
    'default/js/lib/backbone',
    'plugin/shop/site/js/model/menuCatalog',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/menuCatalog'
], function (Backbone, modelCatalogStructureMenu, Utils, tpl) {

    var MenuCatalog = Backbone.View.extend({
        tagName: 'li',
        className: 'dropdown shop-dropdown-catalog',
        id: 'shop-dropdown-catalog-ID',
        model: new modelCatalogStructureMenu(),
        template: tpl,
        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
        },
        render: function() {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return MenuCatalog;

});
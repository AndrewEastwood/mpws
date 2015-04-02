define([
    'backbone',
    'handlebars',
    'plugins/shop/site/js/model/catalogNavigator',
    'utils',
    'text!plugins/shop/site/hbs/catalogNavigator.hbs',
    'text!plugins/shop/site/hbs/catalogNavigatorSubItemChildItems.hbs'
], function (Backbone, Handlebars, ModelCatalogNavigator, Utils, tplList, tplTopLevel) {

    var CatalogNavigator = Backbone.View.extend({
        model: ModelCatalogNavigator.getInstance(),
        className: 'shop-catalog-navigator',
        tagName: 'ul',
        templates: {
            sub: Handlebars.compile(tplTopLevel),
            all: Handlebars.compile(tplList)
        }, // check
        initialize: function (options) {
            this.options = options || {};
            // set default style
            this.options.design = _.extend({style: 'all'}, this.options.design || {});
            this.model.on('change', this.render, this);
        },
        render: function () {
            var design = this.options.design,
                tpl = this.templates[design.style],
                tplData = Utils.getHBSTemplateData(this);
            if (design.parentID) {
                var categoryItem = this.model.findCategoryItem(design.parentID);
                if (categoryItem && categoryItem.childNodes)
                    tplData.data = categoryItem.childNodes;
                else
                    tplData.data = null;
            }
            this.$el.html(tpl(tplData));
            this.$el.addClass('shop-category-list');
            if (design.className) {
                this.$el.addClass(design.className);
            }
            return this;
        },
        hasSubCategories: function (categoryID) {
            return !!this.model.findCategoryItem(categoryID);
        }
    });

    return CatalogNavigator;

});
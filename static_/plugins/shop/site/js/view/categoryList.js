define([
    'backbone',
    'handlebars',
    'plugins/shop/site/js/model/catalogNavigator',
    'utils',
    'text!plugins/shop/site/hbs/categoryList.hbs',
    'text!plugins/shop/site/hbs/categoryTopLevelList.hbs'
], function (Backbone, Handlebars, ModelCatalogNavigator, Utils, tplList, tplTopLevel) {

    var MenuCatalogBar = Backbone.View.extend({
        model: ModelCatalogNavigator.getInstance(),
        tagName: 'ul',
        templates: {
            toplevel: Handlebars.compile(tplTopLevel),
            list: Handlebars.compile(tplList)
        }, // check
        initialize: function (options) {
            this.options = options || {};
            // set default style
            this.options.design = _.extend({style: 'list'}, this.options.design || {});
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

    return MenuCatalogBar;

});
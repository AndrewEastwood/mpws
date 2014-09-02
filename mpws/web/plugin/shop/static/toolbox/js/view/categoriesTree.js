define("plugin/shop/toolbox/js/view/categoriesTree", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/collection/categoriesTree',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/categoriesTree',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    /* extensions */
    'default/js/lib/jstree'
], function (Sandbox, Backbone, Utils, CollectionCategoriesTree, tpl, lang) {
    var CategoriesTree = Backbone.View.extend({
        className: 'shop-toolbox-categories',
        template: tpl,
        lang: lang,
        initialize: function () {
            this.collection = new CollectionCategoriesTree();
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            // debugger;
            this.$('#jstree_categories-ID').jstree({
                "plugins" : [
                    "contextmenu", "dnd", "search",
                    "state", "types", "wholerow"
                  ]
            });
            return this;
        }
    });

    return CategoriesTree;
});
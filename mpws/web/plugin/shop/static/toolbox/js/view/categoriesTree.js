define("plugin/shop/toolbox/js/view/categoriesTree", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/toolbox/js/model/categoriesTree',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/listCategories',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    /* extensions */
    'default/js/lib/jstree',
], function (Sandbox, MView, ModelCategoriesTree, tpl, lang) {
    var CategoriesTree = MView.extend({
        className: 'shop-toolbox-categories well',
        model: new ModelCategoriesTree(),
        template: tpl,
        lang: lang,
        initialize: function () {

            MView.prototype.initialize.call(this);
            var self = this;

            this.on('mview:renderComplete', function () {
                // debugger;
                self.$('#jstree_categories-ID').jstree({
                    "core" : {
                        "theme" : {
                            "variant" : "large"
                        }
                    },
                    "plugins" : [ "wholerow", "checkbox" ]
                });
            });
        }
    });

    return CategoriesTree;
});
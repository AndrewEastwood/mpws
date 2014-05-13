define("plugin/shop/toolbox/js/view/categoriesTree", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/toolbox/js/model/categoriesTree',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/categoriesTree',
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

            Sandbox.eventSubscribe('plugin:shop:category:add', function(data){
                // var popupCategory = new PopupCategory();
                // popupCategory.fetchAndRender();
            // $("#add").click(function() {
                // self.$('#jstree_categories-ID').jstree("create", $("#child1.id"), "inside",  { "data" : "child2" },
                //                   function() { alert("added"); }, true);
            // });
            });

            Sandbox.eventSubscribe('plugin:shop:category:edit', function(data){
                // var popupCategory = new PopupCategory();
                // popupCategory.fetchAndRender();
            });

            this.on('mview:renderComplete', function () {
                // debugger;
                self.$('#jstree_categories-ID').jstree({
                    "plugins" : [
                        "contextmenu", "dnd", "search",
                        "state", "types", "wholerow"
                      ]
                });
            });
        }
    });

    return CategoriesTree;
});
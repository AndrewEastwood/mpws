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
            this.listenTo(this.collection, 'reset', this.render);
        },
        render: function () {
            var self = this;
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            // debugger;
            // this.$('#jstree_categories-ID').jstree({
            //     "plugins" : [
            //         "contextmenu", "djstrnd", "search",
            //         "state", "types", "wholerow"
            //       ]
            // });
            this.$('#html1').jstree({
                core: {
                    check_callback: true,
                    themes: {
                        responsive: false,
                        stripes: true,
                        variant: 'small'
                    }
                },
                plugins: ["state", "contextmenu", "dnd"],
                contextmenu: {
                    items: function () {
                        var tmp = $.jstree.defaults.contextmenu.items();
                        delete tmp.ccp;
                        tmp.create_folder = {
                            "label"             : "New category",
                            "action"            : function (data) {
                                debugger;
                                var inst = $.jstree.reference(data.reference),
                                    obj = inst.get_node(data.reference);
                                inst.create_node(obj, "new category", "last", function (new_node) {
                                    setTimeout(function () { inst.edit(new_node); },0);
                                });
                            }
                        };

                        return tmp;
                    }
                }
            }).on('create_node.jstree', function (e, data) {
                self.collection.create({
                    name: data.node.text,
                    parent: data.node.parent
                }, {
                    success: function () {
                        data.instance.set_id(data.node, d.id);
                    },
                    error: function () {
                        data.instance.refresh();
                    }
                });
            });

            return this;
        }
    });

    return CategoriesTree;
});
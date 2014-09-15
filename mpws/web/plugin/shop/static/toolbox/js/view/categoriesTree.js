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
        className: 'panel panel-default plugin-shop-tree',
        template: tpl,
        lang: lang,
        events: {
            'click #show_removed': 'showRemoved'
        },
        initialize: function () {
            this.collection = new CollectionCategoriesTree();
            this.listenTo(this.collection, 'reset', this.render);
        },
        showRemoved: function (event) {
            this.collection.requestData.removed = $(event.target).is(':checked') ? true : null;
            this.collection.fetch({reset: true});
        },
        render: function () {
            var self = this;
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$('#categoryTree').jstree({
                core: {
                    check_callback: true,
                    themes: {
                        responsive: false,
                        stripes: true,
                        variant: 'small'
                    }
                },
                types: {
                    "REMOVED": {
                        icon: "fa fa-times"
                    }
                },
                plugins: ["state", "contextmenu", "dnd", "types"],
                contextmenu: {
                    items: function () {
                        var tmp = $.jstree.defaults.contextmenu.items();
                        delete tmp.ccp;
                        tmp.create.label = "Нова категорія";
                        tmp.rename.label = "Переіменувати";
                        tmp.remove.label = "Видалити";
                        tmp.remove._disabled = function (data) {
                            return data.reference.parent().data('childcount') > 0;
                        }
                        tmp.rename._disabled = function (data) {
                            return data.reference.parent().data('type') === "root";
                        }
                        tmp.create.action = function (data) {
                            // debugger;
                            var inst = $.jstree.reference(data.reference),
                                parentNode = inst.get_node(data.reference),
                                nodeData = {
                                    ParentID: parseInt(parentNode.id, 10) || null,
                                    Name: tmp.create.label,
                                    text: tmp.create.label
                                };
                            inst.create_node(parentNode, nodeData, "last", function (new_node) {
                                setTimeout(function () { inst.edit(new_node); },0);
                            });
                        }
                        tmp.restore = {
                            _disabled: function (data) {
                                return data.reference.parent().data('status') === "ACTIVE";
                            },
                            label: "Відновити",
                            action: function (data) {
                                var inst = $.jstree.reference(data.reference),
                                    node = inst.get_node(data.reference),
                                    model = self.collection.get(parseInt(node.id, 10));
                                if (model && model.save) {
                                    model.save({
                                        Status: "ACTIVE"
                                    }, {
                                        patch: true,
                                        success: function (model, resp, options) {
                                            // debugger;
                                            // if (resp && resp.Status) {
                                            //     node.type = resp.Status;
                                            // }
                                            self.collection.fetch({reset: true});
                                        },
                                        error: function () {
                                            inst.refresh();
                                        }
                                    });
                                }
                            }
                        };
                        tmp.properties = {
                            label: "Властивості",
                            action: function (data) {
                                var inst = $.jstree.reference(data.reference),
                                    node = inst.get_node(data.reference),
                                    categoryID = parseInt(node.id, 10);
                                Backbone.history.navigate(APP.instances.shop.urls.categoryEdit.replace(':id', categoryID), true);
                            }
                        };
                        return tmp;
                    }
                }
            }).on('create_node.jstree', function (e, data) {
                self.collection.create(data.node.original, {
                    success: function (model, resp, options) {
                        if (!resp || (resp.errors && resp.errors.length) || !resp.ID)
                            data.instance.refresh();
                        else {
                            data.node.data = {
                                id: resp.ID,
                                parent: resp.ParentID
                            };
                            data.instance.set_id(data.node, resp.ID);
                        }
                    },
                    error: function () {
                        data.instance.refresh();
                    }
                });
            }).on('rename_node.jstree', function (e, data) {
                var id = parseInt(data.node.id, 10);
                var model = self.collection.get(id);
                if (model && model.save) {
                    model.save({
                        Name: data.node.text.trim()
                    }, {
                        patch: true,
                        success: function (model, resp, options) {
                            if (!resp || (resp.errors && resp.errors.length) || !resp.ID)
                                data.instance.refresh();
                        },
                        error: function () {
                            data.instance.refresh();
                        }
                    });
                }
            }).on('delete_node.jstree', function (e, data) {
                var id = parseInt(data.node.id, 10);
                var model = self.collection.get(id);
                if (model && model.destroy) {
                    model.destroy({
                        success: function (model, resp, options) {
                            // debugger;
                            // if (resp && resp.Status) {
                            //     data.node.type = resp.Status;
                            // }
                            // data.instance.refresh();
                            self.collection.fetch({reset: true});
                        },
                        error: function () {
                            data.instance.refresh();
                        }
                    });
                }
            }).on('move_node.jstree', function (e, data) {
                // debugger;
                var newParentNode = data.instance.get_node(data.parent);
                var id = parseInt(data.node.id, 10);
                var model = self.collection.get(id);
                if (model && model.save) {
                    model.save({
                        ParentID: newParentNode.data.id || null
                    }, {
                        patch: true,
                        success: function (model, resp, options) {
                            // data.instance.refresh();
                            self.collection.fetch({reset: true});
                        },
                        error: function () {
                            data.instance.refresh();
                        }
                    });
                }
            }).on('activate_node.jstree', function (e, data) {
                var id = parseInt(data.node.data.id, 10) || null;
                self.trigger('categoryTree:changed:category', {
                    id: id,
                    text: data.node.text.trim()
                });
            });

            return this;
        }
    });

    return CategoriesTree;
});
define([
    'backbone',
    'handlebars',
    'utils',
    'cachejs',
    'plugins/shop/toolbox/js/collection/filterTreeCategories',
    /* template */
    'text!plugins/shop/toolbox/hbs/filterTreeCategories.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    /* extensions */
    'toastr',
    'jstree'
], function (Backbone, Handlebars, Utils, Cache, CollectionFilterTreeCategories, tpl, lang, toastr) {
    var FilterTreeCategories = Backbone.View.extend({
        className: 'panel panel-default plugin-shop-tree',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            'click #show_removed': 'showRemoved'
        },
        initialize: function () {
            this.collection = new CollectionFilterTreeCategories();
            this.listenTo(this.collection, 'reset', this.render);
        },
        showRemoved: function (event) {
            this.collection.fetchWithRemoved($(event.target).is(':checked'), {
                reset: true
            });
        },
        render: function () {
            var self = this;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
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
                            return data.reference.parent().data('childcount') > 0 ||
                                data.reference.parent().data('type') === "root" ||
                                data.reference.parent().data('removed');
                        }
                        tmp.remove.icon = "fa fa-times";
                        tmp.rename._disabled = function (data) {
                            return data.reference.parent().data('type') === "root";
                        }
                        tmp.create.action = function (data) {
                            // debugger;
                            var inst = $.jstree.reference(data.reference),
                                parentNode = inst.get_node(data.reference),
                                parentID = parseInt(parentNode.id, 10),
                                nodeData = {
                                    Name: tmp.create.label,
                                    text: tmp.create.label
                                };
                            if (parentID >= 0) {
                                nodeData.ParentID = parentID;
                            }
                            inst.create_node(parentNode, nodeData, "last", function (new_node) {
                                setTimeout(function () {
                                    inst.edit(new_node);
                                }, 0);
                            });
                        }
                        tmp.createProduct = {
                            label: "Додати товар",
                            "separator_after": true,
                            "separator_before": true,
                            "icon": "fa fa-plus",
                            action: function (data) {
                                var inst = $.jstree.reference(data.reference),
                                    node = inst.get_node(data.reference),
                                    categoryID = parseInt(node.id, 10),
                                    categoryText = node.text;
                                Cache.set('mpwsShopPopupProductInitCategory', categoryID + ';;' + categoryText);
                                Backbone.history.navigate(APP.instances.shop.urls.productCreate, true);
                            }
                        };
                        tmp.restore = {
                            _disabled: function (data) {
                                return data.reference.parent().data('status') === "ACTIVE" ||
                                    data.reference.parent().data('type') === "root";
                            },
                            label: "Відновити",
                            "icon": "fa fa-undo",
                            action: function (data) {
                                var inst = $.jstree.reference(data.reference),
                                    node = inst.get_node(data.reference),
                                    model = self.collection.get(parseInt(node.id, 10));
                                if (model && model.save) {
                                    model.save({
                                        Status: "ACTIVE"
                                    }, {
                                        success: function (model, response, options) {
                                            if (!response || !response.success) {
                                                toastr.error('Помилка');
                                            } else {
                                                toastr.success('Успішно');
                                            }
                                            self.collection.fetch({
                                                reset: true
                                            });
                                        },
                                        error: function () {
                                            inst.refresh();
                                            toastr.error('Помилка');
                                        }
                                    });
                                }
                            }
                        };
                        tmp.properties = {
                            label: "Властивості",
                            _disabled: function (data) {
                                return data.reference.parent().data('type') === "root";
                            },
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
                    success: function (model, response, options) {
                        if (!response || (response.errors && response.errors.length) || !response.ID)
                            data.instance.refresh();
                        else {
                            data.node.data = {
                                id: response.ID,
                                parent: response.ParentID
                            };
                            data.instance.set_id(data.node, response.ID);
                        }
                    },
                    error: function () {
                        data.instance.refresh();
                        toastr.error('Помилка');
                    }
                });
            }).on('rename_node.jstree', function (e, data) {
                var id = parseInt(data.node.id, 10);
                var model = self.collection.get(id);
                if (model && model.save) {
                    model.save({
                        Name: data.node.text.trim()
                    }, {
                        success: function (model, response, options) {
                            if (!response || !response.success) {
                                toastr.error('Помилка');
                            } else {
                                toastr.success('Успішно');
                            }
                            if (!response || (response.errors && response.errors.length) || !response.ID)
                                data.instance.refresh();
                        },
                        error: function () {
                            data.instance.refresh();
                            toastr.error('Помилка');
                        }
                    });
                }
            }).on('delete_node.jstree', function (e, data) {
                var id = parseInt(data.node.id, 10);
                var model = self.collection.get(id);
                if (model && model.destroy) {
                    model.destroy({
                        success: function (model, response, options) {
                            if (!response || !response.success) {
                                toastr.error('Помилка');
                            } else {
                                toastr.success('Успішно');
                            }
                            // debugger;
                            // if (resp && resp.Status) {
                            //     data.node.type = resp.Status;
                            // }
                            // data.instance.refresh();
                            self.collection.fetch({
                                reset: true
                            });
                        },
                        error: function () {
                            data.instance.refresh();
                            toastr.error('Помилка');
                        }
                    });
                }
            }).on('move_node.jstree', function (e, data) {
                // debugger;
                var newParentNode = data.instance.get_node(data.parent);
                var nodeData = newParentNode.data || {};
                var parentID = nodeData.id || null;
                var id = parseInt(data.node.id, 10);
                var model = self.collection.get(id);
                if (model && model.save && model.get('ParentID') !== parentID) {
                    model.save({
                        ParentID: parentID >= 0 ? parentID : null
                    }, {
                        success: function (model, response, options) {
                            if (!response || !response.success) {
                                toastr.error('Помилка');
                            } else {
                                toastr.success('Успішно');
                            }
                            // data.instance.refresh();
                            self.collection.fetch({
                                reset: true
                            });
                        },
                        error: function () {
                            data.instance.refresh();
                            toastr.error('Помилка');
                        }
                    });
                } else {
                    data.instance.refresh();
                }
            }).on('activate_node.jstree', function (e, data) {
                var id = parseInt(data.node.data.id, 10) || void(0);
                var ids = [id];
                _(data.node.children).each(function (subCategoryID) {
                    ids.push(parseInt(subCategoryID, 10))
                });
                self.trigger('categoryTree:changed:category', {
                    id: id,
                    allIDs: ids,
                    text: data.node.text.trim()
                });
            }).on('dragover', 'li', function (ev) {
                ev.preventDefault();
                ev.stopPropagation();
                // debugger
                if (currentSelectedNode === null) {
                    currentSelectedNode = jstree.get_selected();
                }
                jstree.deselect_all();
                jstree.select_node(ev.target);
                jstree.open_node(ev.target);
            }).on('drop', 'li', function (ev) {
                ev.preventDefault();
                ev.stopPropagation();
                var categoryID = $(ev.currentTarget).data('id'),
                    productID = ev.originalEvent.dataTransfer.getData('productId');
                self.trigger('productCategoryChanged', {
                    productID: parseInt(productID, 10),
                    categoryID: parseInt(categoryID, 10)
                });
                jstree.deselect_all();
                jstree.select_node(currentSelectedNode);
                currentSelectedNode = null;
            });

            var jstree = this.$('#categoryTree').data('jstree'),
                currentSelectedNode = null;

            this.trigger('rendered');
            return this;
        }
    });

    return FilterTreeCategories;
});
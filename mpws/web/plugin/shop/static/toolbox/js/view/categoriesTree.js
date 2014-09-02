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
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            // debugger;
            // this.$('#jstree_categories-ID').jstree({
            //     "plugins" : [
            //         "contextmenu", "dnd", "search",
            //         "state", "types", "wholerow"
            //       ]
            // });
            this.$('#tree')
                .jstree({
                    'core' : {
                        'data' : {
                            'url' : '//www.jstree.com/demo_filebrowser/index.php?operation=get_node',
                            'data' : function (node) {
                                return { 'id' : node.id };
                            }
                        },
                        'check_callback' : function(o, n, p, i, m) {
                            if(m && m.dnd && m.pos !== 'i') { return false; }
                            if(o === "move_node" || o === "copy_node") {
                                if(this.get_node(n).parent === this.get_node(p).id) { return false; }
                            }
                            return true;
                        },
                        'themes' : {
                            'responsive' : false,
                            'variant' : 'small',
                            'stripes' : true
                        }
                    },
                    'sort' : function(a, b) {
                        return this.get_type(a) === this.get_type(b) ? (this.get_text(a) > this.get_text(b) ? 1 : -1) : (this.get_type(a) >= this.get_type(b) ? 1 : -1);
                    },
                    'contextmenu' : {
                        'items' : function(node) {
                            var tmp = $.jstree.defaults.contextmenu.items();
                            delete tmp.create.action;
                            tmp.create.label = "New";
                            tmp.create.submenu = {
                                "create_folder" : {
                                    "separator_after"   : true,
                                    "label"             : "Folder",
                                    "action"            : function (data) {
                                        var inst = $.jstree.reference(data.reference),
                                            obj = inst.get_node(data.reference);
                                        inst.create_node(obj, { type : "default" }, "last", function (new_node) {
                                            setTimeout(function () { inst.edit(new_node); },0);
                                        });
                                    }
                                },
                                "create_file" : {
                                    "label"             : "File",
                                    "action"            : function (data) {
                                        var inst = $.jstree.reference(data.reference),
                                            obj = inst.get_node(data.reference);
                                        inst.create_node(obj, { type : "file" }, "last", function (new_node) {
                                            setTimeout(function () { inst.edit(new_node); },0);
                                        });
                                    }
                                }
                            };
                            if(this.get_type(node) === "file") {
                                delete tmp.create;
                            }
                            return tmp;
                        }
                    },
                    'types' : {
                        'default' : { 'icon' : 'folder' },
                        'file' : { 'valid_children' : [], 'icon' : 'file' }
                    },
                    'unique' : {
                        'duplicate' : function (name, counter) {
                            return name + ' ' + counter;
                        }
                    },
                    'plugins' : ['state','dnd','sort','types','contextmenu','unique']
                })
                .on('delete_node.jstree', function (e, data) {
                    $.get('//www.jstree.com/demo_filebrowser/index.php?operation=delete_node', { 'id' : data.node.id })
                        .fail(function () {
                            data.instance.refresh();
                        });
                })
                .on('create_node.jstree', function (e, data) {
                    $.get('//www.jstree.com/demo_filebrowser/index.php?operation=create_node', { 'type' : data.node.type, 'id' : data.node.parent, 'text' : data.node.text })
                        .done(function (d) {
                            data.instance.set_id(data.node, d.id);
                        })
                        .fail(function () {
                            data.instance.refresh();
                        });
                })
                .on('rename_node.jstree', function (e, data) {
                    $.get('//www.jstree.com/demo_filebrowser/index.php?operation=rename_node', { 'id' : data.node.id, 'text' : data.text })
                        .done(function (d) {
                            data.instance.set_id(data.node, d.id);
                        })
                        .fail(function () {
                            data.instance.refresh();
                        });
                })
                .on('move_node.jstree', function (e, data) {
                    $.get('//www.jstree.com/demo_filebrowser/index.php?operation=move_node', { 'id' : data.node.id, 'parent' : data.parent })
                        .done(function (d) {
                            //data.instance.load_node(data.parent);
                            data.instance.refresh();
                        })
                        .fail(function () {
                            data.instance.refresh();
                        });
                })
                .on('copy_node.jstree', function (e, data) {
                    $.get('//www.jstree.com/demo_filebrowser/index.php?operation=copy_node', { 'id' : data.original.id, 'parent' : data.parent })
                        .done(function (d) {
                            //data.instance.load_node(data.parent);
                            data.instance.refresh();
                        })
                        .fail(function () {
                            data.instance.refresh();
                        });
                })
                .on('changed.jstree', function (e, data) {
                    if(data && data.selected && data.selected.length) {
                        $.get('//www.jstree.com/demo_filebrowser/index.php?operation=get_content&id=' + data.selected.join(':'), function (d) {
                            if(d && typeof d.type !== 'undefined') {
                                $('#data .content').hide();
                                switch(d.type) {
                                    case 'text':
                                    case 'txt':
                                    case 'md':
                                    case 'htaccess':
                                    case 'log':
                                    case 'sql':
                                    case 'php':
                                    case 'js':
                                    case 'json':
                                    case 'css':
                                    case 'html':
                                        $('#data .code').show();
                                        $('#code').val(d.content);
                                        break;
                                    case 'png':
                                    case 'jpg':
                                    case 'jpeg':
                                    case 'bmp':
                                    case 'gif':
                                        $('#data .image img').one('load', function () { $(this).css({'marginTop':'-' + $(this).height()/2 + 'px','marginLeft':'-' + $(this).width()/2 + 'px'}); }).attr('src',d.content);
                                        $('#data .image').show();
                                        break;
                                    default:
                                        $('#data .default').html(d.content).show();
                                        break;
                                }
                            }
                        });
                    }
                    else {
                        $('#data .content').hide();
                        $('#data .default').html('Select a file from the tree.').show();
                    }
                });

            return this;
        }
    });

    return CategoriesTree;
});
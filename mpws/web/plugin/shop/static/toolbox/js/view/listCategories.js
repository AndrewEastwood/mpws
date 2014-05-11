define("plugin/shop/toolbox/js/view/listCategories", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/toolbox/js/collection/listCategories',
    'default/js/lib/bootstrap-dialog',
    "default/js/lib/backgrid",
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/listCategories',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    /* extensions */
    "default/js/lib/backgrid-paginator",
    "default/js/lib/backgrid-htmlcell",
    'default/js/lib/jstree',
    'default/js/lib/bootstrap-tagsinput',
], function (Sandbox, MView, CollectionListProducts, BootstrapDialog, Backgrid, tpl, lang) {
    var ListCategories = MView.extend({
        className: 'shop-toolbox-categories col-md-12',
        template: tpl,
        lang: lang,
        initialize: function () {

            MView.prototype.initialize.call(this);
            var self = this;

            this.on('mview:renderComplete', function () {
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

    return ListCategories;
});
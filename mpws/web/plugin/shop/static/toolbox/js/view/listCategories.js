define("plugin/shop/toolbox/js/view/listCategories", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/toolbox/js/collection/listCategories',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/listCategories',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    /* extensions */
    'default/js/lib/jstree',
], function (Sandbox, MView, CollectionListProducts, tpl, lang) {
    var ListCategories = MView.extend({
        className: 'shop-toolbox-categories well',
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
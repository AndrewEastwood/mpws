define("plugin/shop/toolbox/js/view/listOrigins", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/toolbox/js/collection/listOrigins',
    'default/js/lib/bootstrap-dialog',
    "default/js/lib/backgrid",
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/listOrigins',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    /* extensions */
    "default/js/lib/backgrid-paginator",
    "default/js/lib/backgrid-htmlcell",
    'default/js/lib/jstree',
    'default/js/lib/bootstrap-tagsinput',
], function (Sandbox, MView, CollectionListProducts, BootstrapDialog, Backgrid, tpl, lang) {
    var ListOrigins = MView.extend({
        className: 'shop-toolbox-origins col-md-12',
        template: tpl,
        lang: lang,
        initialize: function () {

            MView.prototype.initialize.call(this);
        }
    });

    return ListOrigins;
});
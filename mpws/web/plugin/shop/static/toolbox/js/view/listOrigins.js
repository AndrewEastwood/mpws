define("plugin/shop/toolbox/js/view/listOrigins", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/toolbox/js/collection/listOrigins',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/listOrigins',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
], function (Sandbox, MView, CollectionListProducts, tpl, lang) {
    var ListOrigins = MView.extend({
        className: 'shop-toolbox-origins well',
        template: tpl,
        lang: lang,
        initialize: function () {
            MView.prototype.initialize.call(this);
        }
    });

    return ListOrigins;
});
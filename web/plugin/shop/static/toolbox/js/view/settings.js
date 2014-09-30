define('plugin/shop/toolbox/js/view/settings', [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/lib/cache',
    'plugin/shop/toolbox/js/view/managerProducts',
    'plugin/shop/toolbox/js/view/filterPanelOrigins',
    'plugin/shop/toolbox/js/view/filterTreeCategories',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/settings',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-editable',
    'default/js/lib/bootstrap-switch',
    'default/js/lib/jquery.maskedinput'
], function (Sandbox, _, Backbone, Utils, Cache, ViewListProducts, ViewListOrigins, ViewCategoriesTree, tpl, lang) {

    var Settings = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-settings',
        initialize: function (options) {
            // set options
            // ini sub-views
            // debugger;
            // this.viewProductsList = new ViewListProducts(options);
            // this.viewOriginsList = new ViewListOrigins(options);
            // this.viewCatergoriesTree = new ViewCategoriesTree(options);

            // // subscribe on events
            // this.listenTo(this.viewProductsList.collection, 'reset', this.render);
            this.render();
        },
        render: function () {
            // TODO:
            // add expired and todays products
            // permanent layout and some elements
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            // if (this.$el.is(':empty')) {
            //     this.$('.tree').html(this.viewCatergoriesTree.$el);
            //     this.$('.products').html(this.viewProductsList.$el);
            //     this.$('.origins').html(this.viewOriginsList.$el);
            // }
            this.$('.switcher').bootstrapSwitch({
                size: 'mini',
                wrapperClass: 'delivery'
            });

            this.$('.editable').editable({
                mode: 'inline'
            });

            this.$('.myeditable_phone').on('shown', function () {
                // debugger;
                $(this).data('editable').input.$input.mask('(999) 999-99-99');
            });
            return this;
        }
    });

    return Settings;
});
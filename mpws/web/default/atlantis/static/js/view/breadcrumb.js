define("default/js/view/breadcrumb", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/handlebars',
    /* model */
    'default/js/model/breadcrumb',
    /* template */
    'default/js/plugin/text!default/hbs/breadcrumb.hbs',
    /* ui components */
    'default/js/lib/bootstrap'
], function ($, _, Backbone, Handlebars, ModelBreadcrumb ,tpl) {

    var Breadcrumb = Backbone.View.extend({

        model: new ModelBreadcrumb(),

        template: Handlebars.compile(tpl),

        initialize: function (options) {
            if (options.fn)
                this.model._options.fn = options.fn;
        },

        showLocation: function (options) {
            var _self = this;
            this.model.setUrlOptions(options);
            this.model.fetch({
                success: function () {
                    _self._render()
                }
            });
        },

        render: function () {
            this.$el.html(this.template({
                lang: app.config,
                data: this.model.toJSON()
            }));
            return this;
        }

    });

    return Breadcrumb;

});
define("default/js/view/breadcrumb", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    /* model */
    'default/js/model/breadcrumb',
    /* ui components */
    'default/js/lib/bootstrap'
], function (Sandbox, $, _, Backbone, Utils, ModelBreadcrumb, tpl) {

    var Breadcrumb = Backbone.View.extend({
        model: new ModelBreadcrumb(),
        initialize: function (options) {
            this.template = options.template;
        },
        render: function () {
            var self = this;
            var _renderFn = function () {
                require([self.template], function(tpl){
                    var $el = $('<div>').html(tpl(Utils.getHBSTemplateData(self)));
                    Sandbox.eventNotify('global:content:render', {
                        name: 'CommonBreadcrumbTop',
                        el: $el.clone(true)
                    });
                    Sandbox.eventNotify('global:content:render', {
                        name: 'CommonBreadcrumbBottom',
                        el: $el.clone(true)
                    });
                });
            }
            Sandbox.eventSubscribe('global:breadcrumb:show', function (options) {
                self.model.fetch({
                    url: APP.getApiLink(options),
                    success: $.proxy(_renderFn, self)
                });
            });
            return this;
        }
    });

    return Breadcrumb;

});
define("default/js/view/breadcrumb", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/view/mView',
    /* model */
    'default/js/model/breadcrumb',
    /* template */
    // 'default/js/plugin/text!default/hbs/breadcrumb.hbs',
    /* ui components */
    'default/js/lib/bootstrap'
], function ($, _, MView, ModelBreadcrumb, tpl) {

    var Breadcrumb = MView.extend({
        model: new ModelBreadcrumb()
    });

    return Breadcrumb;

});
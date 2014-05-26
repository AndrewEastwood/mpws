define("default/js/lib/backboneCustom", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
], function (jQuery, _, Backbone, Utils) {

    Backbone.View.prototype.renderTemplateIntoElement = function () {
        debugger;
        this.$el.html(this.template(Utils.getHBSTemplateData(this)));
    };

    return Backbone;
});
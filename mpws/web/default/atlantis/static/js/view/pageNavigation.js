define("default/js/view/pageNavigation", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/handlebars',
    /* template */
    'default/js/plugin/text!default/hbs/pageNavigation.hbs'
], function ($, _, Backbone, Handlebars, tpl) {

    var PageNavigation = Backbone.View.extend({
        template: Handlebars.compile(tpl),
        render: function () {
            // debugger;
            this.$el.html(this.template({
                options: {
                    
                }
            }));
            return this;
        }
    });

    return PageNavigation;

});
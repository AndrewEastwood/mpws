define("customer/js/view/menu", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!customer/hbs/menu',
    /* lang */
    'default/js/plugin/i18n!customer/nls/translation',
], function (Sandbox, Backbone, Utils, tpl, lang) {

    return Backbone.View.extend({
        // id: 'toolbox-menu-ID',
        lang: lang,
        template: tpl,
        events: {
            'click #menu-toggle': 'toggleMenu'
        },
        initialize: function () {
            var self = this;
            Backbone.View.prototype.initialize.call(this);
            Sandbox.eventSubscribe('global:route', function () {
                self.refreshLayout();
            });
        },
        refreshLayout: function () {
            if ($("#menu-wrapper-ID").hasClass("active"))
                $("body").addClass("toolbox-menu-active");
            else
                $("body").removeClass("toolbox-menu-active");
        },
        toggleMenu: function (e) {
            this.$("#menu-wrapper-ID").toggleClass("active");
            this.refreshLayout();
            e.preventDefault();
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            Sandbox.eventNotify('global:content:render', {
                name: 'CommonBodyLeft',
                el: this.$el,
                append: true,
                keepExisted: true
            });
            Sandbox.eventNotify('customer:menu:ready', this);
        }
    });



    // debugger;
    // Sandbox.eventSubscribe('plugin:toolbox:render:complete', function () {
    // Sandbox.eventSubscribe('global:loader:complete', function () {
        // return 
        // menu.renderTemplateIntoElement();
        // // menu.$("#menu-toggle").on('click', function(e) {
        // //     e.preventDefault();
        // // });
        // // menu.$("#menu-toggle").trigger('click');
        // debugger;
        // Sandbox.eventNotify('customer:toolbox:menu:display', {
        //     name: 'CommonBodyLeft',
        //     el: menu.$el,
        //     append: true,
        //     keepExisted: true
        // });
        // Sandbox.eventNotify('customer:toolbox:menu:ready');
    // });

});
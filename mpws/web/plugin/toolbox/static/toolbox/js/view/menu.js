define("plugin/toolbox/toolbox/js/view/menu", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/toolbox/toolbox/hbs/menu',
    /* lang */
    'default/js/plugin/i18n!plugin/toolbox/toolbox/nls/translation',
], function (Sandbox, MView, tpl, lang) {

    var menu = new (MView.extend({
        // id: 'toolbox-menu-ID',
        lang: lang,
        template: tpl,
        events: {
            'click #menu-toggle': 'toggleMenu'
        },
        initialize: function () {
            var self = this;
            MView.prototype.initialize.call(this);

            Sandbox.eventSubscribe('global:route', function () {
                self.refreshLayout();
            });
        },
        refreshLayout: function () {
            if ($("#wrapper").hasClass("active"))
                $("body").addClass("toolbox-menu-active");
            else
                $("body").removeClass("toolbox-menu-active");
        },
        toggleMenu: function (e) {
            this.$("#wrapper").toggleClass("active");
            this.refreshLayout();
            e.preventDefault();
        }
    }))();



    // debugger;
    Sandbox.eventSubscribe('plugin:toolbox:render:complete', function () {
        // debugger;
        // return 
        menu.render();

        // menu.$("#menu-toggle").on('click', function(e) {
        //     e.preventDefault();

        // });
        // menu.$("#menu-toggle").trigger('click');



        Sandbox.eventNotify('plugin:toolbox:menu:display', {
            name: 'CommonBodyLeft',
            el: menu.$el,
            append: true,
            keepExisted: true
        });
        Sandbox.eventNotify('plugin:toolbox:menu:ready');
    });

});
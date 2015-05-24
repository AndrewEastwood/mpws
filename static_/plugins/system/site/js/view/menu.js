define([
    'backbone',
    'handlebars',
    'utils',
    'auth',
    'plugins/system/common/js/model/user',
    'text!plugins/system/site/hbs/menu.hbs',
    /* lang */
    'i18n!plugins/system/site/nls/translation'
], function (Backbone, Handlebars, Utils, Auth, ModelUser, tpl, lang) {

    var Menu = Backbone.View.extend({
        lang: lang,
        template: Handlebars.compile(tpl), // check
        className: 'widget-user-menu',
        id: 'widget-user-menu-ID',
        events: {
            "click .user-signout": 'signOut',
        },
        initialize: function () {
            this.model = ModelUser.getInstance();
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        },
        signOut: function () {
            Auth.signout();
        }
    });

    return Menu;

    // return function (models) {
    //     // create SignUp button
    //     var menuSignUp = new MenuSignUp({
    //         model: models.user
    //     });
    //     menuSignUp.render();

    //     // create SignIn button
    //     var menuUser = new MenuUser({
    //         model: models.user
    //     });
    //     menuUser.render();

    //     APP.Sandbox.eventSubscribe('global:loader:complete', function () {
    //         // placeholders.common.menu
    //         APP.Sandbox.eventNotify('global:content:render', [
    //             {
    //                 name: 'CommonMenuRight',
    //                 el: menuSignUp.$el,
    //                 append: true
    //             },
    //             {
    //                 name: 'CommonMenuRight',
    //                 el: menuUser.$el,
    //                 append: true
    //             }
    //         ]);
    //     });
    // }

});
define("plugin/account/js/view/accountProfile", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'default/js/lib/backbone',
    'plugin/account/js/model/account',
    'default/js/plugin/hbs!plugin/account/hbs/accountProfile',
    /* lang */
    'default/js/plugin/i18n!plugin/account/nls/account'
], function (Sandbox, MView, Backbone, ModelAccountInstance, tpl, lang) {

    var AccountProfile = MView.extend({
        viewName: 'AccountProfile',
        // className: 'col-sm-3 col-md-3',
        template: tpl,
        lang: lang,
        model: ModelAccountInstance,
        initialize: function () {
            var self = this;
            this.model.clearErrors();
            this.model.clearStates();
            this.on('mview:renderComplete', function () {
                self.$('a.list-group-item[href*="' + Backbone.history.fragment + '"]').addClass('active');
                self.$('a.list-group-item[href*="' + Backbone.history.fragment + '"]').parents('.panel-collapse').addClass('in');
            });
        },
        addModuleMenuItem: function (item) {
            if (Array.isArray(item)) {
                for (var key in item)
                    this.addModuleMenuItem(item[key]);
            } else {
                this.getModuleMenu().append(this.createMenuItem(item));
            }
        },
        createMenuItem: function (item) {
            var _item = false;

            if (item instanceof $) {
                if (item.is('a'))
                    _item = item;
                else
                    _item = $('<a>').addClass('list-group-item').append(item);
            } else if (typeof item === "string") {
                _item = $('<a>').addClass('list-group-item').text(item);
            }

            if (!_item.hasClass('list-group-item'))
                _item.addClass('list-group-item');

            return _item;
        },
        getModuleMenu: function () {
            return this.$('#collapseModules .list-group');
        },
        getPagePlaceholder: function () {
            return this.$('.account-page-placeholder');
        },
        setPagePlaceholder: function (page) {
            this.getPagePlaceholder().html(page);
        }
    });

    return AccountProfile;

});
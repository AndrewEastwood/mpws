define("plugin/toolbox/js/view/bridge", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'default/js/lib/backbone',
    'plugin/toolbox/js/model/bridge',
    'default/js/plugin/hbs!plugin/toolbox/hbs/toolbox/bridge',
    /* lang */
    'default/js/plugin/i18n!plugin/toolbox/nls/toolbox'
], function (Sandbox, MView, Backbone, ModelToolboxInstance, tpl, lang) {

    var AccountProfile = MView.extend({
        viewName: 'AdminProfile',
        template: tpl,
        lang: lang,
        model: ModelToolboxInstance,
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
            return this.$('#toolbox-menu-ID');
        },
        getPagePlaceholder: function () {
            return this.$('#toolbox-page-ID');
        },
        setPagePlaceholder: function (page) {
            this.getPagePlaceholder().html(page);
        }
    });

    return AccountProfile;

});
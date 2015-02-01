define("plugin/system/site/js/view/userHolder", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/system/site/hbs/userHolder',
    /* lang */
    'default/js/plugin/i18n!plugin/system/site/nls/translation'
], function (Sandbox, Backbone, Utils, tpl, lang) {

    var AccountProfile = Backbone.View.extend({
        // viewName: 'AccountProfile',
        // className: 'col-sm-3 col-md-3',
        template: tpl,
        lang: lang,
        render: function (pageContent) {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            this.getPagePlaceholder().html(pageContent);
            this.$('a.list-group-item[href*="' + Backbone.history.fragment + '"]').addClass('active');
            this.$('a.list-group-item[href*="' + Backbone.history.fragment + '"]').parents('.panel-collapse').addClass('in');
            return this;
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
        }
    });

    return AccountProfile;

});
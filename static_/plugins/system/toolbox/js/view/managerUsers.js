define([
    'backbone',
    'handlebars',
    'utils',
    'plugins/system/toolbox/js/view/listUsers',
    'bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'text!plugins/system/toolbox/hbs/managerUsers.hbs',
    /* lang */
    'i18n!plugins/system/toolbox/nls/translation'
], function (Backbone, Handlebars, Utils, ViewListUsers, BootstrapDialog, BSAlert, tpl, lang) {

    var ManagerUsers = Backbone.View.extend({
        template: Handlebars.compile(tpl), // check
        lang: lang,
        className: 'plugin-system-users',
        events: {
            'click a.js-user-remove': 'userRemove',
            'click a.js-user-restore': 'userRestore',
            'click a.js-user-activate': 'userActivate'
        },
        initialize: function (options) {
            this.options = options || {};
            var userListOptions = _.extend({}, options, {
                adjustColumns: function (columns) {
                    return _(columns).omit('columnValidationString');
                }
            });
            this.viewUsersList = new ViewListUsers(userListOptions);
            if (this.options.status) {
                this.viewUsersList.collection.setCustomQueryField("Status", this.options.status.toUpperCase());
            }
            this.collection = this.viewUsersList.collection;
            this.listenTo(this.collection, 'reset', this.render);
            this.listenTo(this.collection, 'sync', this.updateTabs);
        },
        updateTabs: function () {
            var status = this.collection.getCustomQueryField('Status') || 'active';
            this.$('.tab-link').removeClass('active');
            this.$('.tab-link.users-' + status.toLowerCase()).addClass('active');
        },
        render: function () {
            // TODO:
            // add expired and todays orders
            if (this.$el.is(':empty')) {
                this.$el.html(this.template(Utils.getHBSTemplateData(this)));
                this.viewUsersList.grid.emptyText = $('<h4>').text(lang.managers.users.noData);
                this.viewUsersList.render();
                // show sub-view
                this.$('.user-list').html(this.viewUsersList.$el);
            }
            return this;
        },
        userRestore: function () {
            var that = this,
                $item = $(event.target).parents('.js-user-restore'),
                id = parseInt($item.data('id'), 10);
            BootstrapDialog.confirm("Do you want to restore user?", function (rez) {
                if (rez) {
                    that.userSetState(id, 'ACTIVE');
                }
            });
        },
        userActivate: function () {
            var that = this,
                $item = $(event.target).parents('.js-user-activate'),
                id = parseInt($item.data('id'), 10);
            BootstrapDialog.confirm("Do you want to activate user?", function (rez) {
                if (rez) {
                    that.userSetState(id, 'ACTIVE');
                }
            });
        },
        userSetState: function (id, state) {
            var that = this,
                model = that.collection.get(id);
            if (model && model.save) {
                model.save({
                    Status: state
                }, {
                    patch: true,
                    success: function () {
                        that.collection.fetch({
                            reset: true
                        });
                    },
                    error: function () {
                        BSAlert.danger('Unable to comple action');
                    }
                });
            }
        },
        userRemove: function (event) {
            var that = this;
            BootstrapDialog.confirm("Do you want to remove user?", function (rez) {
                if (rez) {
                    var $item = $(event.target).parents('.js-user-remove'),
                        id = parseInt($item.data('id'), 10),
                        model = that.collection.get(id);
                    if (model && model.destroy) {
                        model.destroy({
                            success: function () {
                                that.collection.fetch({
                                    reset: true
                                });
                            },
                            error: function () {
                                // data.instance.refresh();
                                BSAlert.danger('Unable to comple action');
                            }
                        });
                    }
                }
            });
        }
    });

    return ManagerUsers;

});
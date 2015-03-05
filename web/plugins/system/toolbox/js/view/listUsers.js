define([
    'sandbox',
    'backbone',
    'utils',
    "backgrid",
    /* collection */
    "plugins/system/toolbox/js/collection/listUsers",
    /* template */
    'hbs!plugins/system/toolbox/hbs/buttonMenuUserListItem',
    /* lang */
    'i18n!plugins/system/toolbox/nls/translation',
    /* extensions */
    "spin",
    "backgrid-paginator",
    "backgrid-select-all",
    "backgrid-htmlcell"
], function (Sandbox, Backbone, Utils, Backgrid, CollectionUsers, tplBtnMenuMainItem, lang, Spinner) {

    var opts = {
        lines: 9, // The number of lines to draw
        length: 5, // The length of each line
        width: 8, // The line thickness
        radius: 15, // The radius of the inner circle
        corners: 0.9, // Corner roundness (0..1)
        rotate: 0, // The rotation offset
        direction: 1, // 1: clockwise, -1: counterclockwise
        color: '#000', // #rgb or #rrggbb or array of colors
        speed: 1.1, // Rounds per second
        trail: 58, // Afterglow percentage
        shadow: false, // Whether to render a shadow
        hwaccel: false, // Whether to use hardware acceleration
        className: 'mpwsDataSpinner', // The CSS class to assign to the spinner
        zIndex: 2e9, // The z-index (defaults to 2000000000)
        top: '10%', // Top position relative to parent
        left: '50%' // Left position relative to parent
    };

    var spinner = new Spinner(opts).spin();

    function getColumns() {
        // TODO: do smth to fetch states from server
        // var statuses = ["ACTIVE", "REMOVED", "TEMP"];
        // var orderStatusValues = _(statuses).map(function (status) {
        //     return [lang["product_status_" + status] || status, status];
        // });

        var columnActions = {
            className: "custom-row-context-menu",
            name: "Actions",
            label: "",
            cell: "html",
            editable: false,
            sortable: false,
            formatter: {
                fromRaw: function (value, model) {
                    return tplBtnMenuMainItem(Utils.getHBSTemplateData(model.toJSON()));
                }
            }
        };
        var columnID = {
            name: "ID",
            label: lang.lists.users.columnID,
            cell: 'html',
            editable: false,
            sortable: false,
            formatter: {
                fromRaw: function (value) {
                    var id = $('<span>').addClass('label label-primary').text(value);
                    return id;
                }
            }
        };

        var columnFullName = {
            name: "FullName",
            label: lang.lists.users.columnFullName,
            cell: "string",
            editable: false
        };

        var columnEMail = {
            name: "EMail",
            label: lang.lists.users.columnEMail,
            cell: "string",
            editable: false
        };

        var columnIsOnline = {
            name: "IsOnline",
            label: lang.lists.users.columnIsOnline,
            cell: "html",
            editable: false,
            formatter: {
                fromRaw: function (value) {
                    var id = $('<span>').addClass('fa fa-fw fa-check text-success').toggleClass('fa-times text-danger', !value);
                    return id;
                }
            }
        };

        var columnPhone = {
            name: "Phone",
            label: lang.lists.users.columnPhone,
            cell: "string",
            editable: false
        };

        var columnValidationString = {
            name: "ValidationString",
            label: lang.lists.users.columnValidationString,
            cell: "string",
            editable: false
        };

        var columnDateLastAccess = {
            name: "DateLastAccess",
            label: lang.lists.users.columnDateLastAccess,
            cell: "string",
            editable: false
        };

        var columnDateUpdated = {
            name: "DateUpdated",
            label: lang.lists.users.columnDateUpdated,
            cell: "string",
            editable: false
        };

        var columnDateCreated = {
            name: "DateCreated",
            label: lang.lists.users.columnDateCreated,
            cell: "string",
            editable: false
        };

        return _.extend({}, {
            columnActions: columnActions,
            columnID: columnID,
            columnFullName: columnFullName,
            columnEMail: columnEMail,
            columnIsOnline: columnIsOnline,
            columnPhone: columnPhone,
            columnValidationString: columnValidationString,
            columnDateLastAccess: columnDateLastAccess,
            columnDateUpdated: columnDateUpdated,
            columnDateCreated: columnDateCreated
        });
    }

    var ListOrders = Backbone.View.extend({
        initialize: function (options) {
            this.options = options || {};
            this.collection = this.collection || new CollectionUsers();
            this.listenTo(this.collection, 'reset', this.render);
            this.listenTo(this.collection, 'request', this.startLoadingAnim);
            this.listenTo(this.collection, 'sync error', this.stopLoadingAnim);
            var columns = getColumns();
            if (this.options.adjustColumns)
                columns = this.options.adjustColumns(columns);
            this.grid = new Backgrid.Grid({
                className: "backgrid table table-responsive",
                columns: _(columns).values(),
                collection: this.collection
            });
            this.paginator = new Backgrid.Extension.Paginator({
                collection: this.collection
            });
            _.bindAll(this, 'startLoadingAnim', 'stopLoadingAnim', 'render');
        },
        startLoadingAnim: function () {
            var self = this;
            // debugger
            setTimeout(function () {
                console.log('adding spinner');
                self.$('.mpwsDataSpinner').remove();
                self.$el.append(spinner.el);
                // self.$el.append($anim);
                // $anim.removeClass('hidden');
            }, 0);
        },
        stopLoadingAnim: function () {
            // debugger
            setTimeout(function () {
                this.$('.mpwsDataSpinner').remove();
                // $anim.addClass('hidden');
            }, 200);
        },
        render: function () {
            // console.log('listUsers: render');
            // debugger;
            this.$el.off().empty();
            if (this.collection.length) {
                this.$el.append(this.grid.render().$el);
                this.$el.append(this.paginator.render().$el);
                // this.$el.append($anim);
                this.startLoadingAnim();
            } else {
                this.$el.html(this.grid.emptyText);
            }
            return this;
        }
    });

    return ListOrders;
});
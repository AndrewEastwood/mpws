define([
    'backbone',
    'handlebars',
    'utils',
    "backgrid",
    /* collection */
    "plugins/system/toolbox/js/collection/listCustomers",
    /* template */
    'text!plugins/system/toolbox/hbs/buttonMenuCustomerListItem.hbs',
    'text!base/hbs/animationFacebook.hbs',
    /* lang */
    'i18n!plugins/system/toolbox/nls/translation',
    /* extensions */
    "spin",
    "backgrid-paginator",
    "backgrid-select-all",
    "backgrid-htmlcell"
], function (Backbone, Handlebars, Utils, Backgrid, CollectionCustomers, tplBtnMenuMainItem, animSpinnerFB, lang, Spinner) {

    // var $anim = $(animSpinnerFB).addClass('mp-tableloader');
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
        top: '15%', // Top position relative to parent
        left: '50%' // Left position relative to parent
    };

    var spinner = new Spinner(opts).spin();

    function getColumns() {
        // TODO: do smth to fetch states from server
        // var statuses = ["ACTIVE", "REMOVED"];
        // var orderStatusValues = _(statuses).map(function (status) {
        //     return [lang.customer.statuses[status] || status, status];
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
                    return Handlebars.compile(tplBtnMenuMainItem)(Utils.getHBSTemplateData(model.toJSON()));
                }
            }
        };
        var columnID = {
            name: "ID",
            label: lang.lists.customers.columnID,
            cell: 'html',
            editable: false,
            sortable: false,
            formatter: {
                fromRaw: function (value, model) {
                    var id = $('<span>').addClass('label label-primary').text(value);
                    return id;
                }
            }
        };

        var columnName = {
            name: "HostName",
            label: lang.lists.customers.columnName,
            cell: Backgrid.StringCell.extend({
                initialize: function (options) {
                    Backgrid.StringCell.prototype.initialize.apply(this, arguments);
                    this.listenTo(this.model, "change:Name", function (model) {
                        model.save(model.changed, {
                            silent: true,
                            success: function () {
                                model.collection.fetch({
                                    reset: true
                                });
                            }
                        });
                    });
                }
            })
        };

        // var columnStatus = {
        //     name: "Status",
        //     label: lang.lists.customers.columnStatus,
        //     cell: Backgrid.SelectCell.extend({
        //         // It's possible to render an option group or use a
        //         // function to provide option values too.
        //         optionValues: orderStatusValues,
        //         initialize: function (options) {
        //             Backgrid.SelectCell.prototype.initialize.apply(this, arguments);
        //             this.listenTo(this.model, "change:Status", function (model) {
        //                 model.save(model.changed, {
        //                     success: function () {
        //                         model.collection.fetch({
        //                             reset: true
        //                         });
        //                     }
        //                 });
        //             });
        //         }
        //     })
        // };

        var columnLang = {
            name: "Lang",
            label: lang.lists.customers.columnLang,
            cell: "string",
            editable: false
        };

        var columnLocale = {
            name: "Locale",
            label: lang.lists.customers.columnLocale,
            cell: "string",
            editable: false
        };

        var columnDateUpdated = {
            name: "DateUpdated",
            label: lang.lists.customers.columnDateUpdated,
            cell: "string",
            editable: false
        };

        var columnDateCreated = {
            name: "DateCreated",
            label: lang.lists.customers.columnDateCreated,
            cell: "string",
            editable: false
        };

        return _.extend({}, {
            columnActions: columnActions,
            columnID: columnID,
            columnName: columnName,
            columnLang: columnLang,
            columnLocale: columnLocale,
            // columnStatus: columnStatus,
            columnDateUpdated: columnDateUpdated,
            columnDateCreated: columnDateCreated
        });
    }

    var ListCustomers = Backbone.View.extend({
        className: 'list list-customers',
        initialize: function (options) {
            this.options = options || {};
            this.collection = this.collection || new CollectionCustomers();
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
            console.log('listCustomers: render');
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
    return ListCustomers;
});
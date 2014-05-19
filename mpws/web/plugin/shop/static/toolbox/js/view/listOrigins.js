define("plugin/shop/toolbox/js/view/listOrigins", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    "default/js/lib/backgrid",
    'plugin/shop/toolbox/js/collection/listOrigins',
    "plugin/shop/common/js/lib/utils",
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/listOrigins',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    /* extensions */
    "default/js/lib/backgrid-paginator",
    "default/js/lib/backgrid-htmlcell",
    'default/js/lib/backgrid-filter',
], function (Sandbox, MView, Backgrid, CollectionListOrigins, ShopUtils, tpl, lang) {

    var columnActions = {
        name: "Actions",
        label: lang.pluginMenu_Products_Grid_Column_Actions,
        cell: "html",
        editable: false,
        sortable: false,
        formatter: {
            fromRaw: function (value, model) {
                // debugger;
                var _link = $('<a>').attr({
                    href: "javascript://",
                    "data-oid": model.get('ID'),
                    "data-action": "plugin:shop:origin:edit"
                }).text(lang.pluginMenu_Origins_Grid_link_Edit);
                // debugger;
                return _link;
            }
        }
    };

    var columnName = {
        name: "Name",
        label: lang.pluginMenu_Origins_Grid_Column_Name,
        cell: "string"
    };

    var columnStatus = {
        name: "Status",
        label: lang.pluginMenu_Origins_Grid_Column_Status,
        cell: "boolean",
        editable: true,
        formatter: {
            fromRaw: function (value) {
                return value === "ACTIVE";
            },
            toRaw: function (value) {
                return value ? "ACTIVE" : "REMOVED";
            }
        }
    };

    var columns = [columnActions, columnName, columnStatus];

    var ListOrigins = MView.extend({
        className: 'shop-toolbox-origins well',
        template: tpl,
        lang: lang,
        initialize: function () {
            var self = this;
            MView.prototype.initialize.call(this);

            var collection = new CollectionListOrigins();

            var Grid = new Backgrid.Grid({
                className: "backgrid table table-responsive",
                columns: columns,
                collection: collection
            });

            collection.on('change', function(changedModel) {
                // debugger;
                for (var fieldName in changedModel.changed)
                    ShopUtils.updateOriginField(changedModel.get('ID'), fieldName, changedModel.changed[fieldName]);
            })

            var Paginator = new Backgrid.Extension.Paginator({
                collection: collection
            });
            // ClientSideFilter performs a case-insensitive regular
            // expression search on the client side by OR-ing the keywords in
            // the search box together.
            var clientSideFilter = new Backgrid.Extension.ClientSideFilter({
                collection: collection,
                placeholder: lang.pluginMenu_Origins_Grid_Search_placeholder,
                // The model fields to search for matches
                fields: ['Name'],
                // How long to wait after typing has stopped before searching can start
                wait: 150
            });

            // inject all lists into tabPages
            this.on('mview:renderComplete', function () {
                var $placeholder = self.$('.shop-list-origin');
                var $placeholderSearch = self.$('.shop-list-origin-search');
                $placeholder.empty();
                $placeholder.append(Grid.render().el);
                $placeholder.append(Paginator.render().el);
                $placeholderSearch.html(clientSideFilter.render().el);
                collection.fetch({reset: true});
            });

            Sandbox.eventSubscribe("plugin:shop:origin:updated", function(){
                collection.fetch({reset: true});
            });
            Sandbox.eventSubscribe("plugin:shop:origin:created", function(){
                collection.fetch({reset: true});
            });
        }
    });

    return ListOrigins;
});
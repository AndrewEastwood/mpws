define("plugin/shop/js/view/toolbox/listProducts", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/js/collection/toolbox/listProducts',
    'plugin/shop/js/view/toolbox/orderEntry',
    'default/js/lib/bootstrap-dialog',
    "default/js/lib/backgrid",
    /* template */
    'default/js/plugin/hbs!plugin/shop/hbs/toolbox/listProducts',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/nls/toolbox',
    /* extensions */
    "default/js/lib/backgrid-paginator",
    "default/js/lib/backgrid-htmlcell",
    'default/js/lib/jstree'
], function (Sandbox, MView, CollectionListProducts, ViewOrderEntry, BootstrapDialog, Backgrid, tpl, lang) {

    Sandbox.eventSubscribe('shop-toolbox-product-edit', function(data){
        var orderEntry = new ViewOrderEntry();
        BootstrapDialog.show({
            title: lang.orderEntry_Popup_title + data.oid,
            message: orderEntry.$el,
            cssClass: 'plugin:shop:product:edit',
            buttons: [{
                label: lang.orderEntry_Popup_button_OK,
                action: function (dialog) {
                    dialog.close();
                }
            }]
        });
        orderEntry.fetchAndRender({
            orderID: data.oid
        });
    })

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
                    "data-action": "plugin:shop:product:edit"
                }).text(lang.pluginMenu_Orders_Grid_link_Edit);
                // debugger;
                return _link;
            }
        }
    };

    var columnName = {
        name: "Name",
        label: lang.pluginMenu_Products_Grid_Column_Name,
        cell: "string",
        editable: false,
    };

    var columnModel = {
        name: "Model",
        label: lang.pluginMenu_Products_Grid_Column_Model,
        cell: "string",
        editable: false
    };

    var columnSKU = {
        name: "SKU",
        label: lang.pluginMenu_Products_Grid_Column_SKU,
        cell: "string",
        editable: false
    };

    var columnPrice = {
        name: "Price",
        label: lang.pluginMenu_Products_Grid_Column_Price,
        cell: "string",
        editable: false
    };

    var columnStatus = {
        name: "Status",
        label: lang.pluginMenu_Products_Grid_Column_Status,
        cell: "boolean",
        editable: true,
        formatter: {
            fromRaw: function (value) {
                return value === "ACTIVE";
            },
            toRaw: function (value) {
                return value ? "ACTIVE" : "";
            }
        }
    };

    var columnDateUpdated = {
        name: "DateUpdated",
        label: lang.pluginMenu_Products_Grid_Column_DateUpdated,
        cell: "string",
        editable: false
    };

    var columnDateCreated = {
        name: "DateCreated",
        label: lang.pluginMenu_Products_Grid_Column_DateCreated,
        cell: "string",
        editable: false
    };

    var columns = [columnActions, columnName, columnModel, columnSKU, columnPrice, columnStatus, columnDateUpdated, columnDateCreated];

    var collection = new CollectionListProducts();

    var ToolboxListProductsGrid = new Backgrid.Grid({
      columns: columns,
      collection: collection
    });

    var Paginator = new Backgrid.Extension.Paginator({

      // If you anticipate a large number of pages, you can adjust
      // the number of page handles to show. The sliding window
      // will automatically show the next set of page handles when
      // you click next at the end of a window.
      windowSize: 20, // Default is 10

      // Used to multiple windowSize to yield a number of pages to slide,
      // in the case the number is 5
      slideScale: 0.25, // Default is 0.5

      // Whether sorting should go back to the first page
      // goBackFirstOnSort: false, // Default is true

      collection: collection
    });


    var ListProducts = MView.extend({
        className: 'shop-toolbox-products',
        template: tpl,
        // collection: collection,
        initialize: function () {
            MView.prototype.initialize.call(this);
            var self = this;
            Sandbox.eventSubscribe("plugin:shop:listProducts:refresh", function () {
                self.render();
            });
        },
        render: function () {
            MView.prototype.render.call(this);
            // display products
            this.$('.shop-component-list-products').append(ToolboxListProductsGrid.render().el);
            this.$('.shop-component-list-products').append(Paginator.render().el);
            // display catgories and origins
            this.$('#jstree_categories-ID').jstree({
                "core" : {
                    "theme" : {
                        "variant" : "large"
                    }
                },
                "plugins" : [ "wholerow", "checkbox" ]
            });
            this.$('#jstree_origins-ID').jstree({
                "core" : {
                    "theme" : {
                        "variant" : "large"
                    }
                },
                "plugins" : [ "wholerow", "checkbox" ]
            });

            // fetch products
            collection.fetch({reset: true});
        }
    });

    return ListProducts;

});
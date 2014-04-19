define("plugin/shop/js/view/toolbox/listProducts", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/js/collection/toolbox/listProducts',
    'plugin/shop/js/view/toolbox/popupProductAdd',
    'default/js/lib/bootstrap-dialog',
    "default/js/lib/backgrid",
    /* template */
    'default/js/plugin/hbs!plugin/shop/hbs/toolbox/listProducts',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/nls/toolbox',
    /* extensions */
    "default/js/lib/backgrid-paginator",
    "default/js/lib/backgrid-htmlcell",
    'default/js/lib/jstree',
    'default/js/lib/bootstrap-tagsinput',
], function (Sandbox, MView, CollectionListProducts, ViewOrderEntry, BootstrapDialog, Backgrid, tpl, lang) {

    Sandbox.eventSubscribe('plugin:shop:product:add', function(data){
        var orderEntry = new ViewOrderEntry();
        orderEntry.fetchAndRender();
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

    var ListProducts = MView.extend({
        className: 'shop-toolbox-products col-md-12',
        template: tpl,
        lang: lang,
        // collection: collection,
        events: {
            'click .button-add-product': 'addProduct',
            'click .button-add-category': 'addCategory',
            'click .button-add-origin': 'addOrigin'
        },
        initialize: function () {

            MView.prototype.initialize.call(this);

            var self = this;

            var _getTableCreateFn = function (type) {
                var collection = new CollectionListProducts();

                // set collection status
                collection.queryParams.type = type;

                var ToolboxListProductsGrid = new Backgrid.Grid({
                    className: "backgrid table table-responsive",
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

                return {
                    type: type,
                    collection: collection,
                    Grid: ToolboxListProductsGrid,
                    Paginator: Paginator,
                    fetch: function (options) {
                        collection.fetch(options);
                    }
                }
            }

            var _productsByTypes = null;

            // refresh all lists
            Sandbox.eventSubscribe("plugin:shop:productList:refresh", function () {
                if (!!!_productsByTypes)
                    return;

                _(_productsByTypes).each(function(productList) {
                    productList.fetch({reset: true});
                });
            });

            // when we know how many records are availabel of particular filter
            // we do update  tapPage badge with records count
            Sandbox.eventSubscribe('plugin:shop:productList:parseState', function (data) {
                // debugger;
                var $badge = self.$('a[href="#product_type_' + data.collection.queryParams.type + '-ID"] .badge');
                $badge.text(data.state.totalRecords * 500 || "");
            });

            // inject all lists into tabPages
            this.on('mview:renderComplete', function () {

                // debugger;
                _productsByTypes = _productsByTypes || self.$('ul.nav-tabs > li').map(function() {
                    // debugger;
                    if ($(this).data('type'))
                        return _getTableCreateFn($(this).data('type'));
                });

                // display products lists
                _(_productsByTypes).each(function(productListBuilder){
                    var $tabPage = self.$('.tab-pane#product_type_' + productListBuilder.type + '-ID');
                    $tabPage.empty();
                    $tabPage.append(productListBuilder.Grid.render().el);
                    $tabPage.append(productListBuilder.Paginator.render().el);
                    productListBuilder.fetch({reset: true});
                });

                // display catgories and origins
                self.$('#jstree_categories-ID').jstree({
                    "core" : {
                        "theme" : {
                            "variant" : "large"
                        }
                    },
                    "plugins" : [ "wholerow", "checkbox" ]
                });
                self.$('#jstree_origins-ID').jstree({
                    "core" : {
                        "theme" : {
                            "variant" : "large"
                        }
                    },
                    "plugins" : [ "wholerow", "checkbox" ]
                });

                // fetch products
                // collection.fetch({reset: true});
                self.$("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();

            });

        },
        addProduct: function (argument) {
            // body...
            // debugger;
            var orderEntry = new ViewOrderEntry();
            orderEntry.fetchAndRender({
                orderID: 22
            });
        },
        addCategory: function (argument) {
            // body...
            debugger;
        },
        addOrigin: function (argument) {
            // body...
            debugger;
        },
    });

    return ListProducts;

});
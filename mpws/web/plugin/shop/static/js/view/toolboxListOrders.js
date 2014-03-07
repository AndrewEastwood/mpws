define("plugin/shop/js/view/toolboxListOrders", [
    'default/js/view/mView',
    'plugin/shop/js/collection/toolboxListOrders',
    'plugin/shop/js/view/toolboxOrderItem',
    'default/js/lib/backgrid'
], function (MView, CollListOrders, OrderItem, Backgrid) {

    // Column definitions
    var columns = [{
        name: "ID",
        name: "ID",
        cell: "string"
    }, {
        name: "Comment",
        name: "Comment",
        cell: "string",
        editable: true,
    }, {
        name: "DateCreated",
        name: "DateCreated",
        cell: "string"
    }, {
        name: "DateUpdated",
        name: "DateUpdated",
        cell: "string"
    }, {
        name: "Hash",
        name: "Hash",
        cell: "string"
    }, {
        name: "Shipping",
        name: "Shipping",
        cell: "string"
    }, {
        name: "Status",
        name: "Status",
        cell: "string"
    }, {
        name: "Warehouse",
        name: "Warehouse",
        cell: "string"
    }];

    var ToolboxListOrders = MView.extend({
        className: 'shop-toolbox-orders',
        collection: new CollListOrders(),
        itemViewClass: OrderItem,
        initialize: function () {
            MView.prototype.initialize.call(this);
            var self = this;
            var grid = new Backgrid.Grid({
                columns: columns,
                collection: this.collection,
            });

            this.on('mview:renderComplete', function () {
                self.$el.html(grid.render().el);
            });
        }
        // autoRender: true
    });

    return ToolboxListOrders;

});
define("plugin/shop/toolbox/js/view/stats", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/stats',
    'default/js/lib/bootstrap-dialog',
    "default/js/lib/cache",
    'default/js/lib/utils',
    'default/js/lib/auth',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/stats',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    // "default/js/lib/select2/select2",
    'default/js/lib/bootstrap-editable',
    /* charts */
    'default/js/plugin/async!http://maps.google.com/maps/api/js?sensor=false',
    'default/js/plugin/goog!visualization,1,packages:[corechart,geochart]'
], function (Sandbox, Backbone, ModelStats, BootstrapDialog, Cache, Utils, Auth, tpl, lang) {

    return Backbone.View.extend({
        attributes: {
            id: 'shop-stats-ID'
        },
        className: 'plugin-shop-stats',
        lang: lang,
        template: tpl,
        initialize: function () {
            var self = this;
            this.model = new ModelStats();
            this.listenTo(this.model, "change", this.render);
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            // self.$('select').select2();
            this.$('.helper').tooltip();
            // debugger;
            ///
            if (google) {

                // orders income for last month
                var ordersNewInstensity = this.model.get('orders_intensity_new_last_month');
                var ordersClosedInstensity = this.model.get('orders_intensity_closed_last_month');

                var mergedOrdersDataOfIntensity = {};

                _(ordersNewInstensity).each(function (count, date) {
                    mergedOrdersDataOfIntensity[date] = mergedOrdersDataOfIntensity[date] || {};
                    mergedOrdersDataOfIntensity[date]['placed'] = parseInt(count, 10);
                });

                _(ordersClosedInstensity).each(function (count, date) {
                    mergedOrdersDataOfIntensity[date] = mergedOrdersDataOfIntensity[date] || {};
                    mergedOrdersDataOfIntensity[date]['closed'] = parseInt(count, 10);
                });

                var dataOrders = [['Дата', 'Нові', 'Закриті']];

                _(mergedOrdersDataOfIntensity).each(function (values, date) {
                    dataOrders.push([date, values.placed || 0, values.closed || 0]);
                });

                dataOrders = google.visualization.arrayToDataTable(dataOrders);

                var options = {
                    title: 'Статистика замовлень',
                    curveType: 'function',
                    legend: { position: 'bottom' }
                };

                var chart = new google.visualization.LineChart(self.$('#shop-chart-new-sales-ID').get(0));
                chart.draw(dataOrders, options);

                // products income for last month
                var productsNewInstensity = this.model.get('products_new_intensity_last_month');
                var productsDiscountInstensity = this.model.get('products_discount_intensity_last_month');
                var productsPreorderInstensity = this.model.get('products_preorder_intensity_last_month');

                var mergedProductsDataOfIntensity = {};

                _(productsNewInstensity).each(function (count, date) {
                    mergedProductsDataOfIntensity[date] = mergedProductsDataOfIntensity[date] || {};
                    mergedProductsDataOfIntensity[date]['added'] = parseInt(count, 10);
                });

                _(productsDiscountInstensity).each(function (count, date) {
                    mergedProductsDataOfIntensity[date] = mergedProductsDataOfIntensity[date] || {};
                    mergedProductsDataOfIntensity[date]['discount'] = parseInt(count, 10);
                });

                _(productsPreorderInstensity).each(function (count, date) {
                    mergedProductsDataOfIntensity[date] = mergedProductsDataOfIntensity[date] || {};
                    mergedProductsDataOfIntensity[date]['preorder'] = parseInt(count, 10);
                });

                var dataProducts = [['Дата', 'Нові', 'З знижкою', 'Для замовлення']];

                _(mergedProductsDataOfIntensity).each(function (values, date) {
                    dataProducts.push([date, values.added || 0, values.discount || 0, values.preorder || 0]);
                });

                var dataProducts = google.visualization.arrayToDataTable(dataProducts);

                var options = {
                    title: 'Надходження товарів',
                    legend: { position: 'bottom' }
                };

                var chart = new google.visualization.ColumnChart(self.$('#shop-chart-new-products-ID').get(0));
                chart.draw(dataProducts, options);
            }

            return this;
        }
    });
});
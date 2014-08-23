define("plugin/shop/toolbox/js/view/statsOrdersIntensityLastMonth", [
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/statsOrdersIntensityLastMonth',
    'default/js/lib/utils',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/statsOrdersIntensityLastMonth',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    /* charts */
    'default/js/plugin/async!http://maps.google.com/maps/api/js?sensor=false',
    'default/js/plugin/goog!visualization,1,packages:[corechart,geochart]'
], function (Backbone, ModelOrdersIntensity, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-default',
        lang: lang,
        template: tpl,
        initialize: function () {
            this.model = new ModelOrdersIntensity();
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            if (google) {

                // orders income for last month
                var ordersOpenInstensity = this.model.get('OPEN');
                var ordersClosedInstensity = this.model.get('CLOSED');

                var mergedOrdersDataOfIntensity = {};

                _(ordersOpenInstensity).each(function (count, date) {
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

                var chart = new google.visualization.LineChart(this.$('.panel-body').get(0));
                chart.draw(dataOrders, options);
            }
            return this;
        }
    });
});
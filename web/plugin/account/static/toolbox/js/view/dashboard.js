define("plugin/account/toolbox/js/view/dashboard", [
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'plugin/account/toolbox/js/model/dashboard',
    'default/js/lib/utils',
    /* template */
    'default/js/plugin/hbs!plugin/account/toolbox/hbs/dashboard',
    /* lang */
    'default/js/plugin/i18n!plugin/account/toolbox/nls/translation',
    /* charts */
    'default/js/plugin/async!http://maps.google.com/maps/api/js?sensor=false',
    'default/js/plugin/goog!visualization,1,packages:[corechart]'
], function (Backbone, _, ModelStats, Utils, tpl, lang) {

    return Backbone.View.extend({
        attributes: {
            id: 'account-stats-ID'
        },
        className: 'plugin-account-stats',
        lang: lang,
        template: tpl,
        initialize: function () {
            this.model = new ModelStats();
            this.listenTo(this.model, "change", this.render);
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));

            // debugger;
            if (google) {

                var usersByStatus = this.model.get('overview_accounts');

                var dounutData = [
                    ['Статус', 'К-сть']
                ];

                _(usersByStatus).each(function (count, status) {
                    dounutData.push([status, parseInt(count, 10)]);
                });

                var data = google.visualization.arrayToDataTable(dounutData);

                var optionsPie = {
                    title: 'Співвідношення користувачів',
                    pieHole: 0.4,
                };

                var chart = new google.visualization.PieChart(this.$('.chart-users-interrelation').get(0));
                chart.draw(data, optionsPie);

                // accounts interrelation for last month by states
                var accountsInstensityActive = this.model.get('accounts_intensity_last_month_active');
                var accountsInstensityTemp = this.model.get('accounts_intensity_last_month_temp');
                var accountsInstensityRemoved = this.model.get('accounts_intensity_last_month_removed');

                var mergedOrdersDataOfIntensity = {};

                _(accountsInstensityActive).each(function (count, date) {
                    mergedOrdersDataOfIntensity[date] = mergedOrdersDataOfIntensity[date] || {};
                    mergedOrdersDataOfIntensity[date]['active'] = parseInt(count, 10);
                });

                _(accountsInstensityTemp).each(function (count, date) {
                    mergedOrdersDataOfIntensity[date] = mergedOrdersDataOfIntensity[date] || {};
                    mergedOrdersDataOfIntensity[date]['temp'] = parseInt(count, 10);
                });

                _(accountsInstensityRemoved).each(function (count, date) {
                    mergedOrdersDataOfIntensity[date] = mergedOrdersDataOfIntensity[date] || {};
                    mergedOrdersDataOfIntensity[date]['removed'] = parseInt(count, 10);
                });

                var dataOrders = [
                    ['Дата', 'Активні', 'Видалені', 'Тимчасові']
                ];

                _(mergedOrdersDataOfIntensity).each(function (values, date) {
                    dataOrders.push([date, values.active || 0, values.removed || 0, values.temp || 0]);
                });

                var chart = new google.visualization.LineChart(this.$('.chart-users-lastmonth').get(0));
                chart.draw(google.visualization.arrayToDataTable(dataOrders), {
                    curveType: 'function'
                });
            }
            return this;
        }
    });

});
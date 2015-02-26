define([
    'backbone',
    'underscore',
    'plugins/system/toolbox/js/model/dashboard',
    'utils',
    /* template */
    'hbs!plugins/system/toolbox/hbs/dashboard',
    /* lang */
    'i18n!plugins/system/toolbox/nls/translation',
    /* charts */
    'base/js/plugin/async!http://maps.google.com/maps/api/js?sensor=false',
    'base/js/plugin/goog!visualization,1,packages:[corechart]'
], function (Backbone, _, ModelStats, Utils, tpl, lang) {

    return Backbone.View.extend({
        attributes: {
            id: 'plugin-system-overview'
        },
        className: 'plugin-system-overview',
        lang: lang,
        template: tpl,
        initialize: function () {
            this.model = new ModelStats();
            this.listenTo(this.model, "change", this.render);
        },
        render: function () {

            var self = this;
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));

            // set top container for system plugin
            // this.$el.wrap($('<div/>').attr({
            //     name: 'DashboardForPlugin_system',
            //     "class": 'dashboard-container'
            // }));
            // self.$el.append($('<div/>').attr({
            //     name: 'DashboardForPlugin_system',
            //     "class": 'dashboard-container'
            // }).html($sysTpl));

            // template for system dashaboard
            // var $sysTpl = tpl(Utils.getHBSTemplateData(this));

            // debugger;
            if (google) {

                var usersByStatus = this.model.get('overview_users');

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

                // users interrelation for last month by states
                var usersInstensityActive = this.model.get('users_intensity_last_month_active');
                var usersInstensityTemp = this.model.get('users_intensity_last_month_temp');
                var usersInstensityRemoved = this.model.get('users_intensity_last_month_removed');

                var mergedOrdersDataOfIntensity = {};

                _(usersInstensityActive).each(function (count, date) {
                    mergedOrdersDataOfIntensity[date] = mergedOrdersDataOfIntensity[date] || {};
                    mergedOrdersDataOfIntensity[date]['active'] = parseInt(count, 10);
                });

                _(usersInstensityTemp).each(function (count, date) {
                    mergedOrdersDataOfIntensity[date] = mergedOrdersDataOfIntensity[date] || {};
                    mergedOrdersDataOfIntensity[date]['temp'] = parseInt(count, 10);
                });

                _(usersInstensityRemoved).each(function (count, date) {
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
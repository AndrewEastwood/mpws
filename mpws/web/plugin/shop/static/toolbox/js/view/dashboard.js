define("plugin/shop/toolbox/js/view/dashboard", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/toolbox/js/model/dashboard',
    'default/js/lib/bootstrap-dialog',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/dashboard',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    // "default/js/lib/select2/select2",
    'default/js/lib/bootstrap-editable',
    /* charts */
    'default/js/plugin/async!http://maps.google.com/maps/api/js?sensor=false',
    'default/js/plugin/goog!visualization,1,packages:[corechart,geochart]',
], function (Sandbox, MView, ModelDashboard, BootstrapDialog, tpl, lang) {

    var dashboardModel = new ModelDashboard();
    return MView.extend({
        id: 'shop-dashboard-ID',
        lang: lang,
        template: tpl,
        model: dashboardModel,
        events: {
            'click #refresh': 'refresh'
        },
        initialize: function () {
            MView.prototype.initialize.call(this);
            var self = this;

            this.listenTo(this.model, "change", this.render);

            this.on('mview:renderComplete', function () {
                // self.$('select').select2();
                self.$('.helper').tooltip();

                // debugger;
                ///
                if (google) {

                // function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ['Рік', 'Замовлення'],
                        ['СІЧ',  100],
                        ['ЛЮТ',  110],
                        ['БЕР',  60],
                        ['КВІ',  100]
                    ]);

                    var options = {
                        title: 'Статистика замовлень',
                        curveType: 'function',
                        legend: { position: 'bottom' }
                    };

                    var chart = new google.visualization.LineChart(self.$('#shop-chart-sales-ID').get(0));
                    chart.draw(data, options);
                // }
                    var data = google.visualization.arrayToDataTable([
                      ['Рік', 'Продажі', 'Витрати'],
                      ['СІЧ',  1000,      400],
                      ['ЛЮТ',  1170,      460],
                      ['БЕР',  660,       1120],
                      ['КВІ',  1030,      540]
                    ]);

                    var options = {
                        title: 'Прибутки магазину',
                        legend: { position: 'bottom' }
                    };

                    var chart = new google.visualization.ColumnChart(self.$('#shop-chart-balance-ID').get(0));
                    chart.draw(data, options);
                }
            });
        },
        refresh: function () {
            this.model.fetch({reset: true});
        }
    });
});
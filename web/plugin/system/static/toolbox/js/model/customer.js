define('plugin/system/toolbox/js/model/customer', [
    'default/js/lib/backbone',
    'default/js/lib/moment/moment',
    'default/js/lib/moment/locale/uk'
], function (Backbone, moment) {

    var Feed = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params = {
                source: 'system',
                fn: 'customers',
                id: this.id
            };
            return APP.getApiLink(_params);
        },
        parse: function (data) {
            moment.locale('uk');
            data.fromNowHumanized = moment.duration(moment().diff(moment.unix(data['time']))).humanize(true);
            return data;
        },
        migrate: function () {
            
        }
    });

    return Feed;
});
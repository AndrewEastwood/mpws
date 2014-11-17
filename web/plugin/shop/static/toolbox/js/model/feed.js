define('plugin/shop/toolbox/js/model/feed', [
    'default/js/lib/backbone',
    'default/js/lib/moment/moment',
    'default/js/lib/moment/locale/uk'
], function (Backbone, moment) {

    var Feed = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'shop',
                fn: 'feeds'
            };
            if (!this.isNew()) {
                _params['name'] = this.get('name');
                _params['type'] = this.get('type');
            }
            return APP.getApiLink(_params);
        },
        parse: function (data) {
            moment.locale('uk');
            data.fromNowHumanized = moment.duration(moment().diff(moment.unix(data['time']))).humanize(true);
            return data;
        },
        isUploaded: function () {
            return this.get('type').toLowerCase() === 'uploaded';
        },
        importUploadedProductFeed: function (ajaxOptions) {
            var that = this;
            if (this.isUploaded()) {
                // APP.triggerBackgroundTask('importProductFeed', this.get('name')).done(function (rez) {
                //     if (rez) {
                        
                //     }
                // });
                this.save({
                    import: true
                }, ajaxOptions);
            }
        }
    });

    return Feed;
});
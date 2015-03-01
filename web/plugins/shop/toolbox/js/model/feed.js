define([
    'backbone',
    'moment'
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
                this.save({
                    schedule: true
                }, ajaxOptions);
            }
        },
        cancelActiveImportProductFeed: function (ajaxOptions) {
            var that = this;
            if (this.isUploaded()) {
                this.save({
                    cancel: true
                }, ajaxOptions);
            }
        },
        isGenerated: function () {
            return this.get('type') === 'generated';
        }
    });

    return Feed;
});
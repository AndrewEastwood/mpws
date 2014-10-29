define('plugin/shop/toolbox/js/model/feed', [
    'default/js/lib/backbone'
], function (Backbone) {

    var Feed = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'shop',
                fn: 'feeds'
            };
            return APP.getApiLink(_params);
        },
        importUploadedProductFeed: function () {
            alert('importing feed');
        }
    });

    return Feed;
});